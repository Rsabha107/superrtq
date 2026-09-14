<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\CompleteFanProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\FanResource;
use App\Mail\FanOtpMail;
use App\Models\Fan;
use App\Models\PointsTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public const REGISTRATION_BONUS_POINTS = 500;

    public function register(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'mobile' => ['nullable', 'string'],
            'display_name' => ['nullable', 'string', 'max:255'],
        ]);

        $fan = Fan::where('email', $data['email'])->first();

        if (! $fan) {
            $fan = Fan::create([
                'display_name' => $data['display_name'] ?? null,
                'email' => $data['email'],
                'mobile' => $data['mobile'] ?? null,
                'status' => 'pending_otp',
            ]);
        } elseif ($fan->status === 'pending_otp' && ($data['display_name'] ?? null)) {
            $fan->update(['display_name' => $data['display_name']]);
        }

        $code = Fan::generateOtp();

        $fan->update([
            'otp_code' => $code,
            'otp_expires_at' => now()->addMinutes(10),
            'mobile' => $data['mobile'] ?? $fan->mobile,
        ]);

        Mail::to($fan->email)->send(new FanOtpMail($code));

        return response()->json([
            'message' => 'Verification code sent to your email.',
            'identifier' => $fan->email,
        ]);
    }

    /**
     * Passwordless login for an already-registered fan: same 4-digit
     * emailed OTP mechanism as register(), but requires an existing
     * account instead of silently creating one.
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $fan = Fan::where('email', $data['email'])->first();

        if (! $fan) {
            throw ValidationException::withMessages([
                'email' => 'We could not find an account for that email. Please register instead.',
            ]);
        }

        $code = Fan::generateOtp();

        $fan->update([
            'otp_code' => $code,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($fan->email)->send(new FanOtpMail($code));

        return response()->json([
            'message' => 'Verification code sent to your email.',
            'identifier' => $fan->email,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'identifier' => ['required', 'string'],
            'otp' => ['required', 'string'],
        ]);

        $fan = Fan::where('email', $data['identifier'])
            ->orWhere('mobile', $data['identifier'])
            ->first();

        if (! $fan) {
            throw ValidationException::withMessages([
                'identifier' => 'We could not find a registration for that email or mobile number.',
            ]);
        }

        if (
            $fan->otp_code === null
            || $fan->otp_code !== $data['otp']
            || $fan->otp_expires_at === null
            || $fan->otp_expires_at->isPast()
        ) {
            throw ValidationException::withMessages([
                'otp' => 'That code is incorrect or has expired. Please try again.',
            ]);
        }

        DB::transaction(function () use ($fan) {
            $wasPendingOtp = $fan->status === 'pending_otp';

            $fan->otp_code = null;
            $fan->otp_expires_at = null;

            if ($wasPendingOtp) {
                $fan->status = 'pending_profile';
                $fan->points_balance += self::REGISTRATION_BONUS_POINTS;
            }

            $fan->save();

            if ($wasPendingOtp) {
                PointsTransaction::create([
                    'fan_id' => $fan->id,
                    'type' => 'earn',
                    'points' => self::REGISTRATION_BONUS_POINTS,
                    'description' => 'Registration bonus',
                    'source_type' => 'registration',
                ]);
            }
        });

        $token = $fan->createToken('mobile')->plainTextToken;

        return response()->json([
            'token' => $token,
            'fan' => new FanResource($fan->fresh()),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out.']);
    }

    public function me(Request $request)
    {
        return new FanResource($request->user());
    }

    /**
     * Step 2 of 3 — "A few details". Saves identity details that appear on
     * the Fan ID. Does not touch status or points.
     */
    public function updateDetails(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'nationality' => ['nullable', 'string', 'max:255'],
            'language' => ['nullable', 'string', 'in:en,ar'],
            'gender' => ['nullable', 'string', 'in:male,female,unspecified'],
            'country_of_residence' => ['nullable', 'string', 'max:255'],
        ]);

        $fan = $request->user();
        $fan->fill($data);

        if (! $fan->display_name) {
            $fan->display_name = trim($data['first_name'].' '.$data['last_name']);
        }

        $fan->save();

        return new FanResource($fan);
    }

    /**
     * Step 3 of 3 — "Tell us about you". Issues the Fan ID and the profile
     * completion bonus on first completion (see CompleteFanProfileAction).
     */
    public function updateProfile(Request $request, CompleteFanProfileAction $action)
    {
        $data = $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'language' => ['nullable', 'string', 'in:en,ar'],
            'sports' => ['nullable', 'array'],
            'sports.*' => ['string'],
            'interested_events' => ['nullable', 'array'],
            'interested_events.*' => ['string'],
            'interests' => ['nullable', 'array'],
            'interests.*' => ['string'],
        ]);

        $attributes = [
            'display_name' => $data['display_name'],
            'language' => $data['language'] ?? $request->user()->language,
            'preferences' => [
                'sports' => $data['sports'] ?? [],
                'interested_events' => $data['interested_events'] ?? [],
                'interests' => $data['interests'] ?? [],
            ],
        ];

        $fan = $action->execute($request->user(), $attributes);

        return new FanResource($fan);
    }
}

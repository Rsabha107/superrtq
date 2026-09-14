<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journeys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fan_id')->constrained('fans')->cascadeOnDelete();
            $table->string('name');
            $table->string('journey_type');
            $table->string('from_city')->nullable();
            $table->string('doha_stay')->nullable();
            $table->date('arrival_at')->nullable();
            $table->date('departure_at')->nullable();
            $table->string('attached_label')->nullable();
            $table->json('transport_modes')->nullable();
            $table->json('tours')->nullable();
            $table->json('itinerary');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journeys');
    }
};

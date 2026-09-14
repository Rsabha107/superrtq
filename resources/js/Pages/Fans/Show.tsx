import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

interface FanDetail {
    id: number;
    display_name: string | null;
    email: string | null;
    mobile: string | null;
    fan_number: string | null;
    fan_id: string | null;
    status: string;
    language: string;
    points_balance: number;
    member_since: string | null;
    preferences: {
        sports?: string[];
        teams?: string[];
        interests?: string[];
    };
}

interface PointsTransactionRow {
    id: number;
    type: string;
    points: number;
    description: string;
    created_at: string;
}

interface RedemptionRow {
    id: number;
    reward_title: string;
    points_cost: number;
    status: string;
    redeemed_at: string;
}

export default function Show({
    fan,
    pointsTransactions,
    redemptions,
}: {
    fan: FanDetail;
    pointsTransactions: PointsTransactionRow[];
    redemptions: RedemptionRow[];
}) {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    {fan.display_name}
                </h2>
            }
        >
            <Head title={fan.display_name ?? 'Fan'} />

            <div className="py-12">
                <div className="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-2 gap-4 rounded-lg bg-white p-6 shadow-sm sm:grid-cols-4">
                        <Field label="Fan Number" value={fan.fan_number} />
                        <Field label="Fan ID" value={fan.fan_id} />
                        <Field
                            label="Points Balance"
                            value={fan.points_balance.toLocaleString()}
                        />
                        <Field label="Status" value={fan.status} />
                        <Field label="Email" value={fan.email} />
                        <Field label="Mobile" value={fan.mobile} />
                        <Field label="Language" value={fan.language} />
                        <Field
                            label="Member Since"
                            value={fan.member_since}
                        />
                    </div>

                    <div className="grid grid-cols-1 gap-4 rounded-lg bg-white p-6 shadow-sm sm:grid-cols-3">
                        <Field
                            label="Sports"
                            value={fan.preferences.sports?.join(', ')}
                        />
                        <Field
                            label="Teams"
                            value={fan.preferences.teams?.join(', ')}
                        />
                        <Field
                            label="Interests"
                            value={fan.preferences.interests?.join(', ')}
                        />
                    </div>

                    <div className="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <div className="overflow-hidden rounded-lg bg-white shadow-sm">
                            <h3 className="border-b px-6 py-4 font-semibold text-gray-800">
                                Points Transactions
                            </h3>
                            <ul className="max-h-96 divide-y overflow-y-auto">
                                {pointsTransactions.length === 0 && (
                                    <li className="px-6 py-4 text-sm text-gray-500">
                                        No transactions yet.
                                    </li>
                                )}
                                {pointsTransactions.map((t) => (
                                    <li
                                        key={t.id}
                                        className="flex items-center justify-between px-6 py-3 text-sm"
                                    >
                                        <div>
                                            <div className="text-gray-800">
                                                {t.description}
                                            </div>
                                            <div className="text-xs text-gray-400">
                                                {t.created_at}
                                            </div>
                                        </div>
                                        <div
                                            className={
                                                t.points >= 0
                                                    ? 'font-medium text-green-600'
                                                    : 'font-medium text-rtq-maroon'
                                            }
                                        >
                                            {t.points >= 0 ? '+' : ''}
                                            {t.points}
                                        </div>
                                    </li>
                                ))}
                            </ul>
                        </div>

                        <div className="overflow-hidden rounded-lg bg-white shadow-sm">
                            <h3 className="border-b px-6 py-4 font-semibold text-gray-800">
                                Redemptions
                            </h3>
                            <ul className="max-h-96 divide-y overflow-y-auto">
                                {redemptions.length === 0 && (
                                    <li className="px-6 py-4 text-sm text-gray-500">
                                        No redemptions yet.
                                    </li>
                                )}
                                {redemptions.map((r) => (
                                    <li
                                        key={r.id}
                                        className="flex items-center justify-between px-6 py-3 text-sm"
                                    >
                                        <div>
                                            <div className="text-gray-800">
                                                {r.reward_title}
                                            </div>
                                            <div className="text-xs text-gray-400">
                                                {r.redeemed_at}
                                            </div>
                                        </div>
                                        <div className="text-rtq-maroon">
                                            -{r.points_cost} pts
                                        </div>
                                    </li>
                                ))}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

function Field({ label, value }: { label: string; value?: string | null }) {
    return (
        <div>
            <div className="text-xs uppercase tracking-wide text-gray-400">
                {label}
            </div>
            <div className="mt-1 text-sm font-medium text-gray-800">
                {value || '—'}
            </div>
        </div>
    );
}

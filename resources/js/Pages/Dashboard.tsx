import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

interface Stats {
    fans: number;
    verified_fans: number;
    events: number;
    rewards: number;
    redemptions: number;
    points_issued: number;
    points_redeemed: number;
}

interface RecentRedemption {
    id: number;
    fan_name: string;
    reward_title: string;
    points_cost: number;
    redeemed_at: string;
}

interface UpcomingEvent {
    id: number;
    title: string;
    event_type: string;
    start_at: string;
}

export default function Dashboard({
    stats,
    recentRedemptions,
    upcomingEvents,
}: {
    stats: Stats;
    recentRedemptions: RecentRedemption[];
    upcomingEvents: UpcomingEvent[];
}) {
    const tiles: { label: string; value: number | string }[] = [
        { label: 'Total Fans', value: stats.fans },
        { label: 'Verified Fans', value: stats.verified_fans },
        { label: 'Published Events', value: stats.events },
        { label: 'Active Rewards', value: stats.rewards },
        { label: 'Redemptions', value: stats.redemptions },
        { label: 'Points Issued', value: stats.points_issued.toLocaleString() },
        {
            label: 'Points Redeemed',
            value: stats.points_redeemed.toLocaleString(),
        },
    ];

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard
                </h2>
            }
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                        {tiles.map((tile) => (
                            <div
                                key={tile.label}
                                className="rounded-lg bg-white p-5 shadow-sm"
                            >
                                <div className="text-xs uppercase tracking-wide text-gray-500">
                                    {tile.label}
                                </div>
                                <div className="mt-2 text-2xl font-bold text-gray-900">
                                    {tile.value}
                                </div>
                            </div>
                        ))}
                    </div>

                    <div className="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <div className="overflow-hidden rounded-lg bg-white shadow-sm">
                            <div className="flex items-center justify-between border-b px-6 py-4">
                                <h3 className="font-semibold text-gray-800">
                                    Recent Redemptions
                                </h3>
                                <Link
                                    href={route('admin.redemptions.index')}
                                    className="text-sm text-rtq-maroon hover:underline"
                                >
                                    View all
                                </Link>
                            </div>
                            <ul className="divide-y">
                                {recentRedemptions.length === 0 && (
                                    <li className="px-6 py-4 text-sm text-gray-500">
                                        No redemptions yet.
                                    </li>
                                )}
                                {recentRedemptions.map((r) => (
                                    <li
                                        key={r.id}
                                        className="flex items-center justify-between px-6 py-3 text-sm"
                                    >
                                        <div>
                                            <div className="font-medium text-gray-800">
                                                {r.fan_name}
                                            </div>
                                            <div className="text-gray-500">
                                                {r.reward_title}
                                            </div>
                                        </div>
                                        <div className="text-right">
                                            <div className="font-medium text-rtq-maroon">
                                                -{r.points_cost} pts
                                            </div>
                                            <div className="text-xs text-gray-400">
                                                {r.redeemed_at}
                                            </div>
                                        </div>
                                    </li>
                                ))}
                            </ul>
                        </div>

                        <div className="overflow-hidden rounded-lg bg-white shadow-sm">
                            <div className="flex items-center justify-between border-b px-6 py-4">
                                <h3 className="font-semibold text-gray-800">
                                    Upcoming Events
                                </h3>
                                <Link
                                    href={route('admin.events.index')}
                                    className="text-sm text-rtq-maroon hover:underline"
                                >
                                    View all
                                </Link>
                            </div>
                            <ul className="divide-y">
                                {upcomingEvents.length === 0 && (
                                    <li className="px-6 py-4 text-sm text-gray-500">
                                        No upcoming events.
                                    </li>
                                )}
                                {upcomingEvents.map((e) => (
                                    <li
                                        key={e.id}
                                        className="flex items-center justify-between px-6 py-3 text-sm"
                                    >
                                        <div>
                                            <div className="font-medium text-gray-800">
                                                {e.title}
                                            </div>
                                            <div className="capitalize text-gray-500">
                                                {e.event_type}
                                            </div>
                                        </div>
                                        <div className="text-xs text-gray-400">
                                            {e.start_at}
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

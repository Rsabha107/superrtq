import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Pagination from '@/Components/Pagination';
import PrimaryButton from '@/Components/PrimaryButton';
import { Paginated } from '@/types';
import { Head, Link, router } from '@inertiajs/react';

interface RewardRow {
    id: number;
    title: string;
    points_cost: number;
    quantity: number | null;
    status: string;
}

export default function Index({ rewards }: { rewards: Paginated<RewardRow> }) {
    const destroy = (reward: RewardRow) => {
        if (confirm(`Delete "${reward.title}"?`)) {
            router.delete(route('admin.rewards.destroy', reward.id));
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Rewards
                    </h2>
                    <Link href={route('admin.rewards.create')}>
                        <PrimaryButton>New Reward</PrimaryButton>
                    </Link>
                </div>
            }
        >
            <Head title="Rewards" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden rounded-lg bg-white shadow-sm">
                        <table className="min-w-full divide-y divide-gray-200 text-sm">
                            <thead className="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th className="px-6 py-3">Title</th>
                                    <th className="px-6 py-3">Points Cost</th>
                                    <th className="px-6 py-3">Remaining</th>
                                    <th className="px-6 py-3">Status</th>
                                    <th className="px-6 py-3" />
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100">
                                {rewards.data.map((reward) => (
                                    <tr
                                        key={reward.id}
                                        className="hover:bg-gray-50"
                                    >
                                        <td className="px-6 py-3 font-medium text-gray-800">
                                            {reward.title}
                                        </td>
                                        <td className="px-6 py-3 text-rtq-maroon">
                                            {reward.points_cost.toLocaleString()}
                                        </td>
                                        <td className="px-6 py-3 text-gray-500">
                                            {reward.quantity ?? 'Unlimited'}
                                        </td>
                                        <td className="px-6 py-3">
                                            <span
                                                className={
                                                    'rounded-full px-2 py-0.5 text-xs font-medium ' +
                                                    (reward.status === 'active'
                                                        ? 'bg-green-100 text-green-700'
                                                        : 'bg-gray-100 text-gray-600')
                                                }
                                            >
                                                {reward.status}
                                            </span>
                                        </td>
                                        <td className="space-x-3 px-6 py-3 text-right">
                                            <Link
                                                href={route(
                                                    'admin.rewards.edit',
                                                    reward.id,
                                                )}
                                                className="text-rtq-maroon hover:underline"
                                            >
                                                Edit
                                            </Link>
                                            <button
                                                onClick={() => destroy(reward)}
                                                className="text-red-600 hover:underline"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                    <Pagination links={rewards.links} />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

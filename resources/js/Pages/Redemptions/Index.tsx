import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Pagination from '@/Components/Pagination';
import { Paginated } from '@/types';
import { Head } from '@inertiajs/react';

interface RedemptionRow {
    id: number;
    fan_name: string;
    fan_number: string | null;
    reward_title: string;
    points_cost: number;
    status: string;
    redeemed_at: string;
}

export default function Index({
    redemptions,
}: {
    redemptions: Paginated<RedemptionRow>;
}) {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Redemptions
                </h2>
            }
        >
            <Head title="Redemptions" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden rounded-lg bg-white shadow-sm">
                        <table className="min-w-full divide-y divide-gray-200 text-sm">
                            <thead className="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th className="px-6 py-3">Fan</th>
                                    <th className="px-6 py-3">Reward</th>
                                    <th className="px-6 py-3">Points</th>
                                    <th className="px-6 py-3">Status</th>
                                    <th className="px-6 py-3">Redeemed At</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100">
                                {redemptions.data.map((r) => (
                                    <tr key={r.id} className="hover:bg-gray-50">
                                        <td className="px-6 py-3">
                                            <div className="font-medium text-gray-800">
                                                {r.fan_name}
                                            </div>
                                            <div className="text-xs text-gray-500">
                                                #{r.fan_number}
                                            </div>
                                        </td>
                                        <td className="px-6 py-3 text-gray-700">
                                            {r.reward_title}
                                        </td>
                                        <td className="px-6 py-3 text-rtq-maroon">
                                            {r.points_cost.toLocaleString()}
                                        </td>
                                        <td className="px-6 py-3">
                                            <span className="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">
                                                {r.status}
                                            </span>
                                        </td>
                                        <td className="px-6 py-3 text-gray-500">
                                            {r.redeemed_at}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                    <Pagination links={redemptions.links} />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

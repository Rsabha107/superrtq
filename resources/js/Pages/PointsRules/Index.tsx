import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Pagination from '@/Components/Pagination';
import PrimaryButton from '@/Components/PrimaryButton';
import { Paginated } from '@/types';
import { Head, Link, router } from '@inertiajs/react';

interface PointsRuleRow {
    id: number;
    name: string;
    description: string | null;
    points: number;
    status: string;
    event: { id: number; title: string } | null;
}

export default function Index({
    pointsRules,
}: {
    pointsRules: Paginated<PointsRuleRow>;
}) {
    const destroy = (rule: PointsRuleRow) => {
        if (confirm(`Delete "${rule.name}"?`)) {
            router.delete(route('admin.points-rules.destroy', rule.id));
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Points Rules
                    </h2>
                    <Link href={route('admin.points-rules.create')}>
                        <PrimaryButton>New Rule</PrimaryButton>
                    </Link>
                </div>
            }
        >
            <Head title="Points Rules" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden rounded-lg bg-white shadow-sm">
                        <table className="min-w-full divide-y divide-gray-200 text-sm">
                            <thead className="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th className="px-6 py-3">Name</th>
                                    <th className="px-6 py-3">Linked Event</th>
                                    <th className="px-6 py-3">Points</th>
                                    <th className="px-6 py-3">Status</th>
                                    <th className="px-6 py-3" />
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100">
                                {pointsRules.data.map((rule) => (
                                    <tr key={rule.id} className="hover:bg-gray-50">
                                        <td className="px-6 py-3">
                                            <div className="font-medium text-gray-800">
                                                {rule.name}
                                            </div>
                                            <div className="text-xs text-gray-500">
                                                {rule.description}
                                            </div>
                                        </td>
                                        <td className="px-6 py-3 text-gray-500">
                                            {rule.event?.title ?? 'General'}
                                        </td>
                                        <td className="px-6 py-3 font-medium text-rtq-maroon">
                                            +{rule.points}
                                        </td>
                                        <td className="px-6 py-3">
                                            <span
                                                className={
                                                    'rounded-full px-2 py-0.5 text-xs font-medium ' +
                                                    (rule.status === 'active'
                                                        ? 'bg-green-100 text-green-700'
                                                        : 'bg-gray-100 text-gray-600')
                                                }
                                            >
                                                {rule.status}
                                            </span>
                                        </td>
                                        <td className="space-x-3 px-6 py-3 text-right">
                                            <Link
                                                href={route(
                                                    'admin.points-rules.edit',
                                                    rule.id,
                                                )}
                                                className="text-rtq-maroon hover:underline"
                                            >
                                                Edit
                                            </Link>
                                            <button
                                                onClick={() => destroy(rule)}
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
                    <Pagination links={pointsRules.links} />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

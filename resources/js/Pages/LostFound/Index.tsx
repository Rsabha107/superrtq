import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Pagination from '@/Components/Pagination';
import { Paginated } from '@/types';
import { Head, router } from '@inertiajs/react';

interface LostFoundRow {
    id: number;
    fan_name: string;
    fan_number: string | null;
    item_description: string;
    location: string | null;
    status: string;
    created_at: string;
}

const STATUS_STYLES: Record<string, string> = {
    reported: 'bg-gray-100 text-gray-600',
    found: 'bg-green-100 text-green-700',
    closed: 'bg-blue-100 text-blue-700',
};

export default function Index({
    reports,
}: {
    reports: Paginated<LostFoundRow>;
}) {
    const updateStatus = (report: LostFoundRow, status: string) => {
        router.put(
            route('admin.lost-found.update', report.id),
            { status },
            { preserveScroll: true },
        );
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Lost &amp; Found
                </h2>
            }
        >
            <Head title="Lost & Found" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden rounded-lg bg-white shadow-sm">
                        <table className="min-w-full divide-y divide-gray-200 text-sm">
                            <thead className="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th className="px-6 py-3">Fan</th>
                                    <th className="px-6 py-3">Item</th>
                                    <th className="px-6 py-3">Location</th>
                                    <th className="px-6 py-3">Reported</th>
                                    <th className="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100">
                                {reports.data.map((r) => (
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
                                            {r.item_description}
                                        </td>
                                        <td className="px-6 py-3 text-gray-500">
                                            {r.location ?? '—'}
                                        </td>
                                        <td className="px-6 py-3 text-gray-500">
                                            {r.created_at}
                                        </td>
                                        <td className="px-6 py-3">
                                            <select
                                                value={r.status}
                                                onChange={(e) =>
                                                    updateStatus(
                                                        r,
                                                        e.target.value,
                                                    )
                                                }
                                                className={
                                                    'rounded-full border-0 px-2 py-0.5 text-xs font-medium focus:ring-2 focus:ring-rtq-maroon ' +
                                                    (STATUS_STYLES[
                                                        r.status
                                                    ] ??
                                                        'bg-gray-100 text-gray-600')
                                                }
                                            >
                                                <option value="reported">
                                                    Reported
                                                </option>
                                                <option value="found">
                                                    Found
                                                </option>
                                                <option value="closed">
                                                    Closed
                                                </option>
                                            </select>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                    <Pagination links={reports.links} />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

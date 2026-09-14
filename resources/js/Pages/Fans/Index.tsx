import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Pagination from '@/Components/Pagination';
import { Paginated } from '@/types';
import { Head, Link } from '@inertiajs/react';

interface FanRow {
    id: number;
    display_name: string | null;
    email: string | null;
    mobile: string | null;
    fan_number: string | null;
    status: string;
    points_balance: number;
    member_since: string | null;
}

export default function Index({ fans }: { fans: Paginated<FanRow> }) {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Fans
                </h2>
            }
        >
            <Head title="Fans" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden rounded-lg bg-white shadow-sm">
                        <table className="min-w-full divide-y divide-gray-200 text-sm">
                            <thead className="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th className="px-6 py-3">Name</th>
                                    <th className="px-6 py-3">Fan Number</th>
                                    <th className="px-6 py-3">Contact</th>
                                    <th className="px-6 py-3">Status</th>
                                    <th className="px-6 py-3">Points</th>
                                    <th className="px-6 py-3">Member Since</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100">
                                {fans.data.map((fan) => (
                                    <tr key={fan.id} className="hover:bg-gray-50">
                                        <td className="px-6 py-3">
                                            <Link
                                                href={route(
                                                    'admin.fans.show',
                                                    fan.id,
                                                )}
                                                className="font-medium text-rtq-maroon hover:underline"
                                            >
                                                {fan.display_name ?? '—'}
                                            </Link>
                                        </td>
                                        <td className="px-6 py-3">
                                            {fan.fan_number ?? '—'}
                                        </td>
                                        <td className="px-6 py-3 text-gray-500">
                                            {fan.email ?? fan.mobile ?? '—'}
                                        </td>
                                        <td className="px-6 py-3">
                                            <span
                                                className={
                                                    'rounded-full px-2 py-0.5 text-xs font-medium ' +
                                                    (fan.status === 'verified'
                                                        ? 'bg-green-100 text-green-700'
                                                        : 'bg-amber-100 text-amber-700')
                                                }
                                            >
                                                {fan.status}
                                            </span>
                                        </td>
                                        <td className="px-6 py-3">
                                            {fan.points_balance.toLocaleString()}
                                        </td>
                                        <td className="px-6 py-3 text-gray-500">
                                            {fan.member_since ?? '—'}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                    <Pagination links={fans.links} />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

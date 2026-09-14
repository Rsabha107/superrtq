import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Pagination from '@/Components/Pagination';
import PrimaryButton from '@/Components/PrimaryButton';
import { Paginated } from '@/types';
import { Head, Link, router } from '@inertiajs/react';

interface StadiumRow {
    id: number;
    name: string;
    city: string;
    capacity: number;
    status: string;
}

export default function Index({ stadiums }: { stadiums: Paginated<StadiumRow> }) {
    const destroy = (stadium: StadiumRow) => {
        if (confirm(`Delete "${stadium.name}"?`)) {
            router.delete(route('admin.stadiums.destroy', stadium.id));
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Stadiums
                    </h2>
                    <Link href={route('admin.stadiums.create')}>
                        <PrimaryButton>New Stadium</PrimaryButton>
                    </Link>
                </div>
            }
        >
            <Head title="Stadiums" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden rounded-lg bg-white shadow-sm">
                        <table className="min-w-full divide-y divide-gray-200 text-sm">
                            <thead className="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th className="px-6 py-3">Name</th>
                                    <th className="px-6 py-3">City</th>
                                    <th className="px-6 py-3">Capacity</th>
                                    <th className="px-6 py-3">Status</th>
                                    <th className="px-6 py-3" />
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100">
                                {stadiums.data.map((stadium) => (
                                    <tr
                                        key={stadium.id}
                                        className="hover:bg-gray-50"
                                    >
                                        <td className="px-6 py-3 font-medium text-gray-800">
                                            {stadium.name}
                                        </td>
                                        <td className="px-6 py-3 text-gray-500">
                                            {stadium.city}
                                        </td>
                                        <td className="px-6 py-3 text-gray-500">
                                            {stadium.capacity.toLocaleString()}
                                        </td>
                                        <td className="px-6 py-3">
                                            <span
                                                className={
                                                    'rounded-full px-2 py-0.5 text-xs font-medium ' +
                                                    (stadium.status ===
                                                    'active'
                                                        ? 'bg-green-100 text-green-700'
                                                        : 'bg-gray-100 text-gray-600')
                                                }
                                            >
                                                {stadium.status}
                                            </span>
                                        </td>
                                        <td className="space-x-3 px-6 py-3 text-right">
                                            <Link
                                                href={route(
                                                    'admin.stadiums.edit',
                                                    stadium.id,
                                                )}
                                                className="text-rtq-maroon hover:underline"
                                            >
                                                Edit
                                            </Link>
                                            <button
                                                onClick={() =>
                                                    destroy(stadium)
                                                }
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
                    <Pagination links={stadiums.links} />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

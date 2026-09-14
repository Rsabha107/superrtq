import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Pagination from '@/Components/Pagination';
import PrimaryButton from '@/Components/PrimaryButton';
import { Paginated } from '@/types';
import { Head, Link, router } from '@inertiajs/react';

interface EventRow {
    id: number;
    title: string;
    event_type: string;
    venue: string | null;
    start_at: string;
    status: string;
    is_featured: boolean;
}

export default function Index({ events }: { events: Paginated<EventRow> }) {
    const destroy = (event: EventRow) => {
        if (confirm(`Delete "${event.title}"?`)) {
            router.delete(route('admin.events.destroy', event.id));
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Events
                    </h2>
                    <Link href={route('admin.events.create')}>
                        <PrimaryButton>New Event</PrimaryButton>
                    </Link>
                </div>
            }
        >
            <Head title="Events" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden rounded-lg bg-white shadow-sm">
                        <table className="min-w-full divide-y divide-gray-200 text-sm">
                            <thead className="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th className="px-6 py-3">Title</th>
                                    <th className="px-6 py-3">Type</th>
                                    <th className="px-6 py-3">Venue</th>
                                    <th className="px-6 py-3">Starts</th>
                                    <th className="px-6 py-3">Status</th>
                                    <th className="px-6 py-3">Featured</th>
                                    <th className="px-6 py-3" />
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100">
                                {events.data.map((event) => (
                                    <tr key={event.id} className="hover:bg-gray-50">
                                        <td className="px-6 py-3 font-medium text-gray-800">
                                            {event.title}
                                        </td>
                                        <td className="px-6 py-3 capitalize text-gray-500">
                                            {event.event_type}
                                        </td>
                                        <td className="px-6 py-3 text-gray-500">
                                            {event.venue ?? '—'}
                                        </td>
                                        <td className="px-6 py-3 text-gray-500">
                                            {event.start_at}
                                        </td>
                                        <td className="px-6 py-3">
                                            <span
                                                className={
                                                    'rounded-full px-2 py-0.5 text-xs font-medium ' +
                                                    (event.status ===
                                                    'published'
                                                        ? 'bg-green-100 text-green-700'
                                                        : 'bg-gray-100 text-gray-600')
                                                }
                                            >
                                                {event.status}
                                            </span>
                                        </td>
                                        <td className="px-6 py-3">
                                            {event.is_featured ? '⭐' : ''}
                                        </td>
                                        <td className="space-x-3 px-6 py-3 text-right">
                                            <Link
                                                href={route(
                                                    'admin.events.edit',
                                                    event.id,
                                                )}
                                                className="text-rtq-maroon hover:underline"
                                            >
                                                Edit
                                            </Link>
                                            <button
                                                onClick={() => destroy(event)}
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
                    <Pagination links={events.links} />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

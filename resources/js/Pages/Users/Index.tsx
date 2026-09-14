import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Pagination from '@/Components/Pagination';
import PrimaryButton from '@/Components/PrimaryButton';
import { Paginated, User } from '@/types';
import { Head, Link, router } from '@inertiajs/react';

export default function Index({ users }: { users: Paginated<User> }) {
    const destroy = (user: User) => {
        if (confirm(`Delete "${user.name}"?`)) {
            router.delete(route('admin.users.destroy', user.id));
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Admin Users
                    </h2>
                    <Link href={route('admin.users.create')}>
                        <PrimaryButton>New User</PrimaryButton>
                    </Link>
                </div>
            }
        >
            <Head title="Admin Users" />

            <div className="py-12">
                <div className="mx-auto max-w-5xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden rounded-lg bg-white shadow-sm">
                        <table className="min-w-full divide-y divide-gray-200 text-sm">
                            <thead className="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th className="px-6 py-3">Name</th>
                                    <th className="px-6 py-3">Email</th>
                                    <th className="px-6 py-3">Role</th>
                                    <th className="px-6 py-3">Status</th>
                                    <th className="px-6 py-3" />
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100">
                                {users.data.map((user) => (
                                    <tr key={user.id} className="hover:bg-gray-50">
                                        <td className="px-6 py-3 font-medium text-gray-800">
                                            {user.name}
                                        </td>
                                        <td className="px-6 py-3 text-gray-500">
                                            {user.email}
                                        </td>
                                        <td className="px-6 py-3 capitalize text-gray-600">
                                            {user.role}
                                        </td>
                                        <td className="px-6 py-3">
                                            <span
                                                className={
                                                    'rounded-full px-2 py-0.5 text-xs font-medium ' +
                                                    (user.status === 'active'
                                                        ? 'bg-green-100 text-green-700'
                                                        : 'bg-gray-100 text-gray-600')
                                                }
                                            >
                                                {user.status}
                                            </span>
                                        </td>
                                        <td className="space-x-3 px-6 py-3 text-right">
                                            <Link
                                                href={route(
                                                    'admin.users.edit',
                                                    user.id,
                                                )}
                                                className="text-rtq-maroon hover:underline"
                                            >
                                                Edit
                                            </Link>
                                            <button
                                                onClick={() => destroy(user)}
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
                    <Pagination links={users.links} />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

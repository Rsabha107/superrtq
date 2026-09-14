import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Pagination from '@/Components/Pagination';
import PrimaryButton from '@/Components/PrimaryButton';
import { Paginated } from '@/types';
import { Head, Link, router } from '@inertiajs/react';

interface MatchRow {
    id: number;
    sport: string;
    group_name: string | null;
    home_team: string;
    away_team: string;
    kickoff_at: string;
    home_score: number | null;
    away_score: number | null;
    status: string;
}

export default function Index({ matches }: { matches: Paginated<MatchRow> }) {
    const destroy = (match: MatchRow) => {
        if (
            confirm(`Delete "${match.home_team} vs ${match.away_team}"?`)
        ) {
            router.delete(route('admin.matches.destroy', match.id));
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Matches
                    </h2>
                    <Link href={route('admin.matches.create')}>
                        <PrimaryButton>New Fixture</PrimaryButton>
                    </Link>
                </div>
            }
        >
            <Head title="Matches" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden rounded-lg bg-white shadow-sm">
                        <table className="min-w-full divide-y divide-gray-200 text-sm">
                            <thead className="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th className="px-6 py-3">Fixture</th>
                                    <th className="px-6 py-3">Sport</th>
                                    <th className="px-6 py-3">Group</th>
                                    <th className="px-6 py-3">Kickoff</th>
                                    <th className="px-6 py-3">Score</th>
                                    <th className="px-6 py-3">Status</th>
                                    <th className="px-6 py-3" />
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100">
                                {matches.data.map((match) => (
                                    <tr key={match.id} className="hover:bg-gray-50">
                                        <td className="px-6 py-3 font-medium text-gray-800">
                                            {match.home_team} vs {match.away_team}
                                        </td>
                                        <td className="px-6 py-3 text-gray-500">
                                            {match.sport}
                                        </td>
                                        <td className="px-6 py-3 text-gray-500">
                                            {match.group_name ?? '—'}
                                        </td>
                                        <td className="px-6 py-3 text-gray-500">
                                            {new Date(
                                                match.kickoff_at,
                                            ).toLocaleString()}
                                        </td>
                                        <td className="px-6 py-3 text-gray-500">
                                            {match.home_score !== null &&
                                            match.away_score !== null
                                                ? `${match.home_score}–${match.away_score}`
                                                : '—'}
                                        </td>
                                        <td className="px-6 py-3">
                                            <span
                                                className={
                                                    'rounded-full px-2 py-0.5 text-xs font-medium ' +
                                                    (match.status ===
                                                    'finished'
                                                        ? 'bg-green-100 text-green-700'
                                                        : 'bg-gray-100 text-gray-600')
                                                }
                                            >
                                                {match.status}
                                            </span>
                                        </td>
                                        <td className="space-x-3 px-6 py-3 text-right">
                                            <Link
                                                href={route(
                                                    'admin.matches.edit',
                                                    match.id,
                                                )}
                                                className="text-rtq-maroon hover:underline"
                                            >
                                                Edit
                                            </Link>
                                            <button
                                                onClick={() => destroy(match)}
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
                    <Pagination links={matches.links} />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

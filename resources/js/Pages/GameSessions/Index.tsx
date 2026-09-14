import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Pagination from '@/Components/Pagination';
import { Paginated } from '@/types';
import { Head } from '@inertiajs/react';

interface GameSessionRow {
    id: number;
    fan_name: string;
    fan_number: string | null;
    game: string;
    score: number;
    points_awarded: number;
    created_at: string;
}

const GAME_LABELS: Record<string, string> = {
    flag_quiz: 'Flag Quiz',
    penalty_shootout: 'Penalty Shootout',
    flappy_dunk: 'Flappy Dunk',
};

export default function Index({
    sessions,
}: {
    sessions: Paginated<GameSessionRow>;
}) {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Game Sessions
                </h2>
            }
        >
            <Head title="Game Sessions" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden rounded-lg bg-white shadow-sm">
                        <table className="min-w-full divide-y divide-gray-200 text-sm">
                            <thead className="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th className="px-6 py-3">Fan</th>
                                    <th className="px-6 py-3">Game</th>
                                    <th className="px-6 py-3">Score</th>
                                    <th className="px-6 py-3">Points</th>
                                    <th className="px-6 py-3">Played</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100">
                                {sessions.data.map((s) => (
                                    <tr key={s.id} className="hover:bg-gray-50">
                                        <td className="px-6 py-3">
                                            <div className="font-medium text-gray-800">
                                                {s.fan_name}
                                            </div>
                                            <div className="text-xs text-gray-500">
                                                #{s.fan_number}
                                            </div>
                                        </td>
                                        <td className="px-6 py-3 text-gray-700">
                                            {GAME_LABELS[s.game] ?? s.game}
                                        </td>
                                        <td className="px-6 py-3 text-gray-500">
                                            {s.score}
                                        </td>
                                        <td className="px-6 py-3 text-rtq-maroon">
                                            +{s.points_awarded}
                                        </td>
                                        <td className="px-6 py-3 text-gray-500">
                                            {s.created_at}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                    <Pagination links={sessions.links} />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

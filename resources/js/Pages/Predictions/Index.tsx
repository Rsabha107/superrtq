import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Pagination from '@/Components/Pagination';
import { Paginated } from '@/types';
import { Head } from '@inertiajs/react';

interface PredictionRow {
    id: number;
    fan_name: string;
    fan_number: string | null;
    fixture: string;
    kickoff_at: string;
    predicted_score: string;
    points_awarded: number;
    created_at: string;
}

export default function Index({
    predictions,
}: {
    predictions: Paginated<PredictionRow>;
}) {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Predictions
                </h2>
            }
        >
            <Head title="Predictions" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden rounded-lg bg-white shadow-sm">
                        <table className="min-w-full divide-y divide-gray-200 text-sm">
                            <thead className="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th className="px-6 py-3">Fan</th>
                                    <th className="px-6 py-3">Fixture</th>
                                    <th className="px-6 py-3">Kickoff</th>
                                    <th className="px-6 py-3">Prediction</th>
                                    <th className="px-6 py-3">Points</th>
                                    <th className="px-6 py-3">Submitted</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100">
                                {predictions.data.map((p) => (
                                    <tr key={p.id} className="hover:bg-gray-50">
                                        <td className="px-6 py-3">
                                            <div className="font-medium text-gray-800">
                                                {p.fan_name}
                                            </div>
                                            <div className="text-xs text-gray-500">
                                                #{p.fan_number}
                                            </div>
                                        </td>
                                        <td className="px-6 py-3 text-gray-700">
                                            {p.fixture}
                                        </td>
                                        <td className="px-6 py-3 text-gray-500">
                                            {p.kickoff_at}
                                        </td>
                                        <td className="px-6 py-3 font-medium text-gray-800">
                                            {p.predicted_score}
                                        </td>
                                        <td className="px-6 py-3 text-rtq-maroon">
                                            +{p.points_awarded}
                                        </td>
                                        <td className="px-6 py-3 text-gray-500">
                                            {p.created_at}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                    <Pagination links={predictions.links} />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

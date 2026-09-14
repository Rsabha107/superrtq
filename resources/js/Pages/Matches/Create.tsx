import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import SelectInput from '@/Components/SelectInput';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import { Head, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        sport: 'football',
        group_name: '',
        home_team: '',
        away_team: '',
        venue: '',
        kickoff_at: '',
        home_score: '',
        away_score: '',
        status: 'scheduled',
        is_featured: false as boolean,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('admin.matches.store'));
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    New Fixture
                </h2>
            }
        >
            <Head title="New Fixture" />

            <div className="py-12">
                <div className="mx-auto max-w-3xl sm:px-6 lg:px-8">
                    <form
                        onSubmit={submit}
                        className="space-y-6 rounded-lg bg-white p-6 shadow-sm"
                    >
                        <div className="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel
                                    htmlFor="home_team"
                                    value="Home Team"
                                />
                                <TextInput
                                    id="home_team"
                                    className="mt-1 block w-full"
                                    value={data.home_team}
                                    onChange={(e) =>
                                        setData('home_team', e.target.value)
                                    }
                                />
                                <InputError message={errors.home_team} />
                            </div>
                            <div>
                                <InputLabel
                                    htmlFor="away_team"
                                    value="Away Team"
                                />
                                <TextInput
                                    id="away_team"
                                    className="mt-1 block w-full"
                                    value={data.away_team}
                                    onChange={(e) =>
                                        setData('away_team', e.target.value)
                                    }
                                />
                                <InputError message={errors.away_team} />
                            </div>
                        </div>

                        <div className="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel htmlFor="sport" value="Sport" />
                                <TextInput
                                    id="sport"
                                    className="mt-1 block w-full"
                                    placeholder="football, basketball…"
                                    value={data.sport}
                                    onChange={(e) =>
                                        setData('sport', e.target.value)
                                    }
                                />
                                <InputError message={errors.sport} />
                            </div>
                            <div>
                                <InputLabel
                                    htmlFor="group_name"
                                    value="Group (optional)"
                                />
                                <TextInput
                                    id="group_name"
                                    className="mt-1 block w-full"
                                    placeholder="Group A"
                                    value={data.group_name}
                                    onChange={(e) =>
                                        setData('group_name', e.target.value)
                                    }
                                />
                                <InputError message={errors.group_name} />
                            </div>
                        </div>

                        <div className="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel
                                    htmlFor="venue"
                                    value="Venue (optional)"
                                />
                                <TextInput
                                    id="venue"
                                    className="mt-1 block w-full"
                                    value={data.venue}
                                    onChange={(e) =>
                                        setData('venue', e.target.value)
                                    }
                                />
                                <InputError message={errors.venue} />
                            </div>
                            <div>
                                <InputLabel
                                    htmlFor="kickoff_at"
                                    value="Kickoff"
                                />
                                <TextInput
                                    id="kickoff_at"
                                    type="datetime-local"
                                    className="mt-1 block w-full"
                                    value={data.kickoff_at}
                                    onChange={(e) =>
                                        setData('kickoff_at', e.target.value)
                                    }
                                />
                                <InputError message={errors.kickoff_at} />
                            </div>
                        </div>

                        <div className="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel
                                    htmlFor="home_score"
                                    value="Home Score (if played)"
                                />
                                <TextInput
                                    id="home_score"
                                    type="number"
                                    className="mt-1 block w-full"
                                    value={data.home_score}
                                    onChange={(e) =>
                                        setData('home_score', e.target.value)
                                    }
                                />
                                <InputError message={errors.home_score} />
                            </div>
                            <div>
                                <InputLabel
                                    htmlFor="away_score"
                                    value="Away Score (if played)"
                                />
                                <TextInput
                                    id="away_score"
                                    type="number"
                                    className="mt-1 block w-full"
                                    value={data.away_score}
                                    onChange={(e) =>
                                        setData('away_score', e.target.value)
                                    }
                                />
                                <InputError message={errors.away_score} />
                            </div>
                        </div>

                        <div>
                            <InputLabel htmlFor="status" value="Status" />
                            <SelectInput
                                id="status"
                                className="mt-1 block w-full"
                                value={data.status}
                                onChange={(e) =>
                                    setData('status', e.target.value)
                                }
                            >
                                <option value="scheduled">Scheduled</option>
                                <option value="finished">Finished</option>
                            </SelectInput>
                            <InputError message={errors.status} />
                        </div>

                        <label className="flex items-center gap-2 text-sm text-gray-700">
                            <input
                                type="checkbox"
                                checked={data.is_featured}
                                onChange={(e) =>
                                    setData('is_featured', e.target.checked)
                                }
                                className="rounded border-gray-300 text-rtq-maroon focus:ring-rtq-maroon"
                            />
                            Feature on Home
                        </label>

                        <PrimaryButton disabled={processing}>
                            Create Fixture
                        </PrimaryButton>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

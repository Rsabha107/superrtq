import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import TextArea from '@/Components/TextArea';
import SelectInput from '@/Components/SelectInput';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import { Head, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';

interface EventOption {
    id: number;
    title: string;
}

interface PointsRuleData {
    id: number;
    name: string;
    description: string | null;
    points: number;
    event_id: number | null;
    status: string;
}

export default function Edit({
    pointsRule,
    events,
}: {
    pointsRule: PointsRuleData;
    events: EventOption[];
}) {
    const { data, setData, put, processing, errors } = useForm({
        name: pointsRule.name,
        description: pointsRule.description ?? '',
        points: pointsRule.points,
        event_id: pointsRule.event_id ?? ('' as number | ''),
        status: pointsRule.status,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        put(route('admin.points-rules.update', pointsRule.id));
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Edit Points Rule
                </h2>
            }
        >
            <Head title="Edit Points Rule" />

            <div className="py-12">
                <div className="mx-auto max-w-2xl sm:px-6 lg:px-8">
                    <form
                        onSubmit={submit}
                        className="space-y-6 rounded-lg bg-white p-6 shadow-sm"
                    >
                        <div>
                            <InputLabel htmlFor="name" value="Name" />
                            <TextInput
                                id="name"
                                className="mt-1 block w-full"
                                value={data.name}
                                onChange={(e) =>
                                    setData('name', e.target.value)
                                }
                            />
                            <InputError message={errors.name} />
                        </div>

                        <div>
                            <InputLabel
                                htmlFor="description"
                                value="Description"
                            />
                            <TextArea
                                id="description"
                                rows={3}
                                className="mt-1 block w-full"
                                value={data.description}
                                onChange={(e) =>
                                    setData('description', e.target.value)
                                }
                            />
                            <InputError message={errors.description} />
                        </div>

                        <div className="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel htmlFor="points" value="Points" />
                                <TextInput
                                    id="points"
                                    type="number"
                                    className="mt-1 block w-full"
                                    value={data.points}
                                    onChange={(e) =>
                                        setData(
                                            'points',
                                            Number(e.target.value),
                                        )
                                    }
                                />
                                <InputError message={errors.points} />
                            </div>
                            <div>
                                <InputLabel
                                    htmlFor="event_id"
                                    value="Linked Event (optional)"
                                />
                                <SelectInput
                                    id="event_id"
                                    className="mt-1 block w-full"
                                    value={data.event_id}
                                    onChange={(e) =>
                                        setData(
                                            'event_id',
                                            e.target.value
                                                ? Number(e.target.value)
                                                : '',
                                        )
                                    }
                                >
                                    <option value="">General</option>
                                    {events.map((event) => (
                                        <option key={event.id} value={event.id}>
                                            {event.title}
                                        </option>
                                    ))}
                                </SelectInput>
                                <InputError message={errors.event_id} />
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
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </SelectInput>
                            <InputError message={errors.status} />
                        </div>

                        <PrimaryButton disabled={processing}>
                            Save Changes
                        </PrimaryButton>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

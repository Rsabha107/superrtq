import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import TextArea from '@/Components/TextArea';
import SelectInput from '@/Components/SelectInput';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import { Head, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';

interface EventData {
    id: number;
    title: string;
    event_type: string;
    short_description: string | null;
    description: string | null;
    venue: string | null;
    start_at: string;
    end_at: string | null;
    image: string | null;
    accent: string | null;
    status: string;
    is_featured: boolean;
}

function toLocalInput(value: string | null): string {
    if (!value) return '';
    return value.slice(0, 16);
}

export default function Edit({ event }: { event: EventData }) {
    const { data, setData, put, processing, errors } = useForm({
        title: event.title,
        event_type: event.event_type,
        short_description: event.short_description ?? '',
        description: event.description ?? '',
        venue: event.venue ?? '',
        start_at: toLocalInput(event.start_at),
        end_at: toLocalInput(event.end_at),
        image: event.image ?? '',
        accent: event.accent ?? '#8A1538',
        status: event.status,
        is_featured: event.is_featured,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        put(route('admin.events.update', event.id));
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Edit Event
                </h2>
            }
        >
            <Head title="Edit Event" />

            <div className="py-12">
                <div className="mx-auto max-w-3xl sm:px-6 lg:px-8">
                    <form
                        onSubmit={submit}
                        className="space-y-6 rounded-lg bg-white p-6 shadow-sm"
                    >
                        <div>
                            <InputLabel htmlFor="title" value="Title" />
                            <TextInput
                                id="title"
                                className="mt-1 block w-full"
                                value={data.title}
                                onChange={(e) =>
                                    setData('title', e.target.value)
                                }
                            />
                            <InputError message={errors.title} />
                        </div>

                        <div className="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel
                                    htmlFor="event_type"
                                    value="Event Type"
                                />
                                <TextInput
                                    id="event_type"
                                    className="mt-1 block w-full"
                                    value={data.event_type}
                                    onChange={(e) =>
                                        setData('event_type', e.target.value)
                                    }
                                />
                                <InputError message={errors.event_type} />
                            </div>
                            <div>
                                <InputLabel htmlFor="venue" value="Venue" />
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
                        </div>

                        <div>
                            <InputLabel
                                htmlFor="short_description"
                                value="Short Description"
                            />
                            <TextInput
                                id="short_description"
                                className="mt-1 block w-full"
                                value={data.short_description}
                                onChange={(e) =>
                                    setData(
                                        'short_description',
                                        e.target.value,
                                    )
                                }
                            />
                            <InputError message={errors.short_description} />
                        </div>

                        <div>
                            <InputLabel
                                htmlFor="description"
                                value="Description"
                            />
                            <TextArea
                                id="description"
                                rows={4}
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
                                <InputLabel
                                    htmlFor="start_at"
                                    value="Starts At"
                                />
                                <TextInput
                                    id="start_at"
                                    type="datetime-local"
                                    className="mt-1 block w-full"
                                    value={data.start_at}
                                    onChange={(e) =>
                                        setData('start_at', e.target.value)
                                    }
                                />
                                <InputError message={errors.start_at} />
                            </div>
                            <div>
                                <InputLabel htmlFor="end_at" value="Ends At" />
                                <TextInput
                                    id="end_at"
                                    type="datetime-local"
                                    className="mt-1 block w-full"
                                    value={data.end_at}
                                    onChange={(e) =>
                                        setData('end_at', e.target.value)
                                    }
                                />
                                <InputError message={errors.end_at} />
                            </div>
                        </div>

                        <div className="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel
                                    htmlFor="image"
                                    value="Image Path (optional)"
                                />
                                <TextInput
                                    id="image"
                                    className="mt-1 block w-full"
                                    value={data.image}
                                    onChange={(e) =>
                                        setData('image', e.target.value)
                                    }
                                />
                                <InputError message={errors.image} />
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
                                    <option value="draft">Draft</option>
                                    <option value="published">
                                        Published
                                    </option>
                                </SelectInput>
                                <InputError message={errors.status} />
                            </div>
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
                            Save Changes
                        </PrimaryButton>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

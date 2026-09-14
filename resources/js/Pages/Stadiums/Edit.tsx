import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import TextArea from '@/Components/TextArea';
import SelectInput from '@/Components/SelectInput';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import { Head, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';

interface StadiumData {
    id: number;
    name: string;
    city: string;
    capacity: number;
    description: string | null;
    status: string;
}

export default function Edit({ stadium }: { stadium: StadiumData }) {
    const { data, setData, put, processing, errors } = useForm({
        name: stadium.name,
        city: stadium.city,
        capacity: stadium.capacity,
        description: stadium.description ?? '',
        status: stadium.status,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        put(route('admin.stadiums.update', stadium.id));
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Edit Stadium
                </h2>
            }
        >
            <Head title="Edit Stadium" />

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

                        <div className="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel htmlFor="city" value="City" />
                                <TextInput
                                    id="city"
                                    className="mt-1 block w-full"
                                    value={data.city}
                                    onChange={(e) =>
                                        setData('city', e.target.value)
                                    }
                                />
                                <InputError message={errors.city} />
                            </div>
                            <div>
                                <InputLabel
                                    htmlFor="capacity"
                                    value="Capacity"
                                />
                                <TextInput
                                    id="capacity"
                                    type="number"
                                    className="mt-1 block w-full"
                                    value={data.capacity}
                                    onChange={(e) =>
                                        setData(
                                            'capacity',
                                            Number(e.target.value),
                                        )
                                    }
                                />
                                <InputError message={errors.capacity} />
                            </div>
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

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import TextArea from '@/Components/TextArea';
import SelectInput from '@/Components/SelectInput';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import { Head, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        title: '',
        description: '',
        image: '',
        points_cost: 500,
        quantity: '' as number | '',
        status: 'active',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('admin.rewards.store'));
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    New Reward
                </h2>
            }
        >
            <Head title="New Reward" />

            <div className="py-12">
                <div className="mx-auto max-w-2xl sm:px-6 lg:px-8">
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

                        <div>
                            <InputLabel
                                htmlFor="image"
                                value="Image Path (optional)"
                            />
                            <TextInput
                                id="image"
                                className="mt-1 block w-full"
                                placeholder="/images/rewards/example.jpg"
                                value={data.image}
                                onChange={(e) =>
                                    setData('image', e.target.value)
                                }
                            />
                            <InputError message={errors.image} />
                        </div>

                        <div className="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel
                                    htmlFor="points_cost"
                                    value="Points Cost"
                                />
                                <TextInput
                                    id="points_cost"
                                    type="number"
                                    className="mt-1 block w-full"
                                    value={data.points_cost}
                                    onChange={(e) =>
                                        setData(
                                            'points_cost',
                                            Number(e.target.value),
                                        )
                                    }
                                />
                                <InputError message={errors.points_cost} />
                            </div>
                            <div>
                                <InputLabel
                                    htmlFor="quantity"
                                    value="Quantity (blank = unlimited)"
                                />
                                <TextInput
                                    id="quantity"
                                    type="number"
                                    className="mt-1 block w-full"
                                    value={data.quantity}
                                    onChange={(e) =>
                                        setData(
                                            'quantity',
                                            e.target.value
                                                ? Number(e.target.value)
                                                : '',
                                        )
                                    }
                                />
                                <InputError message={errors.quantity} />
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
                            Create Reward
                        </PrimaryButton>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

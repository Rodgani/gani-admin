import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler } from 'react';

import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AuthLayout from '@/layouts/auth-layout';
import { Role } from '../admin/role/types/role.types';

type RegisterForm = {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    role: string;
    country: string;
};
interface RegisterProps {
    roles: Role[];
    timezones: string[];
}
export default function Register({ roles, timezones }: RegisterProps) {
    const { data, setData, post, processing, errors, reset } = useForm<Required<RegisterForm>>({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: '',
        country: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('register'), {
            onFinish: () => reset('password', 'password_confirmation', 'role', 'country'),
        });
    };

    const fields = [
        { key: 'country', label: 'Country', type: 'select', options: timezones },
        {
            key: 'role',
            label: 'Your Role',
            type: 'select',
            options: roles.map((role) => ({ label: role.name, value: String(role.id) })),
        },
        { key: 'name', label: 'Name', type: 'text', autoComplete: 'name' },
        { key: 'email', label: 'Email address', type: 'text', autoComplete: 'email' },
        { key: 'password', label: 'Password', type: 'password', autoComplete: 'new-password' },
        { key: 'password_confirmation', label: 'Confirm Password', type: 'password', autoComplete: 'new-password' },
    ];

    return (
        <AuthLayout title="Create an account" description="Enter your details below to create your account">
            <Head title="Register" />
            <form className="flex flex-col gap-6" onSubmit={submit}>
                <div className="grid gap-6">
                    {fields.map(({ key, label, type, autoComplete, options }) => (
                        <div className="grid gap-2" key={key}>
                            <Label htmlFor={key}>{label}</Label>

                            {type === 'select' ? (
                                <Select value={data[key as keyof RegisterForm]} onValueChange={(value) => setData(key as keyof RegisterForm, value)}>
                                    <SelectTrigger>
                                        <SelectValue placeholder={`Select ${label}`} />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {options?.map((option) =>
                                            typeof option === 'string' ? (
                                                <SelectItem key={option} value={option}>
                                                    {option}
                                                </SelectItem>
                                            ) : (
                                                <SelectItem key={option.value} value={option.value}>
                                                    {option.label}
                                                </SelectItem>
                                            ),
                                        )}
                                    </SelectContent>
                                </Select>
                            ) : (
                                <Input
                                    id={key}
                                    type={type}
                                    value={data[key as keyof RegisterForm]}
                                    autoComplete={autoComplete}
                                    onChange={(e) => setData(key as keyof RegisterForm, e.target.value)}
                                    placeholder={label}
                                />
                            )}

                            <InputError message={errors[key as keyof RegisterForm]} />
                        </div>
                    ))}

                    <Button type="submit" className="mt-2 w-full" tabIndex={5} disabled={processing}>
                        {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                        Create account
                    </Button>
                </div>

                <div className="text-muted-foreground text-center text-sm">
                    Already have an account?{' '}
                    <TextLink href={route('login')} tabIndex={6}>
                        Log in
                    </TextLink>
                </div>
            </form>
        </AuthLayout>
    );
}

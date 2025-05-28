import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { DialogDescription } from '@radix-ui/react-dialog';
import { useEffect, useMemo, useState } from 'react';
import { User, UserForm } from '../types/user.types';
interface UserFormModalProps {
    isOpen: boolean;
    onClose: () => void;
    user?: User;
    onSubmit: (formData: UserForm, userId?: number) => void;
    errors: UserForm;
    roles: { name: string; id: number }[];
}

export default function UserFormModal({ isOpen, onClose, user, onSubmit, errors, roles }: UserFormModalProps) {
    const [formData, setFormData] = useState<UserForm>({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role_id: '',
    });

    const resetFormData = useMemo(() => ({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role_id: '',
      }), []);

    const [visibleErrors, setVisibleErrors] = useState<UserForm>(resetFormData);

    useEffect(() => {
        setVisibleErrors(errors); // Set errors only when they exist
        if (Object.keys(errors).length > 0) {
            const timer = setTimeout(() => setVisibleErrors(resetFormData), 3000);
            return () => clearTimeout(timer);
        }
    }, [errors,resetFormData]);

    useEffect(() => {
        if (user) {
            setFormData({
                name: user.name,
                email: user.email,
                password: '', // Keep it empty for existing users
                password_confirmation: '', // Keep it empty for existing users,
                role_id: user.role_id,
            });
        } else {
            setFormData(resetFormData);
        }
    }, [user,resetFormData]);

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setFormData((prev) => ({
            ...prev!,
            [e.target.name]: e.target.value,
        }));
    };

    const handleSubmit = () => {
        onSubmit(formData, user?.id); // Pass user ID if it exists
        if (!user?.id) {
            setFormData(resetFormData);
        }
    };

    const fields = [
        { name: 'name', type: 'text', placeholder: 'Name', required: true, inputType: 'input' },
        { name: 'email', type: 'email', placeholder: 'Email', required: true, inputType: 'input' },
        ...(!user
            ? [
                  { name: 'password', type: 'password', placeholder: 'Password', required: true, inputType: 'input' },
                  { name: 'password_confirmation', type: 'password', placeholder: 'Confirm Password', required: true, inputType: 'input' },
              ]
            : []),
        { name: 'role_id', type: 'number', placeholder: 'Select Role', required: true, inputType: 'dropdown' },
    ];

    return (
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>{user ? 'Edit User' : 'Create User'}</DialogTitle>
                    <DialogDescription>{user ? 'Update the user details below.' : 'Fill in the details to create a new user.'}</DialogDescription>
                </DialogHeader>

                <div className="space-y-4">
                    {fields.map(({ name, type, placeholder, required, inputType }) =>
                        inputType === 'input' ? (
                            <Input
                                key={name}
                                name={name}
                                type={type}
                                value={formData[name as keyof typeof formData] ?? ''}
                                onChange={handleChange}
                                placeholder={placeholder}
                                required={required}
                            />
                        ) : (
                            <Select
                                key={name}
                                onValueChange={(value) => setFormData((prev) => ({ ...prev, [name]: value }))}
                                value={formData.role_id?.toLocaleString()}
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Select Role" />
                                </SelectTrigger>
                                <SelectContent>
                                    {roles.map((role) => (
                                        <SelectItem key={role.id} value={role.id.toLocaleString()}>
                                            {role.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        ),
                    )}
                </div>

                <div>
                    {Object.keys(visibleErrors).length > 0 && (
                        <div className="text-red-500">{Object.entries(visibleErrors).map(([key, error]) => error && <p key={key}>{error}</p>)}</div>
                    )}
                </div>

                <DialogFooter>
                    <Button
                        onClick={() => {
                            onClose();
                        }}
                        variant="outline"
                        className="cursor-pointer"
                    >
                        Cancel
                    </Button>
                    <Button onClick={handleSubmit} className="cursor-pointer">
                        Save
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}

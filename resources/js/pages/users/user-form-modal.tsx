import { useState, useEffect } from 'react';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { User, UserErrors } from './user';
import { DialogDescription } from '@radix-ui/react-dialog';
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from "@/components/ui/select";
interface UserFormModalProps {
    isOpen: boolean;
    onClose: () => void;
    user?: User;
    onSubmit: (formData: { name: string; email: string; password?: string; password_confirmation?: string }, userId?: number) => void;
    errors: UserErrors,
    roles: { name: string; slug: string }[]
}

export default function UserFormModal({ isOpen, onClose, user, onSubmit, errors, roles }: UserFormModalProps) {

    const [formData, setFormData] = useState<{
        name: string;
        email: string;
        password?: string;
        password_confirmation?: string
        role_slug: string
    }>({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role_slug: ''
    });

    const errorObject = {
        name: '',
        email: '',
        password: '',
        role_slug: ''
    }

    const [visibleErrors, setVisibleErrors] = useState<UserErrors>(errorObject);

    useEffect(() => {
        setVisibleErrors(errors); // Set errors only when they exist
        if (Object.keys(errors).length > 0) {
            const timer = setTimeout(() => setVisibleErrors(errorObject), 3000);
            return () => clearTimeout(timer);
        }
    }, [errors]);

    useEffect(() => {
        if (user) {
            setFormData({
                name: user.name,
                email: user.email,
                password: '', // Keep it empty for existing users
                password_confirmation: '', // Keep it empty for existing users,
                role_slug: user.role_slug
            });
        } else {
            setFormData({
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                role_slug: ''
            });
        }
    }, [user]);

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setFormData((prev) => ({
            ...prev!,
            [e.target.name]: e.target.value,
        }));
    };

    const handleSubmit = () => {
        onSubmit(formData, user?.id); // Pass user ID if it exists
    };

    const fields = [
        { name: "name", type: "text", placeholder: "Name", required: true, inputType: "input" },
        { name: "email", type: "email", placeholder: "Email", required: true, inputType: "input" },
        ...(!user ? [
            { name: "password", type: "password", placeholder: "Password", required: true, inputType: "input" },
            { name: "password_confirmation", type: "password", placeholder: "Confirm Password", required: true, inputType: "input" }
        ] : []),
        { name: "role_slug", type: "text", placeholder: "Select Role", required: true, inputType: "dropdown" }
    ];

    return (
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>{user ? 'Edit User' : 'Create User'}</DialogTitle>
                    <DialogDescription>
                        {user ? "Update the user details below." : "Fill in the details to create a new user."}
                    </DialogDescription>
                </DialogHeader>

                <div className="space-y-4">
                    {fields.map(({ name, type, placeholder, required, inputType }) => (
                        inputType === "input" ? (
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
                                value={formData.role_slug}
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Select Role" />
                                </SelectTrigger>
                                <SelectContent>
                                    {roles.map((role) => (
                                        <SelectItem key={role.slug} value={role.slug}>
                                            {role.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        )
                    ))}
                </div>

                <div>
                    {Object.keys(visibleErrors).length > 0 && (
                        <div className="text-red-500">
                            {Object.entries(visibleErrors).map(([key, error]) => (
                                error && <p key={key}>{error}</p>
                            ))}
                        </div>
                    )}
                </div>

                <DialogFooter>
                    <Button onClick={() => { onClose() }} variant="outline" className="cursor-pointer">Cancel</Button>
                    <Button onClick={handleSubmit} className='cursor-pointer'>Save</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}

import { useState, useEffect } from 'react';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { User } from './user';
import { DialogDescription } from '@radix-ui/react-dialog';

interface UserFormModalProps {
    isOpen: boolean;
    onClose: () => void;
    user?: User;
    onSubmit: (formData: { name: string; email: string; password?: string; password_confirmation?: string }, userId?: number) => void;
}

export default function UserFormModal({ isOpen, onClose, user, onSubmit }: UserFormModalProps) {
    const [formData, setFormData] = useState<{ name: string; email: string; password?: string; password_confirmation?: string }>({
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
    });

    useEffect(() => {
        if (user) {
            setFormData({
                name: user.name,
                email: user.email,
                password: '', // Keep it empty for existing users
                password_confirmation: '' // Keep it empty for existing users
            });
        } else {
            setFormData({ name: '', email: '', password: '', password_confirmation: '' });
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
        onClose();
    };

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
                    <Input name="name" value={formData.name} onChange={handleChange} placeholder="Name" required />
                    <Input type="email" name="email" value={formData.email} onChange={handleChange} placeholder="Email" required />
                    
                    {/* Show password fields only for new user creation */}
                    {!user && (
                        <>
                            <Input type="password" name="password" value={formData.password} onChange={handleChange} placeholder="Password" required={!user} />
                            <Input type="password" name="password_confirmation" value={formData.password_confirmation} onChange={handleChange} placeholder="Confirm Password" required={!user} />
                        </>
                    )}
                </div>
                <DialogFooter>
                    <Button onClick={onClose} variant="outline">Cancel</Button>
                    <Button onClick={handleSubmit}>{user ? 'Update' : 'Create'}</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}

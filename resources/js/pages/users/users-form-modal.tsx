import { useState, useEffect } from 'react';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { User } from './user';
import { DialogDescription } from '@radix-ui/react-dialog';
import { UserErrors } from './user-errors';

interface UserFormModalProps {
    isOpen: boolean;
    onClose: () => void;
    user?: User;
    onSubmit: (formData: { name: string; email: string; password?: string; password_confirmation?: string }, userId?: number) => void;
    errors: UserErrors,
}

export default function UserFormModal({ isOpen, onClose, user, onSubmit ,errors}: UserFormModalProps) {
    console.log(errors)
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
        role_slug:''
    });

    const errorObject = {
        name:'',
        email: '', 
        password: '',
        role_slug:''
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
                role_slug:''
            });
        } else {
            setFormData({ 
                name: '', 
                email: '', 
                password: '', 
                password_confirmation: '',
                role_slug:'' 
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
                <div>
                    {Object.keys(visibleErrors).length > 0 && (
                        <div className="text-red-500">
                            {visibleErrors.name && <p>{visibleErrors.name}</p>}
                            {visibleErrors.email && <p>{visibleErrors.email}</p>}
                            {visibleErrors.password && <p>{visibleErrors.password}</p>}
                            {visibleErrors.role_slug && <p>{visibleErrors.role_slug}</p>}
                        </div>
                    )}
                </div>
                <DialogFooter>
                    <Button onClick={() => {onClose()}} variant="outline" className="cursor-pointer">Cancel</Button>
                    <Button onClick={handleSubmit} className='cursor-pointer'>{user ? 'Update' : 'Create'}</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}

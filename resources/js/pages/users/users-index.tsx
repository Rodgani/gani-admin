import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/react';

import { PaginatedUsers, User } from './user';
import UserTable from './users-table';
import { useState } from 'react';
import UserFormModal from './users-form-modal';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users Management',
        href: '/admin/users',
    },
];

interface UserIndexProps {
    users: PaginatedUsers;
}

export default function UserIndex({ users }: UserIndexProps) {
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedUser, setSelectedUser] = useState<User | undefined>(undefined);

    const handlePageChange = (page: number) => {
        if (page >= 1 && page <= users.last_page) {
            router.get(
                route('user.index', { page, per_page: 10}), // Add custom query params
                {}, 
                { preserveScroll: true, preserveState: true }
            );
        }
    };    
    
    const handleDelete = (id: number) => {
        if (window.confirm("Are you sure you want to delete this user?")) {
            router.delete(route("user.destroy", { id }), {
                preserveScroll: true,
                onSuccess: () => console.log("User deleted successfully"),
            });
        }
    };
    

    const handleEdit = (user: User) => {
        console.log(user)
        setSelectedUser(user || undefined)
        setIsModalOpen(true)
        // open modal pass the props
    }

    const closeModal = () => {
        setIsModalOpen(false);
        setSelectedUser(undefined);
    };
    
    const handleSubmit = (formData: { name: string; email: string }, userId?: number) => {
        if (userId) {
            console.log('Updating user:', userId, formData);
            router.put(route('user.update', { id: userId }), formData, {
                onSuccess: () => {
                    console.log('User updated successfully');
                    closeModal()
                },
            });
        } else {
            console.log('Creating new user:', formData);
            router.post(route('user.store'), formData, {
                onSuccess: () => {
                    console.log('User created successfully');
                    closeModal()
                },
            });
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Users Management" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">
                    <UserTable users={users} handlePageChange={handlePageChange} handleDelete={handleDelete} handleEdit={handleEdit} />
                </div>
            </div>
            <UserFormModal isOpen={isModalOpen} onClose={closeModal} user={selectedUser}  onSubmit={handleSubmit} />
        </AppLayout>
    );
}

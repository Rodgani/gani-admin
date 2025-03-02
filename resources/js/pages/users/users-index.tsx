import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/react';

import { PaginatedUsers, User } from './user';
import UserTable from './users-table';
import { useState } from 'react';
import UserFormModal from './users-form-modal';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { UserErrors } from './user-errors';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users Management',
        href: '/admin/users',
    },
];

interface UserIndexProps {
    users: PaginatedUsers;
    errors: UserErrors
}

export default function UserIndex({ users,errors }: UserIndexProps) {
   
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedUser, setSelectedUser] = useState<User | undefined>(undefined);
    const [search, setSearch] = useState<string>(""); // Make it a string

    const handleSearch = () => {
        router.get(
            route('user.index'),
            { search, page: users.current_page, per_page: 10 }, // Preserve pagination
            { preserveState: true, preserveScroll: true }
        );
    };

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
                },
            });
        } else {
            console.log('Creating new user:', formData);
            router.post(route('user.store'), formData, {
                onSuccess: () => {
                    console.log('User created successfully');
                },
            });
        }

        if (Object.keys(errors).length === 0) {
            closeModal();
        }
        
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Users Management" />
            <div className="flex items-center py-4 gap-2 m-4">
                    <Input
                        type="text"
                        placeholder="Search users..."
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                        onKeyDown={(e) => e.key === "Enter" && handleSearch()}
                    />
                    <Button onClick={handleSearch} className='cursor-pointer'>Search</Button>
                    <Button onClick={() => setIsModalOpen(true)} className="ml-auto cursor-pointer">
                        Add New User
                    </Button>
                </div>
                <UserTable 
                    users={users} 
                    handlePageChange={handlePageChange} 
                    handleDelete={handleDelete} 
                    handleEdit={handleEdit} 
                />
            <UserFormModal isOpen={isModalOpen} onClose={closeModal} user={selectedUser}  onSubmit={handleSubmit} errors={errors} />
        </AppLayout>
    );
}

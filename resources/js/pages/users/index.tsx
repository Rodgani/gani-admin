import { lazy, Suspense, useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/react';

import { PaginatedUsers, User, UserForm } from './user';
import UserTable from './user-table';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { PlusCircle } from 'lucide-react';

// 🔥 Lazy load the modal
const UserFormModal = lazy(() => import('./user-form-modal'));

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Users Management', href: '/admin/users' },
];

interface UserIndexProps {
    users: PaginatedUsers;
    errors: UserForm;
    roles: { name: string; slug: string }[]

}

export default function UserIndex({ users, roles }: UserIndexProps) {

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedUser, setSelectedUser] = useState<User | undefined>(undefined);
    const [search, setSearch] = useState<string>("");

    const resetForm: UserForm = {
        name: '',
        email: '',
        password: '',
        role_slug: ''
    };

    const [formErrors, setFormErrors] = useState<UserForm>(resetForm);

    const handleSearch = () => {
        router.get(route('user.index'), { search, page: users.current_page, per_page: 10 }, { preserveState: true, preserveScroll: true });
    };

    const handlePageChange = (page: number) => {
        if (page >= 1 && page <= users.last_page) {
            router.get(route('user.index', { page, per_page: 10 }), {}, { preserveScroll: true, preserveState: true });
        }
    };

    const handleDelete = (id: number) => {
        if (window.confirm("Are you sure you want to delete this user?")) {
            router.delete(route("user.destroy", { id }), {
                preserveScroll: true,
                onSuccess: () => alert("User deleted successfully"),
                onError: () => alert("Failed to delete user"),
            });
        }
    };

    const handleEdit = (user: User) => {
        setSelectedUser(user || undefined);
        setIsModalOpen(true);
    };

    const closeModal = () => {
        setFormErrors(resetForm);
        setIsModalOpen(false);
        setSelectedUser(undefined);
    };

    const handleSubmit = (formData: { name: string; email: string }, userId?: number) => {
        if (userId) {
            router.put(route('user.update', { id: userId }), formData, {
                onSuccess: () => {
                    closeModal()
                },
                onError: (errors) => {
                    setFormErrors({ ...resetForm, ...errors }); // ✅ Merge Inertia errors into `UserForm`
                },
            });
        } else {
            router.post(route('user.store'), formData, {
                onSuccess: () => {
                    setFormErrors(resetForm); // ✅ Reset errors on success
                },
                onError: (errors) => {
                    setFormErrors({ ...resetForm, ...errors }); // ✅ Merge Inertia errors into `UserForm`
                },
            });
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Users Management" />
            <div className="flex items-center py-4 gap-2 m-4">
                <Input
                    type="text"
                    placeholder="Search..."
                    value={search}
                    onChange={(e) => setSearch(e.target.value)}
                    onKeyDown={(e) => e.key === "Enter" && handleSearch()}
                />
                <Button onClick={handleSearch} className="cursor-pointer">Search</Button>
                <Button onClick={() => setIsModalOpen(true)} className="ml-auto cursor-pointer flex items-center gap-2">
                    <PlusCircle className="w-4 h-4" /> Create
                </Button>
            </div>

            <UserTable users={users} handlePageChange={handlePageChange} handleDelete={handleDelete} handleEdit={handleEdit} />

            {/* 🔥 Lazy-load UserFormModal when needed */}
            <Suspense fallback={<p>Modal Opening...</p>}>
                {isModalOpen && (
                    <UserFormModal
                        isOpen={isModalOpen}
                        onClose={closeModal}
                        user={selectedUser}
                        onSubmit={handleSubmit}
                        errors={formErrors} // 🔥 Use local state errors
                        roles={roles}
                    />
                )}
            </Suspense>
        </AppLayout>
    );
}

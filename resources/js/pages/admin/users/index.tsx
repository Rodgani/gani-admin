import { lazy, Suspense, useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/react';

import { PaginatedUsers, User, UserForm } from './user';
import UserTable from './user-table';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { PlusCircle, Search } from 'lucide-react';
import CenteredSkeletonLoader from '@/components/centered-skeleton-loader';
import { PER_PAGE_DEFAULT } from '@/constants/app';
import { useToastMessage } from '@/hooks/use-toast-message';
import { useConfirmToast } from '@/hooks/use-confirm-toast';
import { Icon } from '@/components/icon';
import { userPermissions } from '@/hooks/use-permission';

// 🔥 Lazy load the modal
const UserFormModal = lazy(() => import('./user-form-modal'))
const module = "/admin/users"

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Users', href: module },
];

interface UserIndexProps {
    users: PaginatedUsers;
    errors: UserForm;
    roles: { name: string; id: number }[]

}

export default function UserIndex({ users, roles }: UserIndexProps) {

    const { hasPermission } = userPermissions();
    const { hasAnyPermission } = userPermissions();

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedUser, setSelectedUser] = useState<User | undefined>(undefined);
    const [search, setSearch] = useState<string>("");
    const { showToast } = useToastMessage();
    const confirmToast = useConfirmToast();

    const resetForm: UserForm = {
        name: '',
        email: '',
        password: '',
        role_id: ''
    };

    const [formErrors, setFormErrors] = useState<UserForm>(resetForm);

    const handleSearch = () => {

        const params: { [key: string]: string | number } = {
            page: users.current_page,
            per_page: PER_PAGE_DEFAULT
        };
    
        if (search) {
            params.search = search;
        }
    
        router.get(route('user.index'), params, {
            preserveScroll: true,
            preserveState: true
        });
    };

    const handlePageChange = (page: number) => {
        if (page >= 1 && page <= users.last_page) {
            router.get(route('user.index'),
                { page, per_page: PER_PAGE_DEFAULT },
                { preserveScroll: true, preserveState: true }
            )
        }
    };

    const handleDelete = (id: number) => {
        confirmToast({
            message: "Are you sure you want to delete this?",
            onConfirm: () => {
                router.delete(route("user.destroy", { id }), {
                    preserveScroll: true,
                    onSuccess: () => showToast("success", { message: "Deleted successfully!" }),
                    onError: (errors) => {
                        showToast("error", errors)
                    },
                });
            },
            onCancel: () => console.log("Cancelled"),
        });
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
                    showToast("success", { message: "Updated successfully!" })
                },
                onError: (errors) => {
                    setFormErrors({ ...resetForm, ...errors }); // ✅ Merge Inertia errors into `UserForm`
                },
            });
        } else {
            router.post(route('user.store'), formData, {
                onSuccess: () => {
                    setFormErrors(resetForm); // ✅ Reset errors on success
                    showToast("success", { message: "Created successfully!" })
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

            {hasAnyPermission(module, ['search', 'create']) && (
                <div className="flex items-center py-4 gap-2">
                    {hasPermission(module, 'search') && (
                        <>
                            <Input
                                type="text"
                                placeholder="Search..."
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                onKeyDown={(e) => e.key === "Enter" && handleSearch()}
                            />
                            <Button onClick={handleSearch} className="cursor-pointer"> <Icon iconNode={Search} /></Button>
                        </>
                    )}

                    {hasPermission(module, 'create') && (
                        <Button onClick={() => setIsModalOpen(true)} className="ml-auto cursor-pointer flex items-center gap-2">
                            <Icon iconNode={PlusCircle} />
                        </Button>
                    )}
                </div>
            )}


            <UserTable users={users} handlePageChange={handlePageChange} handleDelete={handleDelete} handleEdit={handleEdit} />

            {/* 🔥 Lazy-load UserFormModal when needed */}
            <Suspense fallback={<CenteredSkeletonLoader />}>
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

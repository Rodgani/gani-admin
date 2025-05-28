import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { lazy, Suspense, useState } from 'react';

import CenteredSkeletonLoader from '@/components/centered-skeleton-loader';
import { Icon } from '@/components/icon';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { userPermissions } from '@/hooks/use-permission';
import { PlusCircle, Search } from 'lucide-react';
import UserTable from './components/user-table';
import { useDeleteUser } from './hooks/use-delete-user';
import { useUserPagination } from './hooks/use-user-pagination';
import { UserIndexProps } from './types/user-props.types';
import { User, UserForm } from './types/user.types';
import { useSubmitUserForm } from './hooks/use-submit-form-user';

// 🔥 Lazy load the modal
const UserFormModal = lazy(() => import('./components/user-form-modal'));
const module = '/admin/users';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Users', href: module }];

export default function UserIndex({ users, roles }: UserIndexProps) {
    const { hasPermission } = userPermissions();
    const { hasAnyPermission } = userPermissions();

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedUser, setSelectedUser] = useState<User | undefined>(undefined);
    const [searchTerm, setSearchTerm] = useState('');
    const { handleSearch, handlePageChange } = useUserPagination(users.current_page, searchTerm);
    const { handleDelete } = useDeleteUser();

    const resetForm: UserForm = {
        name: '',
        email: '',
        password: '',
        role_id: '',
    };

    const [formErrors, setFormErrors] = useState<UserForm>(resetForm);

    const handleEdit = (user: User) => {
        setSelectedUser(user || undefined);
        setIsModalOpen(true);
    };

    const closeModal = () => {
        setFormErrors(resetForm);
        setIsModalOpen(false);
        setSelectedUser(undefined);
    };

    const { handleSubmit } = useSubmitUserForm({
        closeModal,
        resetForm,
        setFormErrors: (errors) => setFormErrors({ ...resetForm, ...errors })
    });

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Users Management" />

            {hasAnyPermission(module, ['search', 'create']) && (
                <div className="flex items-center gap-2 py-4">
                    {hasPermission(module, 'search') && (
                        <>
                            <Input
                                type="text"
                                placeholder="Search..."
                                value={searchTerm}
                                onChange={(e) => setSearchTerm(e.target.value)}
                                onKeyDown={(e) => e.key === 'Enter' && handleSearch()}
                            />
                            <Button onClick={handleSearch} className="cursor-pointer">
                                <Icon iconNode={Search} />
                            </Button>
                        </>
                    )}

                    {hasPermission(module, 'create') && (
                        <Button onClick={() => setIsModalOpen(true)} className="ml-auto flex cursor-pointer items-center gap-2">
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

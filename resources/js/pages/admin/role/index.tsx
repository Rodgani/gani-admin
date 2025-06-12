import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

import CenteredSkeletonLoader from '@/components/centered-skeleton-loader';
import { Icon } from '@/components/icon';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { userPermissions } from '@/hooks/use-permission';
import { PlusCircle, Search } from 'lucide-react';
import { lazy, Suspense, useState } from 'react';
import RoleTable from './components/role-table';
import { useRoleFormSubmit } from './hooks/use-role-form-submit';
import { useRolePagination } from './hooks/use-role-pagination';
import { RoleIndexProps } from './types/role-props.types';
import { Role } from './types/role.types';

const module = '/admin/roles';
const breadcrumbs: BreadcrumbItem[] = [{ title: 'Roles & Permissions', href: module }];

// 🔥 Lazy load the modal
const RoleFormModal = lazy(() => import('./components/role-form-modal'));

export default function RoleIndex({ roles, default_menus_permissions, role_types }: RoleIndexProps) {
    const { hasPermission } = userPermissions();
    const { hasAnyPermission } = userPermissions();

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedRole, setSelectedRole] = useState<Role | undefined>(undefined);
    const [searchTerm, setSearchTerm] = useState('');

    const filterParams = {
        ...(searchTerm ? { search: searchTerm } : {}),
        // add more here
    };
    const { handleSearch, handlePageChange } = useRolePagination(roles.current_page, filterParams);

    const handleEdit = (role: Role) => {
        setSelectedRole(role || undefined);
        setIsModalOpen(true);
    };

    const closeModal = () => {
        setIsModalOpen(false);
        setSelectedRole(undefined);
    };

    const { handleSubmit } = useRoleFormSubmit({ closeModal });

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Roles Management" />

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

            <RoleTable roles={roles} handlePageChange={handlePageChange} handleEdit={handleEdit} />

            {/* 🔥 Lazy-load UserFormModal when needed */}
            <Suspense fallback={<CenteredSkeletonLoader />}>
                {isModalOpen && (
                    <RoleFormModal
                        isOpen={isModalOpen}
                        onClose={closeModal}
                        role={selectedRole}
                        defaultMenuManager={default_menus_permissions}
                        onSubmit={handleSubmit}
                        roleTypes={role_types}
                    />
                )}
            </Suspense>
        </AppLayout>
    );
}

import AppLayout from "@/layouts/app-layout";
import { BreadcrumbItem } from "@/types";
import { Head, router } from "@inertiajs/react";

import { MenusPermissions, PaginatedRoles, Role } from "./role";
import RoleTable from "./role-table";
import { lazy, Suspense, useState } from "react";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { PlusCircle, Search } from "lucide-react";
import CenteredSkeletonLoader from "@/components/centered-skeleton-loader";
import { PER_PAGE_DEFAULT } from "@/contants/app";
import { useToastMessage } from "@/hooks/use-toast-message";
import { Icon } from "@/components/icon";

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Roles & Permissions', href: 'admin/roles' },
];

interface RoleIndexProps {
    roles: PaginatedRoles,
    default_menus_permissions: MenusPermissions
}

// 🔥 Lazy load the modal
const RoleFormModal = lazy(() => import('./role-form-modal'));

export default function RoleIndex({ roles, default_menus_permissions }: RoleIndexProps) {

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedRole, setSelectedRole] = useState<Role | undefined>(undefined);
    const [search, setSearch] = useState<string>("");
    const { showToast } = useToastMessage();

    const handleSearch = () => {
        router.get(route('role.index'), { search, page: roles.current_page, per_page: PER_PAGE_DEFAULT }, { preserveScroll: true, preserveState: true });
    };

    const handlePageChange = (page: number) => {
        if (page >= 1 && page <= roles.last_page) {
            router.get(route('role.index', { page, per_page: PER_PAGE_DEFAULT }), { preserveScroll: true, preserveState: true });
        }
    };

    const handleEdit = (role: Role) => {
        setSelectedRole(role || undefined);
        setIsModalOpen(true);
    };

    const closeModal = () => {
        setIsModalOpen(false);
        setSelectedRole(undefined);
    };


    const handleSubmit = (formData: { name: string, slug: string, menus_permissions: MenusPermissions }, roleId?: number) => {

        const payload = {
            name: formData.name,
            slug: formData.slug,
            menus_permissions: JSON.stringify(formData.menus_permissions),
        };

        if (roleId) {
            router.put(route('role.update', { id: roleId }), payload, {
                onSuccess: () => {
                    closeModal()
                    showToast("success", { message: "Updated successfully!" })
                },
                onError: (errors) => {
                    showToast("error", errors);
                },
            });
        } else {
            router.post(route('role.store'), payload, {
                onSuccess: () => {
                    closeModal()
                    showToast("success", { message: "Created successfully!" })
                },
                onError: (errors) => {
                    showToast("error", errors);
                },
            });
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Roles Management" />
            <div className="flex items-center py-4 gap-2 m-4">
                <Input
                    type="text"
                    placeholder="Search..."
                    value={search}
                    onChange={(e) => setSearch(e.target.value)}
                    onKeyDown={(e) => e.key === "Enter" && handleSearch()}
                />
                <Button onClick={handleSearch} className="cursor-pointer"> <Icon iconNode={Search} /></Button>
                <Button onClick={() => setIsModalOpen(true)} className="ml-auto cursor-pointer flex items-center gap-2">
                    <Icon iconNode={PlusCircle} />
                </Button>
            </div>

            <RoleTable roles={roles} handlePageChange={handlePageChange} handleEdit={handleEdit} />

            {/* 🔥 Lazy-load UserFormModal when needed */}
            <Suspense fallback={<CenteredSkeletonLoader />}>
                {isModalOpen && (
                    <RoleFormModal
                        isOpen={isModalOpen}
                        onClose={closeModal}
                        role={selectedRole}
                        defaultMenusPermissions={default_menus_permissions}
                        onSubmit={handleSubmit}
                    />
                )}
            </Suspense>
        </AppLayout>
    );
}
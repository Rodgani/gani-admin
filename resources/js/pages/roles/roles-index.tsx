import AppLayout from "@/layouts/app-layout";
import { BreadcrumbItem } from "@/types";
import { Head, router } from "@inertiajs/react";

import { PaginatedRoles, Role } from "./role";
import RoleTable from "./roles-table";

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Roles Management', href: 'role.index' },
];

interface RoleIndexProps {
    roles: PaginatedRoles
}

export default function RoleIndex({ roles }: RoleIndexProps) {

    const handlePageChange = (page: number) => {
        if (page >= 1 && page <= roles.last_page) {
            router.get(route('role.index', { page, per_page: 10 }), {}, { preserveScroll: true, preserveState: true });
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Roles Management" />
            <RoleTable roles={roles} handlePageChange={handlePageChange} />
        </AppLayout>
    );
}
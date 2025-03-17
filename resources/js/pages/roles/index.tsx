import AppLayout from "@/layouts/app-layout";
import { BreadcrumbItem } from "@/types";
import { Head, router } from "@inertiajs/react";

import { PaginatedRoles, Role } from "./role";
import RoleTable from "./role-table";
import { useState } from "react";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { PlusCircle } from "lucide-react";

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Roles Management', href: 'role.index' },
];

interface RoleIndexProps {
    roles: PaginatedRoles
}

export default function RoleIndex({ roles }: RoleIndexProps) {

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedRole, setSelectedRole] = useState<Role | undefined>(undefined);
    const [search, setSearch] = useState<string>("");

    const handleSearch = () => {
        router.get(route('role.index'), { search, page: roles.current_page, per_page: 10 }, { preserveState: true, preserveScroll: true });
    };

    const handlePageChange = (page: number) => {
        if (page >= 1 && page <= roles.last_page) {
            router.get(route('role.index', { page, per_page: 10 }), {}, { preserveScroll: true, preserveState: true });
        }
    };

    const handleEdit = (role: Role) => {
        alert("hephep")
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
                <Button onClick={handleSearch} className="cursor-pointer">Search</Button>
                {/* <Button onClick={() => setIsModalOpen(true)} className="ml-auto cursor-pointer flex items-center gap-2">
                    <PlusCircle className="w-4 h-4" /> Create
                </Button> */}
            </div>

            <RoleTable roles={roles} handlePageChange={handlePageChange} handleEdit={handleEdit} />
        </AppLayout>
    );
}
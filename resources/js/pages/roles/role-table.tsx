import { Table, TableBody, TableCaption, TableCell, TableFooter, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { PaginatedRoles, Role } from "./role";
import { Button } from "@/components/ui/button";
import TablePagination from "@/components/table-pagination";
import { Icon } from "@/components/ui/icon";
import { SquarePen } from "lucide-react";
import { usePermission } from "@/hooks/use-permission";


interface RoleTableProps {
    roles: PaginatedRoles,
    handlePageChange: (page: number) => void;
    handleEdit: (role: Role) => void;
}

export default function RoleTable({ roles, handlePageChange, handleEdit }: RoleTableProps) {
    const { data, current_page, last_page, total } = roles;
    const { hasPermission } = usePermission();

    return (
        <div className="m-4 border">
            <Table>
                <TableCaption>List of Roles</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead>ID</TableHead>
                        <TableHead>Name</TableHead>
                        <TableHead>Slug</TableHead>
                        <TableHead>Updated At</TableHead>
                        <TableHead>Created At</TableHead>
                        <TableHead className="text-center">Action</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    {data.map((role) => (
                        <TableRow key={role.id}>
                            <TableCell>{role.id}</TableCell>
                            <TableCell>{role.name}</TableCell>
                            <TableCell>{role.slug}</TableCell>
                            <TableCell>{role.updated_at}</TableCell>
                            <TableCell>{role.created_at}</TableCell>
                            {hasPermission('/admin/roles', 'update') && (
                                <TableCell className="flex justify-center gap-2">
                                    <Button size="sm" variant="ghost" onClick={() => handleEdit(role)} className="cursor-pointer"><Icon iconNode={SquarePen} className="w-4 h-4" /></Button>
                                </TableCell>
                            )}
                        </TableRow>
                    ))}
                </TableBody>
                <TableFooter>
                    <TableRow>
                        <TableCell colSpan={12} className="text-center font-medium">
                            Showing {data.length} of {total} roles
                        </TableCell>
                    </TableRow>
                </TableFooter>
            </Table>
            <TablePagination
                currentPage={current_page}
                lastPage={last_page}
                onPageChange={handlePageChange}
            />
        </div>
    );
}
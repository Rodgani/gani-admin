import { Table, TableBody, TableCaption, TableCell, TableFooter, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { PaginatedRoles, Role } from "./role";
import { Button } from "@/components/ui/button";
import TablePagination from "@/components/table-pagination";
import { Icon } from "@/components/ui/icon";
import { SquarePen } from "lucide-react";
import { usePermission } from "@/hooks/use-permission";
import { TableBodyItem, TableHeaderItem } from "@/types/table";


interface RoleTableProps {
    roles: PaginatedRoles,
    handlePageChange: (page: number) => void;
    handleEdit: (role: Role) => void;
}

export default function RoleTable({ roles, handlePageChange, handleEdit }: RoleTableProps) {
    const { data: roleList, current_page, last_page, total } = roles;

    const { hasPermission } = usePermission();
    const module = "/admin/roles";

    const headers: TableHeaderItem[] = [
        { label: 'ID' },
        { label: 'Name' },
        { label: 'Slug' },
        { label: 'Updated At' },
        { label: 'Created At' },
        ...(hasPermission(module, 'update')
            ? [{ label: 'Actions', className: 'text-center' }]
            : []),
    ];

    const fields: TableBodyItem<Role>[] = [
        { label: 'ID', value: (role) => role.id },
        { label: 'Name', value: (role) => role.name },
        { label: 'Slug', value: (role) => role.slug },
        { label: 'Updated At', value: (role) => role.updated_at },
        { label: 'Created At', value: (role) => role.created_at },
    ];

    const actionColumn: TableBodyItem<Role>[] = [
        {
            label: 'Actions',
            render: (role) => (
                <div className="flex justify-center gap-2">
                    <Button size="sm" variant="ghost" onClick={() => handleEdit(role)} className="cursor-pointer"><Icon iconNode={SquarePen} className="w-4 h-4" /></Button>
                </div>
            )
        }
    ];

    const tableBody: TableBodyItem<Role>[] = [
        ...fields,
        ...(hasPermission(module, 'update') ? actionColumn : []),
    ];

    return (
        <>
            <Table>
                <TableCaption>List of Roles</TableCaption>
                <TableHeader>
                    <TableRow>
                        {headers.map((header, index) => (
                            <TableHead key={index} className={header.className || ''}>
                                {header.label}
                            </TableHead>
                        ))}
                    </TableRow>
                </TableHeader>
                <TableBody>
                    {roleList.map((role) => (
                        <TableRow key={role.id}>
                            {tableBody.map((column, index) => (
                                <TableCell key={index}>
                                    {/* Use render function if available, otherwise use value function */}
                                    {column.render ? column.render(role) : column.value && column.value(role)}
                                </TableCell>
                            ))}
                        </TableRow>
                    ))}
                </TableBody>
                <TableFooter>
                    <TableRow>
                        <TableCell colSpan={12} className="text-center font-medium">
                            Showing {roleList.length} of {total} roles
                        </TableCell>
                    </TableRow>
                    <TableRow>
                        <TableCell colSpan={12} className="text-center font-medium">
                            <TablePagination
                                currentPage={current_page}
                                lastPage={last_page}
                                onPageChange={handlePageChange}
                            />
                        </TableCell>
                    </TableRow>
                </TableFooter>
            </Table>
        </>
    );
}
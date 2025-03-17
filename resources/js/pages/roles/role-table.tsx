import { Table, TableBody, TableCaption, TableCell, TableFooter, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { PaginatedRoles, Role } from "./role";
import { Pagination, PaginationContent, PaginationItem, PaginationLink, PaginationNext, PaginationPrevious } from "@/components/ui/pagination";
import { Button } from "@/components/ui/button";


interface RoleTableProps {
    roles: PaginatedRoles,
    handlePageChange: (page: number) => void;
    handleEdit: (role: Role) => void;
}

export default function RoleTable({ roles, handlePageChange, handleEdit }: RoleTableProps) {
    const { data, current_page, last_page, total } = roles;

    return (
        <div className="overflow-auto m-4 border w-sm md:w-auto lg:w-auto">
            <Table className="table-auto">
                <TableCaption>List of Roles</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead>ID</TableHead>
                        <TableHead>Name</TableHead>
                        <TableHead>Slug</TableHead>
                        <TableHead>Updated At</TableHead>
                        <TableHead>Created At</TableHead>
                        <TableHead className="text-center">Actions</TableHead>
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
                            <TableCell className="flex justify-center gap-2">
                                <Button size="sm" variant="ghost" onClick={() => handleEdit(role)} className="cursor-pointer">Edit | Set Permissions</Button>
                            </TableCell>
                        </TableRow>
                    ))}
                </TableBody>
                <TableFooter>
                    <TableRow>
                        <TableCell colSpan={7} className="text-center font-medium">
                            Showing {data.length} of {total} roles
                        </TableCell>
                    </TableRow>
                </TableFooter>
            </Table>
            {/* Pagination */}
            <Pagination className="mt-4 mb-4">
                <PaginationContent>
                    <PaginationItem>
                        <PaginationPrevious
                            onClick={() => handlePageChange(current_page - 1)}
                            className={current_page === 1 ? "pointer-events-none opacity-50" : ""}
                        />
                    </PaginationItem>

                    {Array.from({ length: last_page }, (_, i) => {
                        const page = i + 1;
                        return (
                            <PaginationItem key={page}>
                                <PaginationLink className="cursor-pointer"
                                    isActive={page === current_page}
                                    onClick={() => handlePageChange(page)}
                                >
                                    {page}
                                </PaginationLink>
                            </PaginationItem>
                        );
                    })}

                    <PaginationItem>
                        <PaginationNext
                            onClick={() => handlePageChange(current_page + 1)}
                            className={current_page === last_page ? "pointer-events-none opacity-50" : ""}
                        />
                    </PaginationItem>
                </PaginationContent>
            </Pagination>
        </div>
    );
}
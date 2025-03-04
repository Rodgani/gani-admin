import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableFooter,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import {
  Pagination,
  PaginationContent,
  PaginationItem,
  PaginationLink,
  PaginationNext,
  PaginationPrevious,
} from "@/components/ui/pagination";
import { Button } from "@/components/ui/button";
import { PaginatedUsers, User } from "./user";

interface UserTableProps {
  users: PaginatedUsers;
  handlePageChange: (page: number) => void;
  handleDelete: (id: number) => void;
  handleEdit: (user:User) => void;
}

export default function UserTable({ users, handlePageChange, handleDelete,handleEdit }: UserTableProps) {
  const { data, current_page, last_page, total } = users;

  return (
    <div className="overflow-auto m-4 border w-sm md:w-auto lg:w-auto">
      <Table className="table-auto">
        <TableCaption>List of Users</TableCaption>
        <TableHeader>
          <TableRow>
            <TableHead>ID</TableHead>
            <TableHead>Name</TableHead>
            <TableHead>Email</TableHead>
            <TableHead>Role</TableHead>
            <TableHead>Updated At</TableHead>
            <TableHead>Created At</TableHead>
            <TableHead className="text-center">Actions</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          {data.map((user) => (
            <TableRow key={user.id}>
              <TableCell>{user.id}</TableCell>
              <TableCell>{user.name}</TableCell>
              <TableCell>{user.email}</TableCell>
              <TableCell>{user.role_slug}</TableCell>
              <TableCell>{user.updated_at}</TableCell>
              <TableCell>{user.created_at}</TableCell>
              <TableCell className="flex justify-center gap-2">
                <Button size="sm" variant="ghost" onClick={() => handleEdit(user)} className="cursor-pointer">Edit</Button>
                <Button size="sm" variant="ghost" onClick={() => handleDelete(user.id)} className="cursor-pointer ">Delete</Button>
              </TableCell>
            </TableRow>
          ))}
        </TableBody>
        <TableFooter>
          <TableRow>
            <TableCell colSpan={7} className="text-center font-medium">
                  Showing {data.length} of {total} users
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

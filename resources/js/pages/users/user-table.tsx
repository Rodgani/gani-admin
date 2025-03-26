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
import { Button } from "@/components/ui/button";
import { PaginatedUsers, User } from "./user";
import TablePagination from "@/components/table-pagination";
import { Icon } from "@/components/ui/icon";
import { SquarePen, Trash2 } from "lucide-react";
import { usePermission } from "@/hooks/use-permission";

interface UserTableProps {
  users: PaginatedUsers;
  handlePageChange: (page: number) => void;
  handleDelete: (id: number) => void;
  handleEdit: (user: User) => void;
}

export default function UserTable({ users, handlePageChange, handleDelete, handleEdit }: UserTableProps) {
  const { hasPermission } = usePermission();
  const { hasAnyPermission } = usePermission();

  const module = "/admin/users";

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
            {hasAnyPermission(module, ['update', 'delete']) && (
              <TableHead className="text-center">Actions</TableHead>
            )}
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
              {hasAnyPermission(module, ['update', 'delete']) && (
                <TableCell className="flex justify-center gap-2">
                  {hasPermission(module, 'update') && (
                    <Button size="sm" variant="ghost" onClick={() => handleEdit(user)} className="cursor-pointer"><Icon iconNode={SquarePen} className="w-4 h-4" /></Button>
                  )}
                  {hasPermission(module, 'delete') && (
                    <Button size="sm" variant="ghost" onClick={() => handleDelete(user.id)} className="cursor-pointer "><Icon iconNode={Trash2} className="w-4 h-4" /></Button>
                  )}
                </TableCell>
              )}
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
      <TablePagination
        currentPage={current_page}
        lastPage={last_page}
        onPageChange={handlePageChange}
      />
    </div>
  );
}

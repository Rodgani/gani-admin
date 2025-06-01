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
import { PaginatedUsers, User } from "../types/user.types";
import TablePagination from "@/components/table-pagination";
import { Icon } from "@/components/ui/icon";
import { SquarePen, Trash2 } from "lucide-react";
import { userPermissions } from "@/hooks/use-permission";
import { TableBodyItem, TableHeaderItem } from "@/types/table";

interface UserTableProps {
  users: PaginatedUsers;
  handlePageChange: (page: number) => void;
  handleDelete: (id: number) => void;
  handleEdit: (user: User) => void;
}

export default function UserTable({ users, handlePageChange, handleDelete, handleEdit }: UserTableProps) {
  const { hasPermission } = userPermissions();
  const { hasAnyPermission } = userPermissions();

  const module = "/admin/users";

  const { data: userList, current_page, last_page, total } = users;

  const headers: TableHeaderItem[] = [
    { label: 'ID' },
    { label: 'Name' },
    { label: 'Email' },
    { label: 'Role' },
    { label: 'Timezone' },
    { label: 'Updated At' },
    { label: 'Created At' },
    ...(hasAnyPermission(module, ['update', 'delete'])
      ? [{ label: 'Actions', className: 'text-center' }]
      : []),
  ];

  // Define user fields
  const fields: TableBodyItem<User>[] = [
    { label: 'ID', value: (user) => user.id },
    { label: 'Name', value: (user) => user.name },
    { label: 'Email', value: (user) => user.email },
    { label: 'Role', value: (user) => user.role?.name },
    { label: 'Role', value: (user) => user.timezone },
    { label: 'Updated At', value: (user) => user.updated_at.toLocaleString() },
    { label: 'Created At', value: (user) => user.created_at.toLocaleString() },
  ];

  // Define action column for actions like edit and delete
  const actionColumn: TableBodyItem<User>[] = [
    {
      label: 'Actions',
      render: (user) => (
        <div className="flex justify-center gap-2">
          {hasPermission(module, 'update') && (
            <Button size="sm" variant="ghost" onClick={() => handleEdit(user)}>
              <Icon iconNode={SquarePen} className="w-4 h-4" />
            </Button>
          )}
          {hasPermission(module, 'delete') && (
            <Button size="sm" variant="ghost" onClick={() => handleDelete(user.id)}>
              <Icon iconNode={Trash2} className="w-4 h-4" />
            </Button>
          )}
        </div>
      ),
    },
  ];

  // Combine user fields and action column based on permissions
  const tableBody: TableBodyItem<User>[] = [
    ...fields,
    ...(hasAnyPermission(module, ['update', 'delete']) ? actionColumn : []),
  ];

  return (
    <>
      <Table>
        <TableCaption>List of Users</TableCaption>
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
          {userList.map((user) => (
            <TableRow key={user.id}>
              {tableBody.map((column, index) => (
                <TableCell key={index}>
                  {/* Use render function if available, otherwise use value function */}
                  {column.render ? column.render(user) : column.value && column.value(user)}
                </TableCell>
              ))}
            </TableRow>
          ))}
        </TableBody>
        <TableFooter>
          <TableRow>
            <TableCell colSpan={12} className="text-center font-medium">
              Showing {userList.length} of {total} users
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

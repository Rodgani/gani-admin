import React, { useState } from "react";
import {
  Table,
  TableHeader,
  TableBody,
  TableFooter,
  TableHead,
  TableRow,
  TableCell,
  TableCaption,
} from "@/components/ui/table"; // Adjust the import path if needed
import {
  Pagination,
  PaginationContent,
  PaginationItem,
  PaginationPrevious,
  PaginationNext,
  PaginationLink,
} from "@/components/ui/pagination"; // Adjust the import path if needed
import { Button } from "@/components/ui/button"; // Ensure you have a button component
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from "@/components/ui/dialog"; // Use your modal/dialog component
import { Input } from "@/components/ui/input";

// Fake data (initial users)
const initialUsers = Array.from({ length: 5 }, (_, i) => ({
  id: i + 1,
  name: `User ${i + 1}`,
  email: `user${i + 1}@example.com`,
}));

const ITEMS_PER_PAGE = 5; // Users per page

const UserManagement = () => {
  const [users, setUsers] = useState(initialUsers);
  const [currentPage, setCurrentPage] = useState(1);
  const [modalOpen, setModalOpen] = useState(false);
  const [saveUser, setSaveUser] = useState<{ id?: number; name: string; email: string } | null>(null);

  const totalPages = Math.ceil(users.length / ITEMS_PER_PAGE);

  // Get users for the current page
  const startIndex = (currentPage - 1) * ITEMS_PER_PAGE;
  const paginatedUsers = users.slice(startIndex, startIndex + ITEMS_PER_PAGE);

  // Handle page change
  const handlePageChange = (page: number) => {
    if (page >= 1 && page <= totalPages) {
      setCurrentPage(page);
    }
  };

  // Open modal for new user or editing an existing user
  const openModal = (user?: { id: number; name: string; email: string }) => {
    setSaveUser(user || { name: "", email: "" }); // Reset for new user
    setModalOpen(true);
  };

  // Close modal
  const closeModal = () => {
    setModalOpen(false);
    setSaveUser(null);
  };

  // Handle form submit (create or update)
  const handleSubmit = (event: React.FormEvent) => {
    event.preventDefault();
    if (!saveUser) return;

    if (saveUser.id) {
      // Update user
      setUsers((prev) =>
        prev.map((user) =>
          user.id === saveUser!.id ? { ...user, ...saveUser! } : user
        )
      );
      
    } else {
      // Create new user
      setUsers((prev) => [
        ...prev,
        { ...saveUser, id: prev.length + 1 },
      ]);
    }

    closeModal();
  };

  // Handle delete user
  const handleDelete = (id: number) => {
    setUsers((prev) => prev.filter((user) => user.id !== id));
  };

  return (
    <div className="p-4">
      <Button onClick={() => openModal()} className="mb-4 cursor-pointer">
        + Add New User
      </Button>

      <Table className="border">
        <TableCaption>List of Users</TableCaption>
        <TableHeader>
          <TableRow>
            <TableHead>ID</TableHead>
            <TableHead>Name</TableHead>
            <TableHead>Email</TableHead>
            <TableHead>Actions</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          {paginatedUsers.map((user) => (
            <TableRow key={user.id} >
              <TableCell>{user.id}</TableCell>
              <TableCell>{user.name}</TableCell>
              <TableCell>{user.email}</TableCell>
              <TableCell className="flex gap-2">
                <Button size="sm" onClick={() => openModal(user)} className="cursor-pointer">Edit</Button>
                <Button size="sm" variant="destructive" onClick={() => handleDelete(user.id)} className="cursor-pointer">Delete</Button>
              </TableCell>
            </TableRow>
          ))}
        </TableBody>
        <TableFooter>
          <TableRow>
            <TableCell colSpan={4} className="text-center font-medium">
              Showing {startIndex + 1} -{" "}
              {Math.min(startIndex + ITEMS_PER_PAGE, users.length)} of{" "}
              {users.length} users
            </TableCell>
          </TableRow>
        </TableFooter>
      </Table>

      {/* Pagination */}
      <Pagination className="mt-4">
        <PaginationContent>
          <PaginationItem>
            <PaginationPrevious
              onClick={() => handlePageChange(currentPage - 1)}
              className={currentPage === 1 ? "pointer-events-none opacity-50" : ""}
            />
          </PaginationItem>

          {Array.from({ length: totalPages }, (_, i) => {
            const page = i + 1;
            return (
              <PaginationItem key={page}>
                <PaginationLink className="cursor-pointer"
                  isActive={page === currentPage}
                  onClick={() => handlePageChange(page)}
                >
                  {page}
                </PaginationLink>
              </PaginationItem>
            );
          })}

          <PaginationItem>
            <PaginationNext
              onClick={() => handlePageChange(currentPage + 1)}
              className={currentPage === totalPages ? "pointer-events-none opacity-50" : ""}
            />
          </PaginationItem>
        </PaginationContent>
      </Pagination>

      {/* Modal for Create/Update */}
      {modalOpen && (
        <Dialog open={modalOpen} onOpenChange={setModalOpen}>
          <DialogContent>
            <DialogHeader>
              <DialogTitle>{saveUser?.id ? "Edit User" : "Add New User"}</DialogTitle>
            </DialogHeader>
            <form onSubmit={handleSubmit} className="space-y-4"> 
              <div>
                <label className="block text-sm font-medium">Name</label>
                <Input
                  type="text"
                  className="w-full border px-3 py-2"
                  value={saveUser?.name || ""}
                  onChange={(e) => setSaveUser((prev) => ({ ...prev!, name: e.target.value }))}
                  required
                />
              </div>
              <div>
                <label className="block text-sm font-medium">Email</label>
                <Input
                  type="email"
                  className="w-full border px-3 py-2"
                  value={saveUser?.email || ""}
                  onChange={(e) => setSaveUser((prev) => ({ ...prev!, email: e.target.value }))}
                  required
                />
              </div>
              <DialogFooter>
                <Button type="submit" className="cursor-pointer">{saveUser?.id ? "Update" : "Create"}</Button>
                <Button variant="secondary" onClick={closeModal} className="cursor-pointer">Cancel</Button>
              </DialogFooter>
            </form>
          </DialogContent>
        </Dialog>
      )}
    </div>
  );
};

export default UserManagement;

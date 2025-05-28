import { PaginatedUsers, User, UserForm } from "./user.types";

export interface UserIndexProps {
    users: PaginatedUsers;
    errors: UserForm;
    roles: { name: string; id: number }[]
}

export interface UserFormModalProps {
    isOpen: boolean;
    onClose: () => void;
    user?: User;
    onSubmit: (formData: UserForm, userId?: number) => void;
    errors: UserForm;
    roles: { name: string; id: number }[];
}
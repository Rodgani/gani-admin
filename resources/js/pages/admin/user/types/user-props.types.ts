import { PaginatedUsers, User, UserForm } from "./user.types";

export interface UserIndexProps {
    users: PaginatedUsers;
    errors: UserForm;
    roles: { name: string; id: number }[],
    countries: {
        id: string;
        name: string;
        timezone: string;
    }[]
}

export interface UserFormModalProps {
    isOpen: boolean;
    onClose: () => void;
    user?: User;
    onSubmit: (formData: UserForm, userId?: number) => void;
    errors: UserForm;
    roles: { name: string; id: number }[];
    countries: {
        id: string;
        name: string;
        timezone: string;
    }[]
}

export interface SubmitUserFormHandlers {
    closeModal?: () => void;
    resetForm: UserForm;
    setFormErrors: (errors: Partial<UserForm>) => void;
}
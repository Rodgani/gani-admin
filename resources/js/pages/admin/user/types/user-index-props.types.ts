import { PaginatedUsers, UserForm } from "./user.types";

export interface UserIndexProps {
    users: PaginatedUsers;
    errors: UserForm;
    roles: { name: string; id: number }[]
}
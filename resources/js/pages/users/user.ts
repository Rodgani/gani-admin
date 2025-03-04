import { LaravelPagination } from "@/types/laravel-paginate";

export interface User {
    id: number;
    name: string;
    email: string;
    role_slug:string,
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

/**
 * Type for paginated users
 */
export type PaginatedUsers = LaravelPagination<User>;

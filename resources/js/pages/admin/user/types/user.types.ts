import { LaravelPagination } from '@/types/laravel-paginate';

export interface User {
    id: number;
    name: string;
    email: string;
    role_id: number;
    email_verified_at: string | null;
    created_at: Date;
    updated_at: Date;
    role?: {
        id: number;
        slug: string;
        name: string;
    };
}

export interface UserForm {
    name: string;
    email: string;
    password?: string;
    password_confirmation?: string;
    role_id?: number | string;
    [key: string]: string | number | string[] | undefined;
}

/**
 * Type for paginated users
 */
export type PaginatedUsers = LaravelPagination<User>;

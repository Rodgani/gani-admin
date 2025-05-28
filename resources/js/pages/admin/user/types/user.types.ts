import { LaravelPagination } from "@/types/laravel-paginate";
import type { FormDataConvertible } from '@inertiajs/core'; 

export interface User {
    id: number;
    name: string;
    email: string;
    role_id: number,
    email_verified_at: string | null;
    created_at: Date;
    updated_at: Date;
    role?: {
        id: number,
        slug: string,
        name: string
    }
}

export interface UserForm extends Record<string, FormDataConvertible> {
    name: string;
    email: string;
    password?: string
    password_confirmation?: string
    role_id?: number | string;
}
/**
 * Type for paginated users
 */
export type PaginatedUsers = LaravelPagination<User>;

import { LaravelPagination } from "@/types/laravel-paginate";

export interface Role {
    id: number;
    name: string;
    slug: string;
    menus_permissions : string,
    created_at: string;
    updated_at: string;
}


export type PaginatedRoles = LaravelPagination<Role>;

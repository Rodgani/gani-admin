import { LaravelPagination } from "@/types/laravel-paginate";

export interface MenusPermissions {
    title: string;
    url: string;
    icon?: string;
    permissions?: string[];
    items?: MenusPermissions[]; // Nested menu items
}

export interface Role {
    id: number;
    name: string;
    slug: string;
    menus_permissions : MenusPermissions,
    created_at: string;
    updated_at: string;
}

export type PaginatedRoles = LaravelPagination<Role>;

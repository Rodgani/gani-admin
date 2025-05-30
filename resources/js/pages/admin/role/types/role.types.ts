import { LaravelPagination } from "@/types/laravel-paginate";

export interface MenuItem {
    title: string;
    url: string;
    permissions?: string[];
}

export interface Menu {
    title: string;
    url: string;
    icon: string;
    permissions?: string[];
    items?: MenuItem[];
}

export type MenusPermissions = Menu[];

export interface RoleForm{
    name: string;
    slug: string;
}

export interface RolePayload {
  name: string;
  slug: string;
  menus_permissions: MenusPermissions;
}

export interface Role {
    id: number;
    name: string;
    slug: string;
    menus_permissions: MenusPermissions;
    created_at: string;
    updated_at: string;
}

export type PaginatedRoles = LaravelPagination<Role>;

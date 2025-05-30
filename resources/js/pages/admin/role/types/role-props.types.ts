import { MenusPermissions, PaginatedRoles, Role } from "./role.types";

export interface RoleFormProps {
    isOpen: boolean;
    onClose: () => void;
    role?: Role;
    defaultMenusPermissions: MenusPermissions;
    onSubmit: (formData: {
        name: string;
        slug: string;
        menus_permissions: MenusPermissions
    }, roleId?: number) => void;
}

export interface RoleIndexProps {
    roles: PaginatedRoles,
    default_menus_permissions: MenusPermissions
}
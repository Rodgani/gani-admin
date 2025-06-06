import { MenuManager, PaginatedRoles, Role } from "./role.types";

export interface RoleFormProps {
    isOpen: boolean;
    onClose: () => void;
    role?: Role;
    defaultMenuManager: MenuManager;
    onSubmit: (formData: {
        name: string;
        slug: string;
        menus_permissions: MenuManager;
        type: string;
    }, roleId?: number) => void;
    roleTypes: string[];
}

export interface RoleIndexProps {
    roles: PaginatedRoles,
    default_menus_permissions: MenuManager
    role_types: string[]
}
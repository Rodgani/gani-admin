import { MenuManager, PaginatedRoles, Role } from "./role.types";

export interface RoleFormProps {
    isOpen: boolean;
    onClose: () => void;
    role?: Role;
    defaultMenuPermissions: MenuManager;
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
    defaultMenuPermissions: MenuManager
    roleTypes: string[]
}
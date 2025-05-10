import { usePage } from '@inertiajs/react';

interface PermissionItem {
  title: string;
  url: string;
  icon?: string;
  permissions?: string[];
  items?: PermissionItem[];
}

export function userPermissions() {
    const { menus_permissions: menus } = usePage<{ menus_permissions: PermissionItem[] }>().props;

  const hasPermission = (url: string, permission: string): boolean => {
    const checkPermission = (items: PermissionItem[]): boolean => {
      return items.some((item) => {
        const hasCurrent = item.url === url && item.permissions?.includes(permission);
        const hasChild = item.items ? checkPermission(item.items) : false;
        return hasCurrent || hasChild;
      });
    };

    return checkPermission(menus);
  };

  const hasAnyPermission = (url: string, permissions: string[]): boolean =>
    permissions.some((perm) => hasPermission(url, perm));

  return { hasPermission, hasAnyPermission };
}

import { router } from "@inertiajs/react";
import { RolePayload } from "../types/role.types";

export function fetchRoles(params: Record<string, string | number>) {
    return router.get(route('roles.index'), params, {
        preserveScroll: true,
        preserveState: true,
    });
}

export interface RequestCallbacks {
  onSuccess?: () => void;
  onError?: (errors: Record<string, string[] | string>) => void;
}

export async function submitRole(
  formData: RolePayload,
  roleId?: number
): Promise<void> {
  const payload = {
    name: formData.name,
    slug: formData.slug,
    menus_permissions: JSON.stringify(formData.menus_permissions),
  };

  const method = roleId ? 'put' : 'post';
  const url = roleId
    ? route('roles.update', { id: roleId })
    : route('roles.store');

  return new Promise((resolve, reject) => {
    router[method](url, payload, {
      onSuccess: () => resolve(),
      onError: (errors) => reject(errors),
    });
  });
}
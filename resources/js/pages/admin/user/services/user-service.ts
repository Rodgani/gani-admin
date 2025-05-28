import { router } from '@inertiajs/react';
import { UserForm } from '../types/user.types';

// services/user.service.ts
export function fetchUsers(params: Record<string, string | number>) {
    return router.get(route('users.index'), params, {
        preserveScroll: true,
        preserveState: true,
    });
}

export const deleteUser = async (
  id: number,
  onSuccess?: () => void,
  onError?: (errors: Record<string, string[] | string>) => void
) => {
  router.delete(route('users.destroy', { id }), {
    preserveScroll: true,
    onSuccess,
    onError,
  });
};

export interface RequestCallbacks {
  onSuccess?: () => void;
  onError?: (errors: Record<string, string[] | string>) => void;
}

export function submitUserForm(
  formData: UserForm,
  userId?: number,
  callbacks?: RequestCallbacks
) {
  if (userId) {
    router.put(route('users.update', { id: userId }), formData, {
      onSuccess: callbacks?.onSuccess,
      onError: callbacks?.onError,
    });
  } else {
    router.post(route('users.store'), formData, {
      onSuccess: callbacks?.onSuccess,
      onError: callbacks?.onError,
    });
  }
}

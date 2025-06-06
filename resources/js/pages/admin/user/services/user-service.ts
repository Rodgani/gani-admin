import { router } from '@inertiajs/react';
import { UserForm } from '../types/user.types';

export function fetchUsers(params: Record<string, string | number>) {
    return router.get(route('users.index'), params, {
        preserveScroll: true,
        preserveState: true,
    });
}

export const deleteUser = async (id: number, onSuccess?: () => void, onError?: (errors: Record<string, string[] | string>) => void) => {
    router.delete(route('users.destroy', { id }), {
        onSuccess,
        onError,
    });
};

export interface RequestCallbacks {
    onSuccess?: () => void;
    onError?: (errors: Record<string, string[] | string>) => void;
}

export function submitUser(formData: UserForm, userId?: number, callbacks?: RequestCallbacks) {
    const method = userId ? 'put' : 'post';
    const url = userId ? route('users.update', { id: userId }) : route('users.store');

    router[method](url, formData, {
        onSuccess: callbacks?.onSuccess,
        onError: callbacks?.onError,
    });
}

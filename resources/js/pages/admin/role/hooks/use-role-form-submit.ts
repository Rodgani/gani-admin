import { useToastMessage } from '@/hooks/use-toast-message';
import { submitRole } from '../services/role-service';
import { RolePayload } from '../types/role.types';

interface useRoleFormSubmitOptions {
    closeModal?: () => void;
}

export function useRoleFormSubmit({ closeModal }: useRoleFormSubmitOptions) {
    const { showToast } = useToastMessage();
    const handleSubmit = async (formData: RolePayload, roleId?: number) => {
        try {
            console.log(formData);
            await submitRole(formData, roleId);
            showToast('success', {
                message: roleId ? 'Updated successfully!' : 'Created successfully!',
            });
            closeModal?.();
        } catch (errors) {
            showToast('error', errors as Record<string, string | string[]>);
        }
    };

    return { handleSubmit };
}

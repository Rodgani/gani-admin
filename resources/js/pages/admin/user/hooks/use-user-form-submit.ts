import { useToastMessage } from '@/hooks/use-toast-message';
import { useCallback } from 'react';
import { submitUser } from '../services/user-service';
import { UserForm } from '../types/user.types';
import { SubmitUserFormHandlers } from '../types/user-props.types';

export function useUserFormSubmit({ closeModal, resetForm, setFormErrors }: SubmitUserFormHandlers) {
    const { showToast } = useToastMessage();
    const handleSubmit = useCallback(
        (formData: UserForm, userId?: number) => {
            submitUser(formData, userId, {
                onSuccess: () => {
                    if (userId && closeModal) {
                        closeModal(); // Close modal only when updating
                    }

                    showToast('success', {
                        message: userId ? 'Updated successfully!' : 'Created successfully!',
                    });

                    setFormErrors(resetForm);
                },
                onError: (errors) => {
                    setFormErrors({ ...resetForm, ...errors });
                },
            });
        },
        [closeModal, resetForm, setFormErrors, showToast],
    );

    return {
        handleSubmit,
    };
}

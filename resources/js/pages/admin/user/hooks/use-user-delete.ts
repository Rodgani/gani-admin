import { useConfirmToast } from "@/hooks/use-confirm-toast";
import { useToastMessage } from "@/hooks/use-toast-message";
import { deleteUser } from "../services/user-service";


export function useUserDelete() {
  const { showToast } = useToastMessage();
  const confirmToast = useConfirmToast();

  const handleDelete = (id: number) => {
    confirmToast({
      message: 'Are you sure you want to delete this?',
      onConfirm: () => {
        deleteUser(
          id,
          () => showToast('success', { message: 'Deleted successfully!' }),
          (errors) => showToast('error', errors)
        );
      },
      onCancel: () => console.log('Cancelled'),
    });
  };

  return { handleDelete };
}

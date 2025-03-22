import { toast } from "sonner";
import { useCallback } from "react";
import { Button } from "@/components/ui/button";

interface ConfirmToastProps {
  message: string;
  onConfirm: () => void;
  onCancel?: () => void;
  confirmText?: string;
  cancelText?: string;
}

export const useConfirmToast = () => {
  const confirm = useCallback(
    ({
      message,
      onConfirm,
      onCancel,
      confirmText = "Confirm",
      cancelText = "Cancel",
    }: ConfirmToastProps) => {
      toast.custom(
        (t) => (
          <div className="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow-xl rounded-xl p-4 w-[300px]">
            <p className="mb-4 text-center text-gray-800 dark:text-gray-100">{message}</p>
            <div className="flex justify-center gap-2">
              <Button
                onClick={() => {
                  toast.dismiss(t);
                  onCancel?.();
                }}
                className="cursor-pointer"
              >
                {cancelText}
              </Button>
              <Button
                onClick={() => {
                  toast.dismiss(t);
                  onConfirm();
                }}
                className="cursor-pointer"
                variant="destructive"
              >
                {confirmText}
              </Button>
            </div>
          </div>
        ),
        { position: "top-center" }
      );
    },
    []
  );

  return confirm;
};

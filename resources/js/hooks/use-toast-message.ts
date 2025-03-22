import { toast } from "sonner";

type ToastType = "success" | "error" | "info" | "warning";

export const useToastMessage = () => {
  const showToast = (
    type: ToastType,
    messages: Record<string, string | string[]>
  ) => {
    const combinedMessages = Object.values(messages)
      .flat()
      .map((msg) => `• ${msg}`)
      .join("\n");

    toast[type](combinedMessages, {
      className: "break-words whitespace-pre-wrap text-sm text-start",
      duration: 5000,
      style: { width: "fit-content", maxWidth: "90vw" },
    });
  };

  return { showToast };
};

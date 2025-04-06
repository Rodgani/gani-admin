import { Skeleton } from "@/components/ui/skeleton";

export default function CenteredSkeletonLoader() {
    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/30">
            <div className="flex flex-col items-center gap-4 animate-pulse">
                <Skeleton className="h-4 w-24 rounded" />
                <Skeleton className="h-4 w-32 rounded" />
                <Skeleton className="h-4 w-24 rounded" />
            </div>
        </div>
    );
}

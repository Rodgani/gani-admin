import { useState, useEffect } from 'react';
import { Input } from "@/components/ui/input";
import { Avatar, AvatarImage } from "@/components/ui/avatar";
import InputError from './input-error';

interface AvatarUploaderProps {
  errors: {
    avatar?: string;
  };
  setData: (key: string, value: any) => void;
  user: {
    name: string
    avatar: string
  }
}

export default function AvatarUploader({ errors, setData, user }: AvatarUploaderProps) {

    const [previewUrl, setPreviewUrl] = useState<string | null>(null);

    useEffect(() => {
        return () => {
            // Cleanup URL object when component unmounts or file changes
            if (previewUrl) URL.revokeObjectURL(previewUrl);
        };
    }, [previewUrl]);

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0] ?? null;
        setData('avatar', file);

        if (file) {
            const url = URL.createObjectURL(file);
            setPreviewUrl(url);
        } else {
            setPreviewUrl(null);
        }
        
    };

    return (
        <div className="grid gap-2">
            <div className="relative w-fit group">
                {/* Hidden file input over the avatar */}
                <Input
                    type="file"
                    accept="image/*"
                    className="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                    onChange={handleFileChange}
                />

                {/* Avatar with preview fallback */}
                <Avatar className="size-30 overflow-hidden rounded-full ring-2 ring-gray-300">
                    <AvatarImage
                        src={previewUrl ?? user.avatar}
                        alt={user.name}
                    />
                </Avatar>

                {/* Hover Overlay */}
                <div className="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-full flex items-center justify-center z-0">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        className="w-6 h-6 text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        strokeWidth={2}
                    >
                        <path
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            d="M15.232 5.232l3.536 3.536M9 11l3 3 9-9-3-3-9 9zM12 20h9"
                        />
                    </svg>
                </div>
            </div>

            <InputError className="mt-2" message={errors.avatar} />
        </div>
    );
}

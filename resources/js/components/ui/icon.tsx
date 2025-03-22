import { LucideIcon, Trash, Trash2 } from 'lucide-react';

interface IconProps {
    iconNode?: LucideIcon | null;
    className?: string;
    size?: number;
    strokeWidth?: number;
    color?: string;
}

export function Icon({
    iconNode: IconComponent,
    className,
    size = 20,
    strokeWidth = 2,
    color,
}: IconProps) {
    if (!IconComponent) {
        return null;
    }

    const defaultColor =
        IconComponent === Trash || IconComponent === Trash2 ? '#d01b1b' : 'currentColor';

    return (
        <IconComponent
            className={className}
            size={size}
            strokeWidth={strokeWidth}
            color={color ?? defaultColor}
        />
    );
}

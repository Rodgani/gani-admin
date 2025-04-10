import { Breadcrumbs } from '@/components/breadcrumbs';
import { Icon } from '@/components/icon';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger, DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { Sheet, SheetContent, SheetTrigger } from '@/components/ui/sheet';
import { UserMenuContent } from '@/components/user-menu-content';
import { useInitials } from '@/hooks/use-initials';
import { cn } from '@/lib/utils';
import { type BreadcrumbItem, type NavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { Menu, Search } from 'lucide-react';
import { icons, type LucideIcon } from 'lucide-react'; // For dynamic icons


const activeItemStyles = 'text-neutral-900 dark:bg-neutral-800 dark:text-neutral-100';

interface AppHeaderProps {
    breadcrumbs?: BreadcrumbItem[];
}

export function AppHeader({ breadcrumbs = [] }: AppHeaderProps) {
    const page = usePage<SharedData>();
    const { auth, menus_permissions } = page.props;
    const getInitials = useInitials();

    const getIcon = (iconName?: string): LucideIcon | undefined => {
        if (!iconName) return undefined;
        return icons[iconName as keyof typeof icons] || undefined;
    };

    const safeMenus: NavItem[] = Array.isArray(menus_permissions) ? menus_permissions : [];

    const processedMenus = safeMenus.map((item) => ({
        ...item,
        icon: getIcon(item.icon),
    }));


    return (
        <>
            <div className="border-sidebar-border/80 border-b">
                <div className="mx-auto flex h-16 items-center px-4 md:max-w-7xl">
                    {/* Mobile Menu */}
                    <div className="lg:hidden">
                        <Sheet>
                            <SheetTrigger asChild>
                                <Button variant="ghost" size="icon" className="mr-2 h-[34px] w-[34px]">
                                    <Menu className="h-5 w-5" />
                                </Button>
                            </SheetTrigger>
                            <SheetContent side="left" className="bg-sidebar flex h-full w-64 flex-col items-stretch justify-between">
                                <div className="flex h-full flex-1 flex-col space-y-4 p-4">
                                    <div className="flex h-full flex-col justify-between text-sm">
                                        <div className="flex flex-col space-y-4">
                                            {processedMenus.map((item) => {
                                                return item.items ? (
                                                    // Render dropdown if items exist
                                                    <div key={item.title}>
                                                        <DropdownMenu>
                                                            <DropdownMenuTrigger asChild>
                                                                <Link href="#" className="flex items-center space-x-2 font-medium">
                                                                    {item.icon && <Icon iconNode={item.icon} className="h-5 w-5" />}
                                                                    <span>{item.title}</span>
                                                                </Link>
                                                            </DropdownMenuTrigger>
                                                            <DropdownMenuContent className="w-56">
                                                                {item.items.map((subItem, index) => {
                                                                    return (
                                                                        <DropdownMenuItem key={index}>
                                                                            <Link href={subItem.url}>{subItem.title}</Link>
                                                                        </DropdownMenuItem>
                                                                    );
                                                                })}
                                                            </DropdownMenuContent>
                                                        </DropdownMenu>
                                                    </div>
                                                ) : (
                                                    <Link key={item.title} href={item.url} className="flex items-center space-x-2 font-medium">
                                                        {item.icon && <Icon iconNode={item.icon} className="h-5 w-5" />}
                                                        <span>{item.title}</span>
                                                    </Link>
                                                );
                                            })}
                                        </div>

                                    </div>
                                </div>
                            </SheetContent>
                        </Sheet>
                    </div>

                    {/* Desktop Navigation */}
                    <div className="hidden items-center space-x-2 lg:flex">
                        <div className="flex items-stretch">
                            {processedMenus.map((item, index) => {
                                const isActive = new URL(item.url, window.location.origin).pathname === new URL(page.url, window.location.origin).pathname;
                                return item.items ? (
                                    // Render dropdown for items
                                    <div key={index} className="relative">
                                        <DropdownMenu>
                                            <DropdownMenuTrigger asChild>
                                                <Link
                                                    href="#"
                                                    className={cn(
                                                        'flex items-center space-x-2 h-9 px-3',
                                                        isActive && activeItemStyles
                                                    )}
                                                >
                                                    {item.icon && (
                                                        <Icon iconNode={item.icon} className="mr-2 h-4 w-4" />
                                                    )}
                                                    <span>{item.title}</span>
                                                </Link>
                                            </DropdownMenuTrigger>
                                            <DropdownMenuContent className="w-56">
                                                {item.items.map((subItem, subIndex) => {
                                                    const isSubItemActive =
                                                        new URL(subItem.url, window.location.origin).pathname ===
                                                        new URL(page.url, window.location.origin).pathname;
                                                    return (
                                                        <DropdownMenuItem
                                                            key={subIndex}
                                                            className={cn(isSubItemActive && activeItemStyles)}
                                                        >
                                                            <Link href={subItem.url}>{subItem.title}</Link>
                                                        </DropdownMenuItem>
                                                    );
                                                })}
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </div>
                                ) : (
                                    <Link
                                        key={index}
                                        href={item.url}
                                        className={cn(
                                            'flex items-center space-x-2 h-9 rounded-md',
                                            isActive ? 'px-3' : 'px-0',
                                            isActive && activeItemStyles
                                        )}
                                    >
                                        {item.icon && (
                                            <Icon iconNode={item.icon} className="mr-2 h-4 w-4" />
                                        )}
                                        <span>{item.title}</span>
                                    </Link>
                                );
                            })}
                        </div>
                    </div>

                    <div className="ml-auto flex items-center space-x-2">
                        <div className="relative flex items-center space-x-1">
                            <Button variant="ghost" size="icon" className="group h-9 w-9 cursor-pointer">
                                <Search className="!size-5 opacity-80 group-hover:opacity-100" />
                            </Button>
                        </div>
                        <DropdownMenu>
                            <DropdownMenuTrigger asChild>
                                <Button variant="ghost" className="size-10 rounded-full p-1">
                                    <Avatar className="size-8 overflow-hidden rounded-full">
                                        <AvatarImage src={auth.user.avatar} alt={auth.user.name} />
                                        <AvatarFallback className="rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                            {getInitials(auth.user.name)}
                                        </AvatarFallback>
                                    </Avatar>
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent className="w-56" align="end">
                                <UserMenuContent user={auth.user} />
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                </div>
            </div>
            {breadcrumbs.length > 1 && (
                <div className="border-sidebar-border/70 flex w-full border-b">
                    <div className="mx-auto flex h-12 w-full items-center justify-start px-4 text-neutral-500 md:max-w-7xl">
                        <Breadcrumbs breadcrumbs={breadcrumbs} />
                    </div>
                </div>
            )}
        </>
    );
}

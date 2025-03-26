import * as React from "react"
import { NavMain } from "@/components/nav-main"
import { NavUser } from "@/components/nav-user"
import {
  Sidebar,
  SidebarContent,
  SidebarFooter,
  SidebarHeader,
  SidebarRail,
} from "@/components/ui/sidebar"
import { Link, usePage } from "@inertiajs/react"
import AppLogo from "./app-logo"
import { Separator } from "./ui/separator"
import { NavItem } from "@/types"
import { icons, type LucideIcon } from "lucide-react" // Import all Lucide icons

export function AppSidebar({ ...props }: React.ComponentProps<typeof Sidebar>) {
  const { menus_permissions } = usePage().props as { menus_permissions?: NavItem[] };
  const safeMenus: NavItem[] = Array.isArray(menus_permissions) ? menus_permissions : [];

  // Function to dynamically get an icon from Lucide
  const getIcon = (iconName?: string): LucideIcon | undefined => {
    if (!iconName) return undefined;
    return icons[iconName as keyof typeof icons] || undefined;
  };

  // Process menus and convert icon names to actual components
  const processedMenus = safeMenus.map((menu) => ({
    ...menu,
    icon: getIcon(menu.icon),
    items: menu.items?.map((subItem) => ({
      ...subItem,
      icon: getIcon(subItem.icon),
    })),
  }));

  return (
    <Sidebar collapsible="icon" {...props}>
      <SidebarHeader className="flex" onClick={(e) => e.stopPropagation()}>
        <Link href="#" prefetch className="flex items-center gap-2 mt-1.5">
          <AppLogo />
        </Link>
      </SidebarHeader>
      <Separator className="mt-2" />
      <SidebarContent>
        <NavMain items={processedMenus} />
      </SidebarContent>
      <SidebarFooter>
        <NavUser />
      </SidebarFooter>
      <SidebarRail />
    </Sidebar >
  )
}

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
import { SquareTerminal, Bot, type LucideIcon } from "lucide-react"

// Map icon names (as strings) to actual Lucide components
const ICON_MAP: Record<string, LucideIcon> = {
  SquareTerminal,
  Bot,
  // Add more icons if needed
}

export function AppSidebar({ ...props }: React.ComponentProps<typeof Sidebar>) {
  const { menus_permissions } = usePage().props as { menus_permissions?: NavItem[] };
  const safeMenus: NavItem[] = Array.isArray(menus_permissions) ? menus_permissions : [];

  // Convert icon strings to actual Lucide components
  const processedMenus = safeMenus.map((menu) => ({
    ...menu,
    icon: menu.icon && typeof menu.icon === "string" ? ICON_MAP[menu.icon] : undefined, // Convert icon string to component
    items: menu.items?.map((subItem) => ({
      ...subItem,
      icon: subItem.icon && typeof subItem.icon === "string" ? ICON_MAP[subItem.icon] : undefined,
    })),
  }));

  return (
    <Sidebar collapsible="icon" {...props}>
        <SidebarHeader className="flex" onClick={(e) => e.stopPropagation()}>
            <Link href="/dashboard" prefetch className="flex items-center gap-2 mt-1.5">
                <AppLogo/>
            </Link>
        </SidebarHeader>
        <Separator className="mt-2" />
        <SidebarContent>
            <NavMain items={processedMenus} />
        </SidebarContent>
        <SidebarFooter>
            <NavUser/>
        </SidebarFooter>
      <SidebarRail />
    </Sidebar>
  )
}

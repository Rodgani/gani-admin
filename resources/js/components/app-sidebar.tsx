import * as React from "react"
import {
  Bot,
  SquareTerminal,
} from "lucide-react"
import { NavMain } from "@/components/nav-main"
import { NavUser } from "@/components/nav-user"
import {
  Sidebar,
  SidebarContent,
  SidebarFooter,
  SidebarHeader,
  SidebarRail,
} from "@/components/ui/sidebar"
import { Link } from "@inertiajs/react"
import AppLogo from "./app-logo"
import { Separator } from "./ui/separator"
// This is sample data.
const data = {
  navMain: [
    {
      title: "Admin",
      url: "#",
      icon: SquareTerminal,
      items: [
        {
          title: "Users",
          url: "/admin/users",
        },
        {
          title: "Roles",
          url: "/settings/password",
        },
        {
          title: "Permissions",
          url: "/settings/appearance",
        },
      ],
    },
    {
      title: "Profile",
      url: "/settings/profile",
      icon: Bot,
    },
  ],
}
export function AppSidebar({ ...props }: React.ComponentProps<typeof Sidebar>) {
  return (
    <Sidebar collapsible="icon" {...props}>
        <SidebarHeader className="flex" onClick={(e) => e.stopPropagation()}>
            <Link href="/dashboard" prefetch className="flex items-center gap-2 mt-1.5">
                <AppLogo/>
            </Link>
        </SidebarHeader>
        <Separator className="mt-2" />
        <SidebarContent>
            <NavMain items={data.navMain} />
        </SidebarContent>
        <SidebarFooter>
            <NavUser/>
        </SidebarFooter>
      <SidebarRail />
    </Sidebar>
  )
}

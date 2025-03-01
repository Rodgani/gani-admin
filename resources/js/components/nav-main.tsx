import { Link, usePage } from "@inertiajs/react";
import { useState, useEffect } from "react";
import { ChevronRight, type LucideIcon } from "lucide-react";
import {
  Collapsible,
  CollapsibleContent,
  CollapsibleTrigger,
} from "@/components/ui/collapsible";
import {
  SidebarGroup,
  SidebarGroupLabel,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
  SidebarMenuSub,
  SidebarMenuSubButton,
  SidebarMenuSubItem,
} from "@/components/ui/sidebar";

export function NavMain({
  items,
}: {
  items: {
    title: string;
    url: string;
    icon?: LucideIcon;
    isActive?: boolean;
    items?: {
      title: string;
      url: string;
    }[];
  }[];
}) {
  const page = usePage();
  const currentUrl = page.url; // Get current page URL

  // Track which menus are open
  const [openMenus, setOpenMenus] = useState<Record<string, boolean>>({});

  useEffect(() => {
    setOpenMenus((prev) => {
      const updatedOpenMenus = { ...prev };

      items.forEach((item) => {
        if (item.items?.some((subItem) => {
          // Extract base path, ignoring query parameters
          const baseSubItemUrl = new URL(subItem.url, window.location.origin).pathname;
          const baseCurrentUrl = new URL(currentUrl, window.location.origin).pathname;

          return baseSubItemUrl === baseCurrentUrl;
        })) {
          updatedOpenMenus[item.title] = true; // Keep parent menu open
        }
      });

      return updatedOpenMenus;
    });
  }, [currentUrl, items]);

  const toggleMenu = (title: string) => {
    setOpenMenus((prev) => ({
      ...prev,
      [title]: !prev[title], // Toggle open state
    }));
  };

  return (
    <SidebarGroup>
      <SidebarMenu>
        {items.map((item) =>
          item.items && item.items.length > 0 ? (
            <Collapsible key={item.title} asChild open={openMenus[item.title] || false}>
              <SidebarMenuItem>
                <CollapsibleTrigger asChild onClick={() => toggleMenu(item.title)}>
                  <SidebarMenuButton tooltip={item.title}>
                    {item.icon && <item.icon />}
                    <span>{item.title}</span>
                    <ChevronRight className={`ml-auto transition-transform duration-200 ${openMenus[item.title] ? "rotate-90" : ""}`} />
                  </SidebarMenuButton>
                </CollapsibleTrigger>
                <CollapsibleContent>
                  <SidebarMenuSub>
                    {item.items.map((subItem) => {
                      const isActive = new URL(subItem.url, window.location.origin).pathname ===
                        new URL(currentUrl, window.location.origin).pathname;

                      return (
                        <SidebarMenuSubItem key={subItem.title}>
                          <SidebarMenuSubButton asChild isActive={isActive}>
                            <Link href={subItem.url}>
                              <span>{subItem.title}</span>
                            </Link>
                          </SidebarMenuSubButton>
                        </SidebarMenuSubItem>
                      );
                    })}
                  </SidebarMenuSub>
                </CollapsibleContent>
              </SidebarMenuItem>
            </Collapsible>
          ) : (
            <SidebarMenuItem key={item.title}>
              <SidebarMenuButton asChild tooltip={item.title} isActive={new URL(item.url, window.location.origin).pathname === new URL(currentUrl, window.location.origin).pathname}>
                <Link href={item.url} className="flex items-center gap-2 w-full px-2 py-1.5">
                  {item.icon && <item.icon />}
                  <span>{item.title}</span>
                </Link>
              </SidebarMenuButton>
            </SidebarMenuItem>
          )
        )}
      </SidebarMenu>
    </SidebarGroup>
  );
}

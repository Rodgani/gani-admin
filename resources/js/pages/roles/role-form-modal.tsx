import { Dialog, DialogContent, DialogFooter, DialogHeader } from "@/components/ui/dialog";
import { MenusPermissions, Role, RoleForm } from "./role";
import { DialogDescription, DialogTitle } from "@radix-ui/react-dialog";
import { Button } from "@/components/ui/button";
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from "@/components/ui/accordion";
import { Checkbox } from "@/components/ui/checkbox";  // assuming you have a Checkbox component
import { useEffect, useState } from "react";
import { Input } from "@/components/ui/input";

interface RoleFormProps {
    isOpen: boolean;
    onClose: () => void;
    role?: Role;
    defaultMenusPermissions: MenusPermissions;
    onSubmit: (formData: {
        name: string;
        slug: string;
        menus_permissions: MenusPermissions
    }, roleId?: number) => void;
}

export default function RoleFormModal({ isOpen, onClose, role, defaultMenusPermissions, onSubmit }: RoleFormProps) {
    const [menusPermissionsState, setMenusPermissionsState] = useState<{ [key: string]: string[] }>({});

    const [formData, setFormData] = useState<RoleForm>({
        name: '',
        slug: '',
    });

    useEffect(() => {
        if (role?.menus_permissions) {
            try {
                const parsed = typeof role.menus_permissions === 'string'
                    ? JSON.parse(role.menus_permissions)
                    : role.menus_permissions;

                const mappedState: { [key: string]: string[] } = {};

                parsed.forEach((menu: any) => {
                    if (menu.items) {
                        menu.items.forEach((item: any) => {
                            mappedState[item.url] = item.permissions || [];
                        });
                    } else {
                        mappedState[menu.url] = menu.permissions || [];
                    }
                });

                setMenusPermissionsState(mappedState);
                setFormData({
                    name: role.name,
                    slug: role.slug,
                });
            } catch (error) {
                setMenusPermissionsState({});
            }
        } else {
            setMenusPermissionsState({});
        }
    }, [role, isOpen, defaultMenusPermissions]);

    const hasPermission = (url: string, permission: string): boolean => {
        return menusPermissionsState[url]?.includes(permission) ?? false;
    };

    // const togglePermission = (url: string, permission: string) => {
    //     setMenusPermissionsState((prev) => {
    //         const currentPermissions = prev[url] || [];
    //         const updatedPermissions = currentPermissions.includes(permission)
    //             ? currentPermissions.filter((p) => p !== permission)
    //             : [...currentPermissions, permission];

    //         return {
    //             ...prev,
    //             [url]: updatedPermissions,
    //         };
    //     });
    // };
    const togglePermission = (url: string, permission: string) => {
        setMenusPermissionsState((prev) => {
            const currentPermissions = prev[url] || [];
            const isChecked = currentPermissions.includes(permission);

            // If toggling "view" off, remove all permissions
            if (permission === "view" && isChecked) {
                return {
                    ...prev,
                    [url]: []
                };
            }

            // If toggling "view" on, just add "view"
            if (permission === "view" && !isChecked) {
                return {
                    ...prev,
                    [url]: [...currentPermissions, "view"]
                };
            }

            // If toggling other permissions, only allow if "view" is present
            if (permission !== "view") {
                if (!currentPermissions.includes("view")) {
                    // Don't allow adding other permissions without view
                    return prev;
                }

                const updatedPermissions = isChecked
                    ? currentPermissions.filter((p) => p !== permission)
                    : [...currentPermissions, permission];

                return {
                    ...prev,
                    [url]: updatedPermissions
                };
            }

            return prev;
        });
    };


    const handleSubmit = () => {
        const menusPermissions = defaultMenusPermissions.map((menu) => {
            if (menu.items) {
                return {
                    title: menu.title,
                    url: menu.url,
                    icon: menu.icon,
                    items: menu.items.map((subItem) => ({
                        title: subItem.title,
                        url: subItem.url,
                        permissions: menusPermissionsState[subItem.url] || []
                    }))
                };
            } else {
                return {
                    title: menu.title,
                    url: menu.url,
                    icon: menu.icon,
                    permissions: menusPermissionsState[menu.url] || []
                };
            }
        });

        const mergedData = {
            ...formData,
            menus_permissions: menusPermissions
        };

        onSubmit(mergedData, role?.id);
    };

    const [checkAll, setCheckAll] = useState(false);

    const handleToggleAll = () => {
        if (checkAll) {
            // Uncheck all
            setMenusPermissionsState({});
        } else {
            // Check all
            const allCheckedState: { [key: string]: string[] } = {};
            defaultMenusPermissions.forEach((menu) => {
                if (menu.items) {
                    menu.items.forEach((item) => {
                        allCheckedState[item.url] = item.permissions || [];
                    });
                } else {
                    allCheckedState[menu.url] = menu.permissions || [];
                }
            });
            setMenusPermissionsState(allCheckedState);
        }
        setCheckAll(!checkAll);
    };

    const fields = [
        { name: "name", type: "text", placeholder: "Name", required: true },
        { name: "slug", type: "slug", placeholder: "Slug", required: true, readOnly: !!role },
    ];

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setFormData((prev) => ({
            ...prev!,
            [e.target.name]: e.target.value,
        }));
    };

    return (
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>{role ? 'Edit Role' : 'Create Role'}</DialogTitle>
                    <DialogDescription>
                        {role ? "Update the role permissions below." : "Set permissions for the new role."}
                    </DialogDescription>
                </DialogHeader>

                <div className="space-y-4">
                    <div className="flex justify-start mb-2">
                        <Button variant="secondary" onClick={handleToggleAll}>
                            {checkAll ? "Uncheck All" : "Check All"}
                        </Button>
                    </div>
                    {fields.map(({ name, type, placeholder, required, readOnly }) => (
                        <Input
                            key={name}
                            name={name}
                            type={type}
                            value={formData[name as keyof typeof formData] ?? ''}
                            onChange={handleChange}
                            placeholder={placeholder}
                            required={required}
                            {...(readOnly ? { readOnly: true } : {})}
                        />
                    ))}
                    <Accordion type="multiple" className="w-full">
                        {defaultMenusPermissions.map((menu, index) => (
                            <AccordionItem key={index} value={`item-${index}`}>
                                <AccordionTrigger>{menu.title}</AccordionTrigger>
                                <AccordionContent>
                                    {menu.items ? (
                                        <ul className="pl-4 space-y-2">
                                            {menu.items.map((subItem, subIndex) => (
                                                <li key={subIndex}>
                                                    <div className="font-medium mb-1">{subItem.title}</div>
                                                    <div className="flex flex-col gap-2 pl-2">
                                                        {subItem.permissions?.map((permission) => (
                                                            <label key={permission} className="flex items-center gap-1">
                                                                <Checkbox
                                                                    checked={hasPermission(subItem.url, permission)}
                                                                    disabled={permission !== 'view' && !hasPermission(subItem.url, 'view')}
                                                                    onCheckedChange={() => togglePermission(subItem.url, permission)}
                                                                />
                                                                <span className="capitalize text-sm">{permission}</span>
                                                            </label>
                                                        ))}
                                                    </div>
                                                </li>
                                            ))}
                                        </ul>
                                    ) : (
                                        <div className="flex flex-col gap-2 pl-2">
                                            {menu.permissions?.map((permission) => (
                                                <label key={permission} className="flex items-center gap-1">
                                                    <Checkbox
                                                        checked={hasPermission(menu.url, permission)}
                                                        disabled={permission !== 'view' && !hasPermission(menu.url, 'view')}
                                                        onCheckedChange={() => togglePermission(menu.url, permission)}
                                                    />
                                                    <span className="capitalize text-sm">{permission}</span>
                                                </label>
                                            ))}
                                        </div>
                                    )}
                                </AccordionContent>
                            </AccordionItem>
                        ))}
                    </Accordion>
                </div>

                <DialogFooter>
                    <Button onClick={() => onClose()} variant="outline" className="cursor-pointer">Cancel</Button>
                    <Button onClick={handleSubmit} className="cursor-pointer">Save</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}

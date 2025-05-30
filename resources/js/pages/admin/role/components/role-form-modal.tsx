/* eslint-disable @typescript-eslint/no-explicit-any */
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox'; // assuming you have a Checkbox component
import { Dialog, DialogContent, DialogFooter, DialogHeader } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { DialogDescription, DialogTitle } from '@radix-ui/react-dialog';
import { useEffect, useMemo, useState } from 'react';
import { RoleFormProps } from '../types/role-props.types';
import { RoleForm } from '../types/role.types';

export default function RoleFormModal({ isOpen, onClose, role, defaultMenusPermissions, onSubmit }: RoleFormProps) {
    const [menusPermissionsState, setMenusPermissionsState] = useState<{ [key: string]: string[] }>({});

    const [formData, setFormData] = useState<RoleForm>({
        name: '',
        slug: '',
    });

    useEffect(() => {
        if (role?.menus_permissions) {
            try {
                const parsed = typeof role.menus_permissions === 'string' ? JSON.parse(role.menus_permissions) : role.menus_permissions;

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
                console.log(error);
                setMenusPermissionsState({});
            }
        } else {
            setMenusPermissionsState({});
        }
    }, [role, isOpen, defaultMenusPermissions]);

    const hasPermission = (url: string, permission: string): boolean => {
        return menusPermissionsState[url]?.includes(permission) ?? false;
    };

    const togglePermission = (url: string, permission: string) => {
        setMenusPermissionsState((prev) => {
            const currentPermissions = prev[url] || [];
            const isChecked = currentPermissions.includes(permission);

            let updatedPermissions: string[] = [];

            if (permission === 'view') {
                updatedPermissions = isChecked ? [] : ['view'];
            } else {
                if (!currentPermissions.includes('view')) {
                    // Do nothing if view is not present
                    return prev;
                }

                updatedPermissions = isChecked ? currentPermissions.filter((p) => p !== permission) : [...currentPermissions, permission];
            }

            const newState = { ...prev };

            // If no permissions left, remove that entire key
            if (updatedPermissions.length === 0) {
                delete newState[url];
            } else {
                newState[url] = updatedPermissions;
            }

            return newState;
        });
    };

    const handleSubmit = () => {
        const menusPermissions = defaultMenusPermissions
            .map((menu) => {
                if (menu.items) {
                    const filteredItems = menu.items
                        .map((subItem) => ({
                            title: subItem.title,
                            url: subItem.url,
                            permissions: menusPermissionsState[subItem.url] || [],
                        }))
                        .filter((subItem) => subItem.permissions.length > 0);

                    // Only include parent if it still has items
                    if (filteredItems.length > 0) {
                        return {
                            title: menu.title,
                            url: menu.url,
                            icon: menu.icon,
                            items: filteredItems,
                        };
                    }

                    return null; // if no sub-items left
                } else {
                    const permissions = menusPermissionsState[menu.url] || [];
                    return permissions.length > 0
                        ? {
                              title: menu.title,
                              url: menu.url,
                              icon: menu.icon,
                              permissions,
                          }
                        : null;
                }
            })
            .filter((menu) => menu !== null); // remove empty parents

        const mergedData = {
            ...formData,
            menus_permissions: menusPermissions,
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

    const fields = useMemo(() => {
        return [
            { name: 'name', type: 'text', placeholder: 'Name', required: true },
            { name: 'slug', type: 'slug', placeholder: 'Slug', required: true, readOnly: !!role },
        ];
    }, [role]);

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
                    <DialogDescription>{role ? 'Update the role permissions below.' : 'Set permissions for the new role.'}</DialogDescription>
                </DialogHeader>

                <div className="space-y-4">
                    <div className="mb-2 flex justify-start">
                        <Button variant="secondary" onClick={handleToggleAll}>
                            {checkAll ? 'Uncheck All' : 'Check All'}
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
                                        <ul className="space-y-2 pl-4">
                                            {menu.items.map((subItem, subIndex) => (
                                                <li key={subIndex}>
                                                    <div className="mb-1 font-medium">{subItem.title}</div>
                                                    <div className="flex flex-col gap-2 pl-2">
                                                        {subItem.permissions?.map((permission) => (
                                                            <label key={permission} className="flex items-center gap-1">
                                                                <Checkbox
                                                                    checked={hasPermission(subItem.url, permission)}
                                                                    disabled={permission !== 'view' && !hasPermission(subItem.url, 'view')}
                                                                    onCheckedChange={() => togglePermission(subItem.url, permission)}
                                                                />
                                                                <span className="text-sm capitalize">{permission}</span>
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
                                                    <span className="text-sm capitalize">{permission}</span>
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
                    <Button onClick={() => onClose()} variant="outline" className="cursor-pointer">
                        Cancel
                    </Button>
                    <Button onClick={handleSubmit} className="cursor-pointer">
                        Save
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}

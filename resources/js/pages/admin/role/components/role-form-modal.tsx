/* eslint-disable @typescript-eslint/no-explicit-any */
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox'; // assuming you have a Checkbox component
import { Dialog, DialogContent, DialogFooter, DialogHeader } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { ScrollArea, ScrollBar } from '@/components/ui/scroll-area';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { DialogDescription, DialogTitle } from '@radix-ui/react-dialog';
import { useEffect, useMemo, useState } from 'react';
import { RoleFormProps } from '../types/role-props.types';
import { RoleForm } from '../types/role.types';

export default function RoleFormModal({ isOpen, onClose, role, defaultMenuPermissions, onSubmit, roleTypes }: RoleFormProps) {
    const [MenuManagerState, setMenuManagerState] = useState<{ [key: string]: string[] }>({});

    const [formData, setFormData] = useState<RoleForm>({
        name: '',
        slug: '',
        type: '',
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

                setMenuManagerState(mappedState);
                setFormData({
                    name: role.name,
                    slug: role.slug,
                    type: role.type,
                });
            } catch (error) {
                setMenuManagerState({});
            }
            
            setCheckAll(false);
            if(role?.menus_permissions.length!==0){
                setCheckAll(true);
            }
           
        } else {
            setMenuManagerState({});
        }
    }, [role, isOpen, defaultMenuPermissions]);

    const hasPermission = (url: string, permission: string): boolean => {
        return MenuManagerState[url]?.includes(permission) ?? false;
    };

    const togglePermission = (url: string, permission: string) => {
        setMenuManagerState((prev) => {
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

    const computedMenuManager = useMemo(() => {
        return defaultMenuPermissions
            .map((menu) => {
                if (menu.items) {
                    const filteredItems = menu.items
                        .map((subItem) => ({
                            title: subItem.title,
                            url: subItem.url,
                            permissions: MenuManagerState[subItem.url] || [],
                        }))
                        .filter((subItem) => subItem.permissions.length > 0);

                    return filteredItems.length > 0
                        ? {
                              title: menu.title,
                              url: menu.url,
                              icon: menu.icon,
                              items: filteredItems,
                          }
                        : null;
                } else {
                    const permissions = MenuManagerState[menu.url] || [];
                    return permissions.length > 0 ? { title: menu.title, url: menu.url, icon: menu.icon, permissions } : null;
                }
            })
            .filter((menu) => menu !== null);
    }, [MenuManagerState, defaultMenuPermissions]);

    const handleSubmit = () => {
        onSubmit({ ...formData, menus_permissions: computedMenuManager }, role?.id);
    };

    const [checkAll, setCheckAll] = useState(false);

    const handleToggleAll = () => {
        if (checkAll) {
            // Uncheck all
            setMenuManagerState({});
        } else {
            // Check all
            const allCheckedState: { [key: string]: string[] } = {};
            defaultMenuPermissions.forEach((menu) => {
                if (menu.items) {
                    menu.items.forEach((item) => {
                        allCheckedState[item.url] = item.permissions || [];
                    });
                } else {
                    allCheckedState[menu.url] = menu.permissions || [];
                }
            });
            setMenuManagerState(allCheckedState);
        }
        setCheckAll(!checkAll);
    };

    const fields = [
        { name: 'name', type: 'text', placeholder: 'Name', required: true },
        { name: 'slug', type: 'slug', placeholder: 'Slug', required: true, readOnly: !!role },
        { name: 'type', type: 'select', placeholder: 'Type', options: roleTypes },
    ];

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
        const { name, value } = e.target;
        setFormData((prev) => ({
            ...prev,
            [name]: value,
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
                    {fields.map(({ name, type, placeholder, required, readOnly, options }) => {
                        const value = formData[name as keyof typeof formData] ?? '';

                        return (
                            <div key={name} className="space-y-1">
                                <Label htmlFor={name}>{placeholder}</Label>

                                {type === 'select' ? (
                                    <Select
                                        name={name}
                                        value={value}
                                        onValueChange={(val) => handleChange({ target: { name, value: val } } as any)}
                                        disabled={readOnly}
                                    >
                                        <SelectTrigger id={name}>
                                            <SelectValue placeholder={`Select ${placeholder}`} />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {options?.map((option) => (
                                                <SelectItem key={option} value={option}>
                                                     {option.charAt(0).toUpperCase() + option.slice(1)}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                ) : (
                                    <Input
                                        id={name}
                                        name={name}
                                        type={type}
                                        value={value}
                                        onChange={handleChange}
                                        placeholder={placeholder}
                                        required={required}
                                        {...(readOnly ? { readOnly: true } : {})}
                                    />
                                )}
                            </div>
                        );
                    })}

                    <ScrollArea className="w-full rounded-lg">
                        <div className="max-h-[400px] space-y-4 pr-2">
                            <Accordion type="multiple" className="w-full">
                                {defaultMenuPermissions.map((menu, index) => (
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
                        <ScrollBar orientation="vertical" />
                    </ScrollArea>
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

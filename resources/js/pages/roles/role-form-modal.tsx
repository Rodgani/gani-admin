import { Dialog, DialogContent, DialogFooter, DialogHeader } from "@/components/ui/dialog";
import { MenusPermissions, Role } from "./role";
import { DialogDescription, DialogTitle } from "@radix-ui/react-dialog";
import { Button } from "@/components/ui/button";

interface RoleFormProps {
    isOpen: boolean;
    onClose: () => void;
    role?: Role;
    defaultMenusPermissions: MenusPermissions
}

export default function RoleFormModal({ isOpen, onClose, role, defaultMenusPermissions }: RoleFormProps) {
    console.log(defaultMenusPermissions)
    console.log(role?.menus_permissions)
    return (
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>{role ? 'Edit Role' : 'Create Role'}</DialogTitle>
                    <DialogDescription>
                        {role ? "Update the user details below." : "Fill in the details to create a new role."}
                    </DialogDescription>
                </DialogHeader>

                <div className="space-y-4">
                    <h1>Initial role form modal</h1>
                    {/* {fields.map(({ name, type, placeholder, required, inputType }) => (
                        inputType === "input" ? (
                            <Input
                                key={name}
                                name={name}
                                type={type}
                                value={formData[name as keyof typeof formData] ?? ''}
                                onChange={handleChange}
                                placeholder={placeholder}
                                required={required}
                            />
                        ) : (
                            <Select
                                key={name}
                                onValueChange={(value) => setFormData((prev) => ({ ...prev, [name]: value }))}
                                value={formData.role_slug}
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Select Role" />
                                </SelectTrigger>
                                <SelectContent>
                                    {roles.map((role) => (
                                        <SelectItem key={role.slug} value={role.slug}>
                                            {role.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        )
                    ))} */}
                </div>

                {/* <div>
                    {Object.keys(visibleErrors).length > 0 && (
                        <div className="text-red-500">
                            {Object.entries(visibleErrors).map(([key, error]) => (
                                error && <p key={key}>{error}</p>
                            ))}
                        </div>
                    )}
                </div> */}

                <DialogFooter>
                    <Button onClick={() => { onClose() }} variant="outline" className="cursor-pointer">Cancel</Button>
                    {/* <Button onClick={handleSubmit} className='cursor-pointer'>Save</Button> */}
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}
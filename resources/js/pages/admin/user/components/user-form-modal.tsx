import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { DialogDescription } from '@radix-ui/react-dialog';
import { useEffect, useMemo, useState, useCallback } from 'react';
import { UserFormModalProps } from '../types/user-props.types';
import { UserForm } from '../types/user.types';

const emptyFormData: UserForm = {
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role_id: '',
};

export default function UserFormModal({ isOpen, onClose, user, onSubmit, errors, roles }: UserFormModalProps) {
  const [formData, setFormData] = useState<UserForm>(emptyFormData);
  const [visibleErrors, setVisibleErrors] = useState<UserForm>(emptyFormData);

  // Reset visible errors after 3 seconds when errors change
  useEffect(() => {
    setVisibleErrors(errors);
    if (Object.keys(errors).length > 0) {
      const timer = setTimeout(() => setVisibleErrors(emptyFormData), 3000);
      return () => clearTimeout(timer);
    }
  }, [errors]);

  // Initialize or reset form data when user changes or modal opens
  useEffect(() => {
    if (user) {
      setFormData({
        name: user.name || '',
        email: user.email || '',
        password: '',
        password_confirmation: '',
        role_id: user.role_id || '',
      });
    } else {
      setFormData(emptyFormData);
    }
  }, [user]);

  const handleChange = useCallback((e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  }, []);

  const handleSelectChange = useCallback((name: keyof UserForm, value: string) => {
    setFormData((prev) => ({ ...prev, [name]: value }));
  }, []);

  const handleSubmit = () => {
    onSubmit(formData, user?.id);
    if (!user?.id) {
      setFormData(emptyFormData);
    }
  };

  // Define form fields dynamically
  const fields = useMemo(() => {
    const baseFields = [
      { name: 'name', type: 'text', placeholder: 'Name', required: true, inputType: 'input' },
      { name: 'email', type: 'email', placeholder: 'Email', required: true, inputType: 'input' },
      { name: 'role_id', type: 'number', placeholder: 'Select Role', required: true, inputType: 'dropdown' },
    ];

    if (!user) {
      baseFields.splice(2, 0, // insert password fields before role_id
        { name: 'password', type: 'password', placeholder: 'Password', required: true, inputType: 'input' },
        { name: 'password_confirmation', type: 'password', placeholder: 'Confirm Password', required: true, inputType: 'input' }
      );
    }

    return baseFields;
  }, [user]);

  return (
    <Dialog open={isOpen} onOpenChange={onClose}>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>{user ? 'Edit User' : 'Create User'}</DialogTitle>
          <DialogDescription>{user ? 'Update the user details below.' : 'Fill in the details to create a new user.'}</DialogDescription>
        </DialogHeader>

        <div className="space-y-4">
          {fields.map(({ name, type, placeholder, required, inputType }) => {
            const rawValue = formData[name as keyof UserForm];
            const normalizedValue = typeof rawValue === 'string' ? rawValue : rawValue != null ? String(rawValue) : '';

            if (inputType === 'input') {
              return (
                <Input
                  key={name}
                  name={name}
                  type={type}
                  value={normalizedValue}
                  onChange={handleChange}
                  placeholder={placeholder}
                  required={required}
                />
              );
            }

            return (
              <Select
                key={name}
                value={normalizedValue}
                onValueChange={(value) => handleSelectChange(name as keyof UserForm, value)}
              >
                <SelectTrigger>
                  <SelectValue placeholder={placeholder} />
                </SelectTrigger>
                <SelectContent>
                  {roles.map((role) => (
                    <SelectItem key={role.id} value={String(role.id)}>
                      {role.name}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            );
          })}
        </div>

        {Object.keys(visibleErrors).length > 0 && (
          <div className="space-y-1 text-red-500 mt-2">
            {Object.entries(visibleErrors).map(([key, error]) => {
              if (typeof error === 'string') {
                return <p key={key}>{error}</p>;
              }
              if (Array.isArray(error)) {
                return error.map((msg, idx) => (typeof msg === 'string' ? <p key={`${key}-${idx}`}>{msg}</p> : null));
              }
              return null;
            })}
          </div>
        )}

        <DialogFooter className="mt-4 flex justify-end space-x-2">
          <Button variant="outline" onClick={onClose} className="cursor-pointer">
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

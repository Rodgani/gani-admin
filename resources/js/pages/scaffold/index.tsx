import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/react';

import { useState } from 'react';

import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useToastMessage } from '@/hooks/use-toast-message';
import { Plus, Trash2 } from 'lucide-react';

interface Field {
    name: string;
    type: string;
    nullable: boolean;
    defaultValue: string;
    comment: string;
}
interface ScaffoldIndexProps {
    fieldTypes: string[];
}

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Scaffold', href: '/scaffold' }];

export default function ScaffoldIndex({ fieldTypes }: ScaffoldIndexProps) {
    const { showToast } = useToastMessage();
    
    const [fields, setFields] = useState<Field[]>([
        {
            name: '',
            type: fieldTypes?.[0],
            nullable: false,
            defaultValue: '',
            comment: '',
        },
    ]);

    const [config, setConfig] = useState({
        module: '',
        table: '',
        form_request: true,
    });

    const handleFieldChange = <K extends keyof Field>(index: number, key: K, value: Field[K]) => {
        const updated = [...fields];
        updated[index][key] = value;
        setFields(updated);
    };

    const addField = () => {
        setFields([
            ...fields,
            {
                name: '',
                type: fieldTypes?.[0],
                nullable: false,

                defaultValue: '',
                comment: '',
            },
        ]);
    };

    const removeField = (index: number) => {
        setFields(fields.filter((_, i) => i !== index));
    };

    const handleSubmit = () => {
        const payload = {
            ...config,
            fields: JSON.stringify(fields),
        };

        router.post(route('scaffold.generate'), payload, {
            onSuccess: () => {
                showToast('success', { message: 'Success! Your CRUD files are ready.!' });
            },
            onError: (errors) => {
                showToast('error', errors);
            },
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Scaffold Management" />
            <div className="space-y-6 p-6">
                <h1 className="text-2xl font-semibold">Scaffold</h1>

                <div className="grid grid-cols-1 gap-4">
                    <Input placeholder="Module" required value={config.module} onChange={(e) => setConfig({ ...config, module: e.target.value })} />
                    <Input placeholder="Table name should be in snake case only" required value={config.table} onChange={(e) => setConfig({ ...config, table: e.target.value })} />
                    <div className="flex items-center space-x-2">
                        <Checkbox
                            id="formRequest"
                            checked={config.form_request}
                            onCheckedChange={(val) => setConfig({ ...config, form_request: val as boolean })}
                        />
                        <Label
                            htmlFor="formRequest"
                            className="text-sm leading-none font-medium peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                        >
                            Create Form Request
                        </Label>
                    </div>
                </div>

                <h2 className="text-xl font-semibold">Fields</h2>
                <div className="space-y-2">
                    {fields.map((field, index) => (
                        <div key={index} className="grid grid-cols-6 gap-2">
                            <Input placeholder="Field name" value={field.name} onChange={(e) => handleFieldChange(index, 'name', e.target.value)} />
                            <Select value={field.type} onValueChange={(val) => handleFieldChange(index, 'type', val)}>
                                <SelectTrigger>
                                    <SelectValue placeholder="Select type" />
                                </SelectTrigger>
                                <SelectContent>
                                    {fieldTypes.map((t) => (
                                        <SelectItem key={t} value={t}>
                                            {t}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            <Input
                                placeholder="Default value"
                                value={field.defaultValue}
                                onChange={(e) => handleFieldChange(index, 'defaultValue', e.target.value)}
                            />
                            <Input
                                placeholder="Comment"
                                value={field.comment}
                                onChange={(e) => handleFieldChange(index, 'comment', e.target.value)}
                            />
                            <div className="flex items-center space-x-2">
                                <Checkbox
                                    id="nullable"
                                    checked={field.nullable}
                                    onCheckedChange={(val) => handleFieldChange(index, 'nullable', val as boolean)}
                                />
                                <Label
                                    htmlFor="nullable"
                                    className="text-sm leading-none font-medium peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                >
                                    Nullable
                                </Label>
                            </div>
                            <Button variant="destructive" onClick={() => removeField(index)}>
                                <Trash2 size={16} />
                            </Button>
                        </div>
                    ))}
                </div>

                <Button onClick={addField} variant="secondary" className="mt-2">
                    <Plus size={16} className="mr-1" /> Add field
                </Button>

                <div className="mt-6">
                    <Button className="w-full" onClick={handleSubmit}>
                        Generate Scaffold
                    </Button>
                </div>
            </div>
        </AppLayout>
    );
}

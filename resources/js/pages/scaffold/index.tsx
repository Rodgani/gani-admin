import AppLayout from "@/layouts/app-layout";
import { BreadcrumbItem } from "@/types";
import { Head } from "@inertiajs/react";

import { useState } from 'react';

import { Trash2, Plus } from 'lucide-react';
import { Input } from "@/components/ui/input";
import { Checkbox } from "@/components/ui/checkbox";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Button } from "@/components/ui/button";

interface Field {
    id: number;
    name: string;
    type: any;
    nullable: boolean;
    key: string;
    defaultValue: string;
    comment: string;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Scaffold', href: "/scaffold" },
];

const fieldTypes = [
    'bigIncrements',
    'bigInteger',
    'binary',
    'boolean',
    'char',
    'date',
    'dateTime',
    'dateTimeTz',
    'decimal',
    'double',
    'enum',
    'float',
    'geometry',
    'geometryCollection',
    'increments',
    'integer',
    'ipAddress',
    'json',
    'jsonb',
    'lineString',
    'longText',
    'macAddress',
    'mediumIncrements',
    'mediumInteger',
    'mediumText',
    'morphs',
    'uuidMorphs',
    'multiLineString',
    'multiPoint',
    'multiPolygon',
    'nullableMorphs',
    'nullableUuidMorphs',
    'nullableTimestamps',
    'point',
    'polygon',
    'rememberToken',
    'set',
    'smallIncrements',
    'smallInteger',
    'softDeletes',
    'softDeletesTz',
    'string',
    'text',
    'time',
    'timeTz',
    'timestamp',
    'timestampTz',
    'timestamps',
    'timestampsTz',
    'tinyIncrements',
    'tinyInteger',
    'unsignedBigInteger',
    'unsignedDecimal',
    'unsignedInteger',
    'unsignedMediumInteger',
    'unsignedSmallInteger',
    'unsignedTinyInteger',
    'year',
    'image'
];

export default function ScaffoldIndex() {

    const [fields, setFields] = useState<Field[]>([{
        id: 1,
        name: '',
        type: '',
        nullable: true,
        key: '',
        defaultValue: '',
        comment: '',
    }]);

    const [config, setConfig] = useState({
        moduleName: '',
        tableName: '',
        model: '',
        controller: '',
        createMigration: true,
        createModel: true,
        createController: true,
        runMigrate: true,
        createMenu: true,
        timestamps: true,
        softDeletes: false,
        primaryKey: 'id',
    });

    const handleFieldChange = <K extends keyof Field>(index: number, key: K, value: Field[K]) => {
        const updated = [...fields];
        updated[index][key] = value;
        setFields(updated);
    };

    const addField = () => {
        setFields([...fields, {
            id: Date.now(),
            name: '',
            type: '',
            nullable: true,
            key: '',
            defaultValue: '',
            comment: '',
        }]);
    };

    const removeField = (index: number) => {
        setFields(fields.filter((_, i) => i !== index));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Scaffold Management" />
            <div className="p-6 space-y-6">
                <h1 className="text-2xl font-semibold">Scaffold</h1>

                <div className="grid grid-cols-1 gap-4">
                    <Input placeholder="Module" value={config.moduleName} onChange={(e) => setConfig({ ...config, moduleName: e.target.value })} />
                    <Input placeholder="Table name" value={config.tableName} onChange={(e) => setConfig({ ...config, tableName: e.target.value })} />
                </div>

                <h2 className="text-xl font-semibold">Fields</h2>
                <div className="space-y-2">
                    {fields.map((field, index) => (
                        <div key={field.id} className="grid grid-cols-5 gap-2 items-center">
                            <Input
                                placeholder="Field name"
                                value={field.name}
                                onChange={(e) => handleFieldChange(index, 'name', e.target.value)}
                            />
                            <Select
                                value={field.type}
                                onValueChange={(val) => handleFieldChange(index, 'type', val)}
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Select type" />
                                </SelectTrigger>
                                <SelectContent>
                                    {fieldTypes.map((t) => (
                                        <SelectItem key={t} value={t}>{t}</SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            <Input placeholder="Default value" value={field.defaultValue} onChange={(e) => handleFieldChange(index, 'defaultValue', e.target.value)} />
                            <Input placeholder="Comment" value={field.comment} onChange={(e) => handleFieldChange(index, 'comment', e.target.value)} />
                            <Button variant="destructive" onClick={() => removeField(index)}><Trash2 size={16} /></Button>
                        </div>
                    ))}
                </div>

                <Button onClick={addField} variant="secondary" className="mt-2">
                    <Plus size={16} className="mr-1" /> Add field
                </Button>

                <div className="mt-6">
                    <Button className="w-full">Generate Scaffold</Button>
                </div>
            </div>
        </AppLayout >
    );

}
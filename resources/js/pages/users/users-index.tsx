
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import UserManagement from './users-management';
import { PaginatedUsers } from './user';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users Management',
        href: '/admin/users',
    },
];

interface UserIndexProps {
    users: PaginatedUsers;
}

export default function UserIndex({users}:UserIndexProps) {
    console.log('Users',users)
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">
                  
                </div>
            </div>
           
        </AppLayout>
    );
}

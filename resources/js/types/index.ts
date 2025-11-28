import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface UserSettings {
    theme: 'light' | 'dark' | 'system';
    accent_color: 'indigo' | 'emerald' | 'red' | 'orange' | 'cyan' | 'purple';
    notifications: {
        account_alerts: boolean;
        weekly_summary: boolean;
        goals_achieved: boolean;
    };
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    settings?: UserSettings;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Wallet {
    id: number;
    name: string;
    icon: string | null;
    user_id: number;
    active: boolean;
    slug: string;
    description: string | null;
    is_default: boolean | string;
    balance: number;
    color: string | null;
    type: 'generic' | 'saving' | 'investment';
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

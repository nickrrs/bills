<template>
    <DropdownMenu>
        <DropdownMenuTrigger>
            <div class="border border-[#2F2F2F] rounded-full">
                <img src="https://github.com/shadcn.png" alt="profile" class="!w-[32px] !h-[32px] rounded-full cursor-pointer">
            </div>
        </DropdownMenuTrigger>
        <DropdownMenuContent :align="'end'" class="w-56 bg-[#1E1E1E] border-[#2F2F2F] !rounded-md">
            <DropdownMenuLabel class="flex flex-col gap-1">
                <span class="text-white text-sm font-medium">Nickolas Ribeiro</span>
                <span class="text-[#B6B6B6] text-xs">nick@bills.com.br</span>
            </DropdownMenuLabel>
            <template v-for="(item, index) in menuItems" :key="index">
                <DropdownMenuSeparator v-if="item.separatorBefore" class="bg-[#2F2F2F]"/>
                <DropdownMenuItem class="cursor-pointer text-[#b8b8b8] p-0" :class="logoutItemClass(item.label)">
                    <InertiaLink
                        v-if="item.method"
                        :method="item.method as any"
                        :href="route(item.route)"
                        class="flex items-center gap-x-2 w-full h-full px-2 py-1.5"
                    >
                        <component :is="item.icon" class="!h-4 !w-4" />
                        {{ item.label }}
                    </InertiaLink>
                    <InertiaLink
                        v-else
                        :href="route(item.route)"
                        class="flex items-center gap-x-2 w-full h-full px-2 py-1.5"
                    >
                        <component :is="item.icon" class="!h-4 !w-4" />
                        {{ item.label }}
                    </InertiaLink>
                </DropdownMenuItem>
            </template>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
<script lang="ts">
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import {
    User,
    LogOut,
    Wallet,
    Settings,
    Layers,
    Layers2,
    TrendingUp,
    CreditCard,
} from 'lucide-vue-next'
import { Link as InertiaLink } from '@inertiajs/vue3';

export default {
    components: {
        DropdownMenu,
        DropdownMenuContent,
        DropdownMenuItem,
        DropdownMenuLabel,
        DropdownMenuSeparator,
        DropdownMenuTrigger,
        User,
        LogOut,
        Wallet,
        CreditCard,
        Layers,
        Layers2,
        TrendingUp,
        Settings,
        InertiaLink,
    },
    data() {
        return {
            menuItems: [
                {
                    route: 'dashboard',
                    icon: User,
                    label: 'edit profile',
                    separatorBefore: true,
                },
                {
                    route: 'dashboard',
                    icon: Settings,
                    label: 'settings',
                    separatorBefore: false,
                },
                {
                    route: 'wallets',
                    icon: Wallet,
                    label: 'my wallets',
                    separatorBefore: false,
                },
                {
                    route: 'dashboard',
                    icon: CreditCard,
                    label: 'my cards',
                    separatorBefore: false,
                },
                {
                    route: 'dashboard',
                    icon: Layers,
                    label: 'finance categories',
                    separatorBefore: false,
                },
                {
                    route: 'dashboard',
                    icon: Layers2,
                    label: 'finance subcategories',
                    separatorBefore: false,
                },
                {
                    route: 'dashboard',
                    icon: TrendingUp,
                    label: 'my tracks',
                    separatorBefore: false,
                },
                {
                    route: 'logout',
                    icon: LogOut,
                    label: 'logout',
                    method: 'post',
                    separatorBefore: true,
                },
            ],
        };
    },
    methods: {
        logoutItemClass(itemLabel: string) {
            return itemLabel === 'logout' ? 'hover:!bg-red-400' : 'hover:!bg-[#38353C]';
        }
    },
};
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger>
            <div class="border border-border rounded-full">
                <img src="https://github.com/shadcn.png" alt="profile" class="!w-[32px] !h-[32px] rounded-full cursor-pointer">
            </div>
        </DropdownMenuTrigger>
        <DropdownMenuContent :align="'end'" class="w-56 bg-popover border-border !rounded-md">
            <DropdownMenuLabel class="flex flex-col gap-1">
                <span class="text-foreground text-sm font-medium">Nickolas Ribeiro</span>
                <span class="text-muted-foreground text-xs">nick@bills.com.br</span>
            </DropdownMenuLabel>
            <template v-for="(item, index) in menuItems" :key="index">
                <DropdownMenuSeparator v-if="item.separatorBefore" class="bg-border"/>
                <DropdownMenuItem class="cursor-pointer text-muted-foreground hover:text-accent-foreground p-0" :class="logoutItemClass(item.label)">
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
                    label: 'editar perfil',
                    separatorBefore: true,
                },
                {
                    route: 'settings',
                    icon: Settings,
                    label: 'configurações',
                    separatorBefore: false,
                },
                {
                    route: 'wallets',
                    icon: Wallet,
                    label: 'carteiras',
                    separatorBefore: false,
                },
                {
                    route: 'dashboard',
                    icon: CreditCard,
                    label: 'cartões',
                    separatorBefore: false,
                },
                {
                    route: 'categories',
                    icon: Layers,
                    label: 'categorias financeiras',
                    separatorBefore: false,
                },
                {
                    route: 'subcategories',
                    icon: Layers2,
                    label: 'subcategorias financeiras',
                    separatorBefore: false,
                },
                {
                    route: 'dashboard',
                    icon: TrendingUp,
                    label: 'minhas movimentações',
                    separatorBefore: false,
                },
                {
                    route: 'logout',
                    icon: LogOut,
                    label: 'sair',
                    method: 'post',
                    separatorBefore: true,
                },
            ],
        };
    },
    methods: {
        logoutItemClass(itemLabel: string) {
            return itemLabel === 'sair' ? 'hover:!bg-destructive hover:!text-destructive-foreground' : 'hover:!bg-accent';
        }
    },
};
</script>

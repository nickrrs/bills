<template>
    <DropdownMenu>
        <DropdownMenuTrigger>
            <div class="border border-[#2F2F2F] rounded-full">
                <img src="https://github.com/shadcn.png" alt="profile" class="!w-[32px] !h-[32px] rounded-full cursor-pointer">
            </div>
        </DropdownMenuTrigger>
        <DropdownMenuContent :align="'end'" class="w-56 bg-[#1E1E1E] border-[#2F2F2F]">
            <DropdownMenuLabel class="flex flex-col gap-1">
                <span class="text-white text-sm font-medium">Nickolas Ribeiro</span>
                <span class="text-[#B6B6B6] text-xs">nick@bills.com.br</span>
            </DropdownMenuLabel>
            <DropdownMenuSeparator class="bg-[#2F2F2F]"/>
            <DropdownMenuItem class="cursor-pointer hover:!bg-[#38353C] text-[#b8b8b8]" >
                <InertiaLink :href="route('dashboard')" class="flex items-center gap-x-2">
                    <User class="!h-4 !w-4" />
                    edit profile
                </InertiaLink>
            </DropdownMenuItem>
            <DropdownMenuItem class="cursor-pointer hover:!bg-[#38353C] text-[#b8b8b8]" >
                <InertiaLink :href="route('dashboard')" class="flex items-center gap-x-2">
                    <Settings class="!h-4 !w-4" />
                    settings
                </InertiaLink>
            </DropdownMenuItem>
            <DropdownMenuSeparator class="bg-[#2F2F2F]"/>
            <DropdownMenuItem class="cursor-pointer hover:!bg-[#38353C] text-[#b8b8b8]" >
                <InertiaLink :href="route('dashboard')" class="flex items-center gap-x-2">
                    <Wallet class="!h-4 !w-4" />
                    my accounts
                </InertiaLink>
            </DropdownMenuItem>
            <DropdownMenuItem class="cursor-pointer hover:!bg-[#38353C] text-[#b8b8b8]" >
                <InertiaLink :href="route('dashboard')" class="flex items-center gap-x-2">
                    <CreditCard class="!h-4 !w-4" />
                    my cards
                </InertiaLink>
            </DropdownMenuItem>
            <DropdownMenuItem class="cursor-pointer hover:!bg-[#38353C] text-[#b8b8b8]" >
                <InertiaLink :href="route('dashboard')" class="flex items-center gap-x-2">
                    <Layers class="!h-4 !w-4" />
                    expense categories
                </InertiaLink>
            </DropdownMenuItem>
            <DropdownMenuItem class="cursor-pointer hover:!bg-[#38353C] text-[#b8b8b8]" >
                <InertiaLink :href="route('dashboard')" class="flex items-center gap-x-2">
                    <Layers2 class="!h-4 !w-4" />
                    expense subcategories
                </InertiaLink>
            </DropdownMenuItem>
            <DropdownMenuItem class="cursor-pointer hover:!bg-[#38353C] text-[#b8b8b8]" >
                <InertiaLink :href="route('dashboard')" class="flex items-center gap-x-2">
                    <TrendingUp class="!h-4 !w-4" />
                    my tracks
                </InertiaLink>
            </DropdownMenuItem>
            <DropdownMenuSeparator class="bg-[#2F2F2F]"/>
            <DropdownMenuItem class="cursor-pointer hover:!bg-[#38353C] text-[#b8b8b8]">
                <InertiaLink method="post" :href="route('logout')" class="flex items-center gap-x-2 ">
                    <LogOut class="!h-4 !w-4" />
                    logout
                </InertiaLink>
            </DropdownMenuItem>
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
};
</script>

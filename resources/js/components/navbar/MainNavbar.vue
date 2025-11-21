<template>
    <div class="sticky top-0 w-full flex items-center bg-[#131316] h-[72px] px-8 py-4" :class="{ 'border-b-2 border-[#212124]': noSubNav }">
        <NavigationMenu class="w-full flex flex-row items-center justify-between max-w-[100%]">
            <NavigationMenuList class="w-full flex items-center gap-4">
                <NavigationMenuItem class="flex items-center gap-6">
                    <InertiaLink :href="route('dashboard')" class="flex items-center gap-x-2">
                            <AppLogoIcon />
                    </InertiaLink>
                    <div class="relative shrink-0 bg-border h-full w-px ">
                        <div class="absolute left-1/2 top-1/2 flex -translate-x-1/2 -translate-y-1/2 items-center justify-center bg-[#444444] w-[1px] px-0 py-3"></div>
                    </div>
                </NavigationMenuItem>
                <NavigationMenuItem>
                    <WalletSwitch />
                </NavigationMenuItem>
                <NavigationMenuItem>
                    <div v-if="!emailIsVerified"
                        class="flex items-center justify-center text-center gap-2 h-[32px] px-[8px]  bg-[#1E1E1E] border border-[#2F2F2F] rounded-none"
                    >
                        <OctagonAlert class="!h-4 !w-4 text-[#FFD600] animate-pulse" />
                        <span class="text-sm text-[#FFD600] cursor-default animate-pulse">por favor, cheque seu e-mail para verificar sua conta</span>
                    </div>
                </NavigationMenuItem>
            </NavigationMenuList>

            <NavigationMenuList class="w-full flex self-end gap-4 text-[#B6B6B6] ">
                <NavigationMenuItem class="hover:text-white cursor-pointer">
                    <InertiaLink :href="route('wallets')" class="flex items-center gap-x-2">
                        carteiras
                    </InertiaLink>
                </NavigationMenuItem>
                <NavigationMenuItem class="hover:text-white cursor-pointer">
                    <!-- <InertiaLink :href="route('dashboard')" class="flex items-center gap-x-2">
                        finance categories
                    </InertiaLink> -->
                    <DropdownMenu v-model:open="isOpen">
                        <DropdownMenuTrigger>
                            <DropdownMenuLabel class="flex flex-row items-center gap-1">
                                <span>categorias financeiras</span>
                                <ChevronDown v-if="!isOpen" class="!h-4 !w-4 text-[#767676]" />
                                <ChevronUp v-else class="!h-4 !w-4 text-[#767676]" />
                            </DropdownMenuLabel>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="w-40 bg-[#1E1E1E] border-[#2F2F2F] !rounded-md">
                            <DropdownMenuItem class="!rounded-md cursor-pointer hover:!bg-[#38353C] text-[#b8b8b8]">
                                <InertiaLink :href="route('categories')" class="flex items-center gap-x-2">
                                    categorias
                                </InertiaLink>
                            </DropdownMenuItem>
                            <DropdownMenuItem class="!rounded-md cursor-pointer hover:!bg-[#38353C] text-[#b8b8b8]">
                                <InertiaLink :href="route('subcategories')" class="flex items-center gap-x-2">
                                    subcategorias
                                </InertiaLink>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </NavigationMenuItem>
                <NavigationMenuItem class="hover:text-white cursor-pointer">
                    <InertiaLink :href="route('dashboard')" class="flex items-center gap-x-2">
                        rastreios
                    </InertiaLink>
                </NavigationMenuItem>
                <div class="relative shrink-0 bg-border h-full w-px ">
                    <div class="absolute left-1/2 top-1/2 flex -translate-x-1/2 -translate-y-1/2 items-center justify-center bg-[#444444] w-[1px] px-0 py-3"></div>
                </div>
                <UiButton class="text-white !h-[32px] !border border-[#2F2F2F] bg-[#1E1E1E] hover:bg-[#313131] !rounded-md">
                    nova transação
                </UiButton>
                <div class="relative shrink-0 bg-border h-full w-px ">
                    <div class="absolute left-1/2 top-1/2 flex -translate-x-1/2 -translate-y-1/2 items-center justify-center bg-[#444444] w-[1px] px-0 py-3"></div>
                </div>
                <NavigationMenuItem>
                    <UserDropdown />
                </NavigationMenuItem>
            </NavigationMenuList>
        </NavigationMenu>
    </div>
</template>
<script lang="ts">
import {
  NavigationMenu,
  NavigationMenuItem,
  NavigationMenuList,
} from "@/components/ui/navigation-menu"
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
  DropdownMenuLabel,
} from "@/components/ui/dropdown-menu"
import AppLogoIcon from "../assets/AppLogoIcon.vue";
import { OctagonAlert, ChevronDown, ChevronUp } from "lucide-vue-next";
import { Button as UiButton } from "@/components/ui/button";
import { Link as InertiaLink } from '@inertiajs/vue3';
import WalletSwitch from "@/components/dropdowns/WalletSwitch.vue";
import UserDropdown from "@/components/dropdowns/UserDropdown.vue";
export default {
    name: 'MainNavbar',
    components: {
        NavigationMenu,
        NavigationMenuItem,
        NavigationMenuList,
        AppLogoIcon,
        InertiaLink,
        WalletSwitch,
        OctagonAlert,
        UiButton,
        UserDropdown,
        DropdownMenu,
        DropdownMenuContent,
        DropdownMenuItem,
        DropdownMenuTrigger,
        ChevronDown,
        ChevronUp,
        DropdownMenuLabel,
    },
    props: {
        noSubNav: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            isOpen: false,
        }
    },
    computed: {
        emailIsVerified() {
            return false;
        }
    }
};
</script>

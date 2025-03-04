<template>
    <div class="w-full flex items-center bg-[#131316] h-[72px] px-8 py-4">
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
                    <AccountSwitch />
                </NavigationMenuItem>
                <NavigationMenuItem>
                    <div v-if="!emailIsVerified"
                        class="flex items-center justify-center text-center gap-2 h-[32px] px-[8px]  bg-[#1E1E1E] border border-[#2F2F2F] rounded-md"
                    >
                        <OctagonAlert class="!h-4 !w-4 text-[#FFD600]" />
                        <span class="text-sm text-[#FFD600] cursor-default">please check your e-mail to verify your account</span>
                    </div>
                </NavigationMenuItem>
            </NavigationMenuList>

            <NavigationMenuList class="w-full flex self-end gap-4">
                <NavigationMenuItem>
                        accounts
                </NavigationMenuItem>
                <NavigationMenuItem>
                        expense categories
                </NavigationMenuItem>
                <NavigationMenuItem>
                        mensal plans
                </NavigationMenuItem>
                <NavigationMenuItem>
                        tracks
                </NavigationMenuItem>
                <div class="relative shrink-0 bg-border h-full w-px ">
                    <div class="absolute left-1/2 top-1/2 flex -translate-x-1/2 -translate-y-1/2 items-center justify-center bg-[#444444] w-[1px] px-0 py-3"></div>
                </div>
                <UiButton class="text-white !h-[32px] border border-[#7B90FF] bg-gradient-to-br from-[#818CF8] via-[#6161E0] to-[#4338CA] hover:bg-gradient-to-br hover:from-[#4338CA] hover:via-[#4338CA] hover:to-[#4338CA]">
                    add record
                </UiButton>
                <div class="relative shrink-0 bg-border h-full w-px ">
                    <div class="absolute left-1/2 top-1/2 flex -translate-x-1/2 -translate-y-1/2 items-center justify-center bg-[#444444] w-[1px] px-0 py-3"></div>
                </div>
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
import AppLogoIcon from "../assets/AppLogoIcon.vue";
import { OctagonAlert } from "lucide-vue-next";
import { Button as UiButton } from "@/components/ui/button";
import { Link as InertiaLink } from '@inertiajs/vue3';
import AccountSwitch from "../dropdowns/AccountSwitch.vue";
export default {
    name: 'MainNavbar',
    components: {
        NavigationMenu,
        NavigationMenuItem,
        NavigationMenuList,
        AppLogoIcon,
        InertiaLink,
        AccountSwitch,
        OctagonAlert,
        UiButton
    },
    computed: {
        emailIsVerified() {
            return false;
        }
    }
};
</script>

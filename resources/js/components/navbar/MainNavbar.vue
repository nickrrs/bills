<template>
    <div class="sticky top-0 w-full flex items-center bg-card border-border h-[72px] px-4 sm:px-8 py-4 z-50" :class="{ 'border-b-2': noSubNav }">
        <NavigationMenu class="w-full flex flex-row items-center justify-between max-w-[100%]">
            <!-- lado esquerdo: Logo e WalletSwitch -->
            <NavigationMenuList class="flex items-center gap-2 sm:gap-4">
                <NavigationMenuItem class="flex items-center gap-3 sm:gap-6">
                    <InertiaLink :href="route('dashboard')" class="flex items-center gap-x-2">
                        <AppLogoIcon />
                    </InertiaLink>
                    <div class="hidden sm:block relative shrink-0 bg-border h-full w-px">
                        <div class="absolute left-1/2 top-1/2 flex -translate-x-1/2 -translate-y-1/2 items-center justify-center bg-border w-[1px] px-0 py-3"></div>
                    </div>
                </NavigationMenuItem>
                <NavigationMenuItem>
                    <WalletSwitch />
                </NavigationMenuItem>
                <NavigationMenuItem class="hidden xl:block">
                    <div v-if="!emailIsVerified"
                        class="flex items-center justify-center text-center gap-2 h-[32px] px-[8px] mr-2 bg-card border-2 border-border rounded-md shadow-sm"
                    >
                        <OctagonAlert class="!h-4 !w-4 light:text-yellow-600 dark:text-yellow-500 animate-pulse flex-shrink-0" />
                        <span class="text-sm light:text-yellow-600 dark:text-yellow-500 cursor-default animate-pulse whitespace-nowrap">por favor, cheque seu e-mail para verificar sua conta</span>
                    </div>
                </NavigationMenuItem>
                <NavigationMenuItem class="hidden lg:block xl:hidden">
                    <Tooltip v-if="!emailIsVerified" :open="emailAlertTooltipOpen" @update:open="emailAlertTooltipOpen = $event">
                        <TooltipTrigger as-child>
                            <div
                                @click.stop="emailAlertTooltipOpen = !emailAlertTooltipOpen"
                                @mouseenter="emailAlertTooltipOpen = false"
                                class="flex items-center justify-center text-center gap-1 h-[32px] px-[6px] bg-card border-2 border-border rounded-md shadow-sm cursor-pointer"
                            >
                                <OctagonAlert class="!h-4 !w-4 light:text-yellow-600 dark:text-yellow-500 animate-pulse flex-shrink-0" />
                            </div>
                        </TooltipTrigger>
                        <TooltipContent
                            side="bottom"
                            align="center"
                            class="max-w-xs"
                        >
                            <p class="text-sm light:text-yellow-600 dark:text-yellow-500">
                                por favor, cheque seu e-mail para verificar sua conta
                            </p>
                        </TooltipContent>
                    </Tooltip>
                </NavigationMenuItem>
            </NavigationMenuList>

            <!-- lado direito: Navegação desktop -->
            <NavigationMenuList class="hidden lg:flex items-center gap-1.5 xl:gap-4 text-muted-foreground">
                <NavigationMenuItem class="hover:text-foreground cursor-pointer transition-colors">
                    <InertiaLink :href="route('wallets')" class="flex items-center gap-x-2 whitespace-nowrap text-sm xl:text-base px-1.5 xl:px-0">
                        carteiras
                    </InertiaLink>
                </NavigationMenuItem>
                <NavigationMenuItem class="hover:text-foreground cursor-pointer transition-colors">
                    <DropdownMenu v-model:open="isOpen">
                        <DropdownMenuTrigger>
                            <DropdownMenuLabel class="flex flex-row items-center gap-1 whitespace-nowrap text-sm xl:text-base px-1.5 xl:px-0">
                                <span class="hidden xl:inline">categorias financeiras</span>
                                <span class="xl:hidden">categorias</span>
                                <ChevronDown v-if="!isOpen" class="!h-3.5 !w-3.5 xl:!h-4 xl:!w-4 text-muted-foreground flex-shrink-0" />
                                <ChevronUp v-else class="!h-3.5 !w-3.5 xl:!h-4 xl:!w-4 text-muted-foreground flex-shrink-0" />
                            </DropdownMenuLabel>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="w-40 bg-popover border-border !rounded-md">
                            <DropdownMenuItem class="!rounded-md cursor-pointer hover:!bg-accent text-muted-foreground hover:text-accent-foreground">
                                <InertiaLink :href="route('categories')" class="flex items-center gap-x-2">
                                    categorias
                                </InertiaLink>
                            </DropdownMenuItem>
                            <DropdownMenuItem class="!rounded-md cursor-pointer hover:!bg-accent text-muted-foreground hover:text-accent-foreground">
                                <InertiaLink :href="route('subcategories')" class="flex items-center gap-x-2">
                                    subcategorias
                                </InertiaLink>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </NavigationMenuItem>
                <NavigationMenuItem class="hover:text-foreground cursor-pointer transition-colors">
                    <InertiaLink :href="route('dashboard')" class="flex items-center gap-x-2 whitespace-nowrap text-sm xl:text-base px-1.5 xl:px-0">
                        rastreios
                    </InertiaLink>
                </NavigationMenuItem>
                <div class="relative shrink-0 bg-border h-full w-px">
                    <div class="absolute left-1/2 top-1/2 flex -translate-x-1/2 -translate-y-1/2 items-center justify-center bg-border w-[1px] px-0 py-3"></div>
                </div>
                <UiButton class="text-foreground !h-[32px] !border border-border bg-card hover:bg-accent hover:text-accent-foreground !rounded-md">
                    nova transação
                </UiButton>
                <div class="hidden xl:block relative shrink-0 bg-border h-full w-px">
                    <div class="absolute left-1/2 top-1/2 flex -translate-x-1/2 -translate-y-1/2 items-center justify-center bg-border w-[1px] px-0 py-3"></div>
                </div>
                <NavigationMenuItem>
                    <UserDropdown />
                </NavigationMenuItem>
            </NavigationMenuList>

            <!-- menu mobile: Botão hambúrguer e UserDropdown -->
            <div class="flex lg:hidden items-center gap-3 list-none">
                <NavigationMenuItem>
                    <UserDropdown />
                </NavigationMenuItem>
                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="p-2 rounded-md hover:bg-accent transition-colors flex items-center justify-center min-w-[40px] min-h-[40px]"
                    aria-label="Toggle menu"
                >
                    <template v-if="!mobileMenuOpen">
                        <MenuIcon class="h-6 w-6 text-foreground" />
                    </template>
                    <template v-else>
                        <X class="h-6 w-6 text-foreground" />
                    </template>
                </button>
            </div>
        </NavigationMenu>

        <!-- menu mobile: Overlay e conteúdo -->
        <Transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="mobileMenuOpen"
                class="fixed inset-0 bg-black/50 z-40 lg:hidden"
                @click="mobileMenuOpen = false"
            ></div>
        </Transition>

        <Transition
            enter-active-class="transition-transform duration-300 ease-out"
            enter-from-class="translate-x-full"
            enter-to-class="translate-x-0"
            leave-active-class="transition-transform duration-200 ease-in"
            leave-from-class="translate-x-0"
            leave-to-class="translate-x-full"
        >
            <div
                v-if="mobileMenuOpen"
                class="fixed top-[0px] right-0 bottom-[10px] w-80 max-w-[85vw] bg-card border-l border-border z-50 overflow-y-auto lg:hidden"
            >
                <div class="flex flex-col p-4 gap-4">
                    <!-- alerta de verificação de email -->
                    <div v-if="!emailIsVerified" class="flex items-center gap-2 p-3 bg-card border-2 border-border rounded-md shadow-sm">
                        <OctagonAlert class="!h-4 !w-4 light:text-yellow-600 dark:text-yellow-500 animate-pulse flex-shrink-0" />
                        <span class="text-sm light:text-yellow-600 dark:text-yellow-500 cursor-default animate-pulse">por favor, cheque seu e-mail para verificar sua conta</span>
                    </div>

                    <!-- links de navegação -->
                    <div class="flex flex-col gap-2">
                        <InertiaLink
                            :href="route('wallets')"
                            @click="mobileMenuOpen = false"
                            class="flex items-center gap-x-2 px-3 py-2 rounded-md hover:bg-accent text-muted-foreground hover:text-foreground transition-colors"
                        >
                            carteiras
                        </InertiaLink>

                        <div class="px-3 py-2">
                            <button
                                @click="mobileCategoriesOpen = !mobileCategoriesOpen"
                                class="flex items-center justify-between w-full gap-x-2 text-muted-foreground hover:text-foreground transition-colors"
                            >
                                <span>categorias financeiras</span>
                                <ChevronDown
                                    :class="['!h-4 !w-4 transition-transform', mobileCategoriesOpen ? 'rotate-180' : '']"
                                />
                            </button>
                            <Transition
                                enter-active-class="transition-all duration-200"
                                enter-from-class="opacity-0 max-h-0"
                                enter-to-class="opacity-100 max-h-96"
                                leave-active-class="transition-all duration-200"
                                leave-from-class="opacity-100 max-h-96"
                                leave-to-class="opacity-0 max-h-0"
                            >
                                <div v-if="mobileCategoriesOpen" class="mt-2 ml-4 flex flex-col gap-2">
                                    <InertiaLink
                                        :href="route('categories')"
                                        @click="mobileMenuOpen = false"
                                        class="px-3 py-2 rounded-md hover:bg-accent text-muted-foreground hover:text-foreground transition-colors"
                                    >
                                        categorias
                                    </InertiaLink>
                                    <InertiaLink
                                        :href="route('subcategories')"
                                        @click="mobileMenuOpen = false"
                                        class="px-3 py-2 rounded-md hover:bg-accent text-muted-foreground hover:text-foreground transition-colors"
                                    >
                                        subcategorias
                                    </InertiaLink>
                                </div>
                            </Transition>
                        </div>

                        <InertiaLink
                            :href="route('dashboard')"
                            @click="mobileMenuOpen = false"
                            class="flex items-center gap-x-2 px-3 py-2 rounded-md hover:bg-accent text-muted-foreground hover:text-foreground transition-colors"
                        >
                            rastreios
                        </InertiaLink>
                    </div>

                    <!-- botão nova transação -->
                    <div class="pt-4 border-t border-border">
                        <UiButton
                            @click="mobileMenuOpen = false"
                            class="w-full text-foreground !h-[32px] !border border-border bg-card hover:bg-accent hover:text-accent-foreground !rounded-md"
                        >
                            nova transação
                        </UiButton>
                    </div>
                </div>
            </div>
        </Transition>
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
import {
  Tooltip,
  TooltipContent,
  TooltipTrigger,
} from "@/components/ui/tooltip"
import AppLogoIcon from "../assets/AppLogoIcon.vue";
import { OctagonAlert, ChevronDown, ChevronUp, Menu as MenuIcon, X } from "lucide-vue-next";
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
        Tooltip,
        TooltipContent,
        TooltipTrigger,
        MenuIcon,
        X,
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
            mobileMenuOpen: false,
            mobileCategoriesOpen: false,
            emailAlertTooltipOpen: false,
        }
    },
    computed: {
        emailIsVerified() {
            return false;
        }
    }
};
</script>

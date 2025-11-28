<template>
    <!-- Sidebar Desktop -->
    <aside class="hidden lg:flex fixed left-0 top-0 h-screen w-64 bg-card border-r border-border flex-col z-50">
        <div class="flex flex-col h-full overflow-y-auto relative">
            <!-- Header: Logo -->
            <div class="flex items-center justify-between px-4 py-4 border-b border-border">
                <div class="flex items-center space-x-2 min-w-0 flex-1">
                    <InertiaLink :href="route('dashboard')" class="flex items-center shrink-0">
                        <AppLogoIcon />
                    </InertiaLink>
                    <h1 class="text-2xl font-bold dark:text-white light:text-black whitespace-nowrap">bills</h1>
                </div>
            </div>

            <!-- WalletSwitch -->
            <div class="px-4 py-4 border-b border-border">
                <WalletSwitch />
            </div>

            <!-- Alerta de verificação de email -->
            <div v-if="!emailIsVerified" class="px-4 py-3 border-b border-border">
                <div class="flex items-start gap-2 p-3 bg-card border-2 border-border rounded-md shadow-sm">
                    <OctagonAlert class="!h-4 !w-4 light:text-yellow-600 dark:text-yellow-500 animate-pulse flex-shrink-0 mt-0.5" />
                    <span class="text-xs light:text-yellow-600 dark:text-yellow-500 cursor-default animate-pulse leading-tight">por favor, cheque seu e-mail para verificar sua conta</span>
                </div>
            </div>

            <!-- Navegação -->
            <nav class="flex-1 px-4 py-4 flex flex-col gap-2">
                <InertiaLink
                    :href="route('wallets')"
                    :class="['flex items-center gap-x-2 px-3 py-2 rounded-md transition-colors', isActiveRoute('wallets') ? 'bg-accent text-foreground' : 'hover:bg-accent text-muted-foreground hover:text-foreground']"
                >
                    <Wallet class="h-4 w-4 shrink-0" />
                    <span class="whitespace-nowrap">carteiras</span>
                </InertiaLink>

                <div>
                    <button
                        @click="toggleCategories"
                        :class="['flex items-center justify-between w-full gap-x-2 px-3 py-2 rounded-md transition-colors', 'hover:bg-accent text-muted-foreground hover:text-foreground']"
                    >
                        <div class="flex items-center gap-x-2 min-w-0">
                            <Layers class="h-4 w-4 shrink-0" />
                            <span class="whitespace-nowrap">categorias financeiras</span>
                        </div>
                        <ChevronDown
                            :class="['!h-4 !w-4 transition-transform shrink-0', categoriesOpen ? 'rotate-180' : '']"
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
                        <div v-if="categoriesOpen" class="mt-1 ml-4 flex flex-col gap-1">
                            <InertiaLink
                                :href="route('categories')"
                                :class="['flex items-center gap-x-2 px-3 py-2 rounded-md transition-colors text-sm', isActiveRoute('categories') ? 'bg-accent text-foreground' : 'hover:bg-accent text-muted-foreground hover:text-foreground']"
                            >
                                <Layers class="h-4 w-4 shrink-0" />
                                <span>categorias</span>
                            </InertiaLink>
                            <InertiaLink
                                :href="route('subcategories')"
                                :class="['flex items-center gap-x-2 px-3 py-2 rounded-md transition-colors text-sm', isActiveRoute('subcategories') ? 'bg-accent text-foreground' : 'hover:bg-accent text-muted-foreground hover:text-foreground']"
                            >
                                <Layers2 class="h-4 w-4 shrink-0" />
                                <span>subcategorias</span>
                            </InertiaLink>
                        </div>
                    </Transition>
                </div>

                <InertiaLink
                    :href="route('dashboard')"
                    :class="['flex items-center gap-x-2 px-3 py-2 rounded-md transition-colors', isActiveRoute('dashboard') ? 'bg-accent text-foreground' : 'hover:bg-accent text-muted-foreground hover:text-foreground']"
                >
                    <TrendingUp class="h-4 w-4 shrink-0" />
                    <span class="whitespace-nowrap">rastreios</span>
                </InertiaLink>
            </nav>

            <!-- Botão nova transação -->
            <div class="px-4 py-4 border-t border-border">
                <UiButton class="w-full text-foreground !h-[32px] !border border-border bg-card hover:bg-accent hover:text-accent-foreground !rounded-md justify-center items-center">
                    <span class="whitespace-nowrap">nova transação</span>
                </UiButton>
            </div>

            <!-- UserDropdown -->
            <div class="px-4 py-4 border-t border-border flex flex-row items-center gap-2">
                <UserDropdown />
                <div class="flex flex-col justify-center items-start min-w-0">
                    <span class="text-xs font-bold text-black dark:text-white truncate">{{ user.name }}</span>
                    <span class="text-xs text-muted-foreground truncate">{{ user.email }}</span>
                </div>
            </div>
        </div>
    </aside>

    <!-- Mobile: Navbar fixo -->
    <div class="lg:hidden fixed top-0 left-0 right-0 h-[72px] bg-card border-b border-border px-4 flex items-center justify-between z-50 overflow-visible">
        <div class="flex items-center gap-3 flex-1 min-w-0 overflow-visible">
            <InertiaLink :href="route('dashboard')" class="flex items-center justify-center shrink-0 min-w-[30px] w-[30px] h-[30px] overflow-visible">
                <BillsLogo :width="30" :height="30" gradient-id="mobile-logo-grad" class="block w-[30px] h-[30px] flex-shrink-0 overflow-visible" />
            </InertiaLink>
            <div class="hidden sm:block relative shrink-0 bg-border h-6 w-px"></div>
            <div class="hidden sm:block shrink-0">
                <WalletSwitch />
            </div>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <UserDropdown />
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
    </div>

    <!-- Mobile: Overlay -->
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

    <!-- Mobile: Sidebar drawer -->
    <Transition
        enter-active-class="transition-transform duration-300 ease-out"
        enter-from-class="-translate-x-full"
        enter-to-class="translate-x-0"
        leave-active-class="transition-transform duration-200 ease-in"
        leave-from-class="translate-x-0"
        leave-to-class="-translate-x-full"
    >
        <aside
            v-if="mobileMenuOpen"
            class="fixed top-[72px] left-0 bottom-0 w-80 max-w-[85vw] bg-card border-r border-border z-50 overflow-y-auto lg:hidden"
        >
            <div class="flex flex-col p-4 gap-4 h-full">
                <!-- WalletSwitch (apenas em telas muito pequenas onde não cabe no navbar) -->
                <div class="sm:hidden">
                    <WalletSwitch />
                </div>

                <!-- alerta de verificação de email -->
                <div v-if="!emailIsVerified" class="flex items-center gap-2 p-3 bg-card border-2 border-border rounded-md shadow-sm">
                    <OctagonAlert class="!h-4 !w-4 light:text-yellow-600 dark:text-yellow-500 animate-pulse flex-shrink-0" />
                    <span class="text-sm light:text-yellow-600 dark:text-yellow-500 cursor-default animate-pulse">por favor, cheque seu e-mail para verificar sua conta</span>
                </div>

                <!-- links de navegação -->
                <nav class="flex flex-col gap-2 flex-1">
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
                </nav>

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
        </aside>
    </Transition>
</template>
<script lang="ts">
import AppLogoIcon from "../assets/AppLogoIcon.vue";
import BillsLogo from "../assets/BillsLogo.vue";
import { OctagonAlert, ChevronDown, Menu as MenuIcon, X, Wallet, Layers, Layers2, TrendingUp } from "lucide-vue-next";
import { Button as UiButton } from "@/components/ui/button";
import { Link as InertiaLink } from '@inertiajs/vue3';
import WalletSwitch from "@/components/dropdowns/WalletSwitch.vue";
import UserDropdown from "@/components/dropdowns/UserDropdown.vue";
import { usePage } from "@inertiajs/vue3";
import type { SharedData } from "@/types";
export default {
    name: 'MainNavbar',
    components: {
        AppLogoIcon,
        BillsLogo,
        InertiaLink,
        WalletSwitch,
        OctagonAlert,
        UiButton,
        UserDropdown,
        ChevronDown,
        MenuIcon,
        X,
        Wallet,
        Layers,
        Layers2,
        TrendingUp,
    },
    props: {
        noSubNav: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        const loadCategoriesState = () => {
            if (typeof window !== 'undefined') {
                const currentPath = window.location.pathname.replace(/\/$/, '');
                const isCategories = currentPath === '/categories' || currentPath === '/subcategories';
                if (isCategories) {
                    return true;
                }
                const saved = localStorage.getItem('categoriesOpen');
                return saved === 'true';
            }
            return false;
        };

        return {
            categoriesOpen: loadCategoriesState(),
            mobileMenuOpen: false,
            mobileCategoriesOpen: false,
        }
    },
    mounted() {
        // Garante que o dropdown está aberto se estiver em categorias/subcategorias
        if (this.isCategoriesRoute) {
            this.categoriesOpen = true;
            this.saveCategoriesState();
        }
    },
    computed: {
        emailIsVerified() {
            return false;
        },
        user() {
            return usePage<SharedData>().props.auth.user;
        },
        currentUrl() {
            return usePage<SharedData>().url.slice(1);
        },
        isCategoriesRoute() {
            // Usa window.location.pathname para uma comparação mais confiável
            const currentPath = typeof window !== 'undefined'
                ? window.location.pathname.replace(/\/$/, '')
                : this.currentUrl.split('?')[0].replace(/\/$/, '');
            return currentPath === '/categories' || currentPath === '/subcategories';
        }
    },
    methods: {
        saveCategoriesState() {
            if (typeof window !== 'undefined') {
                localStorage.setItem('categoriesOpen', String(this.categoriesOpen));
            }
        },
        toggleCategories() {
            this.categoriesOpen = !this.categoriesOpen;
            this.saveCategoriesState();
        },
        isActiveRoute(routeName: string) {
            try {
                return this.currentUrl === routeName;
            } catch {
                // Se houver erro ao gerar a rota, retorna false
                return false;
            }
        }
    },
    watch: {
        isCategoriesRoute(newVal: boolean) {
            // Abre o dropdown automaticamente quando navegar para categorias/subcategorias
            if (newVal) {
                this.categoriesOpen = true;
                this.saveCategoriesState();
            }
        }
    }
};
</script>

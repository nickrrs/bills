<template>
    <!-- Sidebar Desktop -->
    <aside class="fixed left-0 top-0 z-50 hidden h-screen w-64 flex-col border-r border-border bg-card lg:flex">
        <div class="relative flex h-full flex-col overflow-y-auto">
            <!-- Header: Logo -->
            <div class="flex items-center justify-between border-b border-border px-4 py-4">
                <div class="flex min-w-0 flex-1 items-center space-x-2">
                    <InertiaLink :href="route('dashboard')" class="flex shrink-0 items-center">
                        <AppLogoIcon />
                    </InertiaLink>
                    <h1 class="whitespace-nowrap text-2xl font-bold light:text-black dark:text-white">bills</h1>
                </div>
            </div>

            <!-- WalletSwitch -->
            <div class="border-b border-border px-4 py-4">
                <WalletSwitch />
            </div>

            <!-- Alerta de verificação de email -->
            <div v-if="!emailIsVerified" class="border-b border-border px-4 py-3">
                <div class="flex items-start gap-2 rounded-md border-2 border-border bg-card p-3 shadow-sm">
                    <OctagonAlert class="mt-0.5 !h-4 !w-4 flex-shrink-0 animate-pulse light:text-yellow-600 dark:text-yellow-500" />
                    <span class="animate-pulse cursor-default text-xs leading-tight light:text-yellow-600 dark:text-yellow-500"
                        >por favor, cheque seu e-mail para verificar sua conta</span
                    >
                </div>
            </div>

            <!-- Navegação -->
            <nav class="flex flex-1 flex-col gap-2 px-4 py-4">
                <InertiaLink
                    :href="route('wallets')"
                    :class="[
                        'flex items-center gap-x-2 rounded-md px-3 py-2 transition-colors',
                        isActiveRoute('wallets') ? 'bg-accent text-foreground' : 'text-muted-foreground hover:bg-accent hover:text-foreground',
                    ]"
                >
                    <Wallet class="h-4 w-4 shrink-0" />
                    <span class="whitespace-nowrap">carteiras</span>
                </InertiaLink>

                <div>
                    <button
                        @click="toggleCategories"
                        :class="[
                            'flex w-full items-center justify-between gap-x-2 rounded-md px-3 py-2 transition-colors',
                            'text-muted-foreground hover:bg-accent hover:text-foreground',
                        ]"
                    >
                        <div class="flex min-w-0 items-center gap-x-2">
                            <Layers class="h-4 w-4 shrink-0" />
                            <span class="whitespace-nowrap">categorias financeiras</span>
                        </div>
                        <ChevronDown :class="['!h-4 !w-4 shrink-0 transition-transform', categoriesOpen ? 'rotate-180' : '']" />
                    </button>
                    <Transition
                        enter-active-class="transition-all duration-200"
                        enter-from-class="opacity-0 max-h-0"
                        enter-to-class="opacity-100 max-h-96"
                        leave-active-class="transition-all duration-200"
                        leave-from-class="opacity-100 max-h-96"
                        leave-to-class="opacity-0 max-h-0"
                    >
                        <div v-if="categoriesOpen" class="ml-4 mt-1 flex flex-col gap-1">
                            <InertiaLink
                                :href="route('categories')"
                                :class="[
                                    'flex items-center gap-x-2 rounded-md px-3 py-2 text-sm transition-colors',
                                    isActiveRoute('categories')
                                        ? 'bg-accent text-foreground'
                                        : 'text-muted-foreground hover:bg-accent hover:text-foreground',
                                ]"
                            >
                                <Layers class="h-4 w-4 shrink-0" />
                                <span>categorias</span>
                            </InertiaLink>
                            <InertiaLink
                                :href="route('subcategories')"
                                :class="[
                                    'flex items-center gap-x-2 rounded-md px-3 py-2 text-sm transition-colors',
                                    isActiveRoute('subcategories')
                                        ? 'bg-accent text-foreground'
                                        : 'text-muted-foreground hover:bg-accent hover:text-foreground',
                                ]"
                            >
                                <Layers2 class="h-4 w-4 shrink-0" />
                                <span>subcategorias</span>
                            </InertiaLink>
                        </div>
                    </Transition>
                </div>

                <InertiaLink
                    :href="route('dashboard')"
                    :class="[
                        'flex items-center gap-x-2 rounded-md px-3 py-2 transition-colors',
                        isActiveRoute('dashboard') ? 'bg-accent text-foreground' : 'text-muted-foreground hover:bg-accent hover:text-foreground',
                    ]"
                >
                    <TrendingUp class="h-4 w-4 shrink-0" />
                    <span class="whitespace-nowrap">rastreios</span>
                </InertiaLink>
            </nav>

            <!-- Botão nova transação -->
            <div class="border-t border-border px-4 py-4">
                <UiButton
                    class="!h-[32px] w-full items-center justify-center !rounded-md !border border-border bg-card text-foreground hover:bg-accent hover:text-accent-foreground"
                >
                    <span class="whitespace-nowrap">nova transação</span>
                </UiButton>
            </div>

            <!-- UserDropdown -->
            <div class="flex flex-row items-center gap-2 border-t border-border px-4 py-4">
                <UserDropdown />
                <div class="flex min-w-0 flex-col items-start justify-center">
                    <span class="truncate text-xs font-bold text-black dark:text-white">{{ user.name }}</span>
                    <span class="truncate text-xs text-muted-foreground">{{ user.email }}</span>
                </div>
            </div>
        </div>
    </aside>

    <!-- Mobile: Navbar fixo -->
    <div
        class="fixed left-0 right-0 top-0 z-50 flex h-[72px] items-center justify-between overflow-visible border-b border-border bg-card px-4 lg:hidden"
    >
        <div class="flex min-w-0 flex-1 items-center gap-3 overflow-visible">
            <InertiaLink :href="route('dashboard')" class="flex h-[30px] w-[30px] min-w-[30px] shrink-0 items-center justify-center overflow-visible">
                <BillsLogo :width="30" :height="30" gradient-id="mobile-logo-grad" class="block h-[30px] w-[30px] flex-shrink-0 overflow-visible" />
            </InertiaLink>
            <div class="relative hidden h-6 w-px shrink-0 bg-border sm:block"></div>
            <div class="hidden shrink-0 sm:block">
                <WalletSwitch />
            </div>
        </div>
        <div class="flex shrink-0 items-center gap-3">
            <UserDropdown />
            <button
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="flex min-h-[40px] min-w-[40px] items-center justify-center rounded-md p-2 transition-colors hover:bg-accent"
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
        <div v-if="mobileMenuOpen" class="fixed inset-0 z-40 bg-black/50 lg:hidden" @click="mobileMenuOpen = false"></div>
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
            class="fixed bottom-0 left-0 top-[72px] z-50 w-80 max-w-[85vw] overflow-y-auto border-r border-border bg-card lg:hidden"
        >
            <div class="flex h-full flex-col gap-4 p-4">
                <!-- WalletSwitch (apenas em telas muito pequenas onde não cabe no navbar) -->
                <div class="sm:hidden">
                    <WalletSwitch />
                </div>

                <!-- alerta de verificação de email -->
                <div v-if="!emailIsVerified" class="flex items-center gap-2 rounded-md border-2 border-border bg-card p-3 shadow-sm">
                    <OctagonAlert class="!h-4 !w-4 flex-shrink-0 animate-pulse light:text-yellow-600 dark:text-yellow-500" />
                    <span class="animate-pulse cursor-default text-sm light:text-yellow-600 dark:text-yellow-500"
                        >por favor, cheque seu e-mail para verificar sua conta</span
                    >
                </div>

                <!-- links de navegação -->
                <nav class="flex flex-1 flex-col gap-2">
                    <InertiaLink
                        :href="route('wallets')"
                        @click="mobileMenuOpen = false"
                        class="flex items-center gap-x-2 rounded-md px-3 py-2 text-muted-foreground transition-colors hover:bg-accent hover:text-foreground"
                    >
                        carteiras
                    </InertiaLink>

                    <div class="px-3 py-2">
                        <button
                            @click="mobileCategoriesOpen = !mobileCategoriesOpen"
                            class="flex w-full items-center justify-between gap-x-2 text-muted-foreground transition-colors hover:text-foreground"
                        >
                            <span>categorias financeiras</span>
                            <ChevronDown :class="['!h-4 !w-4 transition-transform', mobileCategoriesOpen ? 'rotate-180' : '']" />
                        </button>
                        <Transition
                            enter-active-class="transition-all duration-200"
                            enter-from-class="opacity-0 max-h-0"
                            enter-to-class="opacity-100 max-h-96"
                            leave-active-class="transition-all duration-200"
                            leave-from-class="opacity-100 max-h-96"
                            leave-to-class="opacity-0 max-h-0"
                        >
                            <div v-if="mobileCategoriesOpen" class="ml-4 mt-2 flex flex-col gap-2">
                                <InertiaLink
                                    :href="route('categories')"
                                    @click="mobileMenuOpen = false"
                                    class="rounded-md px-3 py-2 text-muted-foreground transition-colors hover:bg-accent hover:text-foreground"
                                >
                                    categorias
                                </InertiaLink>
                                <InertiaLink
                                    :href="route('subcategories')"
                                    @click="mobileMenuOpen = false"
                                    class="rounded-md px-3 py-2 text-muted-foreground transition-colors hover:bg-accent hover:text-foreground"
                                >
                                    subcategorias
                                </InertiaLink>
                            </div>
                        </Transition>
                    </div>

                    <InertiaLink
                        :href="route('dashboard')"
                        @click="mobileMenuOpen = false"
                        class="flex items-center gap-x-2 rounded-md px-3 py-2 text-muted-foreground transition-colors hover:bg-accent hover:text-foreground"
                    >
                        rastreios
                    </InertiaLink>
                </nav>

                <!-- botão nova transação -->
                <div class="border-t border-border pt-4">
                    <UiButton
                        @click="mobileMenuOpen = false"
                        class="!h-[32px] w-full !rounded-md !border border-border bg-card text-foreground hover:bg-accent hover:text-accent-foreground"
                    >
                        nova transação
                    </UiButton>
                </div>
            </div>
        </aside>
    </Transition>
</template>
<script lang="ts">
import UserDropdown from '@/components/dropdowns/UserDropdown.vue';
import WalletSwitch from '@/components/dropdowns/WalletSwitch.vue';
import { Button as UiButton } from '@/components/ui/button';
import type { SharedData } from '@/types';
import { Link as InertiaLink, usePage } from '@inertiajs/vue3';
import { ChevronDown, Layers, Layers2, Menu as MenuIcon, OctagonAlert, TrendingUp, Wallet, X } from 'lucide-vue-next';
import AppLogoIcon from '../assets/AppLogoIcon.vue';
import BillsLogo from '../assets/BillsLogo.vue';
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
        };
    },
    mounted() {
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
            const currentPath =
                typeof window !== 'undefined' ? window.location.pathname.replace(/\/$/, '') : this.currentUrl.split('?')[0].replace(/\/$/, '');
            return currentPath === '/categories' || currentPath === '/subcategories';
        },
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
                return false;
            }
        },
    },
    watch: {
        isCategoriesRoute(newVal: boolean) {
            if (newVal) {
                this.categoriesOpen = true;
                this.saveCategoriesState();
            }
        },
    },
};
</script>

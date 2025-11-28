<template>
    <InertiaHead title="dashboard" />
    <MainLayout>
        <div
            class="relative flex h-[54px] w-full items-end gap-x-2 overflow-x-auto border-border bg-card px-4 sm:gap-x-3 sm:px-6 md:gap-x-4 md:px-8"
        >
            <!-- Barra animada -->
            <div
                class="absolute bottom-0 h-0.5 bg-accent-primary transition-all duration-300 ease-in-out"
                :style="activeBarStyle"
            ></div>

            <div
                ref="overview"
                class="nav-item flex h-full items-center justify-center whitespace-nowrap px-3 sm:px-4"
                :class="selectedNavItemClass('overview')"
            >
                <span class="cursor-pointer select-none text-sm sm:text-base" @click="selectNavItem('overview')">overview</span>
            </div>
            <div
                ref="reports"
                class="nav-item flex h-full items-center justify-center whitespace-nowrap px-3 sm:px-4"
                :class="selectedNavItemClass('reports')"
            >
                <span class="cursor-pointer select-none text-sm sm:text-base" @click="selectNavItem('reports')">relatórios</span>
            </div>
            <div
                ref="releases"
                class="nav-item flex h-full items-center justify-center whitespace-nowrap px-3 sm:px-4"
                :class="selectedNavItemClass('releases')"
            >
                <span class="cursor-pointer select-none text-sm sm:text-base" @click="selectNavItem('releases')">lançamentos</span>
            </div>
            <div
                ref="cards"
                class="nav-item flex h-full items-center justify-center whitespace-nowrap px-3 sm:px-4"
                :class="selectedNavItemClass('cards')"
            >
                <span class="cursor-pointer select-none text-sm sm:text-base" @click="selectNavItem('cards')">cartões</span>
            </div>
            <div
                ref="smart-boxes"
                class="nav-item flex h-full items-center justify-center whitespace-nowrap px-3 sm:px-4"
                :class="selectedNavItemClass('smart-boxes')"
            >
                <span class="cursor-pointer select-none text-sm sm:text-base" @click="selectNavItem('smart-boxes')">caixinhas inteligentes</span>
            </div>
        </div>
        <div class="w-full h-[calc(100vh-54px)] px-2 pb-2 light:bg-white dark:bg-[#131316]">
            <div class="w-full h-full light:bg-[#fafafa] dark:bg-[#0d0d0d] border-2 shadow-md border-border rounded-md">
                tste
            </div>
        </div>
    </MainLayout>
</template>
<script lang="ts">
import MainLayout from '@/layouts/MainLayout.vue';
import { Head as InertiaHead } from '@inertiajs/vue3';

export default {
    name: 'Dashboard',
    components: {
        InertiaHead,
        MainLayout,
    },
    data() {
        return {
            selectedAccount: null,
            selectedNavItem: 'overview',
            activeBarStyle: {
                left: '0px',
                width: '0px',
            },
        };
    },
    mounted() {
        this.$nextTick(() => {
            this.updateActiveBarPosition();
        });
    },
    methods: {
        selectedNavItemClass(label: string) {
            return this.selectedNavItem === label
                ? 'text-accent-primary'
                : 'text-muted-foreground hover:text-foreground';
        },
        selectNavItem(item: string) {
            this.selectedNavItem = item;
            this.$nextTick(() => {
                this.updateActiveBarPosition();
            });
        },
        updateActiveBarPosition() {
            const activeElement = this.$refs[this.selectedNavItem] as HTMLElement;
            if (activeElement && activeElement.parentElement) {
                const container = activeElement.parentElement;
                const containerRect = container.getBoundingClientRect();
                const elementRect = activeElement.getBoundingClientRect();

                this.activeBarStyle = {
                    left: `${elementRect.left - containerRect.left}px`,
                    width: `${elementRect.width}px`,
                };
            }
        },
    },
};
</script>

<style scoped>
.nav-item {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.nav-item:hover {
    transform: translateY(-1px);
}

.nav-item span {
    transition: color 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>

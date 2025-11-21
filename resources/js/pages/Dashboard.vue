<template>
    <InertiaHead title="dashboard" />
    <MainLayout>
        <div
            class="flex h-[54px] w-full items-end gap-x-2 overflow-x-auto border-b-2 border-[#212124] bg-[#131316] px-4 sm:gap-x-3 sm:px-6 md:gap-x-4 md:px-8"
        >
            <div
                class="flex h-full items-center justify-center whitespace-nowrap border-b-2 border-transparent px-3 sm:px-4"
                :class="selectedNavItemClass('overview')"
            >
                <span class="cursor-pointer select-none text-sm sm:text-base" @click="selectedNavItem = 'overview'">overview</span>
            </div>
            <div
                class="flex h-full items-center justify-center whitespace-nowrap border-b-2 border-transparent px-3 sm:px-4"
                :class="selectedNavItemClass('reports')"
            >
                <span class="cursor-pointer select-none text-sm sm:text-base" @click="selectedNavItem = 'reports'">relatórios</span>
            </div>
            <div
                class="flex h-full items-center justify-center whitespace-nowrap border-b-2 border-transparent px-3 sm:px-4"
                :class="selectedNavItemClass('releases')"
            >
                <span class="cursor-pointer select-none text-sm sm:text-base" @click="selectedNavItem = 'releases'">lançamentos</span>
            </div>
            <div
                class="flex h-full items-center justify-center whitespace-nowrap border-b-2 border-transparent px-3 sm:px-4"
                :class="selectedNavItemClass('cards')"
            >
                <span class="cursor-pointer select-none text-sm sm:text-base" @click="selectedNavItem = 'cards'">cartões</span>
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
        };
    },
    methods: {
        selectedNavItemClass(label: string) {
            return this.selectedNavItem === label ? 'border-b-[#3800D8] hover:border-b-opacity-100' : 'hover:border-b-[#FFFFFF33]/20 border-b-none';
        },
    },
};
</script>

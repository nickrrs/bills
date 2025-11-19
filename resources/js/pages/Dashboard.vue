<template>
    <InertiaHead title="dashboard" />
    <MainLayout>
        <div class="w-full h-[54px] bg-[#131316] flex items-end px-4 sm:px-6 md:px-8 gap-x-2 sm:gap-x-3 md:gap-x-4 border-b-2 border-[#212124] overflow-x-auto">
            <div class="h-full flex items-center justify-center px-3 sm:px-4 border-b-2 border-transparent whitespace-nowrap" :class="selectedNavItemClass('overview')">
                <span class="cursor-pointer select-none text-sm sm:text-base" @click="selectedNavItem = 'overview'">overview</span>
            </div>
            <div class="h-full flex items-center justify-center px-3 sm:px-4 border-b-2 border-transparent whitespace-nowrap" :class="selectedNavItemClass('reports')">
                <span class="cursor-pointer select-none text-sm sm:text-base" @click="selectedNavItem = 'reports'">relatorios</span>
            </div>
            <div class="h-full flex items-center justify-center px-3 sm:px-4 border-b-2 border-transparent whitespace-nowrap" :class="selectedNavItemClass('releases')">
                <span class="cursor-pointer select-none text-sm sm:text-base" @click="selectedNavItem = 'releases'">lançamentos</span>
            </div>
            <div class="h-full flex items-center justify-center px-3 sm:px-4 border-b-2 border-transparent whitespace-nowrap" :class="selectedNavItemClass('cards')">
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
        }
    },
};
</script>


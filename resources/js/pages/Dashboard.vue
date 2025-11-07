<template>
    <InertiaHead title="dashboard" />
    <MainLayout>
        <div class="w-full h-[54px] bg-[#131316] flex items-end px-8 gap-x-4 border-b-2 border-[#212124]"> <!-- bg-[#131316] -->
            <div class="w-[64px] h-full flex items-center justify-center gap-x-4 border-b-2 border-transparent" :class="{ 'border-b-[#3800D8]': selectedNavItem === 'overview' }">
                <span class="cursor-pointer select-none" @click="selectedNavItem = 'overview'">overview</span>
            </div>
            <div class="w-[64px] h-full flex items-center justify-center gap-x-4 border-b-2 border-transparent" :class="{ 'border-b-[#3800D8]': selectedNavItem === 'reports' }">
                <span class="cursor-pointer select-none" @click="selectedNavItem = 'reports'">reports</span>
            </div>
            <div class="w-[64px] h-full flex items-center justify-center gap-x-4 border-b-2 border-transparent" :class="{ 'border-b-[#3800D8]': selectedNavItem === 'releases' }">
                <span class="cursor-pointer select-none" @click="selectedNavItem = 'releases'">releases</span>
            </div>
            <div class="w-[64px] h-full flex items-center justify-center gap-x-4 border-b-2 border-transparent" :class="{ 'border-b-[#3800D8]': selectedNavItem === 'cards' }">
                <span class="cursor-pointer select-none" @click="selectedNavItem = 'cards'">cards</span>
            </div>
        </div>
        teste
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
};
</script>


<template>
    <MainLayout :no-sub-nav="true">
        <div class="w-full h-full px-8 py-8">
            <div class="w-full flex items-center justify-between">
                <h1 class="text-white text-3xl font-bold">Accounts</h1>
                <span class="text-sm text-[#B6B6B6]">This table refresh every 30 seconds</span>
            </div>
        </div>
    </MainLayout>
</template>
<script lang="ts">
import MainLayout from '@/layouts/MainLayout.vue';

export default {
    components: {
        MainLayout,
    },
};
</script>

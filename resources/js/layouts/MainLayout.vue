<template>
    <TooltipProvider>
        <div class="w-screen relative h-screen flex bg-background">
            <Toaster />
            <MainNavbar :no-sub-nav="noSubNav" />
            <!-- Conteúdo principal com margem para sidebar -->
            <main class="flex-1 mt-[72px] lg:mt-0 lg:ml-64 h-screen overflow-auto">
                <slot />
            </main>
        </div>
    </TooltipProvider>
</template>
<script lang="ts">
import MainNavbar from '@/components/navbar/MainNavbar.vue';
import { Toaster } from '@/components/ui/toast';
import { TooltipProvider } from '@/components/ui/tooltip';

export default {
    components: {
        MainNavbar,
        Toaster,
        TooltipProvider,
    },
    props: {
        noSubNav: {
            type: Boolean,
            default: false,
        },
    },
};
</script>

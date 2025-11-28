<template>
    <TooltipProvider>
        <div class="w-screen relative h-screen flex flex-col bg-background">
            <Toaster />
            <MainNavbar :no-sub-nav="noSubNav" />
            <div class="w-full h-full overflow-auto">
                <slot />
            </div>
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

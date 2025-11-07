<template>
    <div class="w-screen relative h-screen flex flex-col bg-[#0D0D0D]">
        <MainNavbar :no-sub-nav="noSubNav" />
        <div class="w-full h-full overflow-auto">
            <slot />
        </div>
    </div>
</template>
<script lang="ts">
import MainNavbar from '@/components/navbar/MainNavbar.vue';

export default {
    components: {
        MainNavbar,
    },
    props: {
        noSubNav: {
            type: Boolean,
            default: false,
        },
    },
};
</script>

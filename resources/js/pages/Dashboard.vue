<script lang="ts">
import MainNavbar from '@/components/navbar/MainNavbar.vue';
import { Head as InertiaHead } from '@inertiajs/vue3';

export default {
    name: 'Dashboard',
    components: {
        InertiaHead,
        MainNavbar,
    },
};
</script>

<template>
    <InertiaHead title="dashboard" />
    <MainNavbar />
</template>

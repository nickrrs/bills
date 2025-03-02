<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-svh flex-col items-center justify-center gap-6 bg-gradient-to-br from-indigo-950 via-black to-black p-6 md:p-10">
        <div class="w-full max-w-sm">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4">
                    <Link :href="route('home')" class="flex flex-col items-center gap-2 font-medium">
                        <div class="mb-1 flex items-center justify-center rounded-md">
                            <img src="/images/bills-logo.svg" alt="bills-logo">
                        </div>
                    </Link>
                    <div class="inline-flex text-center gap-1">
                        <h1 class="text-xl font-medium">the efficient way to track your</h1>
                        <h1 class="text-xl font-medium text-[#4338CA]">money</h1>
                    </div>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>

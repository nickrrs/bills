<template>
    <div class="w-full h-[260px] light:bg-[#f5f5f5e7] light:border-white/10 dark:bg-[#131316] dark:border-[#2F2F2F] rounded-3xl p-6 flex flex-col justify-between border border-white/10 shadow-[0_10px_30px_-10px_rgba(0,0,0,0.5)]">
        <!-- Top section: Icon, name, description, and button -->
        <div class="flex items-start justify-between z-10">
            <div class="flex items-center space-x-3">
                <Skeleton class="h-10 w-10 rounded-lg dark:bg-white/10 light:bg-[#cececee7]" />
                <div class="flex flex-col gap-2">
                    <Skeleton class="h-5 w-32 dark:bg-white/10 light:bg-[#cececee7]" />
                    <Skeleton class="h-3 w-24 dark:bg-white/10 light:bg-[#cececee7]" />
                </div>
            </div>
            <Skeleton class="h-8 w-8 rounded-full dark:bg-white/15 light:bg-[#cececee7]" />
        </div>

        <!-- Middle section: Balance -->
        <div class="z-10 flex flex-1 flex-col justify-center">
            <Skeleton class="h-3 w-20 mb-3 dark:bg-white/10 light:bg-[#cececee7]" />
            <div class="flex items-baseline gap-1">
                <Skeleton class="h-8 w-8 dark:bg-white/10 light:bg-[#cececee7]" />
                <Skeleton class="h-8 w-32 dark:bg-white/10 light:bg-[#cececee7]" />
            </div>
        </div>

        <!-- Sparkline skeleton -->
        <Skeleton class="h-6 w-full dark:bg-white/10 light:bg-[#cececee7] rounded" />

        <!-- Footer section -->
        <div class="z-10 mt-2 flex items-center justify-between border-t border-white/10 pt-4">
            <Skeleton class="h-4 w-24 dark:bg-white/10 light:bg-[#cececee7]" />
            <Skeleton class="h-4 w-16 dark:bg-white/10 light:bg-[#cececee7]" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { Skeleton } from '@/components/ui/skeleton';
</script>


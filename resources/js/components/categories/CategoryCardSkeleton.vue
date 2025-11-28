<template>
    <div class="light:bg-[#f5f5f5e7] light:border-white/10 dark:bg-[#131316] dark:border-[#2F2F2F] border rounded-xl p-6 flex flex-col gap-4">
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-3">
                <Skeleton class="h-12 w-12 rounded-xl dark:bg-white/10 light:bg-[#cececee7]" />
                <div class="flex flex-col gap-2">
                    <Skeleton class="h-4 w-32 dark:bg-white/10 light:bg-[#cececee7]" />
                    <Skeleton class="h-3 w-20 dark:bg-white/10 light:bg-[#cececee7]" />
                </div>
            </div>
            <Skeleton class="h-6 w-16 dark:bg-white/10 light:bg-[#cececee7]" />
        </div>

        <div class="flex flex-col gap-3">
            <Skeleton class="h-3 w-40 dark:bg-white/10 light:bg-[#cececee7]" />
            <Skeleton class="h-2 w-full dark:bg-white/10 light:bg-[#cececee7] rounded-full" />
        </div>

        <div class="flex flex-col gap-2">
            <Skeleton class="h-3 w-32 dark:bg-white/10 light:bg-[#cececee7]" />
            <Skeleton class="h-2 w-full dark:bg-white/10 light:bg-[#cececee7] rounded-full" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { Skeleton } from '@/components/ui/skeleton';
</script>


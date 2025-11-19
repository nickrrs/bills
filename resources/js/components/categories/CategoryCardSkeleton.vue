<template>
    <div class="bg-[#131316] border border-[#2F2F2F] rounded-xl p-6 flex flex-col gap-4">
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-3">
                <Skeleton class="h-12 w-12 rounded-xl bg-white/5" />
                <div class="flex flex-col gap-2">
                    <Skeleton class="h-4 w-32 bg-white/10" />
                    <Skeleton class="h-3 w-20 bg-white/10" />
                </div>
            </div>
            <Skeleton class="h-6 w-16 bg-white/10" />
        </div>

        <div class="flex flex-col gap-3">
            <Skeleton class="h-3 w-40 bg-white/10" />
            <Skeleton class="h-2 w-full bg-white/10 rounded-full" />
        </div>

        <div class="flex flex-col gap-2">
            <Skeleton class="h-3 w-32 bg-white/10" />
            <Skeleton class="h-2 w-full bg-white/10 rounded-full" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { Skeleton } from '@/components/ui/skeleton';
</script>


<template>
    <div class="grid grid-cols-2 gap-3">
        <div
            v-for="n in 6"
            :key="`category-skeleton-${n}`"
            class="flex items-center p-3 rounded-xl light:bg-[#f5f5f5e7] light:border-white/10 dark:bg-[#161616] dark:border-white/10 border"
        >
            <Skeleton class="h-10 w-10 rounded-lg dark:bg-white/10 light:bg-[#cececee7] mr-3 flex-shrink-0" />
            <Skeleton class="h-4 w-24 dark:bg-white/10 light:bg-[#cececee7]" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { Skeleton } from '@/components/ui/skeleton';
</script>


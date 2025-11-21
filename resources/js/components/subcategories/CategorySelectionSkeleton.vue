<template>
    <div class="grid grid-cols-2 gap-3">
        <div
            v-for="n in 6"
            :key="`category-skeleton-${n}`"
            class="flex items-center p-3 rounded-xl bg-[#161616] border border-white/10"
        >
            <Skeleton class="h-10 w-10 rounded-lg bg-white/10 mr-3 flex-shrink-0" />
            <Skeleton class="h-4 w-24 bg-white/10" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { Skeleton } from '@/components/ui/skeleton';
</script>


<template>
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
        <div class="flex flex-col gap-1">
            <div class="flex flex-wrap items-center gap-2">
                <h1 class="text-3xl font-bold text-white">{{ title }}</h1>
                <slot name="badge" />
            </div>
            <p v-if="description" class="text-sm text-[#B6B6B6] leading-relaxed">
                {{ description }}
            </p>
        </div>
        <div v-if="$slots.actions" class="flex flex-col gap-3 md:flex-row md:items-center md:justify-end md:text-right">
            <slot name="actions" />
        </div>
    </div>
</template>

<script setup lang="ts">
interface Props {
    title: string;
    description?: string;
}

defineProps<Props>();
</script>


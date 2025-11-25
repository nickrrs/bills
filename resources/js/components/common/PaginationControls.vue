<template>
    <div v-if="shouldRender" class="mt-4 pb-4 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div class="text-sm dark:text-gray-400 light:text-[#080808]">
            <span>mostrando</span>
            <span class="mx-1 font-medium text-accent-primary">{{ displayFrom }}</span>
            <span>até</span>
            <span class="mx-1 font-medium text-accent-primary">{{ displayTo }}</span>
            <span>de</span>
            <span class="mx-1 font-medium text-accent-primary">{{ total ?? 0 }}</span>
            <span>{{ label }}</span>
        </div>

        <div class="flex items-center gap-2">
            <button
                type="button"
                class="flex h-9 w-9 items-center justify-center rounded-md border dark:border-[#2F2F2F] dark:bg-[#1E1E1E] light:bg-card light:text-foreground text-white transition-colors hover:bg-[#313131] disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:bg-[#1E1E1E]"
                :disabled="current === 1"
                @click="emitChange(current - 1)"
            >
                <ChevronLeft class="h-4 w-4" />
            </button>

            <div class="flex items-center gap-1">
                <button
                    v-for="page in pages"
                    :key="page"
                    type="button"
                    class="flex h-9 min-w-9 items-center justify-center rounded-md border px-3 text-sm font-medium transition-colors"
                    :class="page === current ? 'border-accent-primary text-accent-primary' : 'light:border-border light:bg-card light:text-foreground light:hover:bg-accent dark:border-[#2F2F2F] dark:bg-[#1E1E1E] dark:text-white dark:hover:bg-[#313131]'"
                    :style="page === current ? { backgroundColor: accentPrimaryWithOpacity } : {}"
                    @click="emitChange(page)"
                >
                    {{ page }}
                </button>
            </div>

            <button
                type="button"
                class="flex h-9 w-9 items-center justify-center rounded-md border dark:border-[#2F2F2F] dark:bg-[#1E1E1E] light:bg-card light:text-foreground text-white transition-colors hover:bg-[#313131] disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:bg-[#1E1E1E]"
                :disabled="current === last"
                @click="emitChange(current + 1)"
            >
                <ChevronRight class="h-4 w-4" />
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { getAccentPrimaryWithOpacity } from '@/utils/colors';

// Computed para obter a cor accent-primary atual com opacidade (20%)
const accentPrimaryWithOpacity = computed(() => {
    return getAccentPrimaryWithOpacity(0.2);
});


interface Props {
    current: number;
    last: number;
    from?: number | null;
    to?: number | null;
    total?: number | null;
    entityLabel?: string;
    maxButtons?: number;
}

const props = withDefaults(defineProps<Props>(), {
    from: null,
    to: null,
    total: null,
    entityLabel: 'itens',
    maxButtons: 5,
});

const emit = defineEmits<{
    (e: 'change', page: number): void;
}>();

const shouldRender = computed(() => props.last > 1);
const label = computed(() => props.entityLabel);
const displayFrom = computed(() => props.from ?? 0);
const displayTo = computed(() => props.to ?? 0);
const total = computed(() => props.total ?? 0);

const pages = computed(() => {
    const totalPages = props.last;
    const current = Math.min(Math.max(props.current, 1), totalPages);
    const maxButtons = Math.max(props.maxButtons, 1);

    let start = Math.max(1, current - Math.floor(maxButtons / 2));
    const end = Math.min(totalPages, start + maxButtons - 1);

    if (end - start + 1 < maxButtons) {
        start = Math.max(1, end - maxButtons + 1);
    }

    return Array.from({ length: end - start + 1 }, (_, index) => start + index);
});

const emitChange = (page: number) => {
    if (page < 1 || page > props.last || page === props.current) {
        return;
    }
    emit('change', page);
};
</script>


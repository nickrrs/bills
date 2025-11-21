<template>
    <div v-if="shouldRender" class="mt-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div class="text-sm text-gray-400">
            <span>mostrando</span>
            <span class="mx-1 font-medium text-white">{{ displayFrom }}</span>
            <span>até</span>
            <span class="mx-1 font-medium text-white">{{ displayTo }}</span>
            <span>de</span>
            <span class="mx-1 font-medium text-white">{{ total ?? 0 }}</span>
            <span>{{ label }}</span>
        </div>

        <div class="flex items-center gap-2">
            <button
                type="button"
                class="flex h-9 w-9 items-center justify-center rounded-md border border-[#2F2F2F] bg-[#1E1E1E] text-white transition-colors hover:bg-[#313131] disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:bg-[#1E1E1E]"
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
                    :class="page === current ? 'border-indigo-500 bg-indigo-500/20 text-indigo-400' : 'border-[#2F2F2F] bg-[#1E1E1E] text-white hover:bg-[#313131]'"
                    @click="emitChange(page)"
                >
                    {{ page }}
                </button>
            </div>

            <button
                type="button"
                class="flex h-9 w-9 items-center justify-center rounded-md border border-[#2F2F2F] bg-[#1E1E1E] text-white transition-colors hover:bg-[#313131] disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:bg-[#1E1E1E]"
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


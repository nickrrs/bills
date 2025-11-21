<template>
    <Transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 translate-y-4"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition-all duration-200 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-4"
    >
        <div
            v-if="visible && count > 0"
            class="justify-between fixed bottom-6 left-1/2 z-50 flex w-full max-w-3xl -translate-x-1/2 transform items-center gap-4 rounded-lg border border-[#2F2F2F] bg-[#1E1E1E] px-6 py-4 shadow-2xl"
        >
            <div class="flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#6965f2]">
                    <span class="text-sm font-semibold text-white">{{ count }}</span>
                </div>
                <span class="text-sm font-medium text-white">
                    {{ selectionLabel }}
                </span>
            </div>

            <div v-if="showSelectionControls" class="h-6 w-px bg-[#2F2F2F]"></div>

            <div v-if="showSelectionControls" class="flex flex-wrap items-center gap-6">
                <button
                    :disabled="disableSelectAll"
                    class="text-sm text-[#B6B6B6] transition-colors hover:text-white disabled:cursor-not-allowed disabled:opacity-50"
                    type="button"
                    @click="$emit('select-all')"
                >
                    {{ selectAllLabel }}
                </button>
                <button
                    :disabled="disableClear"
                    class="text-sm text-[#B6B6B6] transition-colors hover:text-white disabled:cursor-not-allowed disabled:opacity-50"
                    type="button"
                    @click="$emit('clear')"
                >
                    {{ clearLabel }}
                </button>
            </div>

            <div v-if="$slots.actions" class="h-6 w-px bg-[#2F2F2F]"></div>

            <slot name="actions" />
        </div>
    </Transition>
</template>

<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    count: number;
    visible: boolean;
    singularLabel: string;
    pluralLabel: string;
    selectAllLabel?: string;
    clearLabel?: string;
    disableSelectAll?: boolean;
    disableClear?: boolean;
    showSelectionControls?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    selectAllLabel: 'selecionar todas',
    clearLabel: 'limpar seleção',
    disableSelectAll: false,
    disableClear: false,
    showSelectionControls: true,
});

defineEmits<{
    (event: 'select-all'): void;
    (event: 'clear'): void;
}>();

const selectionLabel = computed(() => (props.count === 1 ? props.singularLabel : props.pluralLabel));
</script>


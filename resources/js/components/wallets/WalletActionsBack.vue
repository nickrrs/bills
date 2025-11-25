<template>
    <div class="h-full w-full dark:bg-[#1a1a1a] light:bg-[#fafafa] border border-border rounded-3xl px-6 py-6 flex flex-col items-center justify-center text-center text-foreground">
        <h4 class="text-muted-foreground tracking-widest text-xs font-semibold uppercase mb-2">{{ title }}</h4>

        <div v-if="actions.length" class="w-full grid gap-3 flex-1 items-center justify-center" :class="columns === 2 ? 'grid-cols-2' : 'grid-cols-3'">
            <button
                v-for="action in actions"
                :key="action.id ?? action.label"
                class="flex flex-col items-center justify-center gap-1.5 rounded-2xl border border-border bg-muted px-2 py-2 text-sm text-foreground transition-colors hover:bg-accent hover:text-accent-foreground"
                :class="action.disabled ? 'opacity-50 cursor-not-allowed' : ''"
                type="button"
                :disabled="action.disabled"
                @click.stop="emit('action', action)"
                @click="action.action?.()"
            >
                <component :is="action.icon" class="w-5 h-5" :class="action.iconColor" />
                <span>{{ action.label }}</span>
            </button>
        </div>

        <div class="w-full">
            <slot />
        </div>

        <button class="inline-flex items-center gap-1.5 mt-2 text-[10px] uppercase tracking-widest text-muted-foreground transition-colors hover:text-foreground" type="button" @click.stop="emit('close')">
            <RotateCcw class="w-3 h-3" /> {{ closeLabel }}
        </button>
    </div>
</template>

<script lang="ts" setup>
import type { Component } from 'vue';
import { RotateCcw } from 'lucide-vue-next';

export interface WalletBackAction {
    id?: string | number;
    label: string;
    disabled?: boolean;
    icon: Component;
    iconColor?: string;
    action?: () => void;
}

interface Props {
    title: string;
    actions: WalletBackAction[];
    columns?: 2 | 3;
    closeLabel?: string;
}

withDefaults(defineProps<Props>(), {
    columns: 2,
    closeLabel: 'Voltar',
});

const emit = defineEmits<{
    action: [WalletBackAction];
    close: [];
}>();
</script>



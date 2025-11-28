<template>
    <Transition
        :enter-active-class="'transition-all duration-300 ease-out'"
        :enter-from-class="'opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-2'"
        :enter-to-class="'opacity-100 translate-y-0 sm:translate-x-0'"
        :leave-active-class="'transition-all duration-200 ease-in'"
        :leave-from-class="'opacity-100 translate-y-0 sm:translate-x-0'"
        :leave-to-class="'opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-2'"
    >
        <div
            v-if="open"
            :class="[
                'group pointer-events-auto relative flex w-full items-center justify-between space-x-4 overflow-hidden rounded-md border p-6 pr-8 shadow-lg transition-all',
                variant === 'destructive'
                    ? 'border border-destructive bg-destructive text-destructive-foreground'
                    : 'border border-border bg-card text-foreground',
            ]"
        >
            <div class="grid gap-1">
                <div
                    v-if="title"
                    :class="[
                        'text-sm font-semibold',
                        variant === 'destructive' ? 'text-destructive-foreground' : 'text-foreground',
                    ]"
                >
                    {{ title }}
                </div>
                <div
                    v-if="description"
                    :class="[
                        'text-sm opacity-90',
                        variant === 'destructive' ? 'text-destructive-foreground' : 'text-muted-foreground',
                    ]"
                >
                    {{ description }}
                </div>
            </div>
            <button
                @click="open = false"
                :class="[
                    'absolute right-2 top-2 rounded-md p-1 opacity-0 transition-opacity focus:opacity-100 focus:outline-none focus:ring-2 focus:ring-offset-2 group-hover:opacity-100',
                    variant === 'destructive'
                        ? 'text-destructive-foreground hover:text-destructive-foreground focus:ring-destructive focus:ring-offset-card'
                        : 'text-muted-foreground hover:text-foreground focus:ring-accent-primary focus:ring-offset-card',
                ]"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="15"
                    height="15"
                    viewBox="0 0 15 15"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.2"
                >
                    <path d="M11.7816 4.03157C12.0062 3.80702 12.0062 3.44295 11.7816 3.2184C11.5571 2.99385 11.193 2.99385 10.9685 3.2184L7.50005 6.68682L4.03164 3.2184C3.80708 2.99385 3.44301 2.99385 3.21846 3.2184C2.99391 3.44295 2.99391 3.80702 3.21846 4.03157L6.68688 7.49999L3.21846 10.9684C2.99391 11.193 2.99391 11.557 3.21846 11.7816C3.44301 12.0061 3.80708 12.0061 4.03164 11.7816L7.50005 8.31316L10.9685 11.7816C11.193 12.0061 11.5571 12.0061 11.7816 11.7816C12.0062 11.557 12.0062 11.193 11.7816 10.9684L8.31322 7.49999L11.7816 4.03157Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </Transition>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';

interface Props {
    title?: string;
    description?: string;
    variant?: 'default' | 'destructive';
    duration?: number;
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'default',
    duration: 5000,
});

const emit = defineEmits<{
    (e: 'update:open', value: boolean): void;
}>();

const open = defineModel<boolean>('open', { default: true });

let timeoutId: ReturnType<typeof setTimeout> | null = null;

watch(
    () => open.value,
    (newValue) => {
        if (newValue && props.duration > 0) {
            if (timeoutId) clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                open.value = false;
            }, props.duration);
        }
    },
    { immediate: true }
);

watch(open, (newValue) => {
    emit('update:open', newValue);
});
</script>


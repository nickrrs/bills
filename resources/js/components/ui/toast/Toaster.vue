<template>
    <Teleport to="body">
        <div
            class="pointer-events-none fixed bottom-0 z-[100] flex max-h-screen w-full flex-col-reverse p-4 sm:bottom-0 sm:right-0 sm:top-auto sm:flex-col md:max-w-[420px]"
        >
            <Toast
                v-for="toast in toasts"
                :key="toast.id"
                v-model:open="toast.open"
                :title="toast.title"
                :description="toast.description"
                :variant="toast.variant"
                :duration="toast.duration"
                @update:open="(value) => handleClose(toast.id, value)"
            />
        </div>
    </Teleport>
</template>

<script setup lang="ts">
import { ref, provide, onMounted } from 'vue';
import Toast from './Toast.vue';
import { TOAST_KEY, setToastInstance } from './use-toast';

export interface ToastData {
    id: string;
    title?: string;
    description?: string;
    variant?: 'default' | 'destructive';
    duration?: number;
    open: boolean;
}

const toasts = ref<ToastData[]>([]);

const addToast = (toast: Omit<ToastData, 'id' | 'open'>) => {
    const id = Math.random().toString(36).substring(2, 9);
    const newToast: ToastData = {
        ...toast,
        id,
        open: true,
    };
    toasts.value.push(newToast);
    return id;
};

const removeToast = (id: string) => {
    const index = toasts.value.findIndex((t) => t.id === id);
    if (index > -1) {
        toasts.value.splice(index, 1);
    }
};

const handleClose = (id: string, value: boolean) => {
    if (!value) {
        setTimeout(() => {
            removeToast(id);
        }, 200);
    }
};

// Disponibilizar via provide para componentes filhos
provide(TOAST_KEY, {
    addToast,
    removeToast,
});

// Também disponibilizar globalmente via singleton
onMounted(() => {
    setToastInstance({
        addToast,
        removeToast,
    });
});
</script>


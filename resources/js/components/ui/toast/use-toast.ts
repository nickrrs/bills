import { inject, type InjectionKey } from 'vue';
import type { ToastData } from './Toaster.vue';

export interface ToastOptions {
    title?: string;
    description?: string;
    variant?: 'default' | 'destructive';
    duration?: number;
}

interface ToastContext {
    addToast: (toast: Omit<ToastData, 'id' | 'open'>) => string;
    removeToast: (id: string) => void;
}

const TOAST_KEY: InjectionKey<ToastContext> = Symbol('toast');

// Singleton para acesso global
let globalToastInstance: ToastContext | null = null;

export function setToastInstance(instance: ToastContext) {
    globalToastInstance = instance;
}

export function useToast() {
    // Primeiro tenta usar o contexto via inject (para componentes filhos)
    const context = inject(TOAST_KEY, null);

    // Se não encontrar via inject, usa o singleton global
    const toastContext = context || globalToastInstance;

    const toast = (options: ToastOptions) => {
        // Tenta usar o contexto disponível
        const currentContext = context || globalToastInstance;

        if (!currentContext) {
            console.warn('Toast não disponível ainda. O Toaster pode não estar montado.');
            // Retorna uma função vazia para não quebrar a aplicação
            return '';
        }

        return currentContext.addToast({
            title: options.title,
            description: options.description,
            variant: options.variant || 'default',
            duration: options.duration ?? 5000,
        });
    };

    return {
        toast,
    };
}

export { TOAST_KEY };


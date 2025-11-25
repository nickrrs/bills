import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h, watch } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';
import { usePage } from '@inertiajs/vue3';

/**
 * Atualiza o CSRF token no meta tag quando ele mudar
 */
function updateCsrfToken(csrfToken: string | undefined): void {
    if (csrfToken) {
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        if (metaTag) {
            metaTag.setAttribute('content', csrfToken);
        }
    }
}

/**
 * Componente invisível que observa mudanças no CSRF token
 */
const CsrfTokenUpdater = {
    setup() {
        const page = usePage();

        // Observa mudanças no csrf_token nas props compartilhadas
        watch(
            () => (page.props as any)?.csrf_token,
            (newToken) => {
                updateCsrfToken(newToken);
            },
            { immediate: true }
        );

        return () => null; // Componente invisível
    },
};

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        // Atualiza o token na primeira carga
        updateCsrfToken((props.initialPage.props as any)?.csrf_token);

        const initialSettings = (props.initialPage.props as any)?.settings;
        initializeTheme(initialSettings?.theme);

        const app = createApp({
            render: () => [
                h(App, props),
                h(CsrfTokenUpdater), // Componente que observa mudanças no CSRF token
            ],
        })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

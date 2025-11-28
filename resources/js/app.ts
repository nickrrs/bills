import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h, watch } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';
import { usePage } from '@inertiajs/vue3';
import { ACCENT_COLOR_MAP, ACCENT_COLOR_LIGHT_MAP } from './composables/useThemeColors';

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

        // Inicializa cores de tema imediatamente após aplicar o tema
        // Usa requestAnimationFrame para garantir que o DOM esteja pronto
        requestAnimationFrame(() => {
            let accentColor = initialSettings?.accent_color || 'indigo';

            // Migração: converte "emerald" antigo para "lime"
            if (accentColor === 'emerald') {
                accentColor = 'lime';
            }

            // Fallback para valores inválidos
            if (!ACCENT_COLOR_MAP[accentColor as keyof typeof ACCENT_COLOR_MAP]) {
                accentColor = 'indigo';
            }

            const isDark = document.documentElement.classList.contains('dark');
            const colorMode = isDark ? 'dark' : 'light';
            const accent = ACCENT_COLOR_MAP[accentColor as keyof typeof ACCENT_COLOR_MAP][colorMode];
            const accentLight = ACCENT_COLOR_LIGHT_MAP[accentColor as keyof typeof ACCENT_COLOR_LIGHT_MAP][colorMode];

            const root = document.documentElement;
            root.style.setProperty('--accent-primary', accent);
            root.style.setProperty('--accent-primary-light', accentLight);

            // Dispara evento para notificar componentes sobre a inicialização
            window.dispatchEvent(new CustomEvent('accent-color-changed', {
                detail: { color: accent, colorMode }
            }));
        });

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

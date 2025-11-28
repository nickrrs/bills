import { computed, watch } from 'vue';
import { useUserSettings, type AccentColor } from './useUserSettings';

/**
 * Mapeamento de cores de destaque para valores hexadecimais
 */
export const ACCENT_COLOR_MAP: Record<AccentColor, { light: string; dark: string }> = {
    indigo: {
        light: '#6366f1', // indigo-500
        dark: '#6366f1',  // indigo-500
    },
    lime: {
        light: '#65a30d', // lime-600
        dark: '#84cc16',  // lime-500
    },
    red: {
        light: '#ef4444', // red-500
        dark: '#dc2626',  // red-600
    },
    orange: {
        light: '#f97316', // orange-500
        dark: '#f97316',  // orange-500
    },
    cyan: {
        light: '#0891b2', // cyan-600
        dark: '#06b6d4',  // cyan-500
    },
    purple: {
        light: '#a855f7', // purple-500
        dark: '#a855f7',  // purple-500
    },
};

/**
 * Cores de destaque mais claras para backgrounds e hovers (hexadecimais)
 */
export const ACCENT_COLOR_LIGHT_MAP: Record<AccentColor, { light: string; dark: string }> = {
    indigo: {
        light: '#eef2ff', // indigo-50
        dark: '#312e81',  // indigo-900
    },
    lime: {
        light: '#f7fee7', // lime-50
        dark: '#365314',  // lime-900
    },
    red: {
        light: '#fef2f2', // red-50
        dark: '#7f1d1d',  // red-900
    },
    orange: {
        light: '#fff7ed', // orange-50
        dark: '#7c2d12',  // orange-900
    },
    cyan: {
        light: '#ecfeff', // cyan-50
        dark: '#164e63',  // cyan-900
    },
    purple: {
        light: '#faf5ff', // purple-50
        dark: '#581c87',  // purple-900
    },
};

/**
 * Composable para gerenciar cores de tema dinamicamente
 * Aplica variáveis CSS baseadas nas configurações do usuário
 */
export function useThemeColors() {
    const { settings } = useUserSettings();

    /**
     * Aplica as cores de tema ao documento via CSS variables
     */
    function applyThemeColors() {
        const root = document.documentElement;
        let accentColor: AccentColor = settings.accent_color;

        // Fallback para valores inválidos
        if (!ACCENT_COLOR_MAP[accentColor]) {
            accentColor = 'indigo';
        }

        const isDark = document.documentElement.classList.contains('dark');
        const colorMode = isDark ? 'dark' : 'light';
        const accent = ACCENT_COLOR_MAP[accentColor][colorMode];
        const accentLight = ACCENT_COLOR_LIGHT_MAP[accentColor][colorMode];

        // Aplica variáveis CSS para a cor de destaque
        root.style.setProperty('--accent-primary', accent);
        root.style.setProperty('--accent-primary-light', accentLight);

        // Dispara evento customizado para notificar componentes sobre mudança de cor
        window.dispatchEvent(new CustomEvent('accent-color-changed', {
            detail: { color: accent, colorMode }
        }));
    }

    // Observa mudanças no tema e cor de destaque
    watch(
        () => [settings.accent_color, document.documentElement.classList.contains('dark')],
        () => {
            applyThemeColors();
        },
        { immediate: true }
    );

    // Computed para classes Tailwind dinâmicas
    const accentClasses = computed(() => {
        const color = settings.accent_color;
        return {
            text: `text-${color}-500`,
            bg: `bg-${color}-500`,
            border: `border-${color}-500`,
            hover: `hover:bg-${color}-600`,
            ring: `ring-${color}-500`,
        };
    });

    return {
        accentColor: computed(() => settings.accent_color),
        accentClasses,
        applyThemeColors,
    };
}


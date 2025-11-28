import { computed } from 'vue';
import { useUserSettings } from '@/composables/useUserSettings';
import type { AccentColor } from '@/composables/useUserSettings';

/**
 * Utilitários para aplicar classes de tema dinamicamente
 */

/**
 * Retorna classes Tailwind para a cor de destaque atual
 */
export function getAccentColorClasses(color?: AccentColor) {
    const { settings } = useUserSettings();
    const accentColor = color || settings.accent_color;

    return {
        text: `text-${accentColor}-500`,
        'text-hover': `hover:text-${accentColor}-600`,
        bg: `bg-${accentColor}-500`,
        'bg-hover': `hover:bg-${accentColor}-600`,
        border: `border-${accentColor}-500`,
        'border-hover': `hover:border-${accentColor}-600`,
        ring: `ring-${accentColor}-500`,
        'bg-light': `bg-${accentColor}-50 dark:bg-${accentColor}-900/20`,
        'text-light': `text-${accentColor}-600 dark:text-${accentColor}-400`,
    };
}

/**
 * Retorna a cor de destaque atual como string
 */
export function getAccentColor(): AccentColor {
    const { settings } = useUserSettings();
    return settings.accent_color;
}

/**
 * Composable para usar cores de tema em componentes
 * Retorna classes computadas que reagem às mudanças de tema
 */
export function useTheme() {
    const { settings } = useUserSettings();

    const accentColor = computed(() => settings.accent_color);
    const isDark = computed(() => {
        if (settings.theme === 'system') {
            return window.matchMedia('(prefers-color-scheme: dark)').matches;
        }
        return settings.theme === 'dark';
    });

    const accentClasses = computed(() => getAccentColorClasses(accentColor.value));

    return {
        accentColor,
        isDark,
        accentClasses,
    };
}


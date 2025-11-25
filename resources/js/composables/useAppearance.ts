import { computed, watch } from 'vue';
import { useUserSettings, type Theme } from './useUserSettings';

export function updateTheme(value: Theme) {
    if (value === 'system') {
        const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        document.documentElement.classList.toggle('dark', systemTheme === 'dark');
    } else {
        document.documentElement.classList.toggle('dark', value === 'dark');
    }
}

const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

let currentTheme: Theme = 'system';

const handleSystemThemeChange = () => {
    updateTheme(currentTheme);
};

export function initializeTheme(theme?: Theme) {
    currentTheme = theme || 'system';
    updateTheme(currentTheme);
    mediaQuery.addEventListener('change', handleSystemThemeChange);
}

export function useAppearance(settingsRef?: any, updateSettingRef?: any) {
    const userSettings = settingsRef && updateSettingRef
        ? { settings: settingsRef, updateSetting: updateSettingRef }
        : useUserSettings();

    const { settings, updateSetting } = userSettings;

    const appearance = computed(() => settings.theme);

    watch(
        () => settings.theme,
        (newTheme) => {
            currentTheme = newTheme;
            updateTheme(newTheme);
        },
        { immediate: true }
    );

    function updateAppearance(value: Theme) {
        updateSetting('theme', value);
        updateTheme(value);
    }

    return {
        appearance,
        updateAppearance,
    };
}

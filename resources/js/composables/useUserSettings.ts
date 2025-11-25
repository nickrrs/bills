import { computed, reactive, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { apiPost } from '@/utils/api';
import type { SharedData } from '@/types';

/**
 * temas e cores de destaque
 */
export type Theme = 'light' | 'dark' | 'system';
export type AccentColor = 'indigo' | 'emerald' | 'red' | 'orange' | 'cyan' | 'purple';

export interface UserSettings {
    theme: Theme;
    accent_color: AccentColor;
    notifications: {
        account_alerts: boolean;
        weekly_summary: boolean;
        goals_achieved: boolean;
    };
}

const DEFAULT_SETTINGS: UserSettings = {
    theme: 'system',
    accent_color: 'indigo',
    notifications: {
        account_alerts: false,
        weekly_summary: false,
        goals_achieved: false,
    },
};

const SYNC_DEBOUNCE_MS = 100;

let syncTimeout: ReturnType<typeof setTimeout> | null = null;
let pendingSync: UserSettings | null = null;

async function syncWithBackend(settings: UserSettings): Promise<void> {
    if (syncTimeout) {
        clearTimeout(syncTimeout);
    }

    pendingSync = settings;

    syncTimeout = setTimeout(async () => {
        if (!pendingSync) return;

        try {
            await apiPost('/api/settings', { settings: pendingSync });
        } catch (error) {
            console.warn('Erro ao sincronizar settings com backend:', error);
        } finally {
            pendingSync = null;
            syncTimeout = null;
        }
    }, SYNC_DEBOUNCE_MS);
}

function mergeWithDefaults(inertiaSettings: any): UserSettings {
    return {
        ...DEFAULT_SETTINGS,
        ...inertiaSettings,
        notifications: {
            ...DEFAULT_SETTINGS.notifications,
            ...(inertiaSettings.notifications || {}),
        },
    };
}

export function useUserSettings() {
    const page = usePage<SharedData>();
    const isAuthenticated = computed(() => !!page.props.auth?.user);

    const settings = reactive<UserSettings>({ ...DEFAULT_SETTINGS });

    function updateSetting<K extends keyof UserSettings>(
        key: K,
        value: UserSettings[K]
    ): void {
        Object.assign(settings, { [key]: value } as Partial<UserSettings>);

        if (isAuthenticated.value) {
            syncWithBackend(settings);
        }
    }

    function updateNestedSetting(
        path: string,
        value: any
    ): void {
        const keys = path.split('.');
        let current: any = settings;

        for (let i = 0; i < keys.length - 1; i++) {
            if (!current[keys[i]]) {
                current[keys[i]] = {};
            }
            current = current[keys[i]];
        }

        current[keys[keys.length - 1]] = value;

        if (isAuthenticated.value) {
            syncWithBackend(settings);
        }
    }

    /**
     * Reseta settings para valores padrão
     */
    function resetSettings(): void {
        Object.assign(settings, DEFAULT_SETTINGS);

        if (isAuthenticated.value) {
            syncWithBackend(settings);
        }
    }

    watch(
        () => (page.props as any)?.settings,
        (newSettings) => {
            if (newSettings) {
                const merged = mergeWithDefaults(newSettings);
                Object.assign(settings, merged);
            } else {
                Object.assign(settings, DEFAULT_SETTINGS);
            }
        },
        { deep: true, immediate: true }
    );

    return {
        settings,
        updateSetting,
        updateNestedSetting,
        resetSettings,
    };
}


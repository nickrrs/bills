<template>
    <InertiaHead title="configurações" />
    <MainLayout :no-sub-nav="true">
        <div class="h-full w-full px-4 py-4 sm:px-6 sm:py-6 lg:px-8 lg:py-8">
            <PageHeader title="configurações" description="personalize sua experiência no bills." />

            <div class="flex flex-col gap-6 sm:gap-8 mt-6 sm:mt-8">
                <div class="flex flex-col gap-3">
                    <div class="flex flex-row gap-2 items-center">
                        <Palette class="h-5 w-5 text-gray-400" />
                        <span class="text-sm font-medium text-gray-300">aparência</span>
                    </div>

                    <div class="flex flex-col lg:flex-row items-start gap-6 lg:gap-8 w-full lg:max-w-4xl">
                        <div class="flex flex-col gap-4 sm:gap-6 flex-1 w-full lg:max-w-md">
                            <div class="flex flex-col gap-2 sm:gap-3">
                                <span class="text-sm text-gray-400">modo de exibição</span>
                                <div class="flex flex-row gap-2 sm:gap-3 flex-wrap">
                                    <button
                                        v-for="themeOption in themeOptions"
                                        :key="themeOption.value"
                                        @click="updateTheme(themeOption.value)"
                                        :class="[
                                            'relative flex flex-col items-center justify-center p-4 sm:p-5 cursor-pointer bg-[#1a1a1a] hover:bg-[#222] rounded-2xl border-2 transition-all duration-200 text-center min-w-[90px] sm:min-w-[110px] h-[100px] sm:h-[120px] flex-1 sm:flex-none',
                                            settings.theme === themeOption.value
                                                ? `${accentColorBorderClass} bg-[#222]`
                                                : 'border-transparent'
                                        ]"
                                    >
                                        <Check
                                            v-if="settings.theme === themeOption.value"
                                            class="absolute top-2 right-2 h-4 w-4"
                                            :class="settings.theme === themeOption.value ? accentColorClass : 'text-indigo-500'"
                                        />
                                        <div
                                            :class="[
                                                'w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center mb-2 sm:mb-3',
                                                themeOption.bgClass
                                            ]"
                                        >
                                            <component :is="themeOption.icon" class="h-5 w-5 sm:h-6 sm:w-6" :class="themeOption.iconClass" />
                                        </div>
                                        <span class="text-xs sm:text-sm text-gray-400">{{ themeOption.label }}</span>
                                    </button>
                                </div>
                            </div>

                            <div class="flex flex-col gap-2 sm:gap-3">
                                <span class="text-sm text-gray-400">Cor de Destaque</span>
                                <div class="flex flex-row gap-2 sm:gap-3 flex-wrap">
                                    <button
                                        v-for="color in accentColors"
                                        :key="color.value"
                                        @click="updateAccentColor(color.value)"
                                        :class="[
                                            'w-10 h-10 sm:w-12 sm:h-12 rounded-full cursor-pointer transition-all duration-200',
                                            color.bgClass,
                                            settings.accent_color === color.value
                                                ? 'ring-2 ring-white ring-offset-2 ring-offset-[#1a1a1a] scale-110'
                                                : 'hover:scale-105'
                                        ]"
                                        :title="color.label"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 w-2/3 lg:w-[320px] lg:flex-shrink-0">
                            <div class="bg-[#1a1a1a] rounded-xl p-4 sm:p-6 border border-[#2a2a2a]">
                                <div class="flex items-center justify-between mb-3 sm:mb-4 flex-wrap gap-2">
                                    <div class="flex items-center gap-2">
                                        <Wallet class="h-4 w-4 sm:h-5 sm:w-5" :class="accentColorClass" />
                                        <div class="h-2 w-16 sm:w-20 bg-gray-700 rounded"></div>
                                    </div>
                                    <button
                                        :class="[
                                            'px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-medium text-white transition-colors',
                                            accentColorBgClass
                                        ]"
                                    >
                                        Botão Ação
                                    </button>
                                </div>
                                <div class="h-2 w-full bg-gray-700 rounded mb-2"></div>
                                <div class="h-2 w-3/4 bg-gray-700 rounded mb-3 sm:mb-4"></div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs sm:text-sm text-gray-400">Status:</span>
                                    <span :class="['text-xs sm:text-sm font-medium', accentColorClass]">Ativo</span>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500">Veja como os elementos se comportam com sua cor.</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-6">
                    <div class="flex flex-row gap-2 items-center">
                        <Bell class="h-5 w-5 text-gray-400" />
                        <span class="text-sm font-medium text-gray-300">Notificações</span>
                    </div>

                    <div class="flex flex-col gap-3 sm:gap-4 w-full sm:w-2/3 lg:w-1/2 xl:w-1/3">
                        <div
                            v-for="notification in notifications"
                            :key="notification.key"
                            class="flex items-center justify-between p-3 sm:p-4 bg-[#1a1a1a] rounded-xl border border-[#2a2a2a] gap-3"
                        >
                            <div class="flex items-center gap-2 sm:gap-3 flex-1 min-w-0">
                                <div :class="['p-1.5 sm:p-2 rounded-lg flex-shrink-0', notification.iconBgClass]">
                                    <component :is="notification.icon" class="h-3.5 w-3.5 sm:h-4 sm:w-4" :class="notification.iconClass" />
                                </div>
                                <div class="flex flex-col min-w-0 flex-1">
                                    <span class="text-xs sm:text-sm font-medium text-gray-300 truncate">{{ notification.title }}</span>
                                    <span class="text-xs text-gray-500 line-clamp-2">{{ notification.description }}</span>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <ToggleSwitch
                                    :checked="notification.checked"
                                    :accent-color="settings.accent_color"
                                    @update:checked="(value) => updateNotification(notification.key, value)"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup lang="ts">
import { computed, defineComponent, h } from 'vue';
import { Head as InertiaHead } from '@inertiajs/vue3';
import {
    Bell,
    Check,
    Palette,
    Moon,
    Monitor,
    Sun,
    Wallet,
    AlertCircle,
    TrendingUp,
    Target,
} from 'lucide-vue-next';
import PageHeader from '@/components/common/PageHeader.vue';
import MainLayout from '@/layouts/MainLayout.vue';
import { useUserSettings, type Theme, type AccentColor } from '@/composables/useUserSettings';
import { useAppearance } from '@/composables/useAppearance';

const { settings, updateSetting, updateNestedSetting } = useUserSettings();
const { updateAppearance } = useAppearance(settings, updateSetting);

const themeOptions = [
    {
        value: 'light' as Theme,
        label: 'Claro',
        icon: Sun,
        bgClass: 'bg-white',
        iconClass: 'text-gray-600'
    },
    {
        value: 'dark' as Theme,
        label: 'Escuro',
        icon: Moon,
        bgClass: 'bg-[#111] border-2 border-white/20',
        iconClass: 'text-white'
    },
    {
        value: 'system' as Theme,
        label: 'Sistema',
        icon: Monitor,
        bgClass: 'bg-gray-100',
        iconClass: 'text-gray-600'
    },
];

const accentColors = [
    { value: 'indigo' as AccentColor, label: 'Indigo', bgClass: 'bg-indigo-500' },
    { value: 'emerald' as AccentColor, label: 'Emerald', bgClass: 'bg-lime-500' },
    { value: 'red' as AccentColor, label: 'Red', bgClass: 'bg-red-500' },
    { value: 'orange' as AccentColor, label: 'Orange', bgClass: 'bg-orange-500' },
    { value: 'cyan' as AccentColor, label: 'Cyan', bgClass: 'bg-cyan-500' },
    { value: 'purple' as AccentColor, label: 'Purple', bgClass: 'bg-purple-500' },
];

const accentColorClass = computed(() => {
    const colorMap: Record<AccentColor, string> = {
        indigo: 'text-indigo-500',
        emerald: 'text-lime-500',
        red: 'text-red-500',
        orange: 'text-orange-500',
        cyan: 'text-cyan-500',
        purple: 'text-purple-500',
    };
    return colorMap[settings.accent_color];
});

const accentColorBgClass = computed(() => {
    const colorMap: Record<AccentColor, string> = {
        indigo: 'bg-indigo-500 hover:bg-indigo-600',
        emerald: 'bg-lime-500 hover:bg-lime-600',
        red: 'bg-red-500 hover:bg-red-600',
        orange: 'bg-orange-500 hover:bg-orange-600',
        cyan: 'bg-cyan-500 hover:bg-cyan-600',
        purple: 'bg-purple-500 hover:bg-purple-600',
    };
    return colorMap[settings.accent_color];
});

const accentColorBorderClass = computed(() => {
    const colorMap: Record<AccentColor, string> = {
        indigo: 'border-indigo-500',
        emerald: 'border-lime-500',
        red: 'border-red-500',
        orange: 'border-orange-500',
        cyan: 'border-cyan-500',
        purple: 'border-purple-500',
    };
    return colorMap[settings.accent_color];
});

const notifications = computed(() => [
    {
        key: 'account_alerts',
        title: 'Alertas de Contas',
        description: 'Receba avisos quando uma conta estiver prestes a vencer.',
        icon: AlertCircle,
        iconBgClass: 'bg-red-500/20',
        iconClass: 'text-red-500',
        checked: settings.notifications.account_alerts,
    },
    {
        key: 'weekly_summary',
        title: 'Resumo Semanal',
        description: 'Um resumo do seu desempenho financeiro toda segunda-feira.',
        icon: TrendingUp,
        iconBgClass: 'bg-indigo-500/20',
        iconClass: 'text-indigo-500',
        checked: settings.notifications.weekly_summary,
    },
    {
        key: 'goals_achieved',
        title: 'Metas Atingidas',
        description: 'Celebre quando você alcançar seus objetivos de economia.',
        icon: Target,
        iconBgClass: 'bg-lime-500/20',
        iconClass: 'text-lime-500',
        checked: settings.notifications.goals_achieved,
    },
]);

function updateTheme(theme: Theme) {
    updateAppearance(theme);
}

function updateAccentColor(color: AccentColor) {
    updateSetting('accent_color', color);
}

function updateNotification(key: string, value: boolean) {
    updateNestedSetting(`notifications.${key}`, value);
}

const ToggleSwitch = defineComponent({
    name: 'ToggleSwitch',
    props: {
        checked: {
            type: Boolean,
            default: false,
        },
        accentColor: {
            type: String,
            default: 'indigo',
        },
    },
    emits: ['update:checked'],
    setup(props, { emit }) {
        const toggle = () => {
            emit('update:checked', !props.checked);
        };

        const getAccentBgClass = () => {
            const colorMap: Record<string, string> = {
                indigo: 'bg-indigo-500',
                emerald: 'bg-lime-500',
                red: 'bg-red-500',
                orange: 'bg-orange-500',
                cyan: 'bg-cyan-500',
                purple: 'bg-purple-500',
            };
            return colorMap[props.accentColor] || 'bg-indigo-500';
        };

        return () =>
            h('button', {
                type: 'button',
                role: 'switch',
                'aria-checked': props.checked,
                onClick: toggle,
                class: [
                    'relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2',
                    props.checked ? getAccentBgClass() : 'bg-gray-600',
                ],
            }, [
                h('span', {
                    class: [
                        'inline-block h-4 w-4 transform rounded-full bg-white transition-transform',
                        props.checked ? 'translate-x-6' : 'translate-x-1',
                    ],
                }),
            ]);
    },
});
</script>

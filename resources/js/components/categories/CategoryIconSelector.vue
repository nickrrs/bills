<template>
    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <label class="text-sm font-medium text-foreground">escolha um ícone</label>
            <button
                v-if="selectedIcon"
                type="button"
                @click="clearSelection"
                class="text-xs text-muted-foreground hover:text-foreground transition-colors"
            >
                remover ícone
            </button>
        </div>

        <div class="flex flex-col gap-3">
            <div class="flex gap-2 border-b border-border pb-2">
                <button
                    v-for="category in iconCategories"
                    :key="category.name"
                    type="button"
                    @click="activeCategory = category.name"
                    :class="[
                        'px-3 py-1.5 text-xs font-medium rounded-md transition-colors',
                        activeCategory === category.name
                            ? 'bg-accent-primary text-white'
                            : 'text-muted-foreground hover:text-foreground hover:bg-accent'
                    ]"
                >
                    {{ category.label }}
                </button>
            </div>

            <div class="grid grid-cols-6 sm:grid-cols-8 md:grid-cols-10 gap-2">
                <button
                    v-for="icon in currentCategoryIcons"
                    :key="icon.name"
                    type="button"
                    @click="selectIcon(icon.name)"
                    :class="[
                        'w-full aspect-square rounded-lg border-2 transition-all hover:scale-105 bg-input flex items-center justify-center',
                        selectedIcon === icon.name
                            ? 'border-accent-primary ring-2 ring-accent-primary ring-offset-2 ring-offset-card category-icon-selected'
                            : 'border-border hover:border-accent-primary'
                    ]"
                    :style="selectedIcon === icon.name ? selectedIconStyle : {}"
                    :title="icon.label"
                >
                    <component :is="icon.component" class="h-5 w-5" :class="selectedIcon === icon.name ? 'text-white' : 'text-muted-foreground'" />
                </button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { computed } from 'vue';
import type { Component } from 'vue';
import {
    ShoppingBag,
    ShoppingCart,
    Coffee,
    UtensilsCrossed,
    Building2,
    Home,
    Wifi,
    Zap,
    Gift,
    Heart,
    Wallet,
    GraduationCap,
    Plane,
    Bus,
    Car,
    Music,
    Film,
    Stethoscope,
    PawPrint,
    Clock,
    Gamepad2,
    Dumbbell,
    BookOpen,
    Camera,
    Palette,
    Trophy,
    Award,
    TreePine,
    Settings,
} from 'lucide-vue-next';
import { getAccentPrimaryWithOpacity } from '@/utils/colors';

interface IconOption {
    name: string;
    label: string;
    component: Component;
}

interface IconCategory {
    name: string;
    label: string;
    icons: IconOption[];
}

export default {
    name: 'CategoryIconSelector',
    props: {
        modelValue: {
            type: String,
            default: null,
        },
    },
    emits: ['update:modelValue'],
    setup() {
        // Computed para o estilo do ícone selecionado
        const selectedIconStyle = computed(() => {
            return {
                backgroundColor: getAccentPrimaryWithOpacity(0.2),
            };
        });

        return {
            selectedIconStyle,
        };
    },
    data() {
        return {
            activeCategory: 'general',
            iconCategories: [
                {
                    name: 'general',
                    label: 'geral',
                    icons: [
                        { name: 'ShoppingBag', label: 'sacola de compras', component: ShoppingBag },
                        { name: 'ShoppingCart', label: 'carrinho de compras', component: ShoppingCart },
                        { name: 'Coffee', label: 'café', component: Coffee },
                        { name: 'UtensilsCrossed', label: 'restaurante', component: UtensilsCrossed },
                        { name: 'Building2', label: 'prédio', component: Building2 },
                        { name: 'Home', label: 'casa', component: Home },
                        { name: 'Wifi', label: 'wi-fi', component: Wifi },
                        { name: 'Zap', label: 'energia', component: Zap },
                        { name: 'Gift', label: 'presente', component: Gift },
                        { name: 'Heart', label: 'coração', component: Heart },
                        { name: 'Wallet', label: 'carteira', component: Wallet },
                        { name: 'GraduationCap', label: 'educação', component: GraduationCap },
                        { name: 'Plane', label: 'avião', component: Plane },
                        { name: 'Bus', label: 'ônibus', component: Bus },
                        { name: 'Car', label: 'carro', component: Car },
                        { name: 'Music', label: 'música', component: Music },
                        { name: 'Film', label: 'filme', component: Film },
                        { name: 'Stethoscope', label: 'saúde', component: Stethoscope },
                        { name: 'PawPrint', label: 'pet', component: PawPrint },
                        { name: 'Clock', label: 'relógio', component: Clock },
                    ],
                },
                {
                    name: 'transport',
                    label: 'transporte',
                    icons: [
                        { name: 'Car', label: 'carro', component: Car },
                        { name: 'Bus', label: 'ônibus', component: Bus },
                        { name: 'Plane', label: 'avião', component: Plane },
                    ],
                },
                {
                    name: 'leisure',
                    label: 'lazer',
                    icons: [
                        { name: 'Film', label: 'filme', component: Film },
                        { name: 'Music', label: 'música', component: Music },
                        { name: 'Gamepad2', label: 'jogos', component: Gamepad2 },
                        { name: 'Dumbbell', label: 'academia', component: Dumbbell },
                        { name: 'BookOpen', label: 'livro', component: BookOpen },
                        { name: 'Camera', label: 'câmera', component: Camera },
                        { name: 'Palette', label: 'arte', component: Palette },
                        { name: 'Trophy', label: 'troféu', component: Trophy },
                        { name: 'Award', label: 'prêmio', component: Award },
                    ],
                },
                {
                    name: 'home',
                    label: 'casa',
                    icons: [
                        { name: 'Home', label: 'casa', component: Home },
                        { name: 'Wifi', label: 'internet', component: Wifi },
                        { name: 'Zap', label: 'energia', component: Zap },
                        { name: 'ShoppingBag', label: 'compras', component: ShoppingBag },
                        { name: 'TreePine', label: 'jardim', component: TreePine },
                        { name: 'Settings', label: 'manutenção', component: Settings },
                    ],
                },
            ] as IconCategory[],
        };
    },
    computed: {
        selectedIcon: {
            get(): string | null {
                return this.modelValue;
            },
            set(value: string | null) {
                this.$emit('update:modelValue', value);
            },
        },
        currentCategoryIcons(): IconOption[] {
            const category = this.iconCategories.find(cat => cat.name === this.activeCategory);
            return category ? category.icons : [];
        },
    },
    methods: {
        selectIcon(iconName: string) {
            this.selectedIcon = iconName;
        },
        clearSelection() {
            this.selectedIcon = null;
        },
    },
};
</script>


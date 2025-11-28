<template>
    <div class="rounded-lg border border-border bg-card">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-border bg-muted/50">
                    <tr>
                        <th v-if="isSelectionMode" class="w-12 p-4">
                            <div class="flex items-center justify-center">
                                <div
                                    class="flex h-5 w-5 cursor-pointer items-center justify-center rounded border-2 transition-all"
                                    :class="allSelected ? 'border-accent-primary bg-accent-primary' : 'border-border bg-card'"
                                    @click="$emit('toggle-all')"
                                >
                                    <svg
                                        v-if="allSelected"
                                        class="h-3 w-3 text-white"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                        </th>
                        <th class="p-4 text-left text-sm font-semibold text-foreground">categoria</th>
                        <th class="p-4 text-left text-sm font-semibold text-foreground">tipo</th>
                        <th class="p-4 text-right text-sm font-semibold text-foreground">valor</th>
                        <th class="p-4 text-center text-sm font-semibold text-foreground">transações</th>
                        <th class="p-4 text-center text-sm font-semibold text-foreground">impacto</th>
                        <th class="p-4 text-center text-sm font-semibold text-foreground">criada em</th>
                        <th v-if="!isSelectionMode" class="w-24 p-4 text-center text-sm font-semibold text-foreground">ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="category in categories"
                        :key="category.id"
                        class="border-b border-border transition-colors hover:bg-muted/30"
                        :class="{
                            'bg-accent/20': isSelectionMode && selectedIds.includes(category.id),
                            'cursor-pointer': isSelectionMode,
                        }"
                        @click="isSelectionMode ? $emit('toggle-selection', category.id) : null"
                    >
                        <td v-if="isSelectionMode" class="p-4">
                            <div class="flex items-center justify-center">
                                <div
                                    class="flex h-5 w-5 items-center justify-center rounded border-2 transition-all"
                                    :class="selectedIds.includes(category.id) ? 'border-accent-primary bg-accent-primary' : 'border-border bg-card'"
                                >
                                    <svg
                                        v-if="selectedIds.includes(category.id)"
                                        class="h-3 w-3 text-white"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg"
                                    :style="{ backgroundColor: category.color + '20' }"
                                >
                                    <component
                                        v-if="getIconComponent(category.icon)"
                                        :is="getIconComponent(category.icon)"
                                        class="h-5 w-5"
                                        :style="{ color: category.color }"
                                    />
                                    <ShoppingBag v-else class="h-5 w-5" :style="{ color: category.color }" />
                                </div>
                                <div>
                                    <div class="font-semibold text-foreground">{{ category.name }}</div>
                                    <div class="text-sm text-muted-foreground">{{ category.id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <span
                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                :class="category.type === 'expense'
                                    ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400'
                                    : 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400'"
                            >
                                {{ category.type === 'expense' ? 'despesa' : 'receita' }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <span class="font-semibold" :style="{ color: category.color }">R$ 0,00</span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-sm font-semibold text-foreground">0</span>
                                <span class="text-xs text-muted-foreground">este mês</span>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-sm font-semibold text-foreground">0%</span>
                                <div class="mt-1 h-1.5 w-16 overflow-hidden rounded-full bg-muted">
                                    <div
                                        class="h-full rounded-full transition-all"
                                        :style="{
                                            width: '0%',
                                            backgroundColor: category.color,
                                        }"
                                    ></div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <span class="text-sm text-muted-foreground">{{ formatDate(category.created_at) }}</span>
                        </td>
                        <td v-if="!isSelectionMode" class="p-4">
                            <div class="flex items-center justify-center gap-1">
                                <button
                                    @click.stop="$emit('edit', category.id)"
                                    class="rounded-md p-1.5 text-muted-foreground transition-colors hover:bg-accent hover:text-foreground"
                                    title="Editar"
                                >
                                    <Pencil class="h-4 w-4" />
                                </button>
                                <button
                                    @click.stop="$emit('delete', category.id)"
                                    class="rounded-md p-1.5 text-muted-foreground transition-colors hover:bg-destructive hover:text-destructive-foreground"
                                    title="Excluir"
                                >
                                    <Trash2 class="h-4 w-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-if="categories.length === 0" class="flex flex-col items-center justify-center p-10 text-center">
            <p class="text-base font-medium text-muted-foreground">nenhuma categoria encontrada!</p>
            <p class="text-sm text-muted-foreground">clique em "nova categoria" para criar sua primeira categoria.</p>
        </div>
    </div>
</template>

<script lang="ts">
import { formatDate } from '@/utils/formatters';
import { Pencil, Trash2, ShoppingBag } from 'lucide-vue-next';
import type { Component } from 'vue';
import {
    Award,
    BookOpen,
    Building2,
    Bus,
    Camera,
    Car,
    Clock,
    Coffee,
    Dumbbell,
    Film,
    Gamepad2,
    Gift,
    GraduationCap,
    Heart,
    Home,
    Music,
    Palette,
    PawPrint,
    Plane,
    Settings,
    ShoppingCart,
    Stethoscope,
    TreePine,
    Trophy,
    UtensilsCrossed,
    Wallet,
    Wifi,
    Zap,
} from 'lucide-vue-next';

interface Category {
    id: number;
    name: string;
    icon: string | null;
    color: string;
    type: 'expense' | 'recept';
    user_id: number;
    created_at: string;
    updated_at: string;
}

export default {
    name: 'CategoriesTable',
    components: {
        Pencil,
        Trash2,
        ShoppingBag,
    },
    props: {
        categories: {
            type: Array as () => Category[],
            required: true,
        },
        isSelectionMode: {
            type: Boolean,
            default: false,
        },
        selectedIds: {
            type: Array as () => number[],
            default: () => [],
        },
        allSelected: {
            type: Boolean,
            default: false,
        },
    },
    emits: ['edit', 'delete', 'toggle-selection', 'toggle-all'],
    methods: {
        formatDate,
        getIconComponent(iconName: string | null): Component | null {
            if (!iconName) return null;
            const availableIcons: Record<string, Component> = {
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
            };
            return availableIcons[iconName] || null;
        },
    },
};
</script>

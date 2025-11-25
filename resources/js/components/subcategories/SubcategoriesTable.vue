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
                        <th class="p-4 text-left text-sm font-semibold text-foreground">subcategoria</th>
                        <th class="p-4 text-left text-sm font-semibold text-foreground">categoria pai</th>
                        <th class="p-4 text-right text-sm font-semibold text-foreground">valor</th>
                        <th class="p-4 text-center text-sm font-semibold text-foreground">transações</th>
                        <th class="p-4 text-center text-sm font-semibold text-foreground">impacto</th>
                        <th class="p-4 text-center text-sm font-semibold text-foreground">criada em</th>
                        <th v-if="!isSelectionMode" class="w-24 p-4 text-center text-sm font-semibold text-foreground">ações</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="categoryGroup in groupedCategories" :key="categoryGroup.category.id">
                        <tr v-if="categoryGroup.subcategories.length > 0" class="bg-muted/20">
                            <td :colspan="isSelectionMode ? 8 : 7" class="p-3">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-md"
                                        :style="{ backgroundColor: categoryGroup.category.color + '20' }"
                                    >
                                        <component
                                            v-if="getIconComponent(categoryGroup.category.icon)"
                                            :is="getIconComponent(categoryGroup.category.icon)"
                                            class="h-4 w-4"
                                            :style="{ color: categoryGroup.category.color }"
                                        />
                                        <ShoppingBag v-else class="h-4 w-4" :style="{ color: categoryGroup.category.color }" />
                                    </div>
                                    <div>
                                        <span class="font-semibold text-foreground">{{ categoryGroup.category.name }}</span>
                                        <span class="ml-2 text-sm text-muted-foreground">
                                            {{ categoryGroup.totalSubcategories }}
                                            {{ categoryGroup.totalSubcategories === 1 ? 'subcategoria' : 'subcategorias' }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr
                            v-for="subcategory in categoryGroup.subcategories"
                            :key="subcategory.id"
                            class="border-b border-border transition-colors hover:bg-muted/30"
                            :class="{
                                'bg-accent/20': isSelectionMode && selectedIds.includes(subcategory.id),
                                'cursor-pointer': isSelectionMode,
                            }"
                            @click="isSelectionMode ? $emit('toggle-selection', subcategory.id) : null"
                        >
                            <td v-if="isSelectionMode" class="p-4">
                                <div class="flex items-center justify-center">
                                    <div
                                        class="flex h-5 w-5 items-center justify-center rounded border-2 transition-all"
                                        :class="selectedIds.includes(subcategory.id) ? 'border-accent-primary bg-accent-primary' : 'border-border bg-card'"
                                    >
                                        <svg
                                            v-if="selectedIds.includes(subcategory.id)"
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
                                <div class="flex items-center gap-3 pl-4">
                                    <div
                                        class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full"
                                        :style="{ backgroundColor: subcategory.color + '20' }"
                                    >
                                        <component
                                            v-if="getIconComponent(subcategory.icon)"
                                            :is="getIconComponent(subcategory.icon)"
                                            class="h-4 w-4"
                                            :style="{ color: subcategory.color }"
                                        />
                                        <Star v-else class="h-4 w-4" :style="{ color: subcategory.color }" />
                                    </div>
                                    <div>
                                        <div class="font-semibold text-foreground">{{ subcategory.name }}</div>
                                        <div class="text-sm text-muted-foreground">{{ subcategory.slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="flex h-6 w-6 items-center justify-center rounded"
                                        :style="{ backgroundColor: categoryGroup.category.color + '20' }"
                                    >
                                        <component
                                            v-if="getIconComponent(categoryGroup.category.icon)"
                                            :is="getIconComponent(categoryGroup.category.icon)"
                                            class="h-3 w-3"
                                            :style="{ color: categoryGroup.category.color }"
                                        />
                                        <ShoppingBag v-else class="h-3 w-3" :style="{ color: categoryGroup.category.color }" />
                                    </div>
                                    <span class="text-sm text-foreground">{{ categoryGroup.category.name }}</span>
                                </div>
                            </td>
                            <td class="p-4 text-right">
                                <span class="font-semibold" :style="{ color: subcategory.color }">R$ 0,00</span>
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
                                                width: '70%',
                                                backgroundColor: subcategory.color,
                                            }"
                                        ></div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-center">
                                <span class="text-sm text-muted-foreground">{{ formatDate(subcategory.created_at || '') }}</span>
                            </td>
                            <td v-if="!isSelectionMode" class="p-4">
                                <div class="flex items-center justify-center gap-1">
                                    <button
                                        @click.stop="$emit('edit', subcategory.id)"
                                        class="rounded-md p-1.5 text-muted-foreground transition-colors hover:bg-accent hover:text-foreground"
                                        title="Editar"
                                    >
                                        <Pencil class="h-4 w-4" />
                                    </button>
                                    <button
                                        @click.stop="$emit('delete', subcategory.id)"
                                        class="rounded-md p-1.5 text-muted-foreground transition-colors hover:bg-destructive hover:text-destructive-foreground"
                                        title="Excluir"
                                    >
                                        <Trash2 class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <div v-if="totalSubcategories === 0" class="flex flex-col items-center justify-center p-10 text-center">
            <p class="text-base font-medium text-muted-foreground">nenhuma subcategoria encontrada!</p>
            <p class="text-sm text-muted-foreground">clique em "nova subcategoria" para criar sua primeira subcategoria.</p>
        </div>
    </div>
</template>

<script lang="ts">
import { formatDate } from '@/utils/formatters';
import { Pencil, Trash2, ShoppingBag, Star } from 'lucide-vue-next';
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

interface Subcategory {
    id: number;
    name: string;
    slug: string;
    category_id: number;
    icon: string | null;
    color: string;
    created_at?: string;
    category?: Category;
}

interface Category {
    id: number;
    name: string;
    icon: string | null;
    color: string;
    type: 'expense' | 'recept';
    subcategories?: Subcategory[];
}

interface CategoryGroup {
    category: Category;
    subcategories: Subcategory[];
    totalSubcategories: number;
}

export default {
    name: 'SubcategoriesTable',
    components: {
        Pencil,
        Trash2,
        ShoppingBag,
        Star,
    },
    props: {
        groupedCategories: {
            type: Array as () => CategoryGroup[],
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
    computed: {
        totalSubcategories(): number {
            return this.groupedCategories.reduce((total, group) => total + group.subcategories.length, 0);
        },
    },
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
                Star,
            };
            return availableIcons[iconName] || null;
        },
    },
};
</script>

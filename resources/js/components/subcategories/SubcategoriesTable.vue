<template>
    <div class="rounded-lg border border-border bg-card">
        <div class="overflow-x-auto">
            <table class="w-full min-w-full max-w-full" style="table-layout: fixed;">
                <thead class="border-b border-border bg-muted/50">
                    <tr>
                        <th v-if="isSelectionMode" class="w-12 min-w-12 max-w-12 p-4">
                            <div class="flex items-center justify-center">
                                <div
                                    class="flex h-5 w-5 flex-shrink-0 cursor-pointer items-center justify-center rounded-full border-2 transition-all"
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
                        <th class="p-4 text-left text-sm font-semibold text-foreground" style="width: 280px; min-width: 280px; max-width: 280px;">subcategoria</th>
                        <th class="p-4 text-left text-sm font-semibold text-foreground" style="width: 200px; min-width: 200px; max-width: 200px;">categoria pai</th>
                        <th class="p-4 text-right text-sm font-semibold text-foreground" style="width: 120px; min-width: 120px; max-width: 120px;">valor</th>
                        <th class="p-4 text-center text-sm font-semibold text-foreground" style="width: 110px; min-width: 110px; max-width: 110px;">transações</th>
                        <th class="p-4 text-center text-sm font-semibold text-foreground" style="width: 140px; min-width: 140px; max-width: 140px;">impacto</th>
                        <th class="p-4 text-center text-sm font-semibold text-foreground" style="width: 130px; min-width: 130px; max-width: 130px;">criada em</th>
                        <th class="p-4 text-center text-sm font-semibold text-foreground" style="width: 130px; min-width: 130px; max-width: 130px;">atualizada em</th>
                        <th class="w-20 p-4 text-center text-sm font-semibold text-foreground" style="width: 140px; min-width: 140px; max-width: 140px;">
                            <div class="flex items-center justify-center gap-1">
                                <button
                                    @click="toggleAllCategories"
                                    class="flex items-center gap-1 rounded-md border border-border px-2 py-1 text-xs font-medium text-muted-foreground transition-all hover:bg-accent hover:text-foreground"
                                    :title="allCategoriesCollapsed ? 'expandir todas' : 'recolher todas'"
                                >
                                    <div v-if="!allCategoriesCollapsed" class="flex items-center gap-1">
                                        <ChevronUp class="h-3 w-3" />
                                        <span>recolher todas</span>
                                    </div>
                                    <div v-else class="flex items-center gap-1">
                                        <ChevronDown class="h-3 w-3" />
                                        <span>expandir todas</span>
                                    </div>
                                </button>
                                <button
                                    v-if="singleCategoryMode"
                                    @click="exitSingleCategoryView"
                                    class="flex items-center gap-1 rounded-md border border-border px-2 py-1 text-xs font-medium text-accent-primary transition-all hover:bg-accent-primary hover:text-foreground"
                                    title="voltar à visualização normal"
                                >
                                    <X class="h-3 w-3" />
                                    <span class="text-xs">voltar</span>
                                </button>
                            </div>
                        </th>
                        <th class="w-24 min-w-24 max-w-24 p-4 text-center text-sm font-semibold text-foreground">ações</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(categoryGroup, groupIndex) in groupedCategories" :key="categoryGroup.category.id">
                        <tr v-if="groupIndex > 0">
                            <td :colspan="isSelectionMode ? 10 : 9" class="p-0">
                                <div class="border-t border-border/30 mx-4"></div>
                            </td>
                        </tr>

                        <tr v-if="categoryGroup.subcategories.length > 0" class="bg-muted/20">
                            <td :colspan="isSelectionMode ? 10 : 9" class="p-3">
                                <div class="flex items-center justify-between">
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
                                    <div class="flex items-center gap-3">
                                        <!-- Paginação da categoria -->
                                        <div v-if="categoryGroup.pagination.totalPages > 1" class="flex items-center gap-2">
                                            <button
                                                @click="$emit('change-category-page', categoryGroup.category.id, 'prev')"
                                                :disabled="categoryGroup.pagination.currentPage === 1"
                                                class="rounded-md border border-border px-2 py-1 text-xs text-foreground transition-colors hover:bg-accent disabled:cursor-not-allowed disabled:opacity-50"
                                            >
                                                <ChevronLeft class="h-3 w-3" />
                                            </button>
                                            <span class="text-xs text-muted-foreground">
                                                {{ categoryGroup.pagination.currentPage }} / {{ categoryGroup.pagination.totalPages }}
                                            </span>
                                            <button
                                                @click="$emit('change-category-page', categoryGroup.category.id, 'next')"
                                                :disabled="categoryGroup.pagination.currentPage === categoryGroup.pagination.totalPages"
                                                class="rounded-md border border-border px-2 py-1 text-xs text-foreground transition-colors hover:bg-accent disabled:cursor-not-allowed disabled:opacity-50"
                                            >
                                                <ChevronRight class="h-3 w-3" />
                                            </button>
                                        </div>
                                        <!-- recolher/expandir categoria -->
                                        <button
                                            @click="toggleCategoryCollapse(categoryGroup.category.id)"
                                            class="flex items-center gap-1 rounded-md border border-border px-2 py-1 text-xs font-medium text-muted-foreground transition-all hover:bg-accent hover:text-foreground"
                                            :title="isCategoryCollapsed(categoryGroup.category.id) ? 'Expandir categoria' : 'Recolher categoria'"
                                        >
                                            <ChevronUp
                                                v-if="!isCategoryCollapsed(categoryGroup.category.id)"
                                                class="h-3 w-3 transition-transform duration-200"
                                            />
                                            <ChevronDown
                                                v-else
                                                class="h-3 w-3 transition-transform duration-200"
                                            />
                                            <span>{{ isCategoryCollapsed(categoryGroup.category.id) ? 'expandir' : 'recolher' }}</span>
                                        </button>
                                        <!-- ver mais -->
                                        <button
                                            v-if="!singleCategoryMode"
                                            @click="expandSingleCategory(categoryGroup.category.id)"
                                            class="flex items-center gap-1 rounded-md border border-border px-3 py-1 text-xs font-medium text-muted-foreground transition-all hover:bg-accent-primary hover:text-white"
                                        >
                                            <Eye class="h-3 w-3" />
                                            <span>ver mais</span>
                                        </button>
                                        <!-- adicionar subcategoria -->
                                        <button
                                            @click="$emit('add-to-category', categoryGroup.category.id)"
                                            class="flex items-center gap-1 rounded-md border border-dashed border-border px-3 py-1 text-xs font-medium text-muted-foreground transition-all hover:border-accent-primary hover:bg-accent-primary/5 hover:text-accent-primary"
                                        >
                                            <Plus class="h-3 w-3" />
                                            <span>adicionar</span>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="isCategoryCollapsed(categoryGroup.category.id)" class="border-b border-border">
                            <td :colspan="isSelectionMode ? 10 : 9" class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2 text-muted-foreground">
                                    <ChevronDown class="h-4 w-4" />
                                    <span class="text-sm">
                                        {{ categoryGroup.totalSubcategories }}
                                        {{ categoryGroup.totalSubcategories === 1 ? 'subcategoria oculta' : 'subcategorias ocultas' }}
                                    </span>
                                    <button
                                        @click="toggleCategoryCollapse(categoryGroup.category.id)"
                                        class="ml-2 text-xs text-accent-primary hover:underline"
                                    >
                                        clique para expandir
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr
                            v-for="subcategory in categoryGroup.subcategories"
                            :key="subcategory.id"
                            v-show="!isCategoryCollapsed(categoryGroup.category.id)"
                            class="border-b border-border transition-all duration-200 hover:bg-muted/30"
                            :class="{
                                'bg-accent/20': isSelectionMode && selectedIds.includes(subcategory.id),
                                'cursor-pointer': isSelectionMode,
                            }"
                            @click="isSelectionMode ? $emit('toggle-selection', subcategory.id) : null"
                        >
                        <td v-if="isSelectionMode" class="p-4" style="width: 48px; min-width: 48px; max-width: 48px;">
                            <div class="flex items-center justify-center">
                                <div
                                    class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full border-2 transition-all"
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
                            <!-- subcategoria -->
                        <td class="p-4" style="width: 280px; min-width: 280px; max-width: 280px;">
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
                                <div class="min-w-0 flex-1">
                                    <div class="truncate font-semibold text-foreground" title="{{ subcategory.name }}">{{ subcategory.name }}</div>
                                    <div class="truncate text-sm text-muted-foreground" title="{{ subcategory.slug }}">{{ subcategory.preferred ? 'em destaque' : '' }}</div>
                                </div>
                            </div>
                        </td>
                        <!-- categoria pai -->
                        <td class="p-4" style="width: 200px; min-width: 200px; max-width: 200px;">
                            <div class="flex items-center gap-2">
                                <div
                                    class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded"
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
                                <span class="truncate text-sm text-foreground min-w-0" :title="categoryGroup.category.name">{{ categoryGroup.category.name }}</span>
                            </div>
                        </td>
                        <!-- valor -->
                        <td class="p-4 text-right" style="width: 120px; min-width: 120px; max-width: 120px;">
                            <span class="font-semibold" :style="{ color: subcategory.color }">R$ 0,00</span>
                        </td>
                        <!-- transações -->
                        <td class="p-4 text-center" style="width: 110px; min-width: 110px; max-width: 110px;">
                            <div class="flex flex-col items-center">
                                <span class="text-sm font-semibold text-foreground">0</span>
                                <span class="text-xs text-muted-foreground">este mês</span>
                            </div>
                        </td>
                        <!-- impacto -->
                        <td class="p-4 text-center" style="width: 140px; min-width: 140px; max-width: 140px;">
                            <div class="flex flex-col items-center">
                                <span class="text-sm font-semibold text-foreground">0%</span>
                                <div class="mt-1 h-1.5 w-20 overflow-hidden rounded-full bg-muted mx-auto">
                                    <div
                                        class="h-full rounded-full transition-all"
                                        :style="{
                                            width: '0%',
                                            backgroundColor: subcategory.color,
                                        }"
                                    ></div>
                                </div>
                            </div>
                        </td>
                        <!-- criada em -->
                        <td class="p-4 text-center" style="width: 130px; min-width: 130px; max-width: 130px;">
                            <span class="text-sm text-muted-foreground">{{ formatDate(subcategory.created_at || '') }}</span>
                        </td>
                        <!-- atualizada em -->
                        <td class="p-4 text-center" style="width: 130px; min-width: 130px; max-width: 130px;">
                            <span class="text-sm text-muted-foreground">{{ formatDate(subcategory.updated_at || '') }}</span>
                        </td>
                        <td style="width: 140px; min-width: 140px; max-width: 140px;"></td>
                        <td class="p-4 min-w-24 max-w-24 w-24">
                            <div class="flex items-center justify-center gap-1">
                                <button
                                    @click.stop="!isSelectionMode && $emit('edit', subcategory.id)"
                                    :disabled="isSelectionMode"
                                    :class="[
                                        'rounded-md p-1.5 transition-colors',
                                        isSelectionMode
                                            ? 'text-muted-foreground/30 cursor-not-allowed'
                                            : 'text-muted-foreground hover:bg-accent hover:text-foreground'
                                    ]"
                                    :title="isSelectionMode ? 'desabilitado no modo seleção' : 'editar'"
                                >
                                    <Pencil class="h-4 w-4" />
                                </button>
                                <button
                                    @click.stop="!isSelectionMode && $emit('delete', subcategory.id)"
                                    :disabled="isSelectionMode"
                                    :class="[
                                        'rounded-md p-1.5 transition-colors',
                                        isSelectionMode
                                            ? 'text-muted-foreground/30 cursor-not-allowed'
                                            : 'text-muted-foreground hover:bg-destructive hover:text-destructive-foreground'
                                    ]"
                                    :title="isSelectionMode ? 'desabilitado no modo seleção' : 'excluir'"
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
import { Pencil, Trash2, ShoppingBag, Star, Plus, ChevronLeft, ChevronRight, ChevronDown, ChevronUp, Eye, X } from 'lucide-vue-next';
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
    preferred: boolean;
    category_id: number;
    icon: string | null;
    color: string;
    created_at?: string;
    updated_at?: string;
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
    pagination: {
        currentPage: number;
        totalPages: number;
    };
}

export default {
    name: 'SubcategoriesTable',
    components: {
        Pencil,
        Trash2,
        ShoppingBag,
        Star,
        Plus,
        ChevronLeft,
        ChevronRight,
        ChevronDown,
        ChevronUp,
        Eye,
        X,
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
        singleCategoryMode: {
            type: Boolean,
            default: false,
        },
    },
    emits: ['edit', 'delete', 'toggle-selection', 'toggle-all', 'add-to-category', 'change-category-page', 'expand-single-category', 'exit-single-category-view'],
    data() {
        return {
            expandedCategory: null as number | null,
            collapsedCategories: new Set<number>(),
            expandedSingleCategory: null as number | null,
        };
    },
    computed: {
        totalSubcategories(): number {
            return this.groupedCategories.reduce((total, group) => total + group.subcategories.length, 0);
        },
        allCategoriesCollapsed(): boolean {
            if (this.groupedCategories.length === 0) return false;
            return this.groupedCategories.every(group => this.collapsedCategories.has(group.category.id));
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
        toggleCategoryCollapse(categoryId: number) {
            if (this.collapsedCategories.has(categoryId)) {
                this.collapsedCategories.delete(categoryId);
            } else {
                this.collapsedCategories.add(categoryId);
            }
        },
        isCategoryCollapsed(categoryId: number): boolean {
            return this.collapsedCategories.has(categoryId);
        },
        toggleAllCategories() {
            if (this.allCategoriesCollapsed) {
                // expandir todas
                this.collapsedCategories.clear();
            } else {
                // recolher todas
                this.groupedCategories.forEach(group => {
                    this.collapsedCategories.add(group.category.id);
                });
            }
        },
        expandSingleCategory(categoryId: number) {
            this.expandedSingleCategory = categoryId;
            this.$emit('expand-single-category', categoryId);
        },
        exitSingleCategoryView() {
            this.expandedSingleCategory = null;
            this.$emit('exit-single-category-view');
        },
    },
};
</script>

<style scoped>
/* garante que a tabela mantenha larguras fixas */
table {
    table-layout: fixed;
    border-collapse: separate;
    border-spacing: 0;
}

/* previne quebra de texto em células pequenas */
th, td {
    white-space: nowrap;
    overflow: hidden;
}

/* permite quebra de texto apenas nas colunas de nome */
td:nth-child(2) div,
td:nth-child(3) span {
    white-space: normal;
    word-wrap: break-word;
}

/* garante que elementos com truncate funcionem corretamente */
.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* melhora a responsividade da barra de progresso */
.progress-bar {
    min-width: 80px;
    max-width: 80px;
}
</style>

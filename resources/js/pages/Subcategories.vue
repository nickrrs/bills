<template>
    <InertiaHead title="Subcategorias" />
    <MainLayout :no-sub-nav="true">
        <div class="h-full w-full px-8 py-8">
            <PageHeader title="subcategorias" description="gerencie a subclassificação de suas categorias.">
                <template #badge>
                    <span class="rounded-md bg-muted px-2 py-1 text-xs font-semibold text-muted-foreground">
                        {{ totalSubcategories }} Total
                    </span>
                </template>
                <template #actions>
                    <div class="flex flex-col gap-3 md:flex-row md:items-center">
                        <SearchInput
                            v-model="searchQuery"
                            placeholder="buscar subcategoria..."
                            :debounce="400"
                            wrapper-class="w-full md:w-64"
                            @search="handleSearch"
                        />
                        <div class="flex flex-wrap items-center gap-2">
                            <button
                                v-if="!isSelectionMode"
                                @click="toggleSelectionMode"
                                class="flex items-center gap-x-2 rounded-md border border-border bg-card px-4 py-2 text-foreground transition-colors hover:bg-accent"
                                title="selecionar múltiplas subcategorias (Ctrl + Espaço)"
                            >
                                <Target class="!h-4 !w-4" />
                                <span>selecionar</span>
                                <span class="hidden rounded border border-border px-1 py-0.5 text-[11px] uppercase text-muted-foreground lg:inline-flex">
                                    Ctrl + Espaço
                                </span>
                            </button>
                            <button
                                v-else
                                @click="exitSelectionMode"
                                class="flex items-center gap-x-2 rounded-md border border-border bg-card px-4 py-2 text-foreground transition-colors hover:bg-accent"
                            >
                                cancelar seleção
                            </button>
                            <button
                                @click="openModal"
                                class="flex items-center gap-x-2 rounded-md border border-border bg-card px-4 py-2 text-foreground transition-colors hover:bg-accent"
                            >
                                <Plus class="!h-4 !w-4" />
                                nova subcategoria
                            </button>
                        </div>
                    </div>
                </template>
            </PageHeader>
            <div class="grid grid-cols-1 items-start gap-6 pb-4 md:grid-cols-2 lg:grid-cols-3">
                <template v-if="loadingCategories && categories.length === 0">
                    <CategoryCardSkeleton v-for="n in 3" :key="`category-skeleton-${n}`" />
                </template>
                <template v-else-if="!loadingCategories && loading && categories.length > 0">
                    <div
                        v-for="category in categories"
                        :key="`category-loading-${category.id}`"
                        class="h-full w-full rounded-[20px] border border-border bg-card transition-all duration-300"
                    >
                        <div
                            class="flex flex-row items-center justify-between space-x-3 rounded-t-[20px] border-b border-border bg-gradient-to-b from-muted/30 to-transparent p-[20px]"
                        >
                            <div class="flex flex-row items-center space-x-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-md"
                                    :style="{ backgroundColor: category.color + '20' }"
                                >
                                    <component
                                        v-if="getIconComponent(category.icon)"
                                        :is="getIconComponent(category.icon)"
                                        class="!h-5 !w-5"
                                        :style="{ color: category.color }"
                                    />
                                    <ShoppingBag v-else class="!h-5 !w-5" :style="{ color: category.color }" />
                                </div>
                                <div class="flex flex-col items-start">
                                    <span class="text-sm font-semibold text-foreground">{{ category.name }}</span>
                                    <span class="text-xs font-medium text-muted-foreground">carregando subcategorias...</span>
                                </div>
                            </div>
                            <span class="mb-1 whitespace-nowrap font-semibold" :style="{ color: category.color }">R$ 0,00</span>
                        </div>
                        <div class="border-b border-border p-3">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 !h-4 !w-4 -translate-y-1/2 transform text-muted-foreground" />
                                <input
                                    type="text"
                                    :placeholder="`buscar em ${category.name.toLowerCase()}...`"
                                    disabled
                                    class="w-full rounded-md border border-border bg-input py-2 pl-9 pr-4 text-sm text-foreground placeholder-muted-foreground opacity-50 cursor-not-allowed"
                                />
                            </div>
                        </div>
                        <div class="flex flex-col p-[8px] gap-1">
                            <div
                                v-for="n in 3"
                                :key="`subcategory-skeleton-${category.id}-${n}`"
                                class="relative flex flex-row items-center space-x-3 p-[12px] rounded-[10px]"
                            >
                                <div class="h-8 w-8 rounded-full bg-white/10 flex-shrink-0 animate-pulse"></div>
                                <div class="flex-1">
                                    <div class="flex flex-row items-center justify-between">
                                        <div class="h-4 w-32 bg-white/10 rounded animate-pulse"></div>
                                        <div class="h-3 w-16 bg-white/10 rounded animate-pulse"></div>
                                    </div>
                                </div>
                                <div class="absolute bottom-0 left-0 right-[12px] h-[2px] rounded-[2px] overflow-hidden bg-[#ffffff21]">
                                    <div class="h-full w-[70%] bg-white/10 rounded-[2px] animate-pulse"></div>
                                </div>
                            </div>
                            <div class="h-10 mx-3 mb-3 mt-2 rounded-xl bg-white/5 animate-pulse"></div>
                        </div>
                    </div>
                </template>
                <template v-else-if="groupedCategories.length > 0">
                    <div
                        v-for="categoryGroup in groupedCategories"
                        :key="categoryGroup.category.id"
                                class="h-full w-full rounded-[20px] border border-border bg-card transition-all duration-300 hover:-translate-y-1 hover:border-accent"
                    >
                        <div
                            class="flex flex-row items-center justify-between space-x-3 rounded-t-[20px] border-b border-border bg-gradient-to-b from-muted/30 to-transparent p-[20px]"
                        >
                            <div class="flex flex-row items-center space-x-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-md"
                                    :style="{ backgroundColor: categoryGroup.category.color + '20' }"
                                >
                                    <component
                                        v-if="getIconComponent(categoryGroup.category.icon)"
                                        :is="getIconComponent(categoryGroup.category.icon)"
                                        class="!h-5 !w-5"
                                        :style="{ color: categoryGroup.category.color }"
                                    />
                                    <ShoppingBag v-else class="!h-5 !w-5" :style="{ color: categoryGroup.category.color }" />
                                </div>
                                <div class="flex flex-col items-start">
                                    <span class="text-sm font-semibold text-foreground">{{ categoryGroup.category.name }}</span>
                                    <span class="text-xs font-medium text-muted-foreground">
                                        {{ categoryGroup.totalSubcategories }}
                                        {{ categoryGroup.totalSubcategories === 1 ? 'subcategoria' : 'subcategorias' }}
                                    </span>
                                </div>
                            </div>
                            <span class="mb-1 whitespace-nowrap font-semibold" :style="{ color: categoryGroup.category.color }">R$ 0,00</span>
                        </div>
                        <div class="border-b border-border p-3">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 !h-4 !w-4 -translate-y-1/2 transform text-muted-foreground" />
                                <input
                                    :value="categorySearchQueries[categoryGroup.category.id] || ''"
                                    @input="handleCategorySearchInput(categoryGroup.category.id, ($event.target as HTMLInputElement).value)"
                                    type="text"
                                    :placeholder="`buscar em ${categoryGroup.category.name.toLowerCase()}...`"
                                    class="w-full rounded-md border border-border bg-input py-2 pl-9 pr-8 text-sm text-foreground placeholder-muted-foreground transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-accent-primary"
                                />
                                <button
                                    v-if="categorySearchQueries[categoryGroup.category.id]"
                                    @click="clearCategorySearch(categoryGroup.category.id)"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 transform text-muted-foreground transition-colors hover:text-foreground"
                                >
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1 p-[8px]">
                            <div
                                v-for="subcategory in categoryGroup.subcategories"
                                :key="subcategory.id"
                                class="group relative flex flex-row items-center space-x-3 rounded-[10px] p-[12px] transition-all duration-300 hover:bg-accent/60"
                                :class="{
                                    'ring-2 ring-accent-primary ring-offset-2 ring-offset-card':
                                        isSelectionMode && selectedIds.includes(subcategory.id),
                                    'cursor-pointer': isSelectionMode,
                                }"
                                @click="isSelectionMode ? toggleSubcategorySelection(subcategory.id) : null"
                            >
                                <div
                                    v-if="isSelectionMode"
                                    class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded border-2 transition-all"
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
                                <div
                                    class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full"
                                    :style="{ backgroundColor: subcategory.color + '20' }"
                                >
                                    <component
                                        v-if="getIconComponent(subcategory.icon)"
                                        :is="getIconComponent(subcategory.icon)"
                                        class="!h-4 !w-4"
                                        :style="{ color: subcategory.color }"
                                    />
                                    <Star v-else class="!h-4 !w-4" :style="{ color: subcategory.color }" />
                                </div>
                                <div class="flex-1">
                                    <div class="flex flex-row items-center justify-between">
                                        <span class="text-sm font-semibold text-foreground">{{ subcategory.name }}</span>
                                        <span class="text-xs font-medium text-muted-foreground">R$ 0,00</span>
                                    </div>
                                </div>
                                <div v-if="!isSelectionMode" class="flex gap-1 opacity-0 transition-opacity group-hover:opacity-100">
                                    <button
                                        @click.stop="handleEditSubcategory(subcategory.id)"
                                        class="rounded-[6px] p-[4px] text-muted-foreground transition-all duration-300 hover:bg-accent-primary hover:text-white"
                                        title="Editar"
                                    >
                                        <Pencil class="!h-[14px] !w-[14px]" />
                                    </button>
                                    <button
                                        @click.stop="handleDeleteSubcategory(subcategory.id)"
                                        class="rounded-[6px] p-[4px] text-muted-foreground transition-all duration-300 hover:bg-destructive hover:text-destructive-foreground"
                                        title="Excluir"
                                    >
                                        <Trash2 class="!h-[14px] !w-[14px]" />
                                    </button>
                                </div>
                                <div class="absolute bottom-0 left-0 right-[12px] h-[2px] overflow-hidden rounded-[2px] bg-[#ffffff21]">
                                    <div
                                        class="h-full rounded-[2px]"
                                        :style="{
                                            width: '70%',
                                            backgroundColor: subcategory.color,
                                        }"
                                    ></div>
                                </div>
                            </div>
                            <div
                                v-if="categoryGroup.pagination.totalPages > 1"
                                class="mx-3 mt-2 flex items-center justify-between rounded-lg border border-border px-3 py-2 text-xs text-muted-foreground"
                            >
                                <button
                                    class="rounded-md border border-border px-3 py-1 text-foreground transition-colors hover:bg-accent disabled:cursor-not-allowed disabled:opacity-50"
                                    @click="changeCategoryPage(categoryGroup.category.id, 'prev')"
                                    :disabled="categoryGroup.pagination.currentPage === 1"
                                >
                                    anterior
                                </button>
                                <span class="text-foreground">
                                    página {{ categoryGroup.pagination.currentPage }} de {{ categoryGroup.pagination.totalPages }}
                                </span>
                                <button
                                    class="rounded-md border border-border px-3 py-1 text-foreground transition-colors hover:bg-accent disabled:cursor-not-allowed disabled:opacity-50"
                                    @click="changeCategoryPage(categoryGroup.category.id, 'next')"
                                    :disabled="categoryGroup.pagination.currentPage === categoryGroup.pagination.totalPages"
                                >
                                    próxima
                                </button>
                            </div>
                            <button
                                @click="openModalForCategory(categoryGroup.category.id)"
                                class="mx-3 mb-3 mt-2 flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-dashed border-border p-2.5 text-center text-sm font-medium text-muted-foreground transition-all duration-200 hover:border-accent-primary hover:bg-accent-primary/5 hover:text-accent-primary"
                            >
                                <PlusCircle class="!h-4 !w-4" />
                                <span class="text-sm font-medium">adicionar em categoria</span>
                            </button>
                        </div>
                    </div>
                </template>
                <div
                    v-else
                    class="col-span-full flex flex-col items-center justify-center rounded-xl border border-dashed border-border p-10 text-center text-muted-foreground"
                >
                    <p class="text-base font-medium">nenhuma subcategoria encontrada!</p>
                    <p class="text-sm text-muted-foreground">clique em "nova subcategoria" para criar sua primeira subcategoria.</p>
                </div>
            </div>
            <PaginationControls
                v-if="pagination"
                :current="pagination.current_page"
                :last="pagination.last_page"
                :from="pagination.from"
                :to="pagination.to"
                :total="pagination.total"
                :entity-label="pagination.total === 1 ? 'categoria' : 'categorias'"
                @change="goToPage"
            />
        </div>

        <!-- Modal de Criação -->
        <UiDialog v-model:open="isModalOpen">
            <DraggableDialogContent class="max-h-[80vh] w-full max-w-6xl overflow-y-auto">
                <template #header>
                    <DialogTitle class="text-lg font-semibold text-foreground">criar nova subcategoria</DialogTitle>
                    <DialogDescription class="sr-only">preencha os dados para criar uma nova subcategoria</DialogDescription>
                </template>
                <CreateSubcategoryForm
                    :reset-form="shouldResetForm"
                    :preselected-category-id="preselectedCategoryId || undefined"
                    @success="handleSubcategoryCreated"
                    @cancel="closeModal"
                    @reset-complete="handleResetComplete"
                />
            </DraggableDialogContent>
        </UiDialog>

        <!-- Modal de Edição -->
        <UiDialog v-model:open="isEditDialogOpen">
            <DraggableDialogContent class="max-h-[80vh] w-full max-w-6xl overflow-y-auto">
                <template #header>
                    <DialogTitle class="text-lg font-semibold text-foreground">editar subcategoria</DialogTitle>
                    <DialogDescription class="sr-only">edite os dados da subcategoria</DialogDescription>
                </template>
                <CreateSubcategoryForm
                    v-if="subcategoryToEdit"
                    :subcategory="subcategoryToEdit"
                    @success="handleSubcategoryUpdated"
                    @cancel="closeEditModal"
                />
            </DraggableDialogContent>
        </UiDialog>

        <!-- Modal de Confirmação de Exclusão -->
        <UiDialog v-model:open="isDeleteDialogOpen">
            <DraggableDialogContent class="w-full max-w-md">
                <template #header>
                    <DialogTitle class="text-lg font-semibold text-foreground">confirmar exclusão</DialogTitle>
                    <DialogDescription class="sr-only">confirme a exclusão da subcategoria</DialogDescription>
                </template>
                <div class="flex flex-col gap-4">
                    <p class="text-sm text-muted-foreground">tem certeza que deseja excluir esta subcategoria? esta ação não pode ser desfeita.</p>
                    <div class="flex items-center justify-end gap-2 pt-2">
                        <UiButton
                            variant="outline"
                            @click="isDeleteDialogOpen = false"
                            class="cursor-pointer rounded-md border-border bg-card p-2 text-foreground hover:bg-accent"
                        >
                            cancelar
                        </UiButton>
                        <UiButton
                            variant="destructive"
                            @click="confirmDelete"
                            class="cursor-pointer rounded-md bg-destructive p-2 text-destructive-foreground hover:bg-destructive/90"
                        >
                            excluir
                        </UiButton>
                    </div>
                </div>
            </DraggableDialogContent>
        </UiDialog>

        <!-- Modal de Exclusão em Lote -->
        <UiDialog v-model:open="isBulkDeleteDialogOpen">
            <DraggableDialogContent class="w-full max-w-md">
                <template #header>
                    <DialogTitle class="text-lg font-semibold text-foreground">confirmar exclusão em lote</DialogTitle>
                    <DialogDescription class="sr-only">confirme a exclusão das subcategorias selecionadas</DialogDescription>
                </template>
                <div class="flex flex-col gap-4">
                    <p class="text-sm text-muted-foreground">
                        tem certeza que deseja excluir {{ selectedIds.length }} {{ selectedIds.length === 1 ? 'subcategoria' : 'subcategorias' }}?
                        esta ação não pode ser desfeita.
                    </p>
                    <div class="flex items-center justify-end gap-2 pt-2">
                        <UiButton
                            variant="outline"
                            @click="isBulkDeleteDialogOpen = false"
                            class="cursor-pointer rounded-md border-border bg-card p-2 text-foreground hover:bg-accent"
                        >
                            cancelar
                        </UiButton>
                        <UiButton
                            variant="destructive"
                            @click="confirmBulkDeleteAction"
                            class="cursor-pointer rounded-md bg-destructive p-2 text-destructive-foreground hover:bg-destructive/90"
                        >
                            excluir {{ selectedIds.length }}
                        </UiButton>
                    </div>
                </div>
            </DraggableDialogContent>
        </UiDialog>

        <SelectionToolbar
            :visible="isSelectionMode"
            :count="selectedIds.length"
            singular-label="subcategoria selecionada"
            plural-label="subcategorias selecionadas"
            @select-all="selectAllSubcategories"
            @clear="clearSelection"
        >
            <template #actions>
                <button
                    @click="confirmBulkDelete"
                    class="flex items-center gap-2 rounded-md bg-destructive px-4 py-2 text-destructive-foreground transition-colors hover:bg-destructive/90"
                >
                    <Trash2 class="!h-4 !w-4" />
                    excluir ({{ selectedIds.length }})
                </button>
            </template>
        </SelectionToolbar>
    </MainLayout>
</template>

<script lang="ts">
import SelectionToolbar from '@/components/common/SelectionToolbar.vue';
import PageHeader from '@/components/common/PageHeader.vue';
import PaginationControls from '@/components/common/PaginationControls.vue';
import SearchInput from '@/components/common/SearchInput.vue';
import CreateSubcategoryForm from '@/components/subcategories/CreateSubcategoryForm.vue';
import CategoryCardSkeleton from '@/components/subcategories/CategoryCardSkeleton.vue';
import { Button as UiButton } from '@/components/ui/button';
import { DialogDescription, DialogTitle, Dialog as UiDialog } from '@/components/ui/dialog';
import DraggableDialogContent from '@/components/ui/dialog/DraggableDialogContent.vue';
import { useToast } from '@/components/ui/toast';
import MainLayout from '@/layouts/MainLayout.vue';
import selectionModeMixin from '@/mixins/selectionModeMixin';
import { apiBulkDelete, apiDelete, apiGet } from '@/utils/api';
import { Head as InertiaHead } from '@inertiajs/vue3';
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
    Pencil,
    Plane,
    Plus,
    PlusCircle,
    Search,
    Settings,
    ShoppingBag,
    ShoppingCart,
    Star,
    Stethoscope,
    Target,
    Trash2,
    TreePine,
    Trophy,
    UtensilsCrossed,
    Wallet,
    Wifi,
    Zap,
} from 'lucide-vue-next';
import type { Component } from 'vue';

interface Subcategory {
    id: number;
    name: string;
    slug: string;
    category_id: number;
    icon: string | null;
    color: string;
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
    name: 'Subcategories',
    mixins: [selectionModeMixin],
    components: {
        PageHeader,
        SearchInput,
        PaginationControls,
        SelectionToolbar,
        MainLayout,
        InertiaHead,
        Search,
        Target,
        Plus,
        ShoppingBag,
        Pencil,
        Trash2,
        PlusCircle,
        Star,
        UiDialog,
        DialogTitle,
        DialogDescription,
        UiButton,
        DraggableDialogContent,
        CreateSubcategoryForm,
        CategoryCardSkeleton,
    },
    setup() {
        const { toast } = useToast();
        return { toast };
    },
    data() {
        return {
            categories: [] as Category[],
            isModalOpen: false,
            isEditDialogOpen: false,
            isDeleteDialogOpen: false,
            isBulkDeleteDialogOpen: false,
            subcategoryToEditId: null as number | null,
            subcategoryToDeleteId: null as number | null,
            loading: true,
            loadingCategories: true,
            shouldResetForm: false,
            searchQuery: '',
            preselectedCategoryId: null as number | null,
            currentPage: 1,
            groupsPerPage: 3,
            perCategoryPageSize: 4,
            categorySearchQueries: {} as Record<number, string>,
            categoryPagination: {} as Record<number, number>,
            pagination: null as {
                total: number;
                per_page: number;
                current_page: number;
                last_page: number;
                from: number | null;
                to: number | null;
            } | null,
        };
    },
    computed: {
        subcategoryToEdit(): Subcategory | null {
            if (!this.subcategoryToEditId) return null;
            for (const category of this.categories) {
                if (category.subcategories) {
                    const subcategory = category.subcategories.find((s) => s.id === this.subcategoryToEditId);
                    if (subcategory) return subcategory;
                }
            }
            return null;
        },
        allGroupedCategories(): CategoryGroup[] {
            return this.categories.map((category) => {
                const subcategories = category.subcategories || [];
                const searchQuery = this.categorySearchQueries[category.id] || '';
                const filtered = searchQuery.trim()
                    ? subcategories.filter((subcategory) =>
                        subcategory.name.toLowerCase().includes(searchQuery.toLowerCase())
                    )
                    : subcategories;

                const totalSubcategories = filtered.length;
                const totalPages = Math.max(1, Math.ceil(totalSubcategories / this.perCategoryPageSize));
                const requestedPage = this.categoryPagination[category.id] ?? 1;
                const currentPage = Math.min(Math.max(requestedPage, 1), totalPages);
                const start = (currentPage - 1) * this.perCategoryPageSize;
                const paginatedSubcategories = filtered.slice(start, start + this.perCategoryPageSize);

                return {
                    category,
                    subcategories: paginatedSubcategories,
                    totalSubcategories,
                    pagination: {
                        currentPage,
                        totalPages,
                    },
                };
            });
        },
        groupedCategories(): CategoryGroup[] {
            return this.allGroupedCategories;
        },
        totalSubcategories(): number {
            return this.categories.reduce((total, category) => {
                return total + (category.subcategories?.length || 0);
            }, 0);
        },
    },
    mounted() {
        this.loadCategoriesWithSubcategories(1);
    },
    beforeUnmount() {
    },
    methods: {
        async loadCategoriesWithSubcategories(page?: number) {
            try {
                this.loading = true;
                this.loadingCategories = true;

                let url = `/api/categories?per_page=${this.groupsPerPage}&order_direction=asc&page=${page || 1}&with_subcategories=true`;
                if (this.searchQuery.trim()) {
                    url += `&subcategory_search=${encodeURIComponent(this.searchQuery.trim())}`;
                }

                const response = await apiGet(url);

                if (!response.ok) {
                    throw new Error('Failed to load categories with subcategories');
                }

                const data = await response.json();
                this.categories = data.data || [];
                this.categoryPagination = {};

                this.pagination = {
                    total: data.total || 0,
                    per_page: data.per_page || this.groupsPerPage,
                    current_page: data.current_page || 1,
                    last_page: data.last_page || 1,
                    from: data.from || null,
                    to: data.to || null,
                };

                this.currentPage = data.current_page || 1;
            } catch (error) {
                console.error('Error loading categories with subcategories:', error);
                this.toast({
                    title: 'erro ao carregar subcategorias',
                    description: 'não foi possível carregar as subcategorias. tente novamente.',
                    variant: 'destructive',
                });
            } finally {
                this.loading = false;
                this.loadingCategories = false;
            }
        },
        goToPage(page: number) {
            this.loadCategoriesWithSubcategories(page);
        },
        async deleteSubcategory(subcategoryId: number) {
            try {
                const response = await apiDelete(`/api/subcategories/${subcategoryId}`);

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || 'Erro ao deletar subcategoria');
                }

                this.toast({
                    title: 'subcategoria deletada',
                    description: 'a subcategoria foi deletada com sucesso.',
                    variant: 'default',
                });

                await this.loadCategoriesWithSubcategories(this.currentPage);
            } catch (error: any) {
                console.error('Error deleting subcategory:', error);
                this.toast({
                    title: 'erro ao deletar subcategoria',
                    description: error.message || 'erro ao deletar subcategoria. tente novamente.',
                    variant: 'destructive',
                });
            }
        },
        async handleSubcategoryCreated() {
            this.closeModal();
            await this.loadCategoriesWithSubcategories(this.currentPage);
        },
        async handleSubcategoryUpdated() {
            this.closeEditModal();
            await this.loadCategoriesWithSubcategories(this.currentPage);
        },
        openModal() {
            this.preselectedCategoryId = null;
            this.isModalOpen = true;
            this.shouldResetForm = false;
        },
        openModalForCategory(categoryId: number) {
            this.preselectedCategoryId = categoryId;
            this.isModalOpen = true;
            this.shouldResetForm = false;
        },
        closeModal() {
            this.isModalOpen = false;
            this.shouldResetForm = true;
            this.preselectedCategoryId = null;
        },
        handleResetComplete() {
            this.shouldResetForm = false;
        },
        closeEditModal() {
            this.isEditDialogOpen = false;
            this.subcategoryToEditId = null;
        },
        handleEditSubcategory(subcategoryId: number) {
            this.subcategoryToEditId = subcategoryId;
            this.isEditDialogOpen = true;
        },
        handleDeleteSubcategory(subcategoryId: number) {
            this.subcategoryToDeleteId = subcategoryId;
            this.isDeleteDialogOpen = true;
        },
        async confirmDelete() {
            if (this.subcategoryToDeleteId) {
                this.isDeleteDialogOpen = false;
                await this.deleteSubcategory(this.subcategoryToDeleteId);
                this.subcategoryToDeleteId = null;
            }
        },
        handleSearch() {
            this.currentPage = 1;
            this.loadCategoriesWithSubcategories(1);
        },
        handleCategorySearchInput(categoryId: number, query: string) {
            this.categorySearchQueries = {
                ...this.categorySearchQueries,
                [categoryId]: query,
            };
            this.categoryPagination = {
                ...this.categoryPagination,
                [categoryId]: 1,
            };
        },
        clearCategorySearch(categoryId: number) {
            const queries = { ...this.categorySearchQueries };
            delete queries[categoryId];
            this.categorySearchQueries = queries;
            this.categoryPagination = {
                ...this.categoryPagination,
                [categoryId]: 1,
            };
        },
        changeCategoryPage(categoryId: number, direction: 'next' | 'prev') {
            const group = this.allGroupedCategories.find((g) => g.category.id === categoryId);
            if (!group) return;

            const delta = direction === 'next' ? 1 : -1;
            const currentPage = this.categoryPagination[categoryId] ?? 1;
            const nextPage = Math.min(Math.max(currentPage + delta, 1), group.pagination.totalPages);

            if (nextPage !== currentPage) {
                this.categoryPagination = {
                    ...this.categoryPagination,
                    [categoryId]: nextPage,
                };
            }
        },
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
                Trash2,
                Pencil,
                Plus,
                PlusCircle,
                Search,
                Target,
            };
            return availableIcons[iconName] || null;
        },
        toggleSubcategorySelection(subcategoryId: number) {
            this.toggleSelectionItem(subcategoryId);
        },
        selectAllSubcategories() {
            this.selectAllItems();
        },
        confirmBulkDelete() {
            if (this.selectedIds.length === 0) return;
            this.isBulkDeleteDialogOpen = true;
        },
        async confirmBulkDeleteAction() {
            this.isBulkDeleteDialogOpen = false;
            const idsToDelete = [...this.selectedIds];

            if (idsToDelete.length === 0) return;

            try {
                const response = await apiBulkDelete('/api/subcategories/bulk', idsToDelete);

                if (response.ok) {
                    const result = await response.json();
                    const deletedCount = result.deleted_count || idsToDelete.length;

                    this.toast({
                        title: 'subcategorias deletadas',
                        description: `${deletedCount} subcategoria(s) deletada(s) com sucesso.`,
                        variant: 'default',
                    });

                    this.exitSelectionMode();
                    await this.loadCategoriesWithSubcategories(this.currentPage);
                } else {
                    this.toast({
                        title: 'erro ao deletar subcategorias',
                        description: 'não foi possível deletar as subcategorias selecionadas.',
                        variant: 'destructive',
                    });
                }
            } catch (error) {
                console.error('Error deleting subcategories:', error);
                this.toast({
                    title: 'erro ao deletar subcategorias',
                    description: 'ocorreu um erro ao tentar deletar as subcategorias.',
                    variant: 'destructive',
                });
            }
        },
        getSelectionIds() {
            // Coletar IDs de todas as subcategorias de todas as categorias
            const ids: number[] = [];
            this.categories.forEach((category) => {
                if (category.subcategories) {
                    category.subcategories.forEach((subcategory) => {
                        ids.push(subcategory.id);
                    });
                }
            });
            return ids;
        },
    },
};
</script>

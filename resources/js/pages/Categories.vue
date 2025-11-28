<template>
    <InertiaHead title="categorias" />
    <MainLayout :no-sub-nav="true">
        <div class="h-full w-full px-8 py-8">
            <PageHeader title="categorias" description="gerencie como suas transações são classificadas.">
                <template #badge>
                    <span class="rounded-md bg-muted px-2 py-1 text-xs font-semibold text-muted-foreground">
                        {{ pagination?.total ?? categories.length }} Total
                    </span>
                </template>
                <template #actions>
                    <div class="flex flex-col gap-3 md:flex-row md:items-center">
                        <SearchInput
                            v-model="searchQuery"
                            placeholder="buscar categoria..."
                            :debounce="400"
                            wrapper-class="w-full md:w-64"
                            @search="handleSearch"
                        />
                        <div class="flex flex-wrap items-center gap-2">
                            <ViewToggle
                                v-model:view="viewMode"
                                grid-label="grade"
                                table-label="tabela"
                            />
                            <button
                                v-if="!isSelectionMode"
                                @click="toggleSelectionMode"
                                class="flex items-center gap-x-2 rounded-md border border-border bg-card px-4 py-2 text-foreground transition-colors hover:bg-accent"
                                title="selecionar múltiplas categorias (Ctrl + Espaço)"
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
                                nova categoria
                            </button>
                        </div>
                    </div>
                </template>
            </PageHeader>

            <!-- Tabs -->
            <div class="mb-6 flex gap-2 border-b border-border">
                <button
                    @click="activeTab = 'expense'"
                    :class="[
                        'relative px-4 py-2 text-sm font-medium transition-colors',
                        activeTab === 'expense' ? 'text-foreground' : 'text-muted-foreground hover:text-foreground',
                    ]"
                >
                    Despesas
                    <span v-if="activeTab === 'expense'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-red-500"></span>
                </button>
                <button
                    @click="activeTab = 'recept'"
                    :class="[
                        'relative px-4 py-2 text-sm font-medium transition-colors',
                        activeTab === 'recept' ? 'text-foreground' : 'text-muted-foreground hover:text-foreground',
                    ]"
                >
                    Receitas
                    <span v-if="activeTab === 'recept'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-green-500"></span>
                </button>
            </div>

            <!-- Grid de Categorias -->
            <div v-if="viewMode === 'grid'" class="grid grid-cols-1 gap-6 pb-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <template v-if="loading">
                    <CategoryCardSkeleton v-for="n in skeletonCount" :key="`category-skeleton-${n}`" />
                </template>
                <template v-else-if="filteredCategories.length">
                    <div
                        v-for="category in filteredCategories"
                        :key="category.id"
                        class="group relative rounded-xl border border-border bg-card p-6 transition-all duration-300 hover:border-accent"
                        :class="{
                            'cursor-pointer ring-2 ring-accent-primary ring-offset-2 ring-offset-card':
                                isSelectionMode && selectedIds.includes(category.id),
                            'cursor-pointer': isSelectionMode && !selectedIds.includes(category.id),
                            'hover:-translate-y-1': !isSelectionMode,
                        }"
                        @click="isSelectionMode ? toggleCategorySelection(category.id) : null"
                    >
                        <!-- Botões de ação -->
                        <div v-if="!isSelectionMode" class="absolute right-3 top-3 flex gap-2 opacity-0 transition-opacity group-hover:opacity-100">
                            <button
                                @click.stop
                                @click="handleEditCategory(category.id)"
                                class="rounded-md border border-border bg-card p-1.5 text-muted-foreground transition-colors hover:border-accent-primary hover:text-foreground"
                                title="Editar"
                            >
                                <Pencil class="h-4 w-4" />
                            </button>
                            <button
                                @click.stop
                                @click="handleDeleteCategory(category.id)"
                                class="rounded-md border border-border bg-card p-1.5 text-muted-foreground transition-colors hover:border-destructive hover:text-destructive"
                                title="Excluir"
                            >
                                <Trash2 class="h-4 w-4" />
                            </button>
                        </div>

                        <!-- Ícone -->
                        <div
                            class="relative mb-4 flex h-12 w-12 items-center justify-center rounded-xl"
                            :style="{ backgroundColor: category.color + '20' }"
                        >
                            <component
                                v-if="getIconComponent(category.icon)"
                                :is="getIconComponent(category.icon)"
                                class="h-6 w-6"
                                :style="{ color: category.color }"
                            />
                            <ShoppingBag v-else class="h-6 w-6" :style="{ color: category.color }" />
                            <div
                                v-if="isSelectionMode"
                                class="absolute -left-2 -top-2 flex h-6 w-6 items-center justify-center rounded-full border-2 transition-all"
                                :class="selectedIds.includes(category.id) ? 'border-accent-primary bg-accent-primary' : 'border-border bg-card/80'"
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

                        <!-- Nome -->
                        <div class="mb-1 flex items-center justify-between gap-3">
                            <h3 class="mb-0 truncate text-lg font-bold text-foreground" :title="category.name">
                                {{ category.name }}
                            </h3>
                            <span class="mb-1 whitespace-nowrap font-semibold" :style="{ color: category.color }"> R$ 0,00 </span>
                        </div>

                        <!-- Informações (simuladas) -->
                        <div class="space-y-2">
                            <div class="flex flex-row items-center text-sm gap-x-1">
                                <span class="text-xs font-bold" :style="{ color: category.color }">0</span>
                                <span class="text-xs text-muted-foreground">transações este mês</span>
                            </div>
                            <div class="mt-3">
                                <div class="mb-1 flex items-center justify-between text-xs">
                                    <span class="text-muted-foreground">impacto no orçamento</span>
                                    <span class="text-foreground">0%</span>
                                </div>
                                <div class="h-2 w-full overflow-hidden rounded-full bg-muted">
                                    <div
                                        class="h-full rounded-full transition-all"
                                        :style="{
                                            width: '0%',
                                            backgroundColor: category.color,
                                        }"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <div
                    v-else
                    class="col-span-full flex flex-col items-center justify-center rounded-xl border border-dashed border-border p-10 text-center text-muted-foreground"
                >
                    <p class="text-base font-medium">nenhuma categoria encontrada!</p>
                    <p class="text-sm text-muted-foreground">clique abaixo para criar sua primeira categoria.</p>
                </div>

                <!-- Card de Criar Nova -->
                <button
                    class="wallet-add-button flex h-[260px] flex-col items-center justify-center rounded-3xl border-2 border-dashed dark:border-white/10 light:border-border dark:text-gray-400 transition-all hover:bg-white/5 dark:hover:text-white light:hover:text-[#6e6e6e]"
                    :class="{
                        'cursor-not-allowed opacity-50': loading,
                    }"
                    :style="hoverBorderStyle"
                    type="button"
                    :disabled="loading"
                    @click="openModal"
                >
                    <div
                        class="wallet-add-icon mb-4 flex h-16 w-16 items-center justify-center rounded-full border border-white/5 dark:bg-white/5 light:bg-black/5 transition-colors"
                        :style="walletAddIconStyle"
                    >
                        <Plus class="h-8 w-8" />
                    </div>
                    <span class="text-sm font-medium">adicionar categoria</span>
                </button>
            </div>

            <!-- Tabela de Categorias -->
            <div v-else-if="viewMode === 'table'" class="pb-4">
                <template v-if="loading">
                    <div class="rounded-lg border border-border bg-card p-8">
                        <div class="flex items-center justify-center">
                            <div class="text-center">
                                <div class="h-8 w-8 animate-spin rounded-full border-2 border-accent-primary border-t-transparent mx-auto mb-4"></div>
                                <p class="text-muted-foreground">carregando categorias...</p>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <CategoriesTable
                        :categories="filteredCategories"
                        :is-selection-mode="isSelectionMode"
                        :selected-ids="selectedIds"
                        :all-selected="selectedIds.length > 0 && selectedIds.length === filteredCategories.length"
                        @edit="handleEditCategory"
                        @delete="handleDeleteCategory"
                        @toggle-selection="toggleCategorySelection"
                        @toggle-all="selectedIds.length === filteredCategories.length ? clearSelection() : selectAllCategories()"
                    />
                </template>
            </div>

            <PaginationControls
                v-if="pagination"
                :current="pagination.current_page"
                :last="pagination.last_page"
                :from="pagination.from"
                :to="pagination.to"
                :total="pagination.total"
                entity-label="categorias"
                @change="goToPage"
            />
        </div>

        <!-- Modal de Criação -->
        <UiDialog v-model:open="isModalOpen">
            <DraggableDialogContent class="max-h-[80vh] w-full max-w-6xl overflow-y-auto">
                <template #header>
                    <DialogTitle class="text-lg font-semibold text-foreground">criar nova categoria</DialogTitle>
                    <DialogDescription class="sr-only">preencha os dados para criar uma nova categoria</DialogDescription>
                </template>
                <CreateCategoryForm
                    :reset-form="shouldResetForm"
                    @success="handleCategoryCreated"
                    @cancel="closeModal"
                    @reset-complete="handleResetComplete"
                />
            </DraggableDialogContent>
        </UiDialog>

        <!-- Modal de Edição -->
        <UiDialog v-model:open="isEditDialogOpen">
            <DraggableDialogContent class="max-h-[80vh] w-full max-w-6xl overflow-y-auto">
                <template #header>
                    <DialogTitle class="text-lg font-semibold text-foreground">editar categoria</DialogTitle>
                    <DialogDescription class="sr-only">edite os dados da categoria</DialogDescription>
                </template>
                <CreateCategoryForm v-if="categoryToEdit" :category="categoryToEdit" @success="handleCategoryUpdated" @cancel="closeEditModal" />
            </DraggableDialogContent>
        </UiDialog>

        <!-- Modal de Confirmação de Exclusão -->
        <UiDialog v-model:open="isDeleteDialogOpen">
            <DraggableDialogContent class="w-full max-w-md">
                <template #header>
                    <DialogTitle class="text-lg font-semibold text-foreground">confirmar exclusão</DialogTitle>
                    <DialogDescription class="sr-only">confirme a exclusão da categoria</DialogDescription>
                </template>
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-2">
                        <p class="text-sm text-muted-foreground text-justify">tem certeza que deseja excluir esta categoria?</p>
                        <p class="text-sm text-muted-foreground text-justify">todas as subcategorias vinculadas também serão excluídas. esta ação não pode ser desfeita.</p>
                    </div>
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
                    <DialogDescription class="sr-only">confirme a exclusão das categorias selecionadas</DialogDescription>
                </template>
                <div class="flex flex-col gap-4">
                    <p class="text-sm text-muted-foreground">
                        tem certeza que deseja excluir {{ selectedIds.length }} {{ selectedIds.length === 1 ? 'categoria' : 'categorias' }}? esta ação
                        não pode ser desfeita.
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
            singular-label="categoria selecionada"
            plural-label="categorias selecionadas"
            @select-all="selectAllCategories"
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
import ViewToggle from '@/components/common/ViewToggle.vue';
import CategoryCardSkeleton from '@/components/categories/CategoryCardSkeleton.vue';
import CreateCategoryForm from '@/components/categories/CreateCategoryForm.vue';
import CategoriesTable from '@/components/categories/CategoriesTable.vue';
import { Button as UiButton } from '@/components/ui/button';
import { DialogDescription, DialogTitle, Dialog as UiDialog } from '@/components/ui/dialog';
import DraggableDialogContent from '@/components/ui/dialog/DraggableDialogContent.vue';
import { useToast } from '@/components/ui/toast';
import MainLayout from '@/layouts/MainLayout.vue';
import selectionModeMixin from '@/mixins/selectionModeMixin';
import { useUserSettings } from '@/composables/useUserSettings';
import { apiBulkDelete, apiDelete, apiGet } from '@/utils/api';
import { getAccentPrimaryWithOpacity } from '@/utils/colors';
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
    Settings,
    ShoppingBag,
    ShoppingCart,
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
import { computed, onBeforeUnmount, onMounted, ref, type Component } from 'vue';

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
    name: 'Categories',
    mixins: [selectionModeMixin],
    components: {
        PageHeader,
        SearchInput,
        PaginationControls,
        SelectionToolbar,
        ViewToggle,
        MainLayout,
        InertiaHead,
        Plus,
        Pencil,
        Trash2,
        ShoppingBag,
        Target,
        UiDialog,
        DialogTitle,
        DialogDescription,
        UiButton,
        DraggableDialogContent,
        CreateCategoryForm,
        CategoryCardSkeleton,
        CategoriesTable,
    },
    setup() {
        const { toast } = useToast();
        const { settings } = useUserSettings();
        const colorUpdateKey = ref(0);

        const hoverBorderStyle = computed(() => {
            void settings.accent_color;
            void document.documentElement.classList.contains('dark');
            void colorUpdateKey.value;

            const accentColor = getComputedStyle(document.documentElement).getPropertyValue('--accent-primary').trim();

            if (!accentColor || accentColor === '#6366f1') {
                return {
                    '--hover-border-color': getAccentPrimaryWithOpacity(0.5, 'rgba(99, 102, 241, 0.5)'),
                };
            }

            return {
                '--hover-border-color': getAccentPrimaryWithOpacity(1),
            };
        });

        const walletAddIconStyle = computed(() => {
            void settings.accent_color;
            void colorUpdateKey.value;
            return {
                '--hover-icon-bg': getAccentPrimaryWithOpacity(0.2),
            };
        });

        const handleAccentColorChange = () => {
            colorUpdateKey.value++;
        };

        onMounted(() => {
            window.addEventListener('accent-color-changed', handleAccentColorChange);
            setTimeout(() => {
                colorUpdateKey.value++;
            }, 100);
        });

        onBeforeUnmount(() => {
            window.removeEventListener('accent-color-changed', handleAccentColorChange);
        });

        return { toast, hoverBorderStyle, walletAddIconStyle };
    },
    data() {
        return {
            categories: [] as Category[],
            isModalOpen: false,
            isEditDialogOpen: false,
            isDeleteDialogOpen: false,
            isBulkDeleteDialogOpen: false,
            categoryToEditId: null as number | null,
            categoryToDeleteId: null as number | null,
            loading: false,
            shouldResetForm: false,
            pagination: null as {
                total: number;
                per_page: number;
                current_page: number;
                last_page: number;
                from: number | null;
                to: number | null;
            } | null,
            currentPage: 1,
            perPage: 10,
            activeTab: 'expense' as 'expense' | 'recept',
            searchQuery: '',
            viewMode: 'grid' as 'grid' | 'table',
        };
    },
    computed: {
        categoryToEdit(): Category | null {
            if (!this.categoryToEditId) return null;
            return this.categories.find((c) => c.id === this.categoryToEditId) || null;
        },
        filteredCategories(): Category[] {
            return this.categories.filter((category) => category.type === this.activeTab);
        },
        skeletonCount(): number {
            return Math.min(this.perPage, 8);
        },
    },
    watch: {
        activeTab() {
            if (this.isSelectionMode) {
                this.clearSelection();
            }
            this.currentPage = 1;
            this.loadCategories(1);
        },
    },
    mounted() {
        this.loadCategories();
    },
    methods: {
        async fetchCategories(page?: number) {
            const targetPage = page ?? this.currentPage;
            try {
                this.loading = true;

                let url = `/api/categories?page=${targetPage}&per_page=${this.perPage}`;
                if (this.searchQuery.trim()) {
                    url += `&search=${encodeURIComponent(this.searchQuery.trim())}`;
                }
                if (this.activeTab) {
                    url += `&type=${this.activeTab}`;
                }

                const response = await apiGet(url);

                if (!response.ok) {
                    throw new Error('Failed to load categories');
                }

                const data = await response.json();
                this.categories = data.data;
                this.pagination = {
                    total: data.total,
                    per_page: data.per_page,
                    current_page: data.current_page,
                    last_page: data.last_page,
                    from: data.from,
                    to: data.to,
                };
                this.currentPage = data.current_page;
            } catch (error) {
                console.error('Error loading categories:', error);
                this.toast({
                    title: 'erro ao carregar categorias',
                    description: 'não foi possível carregar as categorias. tente novamente.',
                    variant: 'destructive',
                });
            } finally {
                this.loading = false;
            }
        },
        async loadCategories(page: number = 1) {
            await this.fetchCategories(page);
        },
        async deleteCategory(categoryId: number) {
            try {
                const response = await apiDelete(`/api/categories/${categoryId}`);

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || 'Erro ao deletar categoria');
                }

                this.toast({
                    title: 'categoria deletada',
                    description: 'a categoria foi deletada com sucesso.',
                    variant: 'default',
                });

                await this.loadCategories(this.currentPage);
            } catch (error: any) {
                console.error('Error deleting category:', error);
                this.toast({
                    title: 'erro ao deletar categoria',
                    description: error.message || 'erro ao deletar categoria. tente novamente.',
                    variant: 'destructive',
                });
            }
        },
        goToPage(page: number) {
            if (page >= 1 && page <= (this.pagination?.last_page || 1)) {
                this.loadCategories(page);
            }
        },
        async handleCategoryCreated() {
            this.closeModal();
            await this.loadCategories(this.currentPage);
        },
        async handleCategoryUpdated() {
            this.closeEditModal();
            await this.loadCategories(this.currentPage);
        },
        openModal() {
            this.isModalOpen = true;
            this.shouldResetForm = false;
        },
        closeModal() {
            this.isModalOpen = false;
            this.shouldResetForm = true;
        },
        handleResetComplete() {
            this.shouldResetForm = false;
        },
        closeEditModal() {
            this.isEditDialogOpen = false;
            this.categoryToEditId = null;
        },
        handleEditCategory(categoryId: number) {
            this.categoryToEditId = categoryId;
            this.isEditDialogOpen = true;
        },
        handleDeleteCategory(categoryId: number) {
            this.categoryToDeleteId = categoryId;
            this.isDeleteDialogOpen = true;
        },
        async confirmDelete() {
            if (this.categoryToDeleteId) {
                this.isDeleteDialogOpen = false;
                await this.deleteCategory(this.categoryToDeleteId);
                this.categoryToDeleteId = null;
            }
        },
        handleSearch() {
            this.currentPage = 1;
            this.loadCategories(1);
        },
        getIconComponent(iconName: string | null): Component | null {
            if (!iconName) return null;
            // Objeto com os ícones disponíveis para acesso dinâmico
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
        toggleCategorySelection(categoryId: number) {
            this.toggleSelectionItem(categoryId);
        },
        selectAllCategories() {
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
                const response = await apiBulkDelete('/api/categories/bulk', idsToDelete);

                if (response.ok) {
                    const result = await response.json();
                    const deletedCount = result.deleted_count || idsToDelete.length;

                    this.toast({
                        title: 'categorias deletadas',
                        description: `${deletedCount} categoria(s) deletada(s) com sucesso.`,
                        variant: 'default',
                    });

                    this.exitSelectionMode();
                    await this.loadCategories(this.currentPage);
                } else {
                    this.toast({
                        title: 'erro ao deletar categorias',
                        description: 'não foi possível deletar as categorias selecionadas.',
                        variant: 'destructive',
                    });
                }
            } catch (error) {
                console.error('Error deleting categories:', error);
                this.toast({
                    title: 'erro ao deletar categorias',
                    description: 'ocorreu um erro ao tentar deletar as categorias.',
                    variant: 'destructive',
                });
            }
        },
        getSelectionIds() {
            return this.filteredCategories.map((category) => category.id);
        },
    },
};
</script>
<style scoped>
.wallet-add-button:hover {
    border-color: var(--hover-border-color) !important;
}

.wallet-add-icon:hover {
    background-color: var(--hover-icon-bg) !important;
}
</style>

<template>
    <InertiaHead title="Categorias" />
    <MainLayout :no-sub-nav="true">
        <div class="h-full w-full px-8 py-8">
            <PageHeader title="categorias" description="gerencie como suas transações são classificadas.">
                <template #badge>
                    <span class="rounded-md bg-white/10 px-2 py-1 text-xs font-semibold text-gray-300">
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
                            <button
                                v-if="!isSelectionMode"
                                @click="toggleSelectionMode"
                                class="flex items-center gap-x-2 rounded-md border border-[#2F2F2F] bg-[#1E1E1E] px-4 py-2 text-white transition-colors hover:bg-[#313131]"
                                title="selecionar múltiplas categorias (Ctrl + Espaço)"
                            >
                                <Target class="!h-4 !w-4" />
                                <span>selecionar</span>
                                <span class="hidden rounded border border-[#2F2F2F] px-1 py-0.5 text-[11px] uppercase text-[#8c8c8c] lg:inline-flex">
                                    Ctrl + Espaço
                                </span>
                            </button>
                            <button
                                v-else
                                @click="exitSelectionMode"
                                class="flex items-center gap-x-2 rounded-md border border-[#2F2F2F] bg-[#1E1E1E] px-4 py-2 text-white transition-colors hover:bg-[#313131]"
                            >
                                cancelar seleção
                            </button>
                            <button
                                @click="openModal"
                                class="flex items-center gap-x-2 rounded-md border border-[#2F2F2F] bg-[#1E1E1E] px-4 py-2 text-white transition-colors hover:bg-[#313131]"
                            >
                                <Plus class="!h-4 !w-4" />
                                nova categoria
                            </button>
                        </div>
                    </div>
                </template>
            </PageHeader>

            <!-- Tabs -->
            <div class="mb-6 flex gap-2 border-b border-[#2F2F2F]">
                <button
                    @click="activeTab = 'expense'"
                    :class="[
                        'relative px-4 py-2 text-sm font-medium transition-colors',
                        activeTab === 'expense' ? 'text-white' : 'text-[#767676] hover:text-white',
                    ]"
                >
                    Despesas
                    <span v-if="activeTab === 'expense'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-red-500"></span>
                </button>
                <button
                    @click="activeTab = 'recept'"
                    :class="[
                        'relative px-4 py-2 text-sm font-medium transition-colors',
                        activeTab === 'recept' ? 'text-white' : 'text-[#767676] hover:text-white',
                    ]"
                >
                    Receitas
                    <span v-if="activeTab === 'recept'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-green-500"></span>
                </button>
            </div>

            <!-- Grid de Categorias -->
            <div class="grid grid-cols-1 gap-6 pb-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <template v-if="loading">
                    <CategoryCardSkeleton v-for="n in skeletonCount" :key="`category-skeleton-${n}`" />
                </template>
                <template v-else-if="filteredCategories.length">
                    <div
                        v-for="category in filteredCategories"
                        :key="category.id"
                        class="group relative rounded-xl border border-[#2F2F2F] bg-[#131316] p-6 transition-all duration-300 hover:border-[#525151]"
                        :class="{
                            'cursor-pointer ring-2 ring-[#6965f2] ring-offset-2 ring-offset-[#131316]':
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
                                class="rounded-md border border-[#2F2F2F] bg-[#1E1E1E] p-1.5 text-[#767676] transition-colors hover:border-[#3800D8] hover:text-white"
                                title="Editar"
                            >
                                <Pencil class="h-4 w-4" />
                            </button>
                            <button
                                @click.stop
                                @click="handleDeleteCategory(category.id)"
                                class="rounded-md border border-[#2F2F2F] bg-[#1E1E1E] p-1.5 text-[#767676] transition-colors hover:border-red-500 hover:text-red-400"
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
                                :class="selectedIds.includes(category.id) ? 'border-[#6965f2] bg-[#6965f2]' : 'border-white/30 bg-[#131316]/80'"
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
                            <h3 class="mb-0 truncate text-lg font-bold text-white" :title="category.name">
                                {{ category.name }}
                            </h3>
                            <span class="mb-1 whitespace-nowrap font-semibold" :style="{ color: category.color }"> R$ 0,00 </span>
                        </div>

                        <!-- Informações (simuladas) -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-xs text-[#767676]">12 transações este mês</span>
                            </div>
                            <div class="mt-3">
                                <div class="mb-1 flex items-center justify-between text-xs">
                                    <span class="text-[#767676]">impacto no orçamento</span>
                                    <span class="text-white">0%</span>
                                </div>
                                <div class="h-2 w-full overflow-hidden rounded-full bg-[#1E1E1E]">
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
                    class="col-span-full flex flex-col items-center justify-center rounded-xl border border-dashed border-white/10 p-10 text-center text-gray-400"
                >
                    <p class="text-base font-medium">nenhuma categoria encontrada!</p>
                    <p class="text-sm text-gray-500">clique abaixo para criar sua primeira categoria.</p>
                </div>

                <!-- Card de Criar Nova -->
                <button
                    class="flex h-full min-h-[200px] flex-col items-center justify-center rounded-xl border-2 border-dashed border-white/10 text-gray-400 transition-all hover:border-indigo-500/50 hover:bg-white/5 hover:text-white"
                    :class="{
                        'cursor-not-allowed opacity-50': loading,
                    }"
                    type="button"
                    :disabled="loading"
                    @click="openModal"
                >
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full border border-white/5 bg-white/5 transition-colors">
                        <Plus class="h-8 w-8" />
                    </div>
                    <span class="text-sm font-medium">adicionar categoria</span>
                </button>
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
                    <DialogTitle class="text-lg font-semibold text-white">criar nova categoria</DialogTitle>
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
                    <DialogTitle class="text-lg font-semibold text-white">editar categoria</DialogTitle>
                    <DialogDescription class="sr-only">edite os dados da categoria</DialogDescription>
                </template>
                <CreateCategoryForm v-if="categoryToEdit" :category="categoryToEdit" @success="handleCategoryUpdated" @cancel="closeEditModal" />
            </DraggableDialogContent>
        </UiDialog>

        <!-- Modal de Confirmação de Exclusão -->
        <UiDialog v-model:open="isDeleteDialogOpen">
            <DraggableDialogContent class="w-full max-w-md">
                <template #header>
                    <DialogTitle class="text-lg font-semibold text-white">confirmar exclusão</DialogTitle>
                    <DialogDescription class="sr-only">confirme a exclusão da categoria</DialogDescription>
                </template>
                <div class="flex flex-col gap-4">
                    <p class="text-sm text-[#B6B6B6]">tem certeza que deseja excluir esta categoria? esta ação não pode ser desfeita.</p>
                    <div class="flex items-center justify-end gap-2 pt-2">
                        <UiButton
                            variant="outline"
                            @click="isDeleteDialogOpen = false"
                            class="cursor-pointer rounded-md border-[#2F2F2F] bg-[#1E1E1E] p-2 text-white hover:bg-[#313131]"
                        >
                            cancelar
                        </UiButton>
                        <UiButton
                            variant="destructive"
                            @click="confirmDelete"
                            class="cursor-pointer rounded-md bg-red-600 p-2 text-white hover:bg-red-700"
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
                    <DialogTitle class="text-lg font-semibold text-white">confirmar exclusão em lote</DialogTitle>
                    <DialogDescription class="sr-only">confirme a exclusão das categorias selecionadas</DialogDescription>
                </template>
                <div class="flex flex-col gap-4">
                    <p class="text-sm text-[#B6B6B6]">
                        tem certeza que deseja excluir {{ selectedIds.length }} {{ selectedIds.length === 1 ? 'categoria' : 'categorias' }}? esta ação
                        não pode ser desfeita.
                    </p>
                    <div class="flex items-center justify-end gap-2 pt-2">
                        <UiButton
                            variant="outline"
                            @click="isBulkDeleteDialogOpen = false"
                            class="cursor-pointer rounded-md border-[#2F2F2F] bg-[#1E1E1E] p-2 text-white hover:bg-[#313131]"
                        >
                            cancelar
                        </UiButton>
                        <UiButton
                            variant="destructive"
                            @click="confirmBulkDeleteAction"
                            class="cursor-pointer rounded-md bg-red-600 p-2 text-white hover:bg-red-700"
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
                    class="flex items-center gap-2 rounded-md bg-red-600 px-4 py-2 text-white transition-colors hover:bg-red-700"
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
import CategoryCardSkeleton from '@/components/categories/CategoryCardSkeleton.vue';
import CreateCategoryForm from '@/components/categories/CreateCategoryForm.vue';
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
import type { Component } from 'vue';

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

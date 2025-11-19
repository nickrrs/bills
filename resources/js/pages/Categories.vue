<template>
    <InertiaHead title="Categorias" />
    <MainLayout :no-sub-nav="true">
        <div class="w-full h-full px-8 py-8">
            <div class="w-full flex items-center justify-between mb-4">
                <div class="flex flex-col gap-1">
                    <div class="flex flex-inline items-center gap-2">
                        <h1 class="text-white text-3xl font-bold">categorias</h1>
                        <span class="text-xs font-semibold bg-white/10 text-gray-300 px-2 py-1 rounded-md">
                            {{ pagination?.total ?? categories.length }} Total
                        </span>
                    </div>
                    <span class="text-sm text-[#B6B6B6]">gerencie como suas transações são classificadas.</span>
                </div>
                <div class="flex flex-row items-center gap-2">
                    <!-- Campo de busca -->
                    <div class="relative max-w-md">
                        <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 !h-5 !w-5 text-[#767676]" />
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="buscar categoria..."
                            class="w-full pl-10 pr-4 py-2.5 bg-[#131316] border border-[#2F2F2F] rounded-md text-white text-sm placeholder-[#767676] focus:outline-none focus:ring-2 focus:ring-[#3800D8] focus:border-transparent transition-all"
                            @input="handleSearchInput"
                        />
                        <button
                            v-if="searchQuery"
                            @click="clearSearch"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-[#767676] hover:text-white transition-colors"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <button
                        v-if="!isSelectionMode"
                        @click="toggleSelectionMode"
                        class="flex items-center gap-x-2 px-4 py-2 bg-[#1E1E1E] border border-[#2F2F2F] text-white hover:bg-[#313131] rounded-md transition-colors"
                        title="selecionar múltiplas categorias (Ctrl + Espaço)"
                    >
                        <Target class="!h-4 !w-4" />
                        <span>selecionar</span>
                        <span class="text-[11px] uppercase text-[#8c8c8c] border border-[#2F2F2F] rounded px-1 py-0.5 hidden lg:inline-flex">Ctrl + Espaço</span>
                    </button>
                    <button
                        v-else
                        @click="exitSelectionMode"
                        class="flex items-center gap-x-2 px-4 py-2 bg-[#1E1E1E] border border-[#2F2F2F] text-white hover:bg-[#313131] rounded-md transition-colors"
                    >
                        cancelar seleção
                    </button>
                    <button
                        @click="openModal"
                        class="flex items-center gap-x-2 px-4 py-2 bg-[#1E1E1E] border border-[#2F2F2F] text-white hover:bg-[#313131] rounded-md transition-colors"
                    >
                        <Plus class="!h-4 !w-4" />
                        nova categoria
                    </button>
                </div>
            </div>

            <!-- Tabs -->
            <div class="flex gap-2 mb-6 border-b border-[#2F2F2F]">
                <button
                    @click="activeTab = 'expense'"
                    :class="[
                        'px-4 py-2 text-sm font-medium transition-colors relative',
                        activeTab === 'expense'
                            ? 'text-white'
                            : 'text-[#767676] hover:text-white'
                    ]"
                >
                    Despesas
                    <span
                        v-if="activeTab === 'expense'"
                        class="absolute bottom-0 left-0 right-0 h-0.5 bg-red-500"
                    ></span>
                </button>
                <button
                    @click="activeTab = 'recept'"
                    :class="[
                        'px-4 py-2 text-sm font-medium transition-colors relative',
                        activeTab === 'recept'
                            ? 'text-white'
                            : 'text-[#767676] hover:text-white'
                    ]"
                >
                    Receitas
                    <span
                        v-if="activeTab === 'recept'"
                        class="absolute bottom-0 left-0 right-0 h-0.5 bg-green-500"
                    ></span>
                </button>
            </div>

            <!-- Grid de Categorias -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 pb-20">
                <template v-if="filteredCategories.length">
                    <div
                        v-for="category in filteredCategories"
                        :key="category.id"
                        class="relative bg-[#131316] border border-[#2F2F2F] rounded-xl p-6 transition-all duration-300 group"
                        :class="{
                            'ring-2 ring-[#6965f2] ring-offset-2 ring-offset-[#131316] cursor-pointer': isSelectionMode && selectedIds.includes(category.id),
                            'cursor-pointer': isSelectionMode && !selectedIds.includes(category.id),
                            'hover:-translate-y-1': !isSelectionMode
                        }"
                        @click="isSelectionMode ? toggleCategorySelection(category.id) : null"
                    >
                        <!-- Botões de ação -->
                        <div
                            v-if="!isSelectionMode"
                            class="absolute top-3 right-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity"
                        >
                            <button
                                @click.stop
                                @click="handleEditCategory(category.id)"
                                class="p-1.5 rounded-md bg-[#1E1E1E] border border-[#2F2F2F] text-[#767676] hover:text-white hover:border-[#3800D8] transition-colors"
                                title="Editar"
                            >
                                <Pencil class="h-4 w-4" />
                            </button>
                            <button
                                @click.stop
                                @click="handleDeleteCategory(category.id)"
                                class="p-1.5 rounded-md bg-[#1E1E1E] border border-[#2F2F2F] text-[#767676] hover:text-red-400 hover:border-red-500 transition-colors"
                                title="Excluir"
                            >
                                <Trash2 class="h-4 w-4" />
                            </button>
                        </div>

                        <!-- Ícone -->
                        <div
                            class="w-12 h-12 rounded-xl flex items-center justify-center mb-4 relative"
                            :style="{ backgroundColor: category.color + '20' }"
                        >
                            <component
                                v-if="getIconComponent(category.icon)"
                                :is="getIconComponent(category.icon)"
                                class="h-6 w-6"
                                :style="{ color: category.color }"
                            />
                            <ShoppingBag
                                v-else
                                class="h-6 w-6"
                                :style="{ color: category.color }"
                            />
                            <div
                                v-if="isSelectionMode"
                                class="absolute -top-2 -left-2 w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all"
                                :class="selectedIds.includes(category.id) ? 'bg-[#6965f2] border-[#6965f2]' : 'bg-[#131316]/80 border-white/30'"
                            >
                                <svg
                                    v-if="selectedIds.includes(category.id)"
                                    class="w-3 h-3 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>

                        <!-- Nome -->
                        <div class="flex items-center justify-between gap-3 mb-1">
                            <h3 class="text-lg font-bold text-white mb-0 truncate" :title="category.name">
                                {{ category.name }}
                            </h3>
                            <span class="font-semibold whitespace-nowrap mb-1" :style="{ color: category.color }">
                                R$ 850,20
                            </span>
                        </div>

                        <!-- Informações (simuladas) -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-[#767676] text-xs">12 transações este mês</span>
                            </div>
                            <div class="mt-3">
                                <div class="flex items-center justify-between text-xs mb-1">
                                    <span class="text-[#767676]">impacto no orçamento</span>
                                    <span class="text-white">25%</span>
                                </div>
                                <div class="w-full h-2 bg-[#1E1E1E] rounded-full overflow-hidden">
                                    <div
                                        class="h-full rounded-full transition-all"
                                        :style="{
                                            width: '25%',
                                            backgroundColor: category.color
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
                    type="button"
                    @click="openModal"
                >
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full border border-white/5 bg-white/5 transition-colors">
                        <Plus class="h-8 w-8" />
                    </div>
                    <span class="text-sm font-medium">adicionar categoria</span>
                </button>
            </div>

            <!-- Paginação -->
            <div v-if="pagination && pagination.last_page > 1" class="mt-8 flex items-center justify-between">
                <div class="text-sm text-gray-400">
                    <span>mostrando</span>
                    <span class="mx-1 font-medium text-white">{{ pagination.from ?? 0 }}</span>
                    <span>até</span>
                    <span class="mx-1 font-medium text-white">{{ pagination.to ?? 0 }}</span>
                    <span>de</span>
                    <span class="mx-1 font-medium text-white">{{ pagination.total }}</span>
                    <span>categorias</span>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        @click="goToPage(pagination.current_page - 1)"
                        :disabled="pagination.current_page === 1"
                        class="flex h-9 w-9 items-center justify-center rounded-md border border-[#2F2F2F] bg-[#1E1E1E] text-white transition-colors hover:bg-[#313131] disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:bg-[#1E1E1E]"
                    >
                        <ChevronLeft class="h-4 w-4" />
                    </button>

                    <div class="flex items-center gap-1">
                        <button
                            v-for="page in getPageNumbers()"
                            :key="page"
                            @click="goToPage(page)"
                            :class="[
                                'flex h-9 min-w-9 items-center justify-center rounded-md border px-3 text-sm font-medium transition-colors',
                                page === pagination.current_page
                                    ? 'border-indigo-500 bg-indigo-500/20 text-indigo-400'
                                    : 'border-[#2F2F2F] bg-[#1E1E1E] text-white hover:bg-[#313131]'
                            ]"
                        >
                            {{ page }}
                        </button>
                    </div>

                    <button
                        @click="goToPage(pagination.current_page + 1)"
                        :disabled="pagination.current_page === pagination.last_page"
                        class="flex h-9 w-9 items-center justify-center rounded-md border border-[#2F2F2F] bg-[#1E1E1E] text-white transition-colors hover:bg-[#313131] disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:bg-[#1E1E1E]"
                    >
                        <ChevronRight class="h-4 w-4" />
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal de Criação -->
        <UiDialog v-model:open="isModalOpen">
            <DraggableDialogContent class="w-full max-w-6xl max-h-[80vh] overflow-y-auto">
                <template #header>
                    <DialogTitle class="text-white text-lg font-semibold">criar nova categoria</DialogTitle>
                    <DialogDescription class="sr-only">preencha os dados para criar uma nova categoria</DialogDescription>
                </template>
                <CreateCategoryForm :reset-form="shouldResetForm" @success="handleCategoryCreated" @cancel="closeModal" @reset-complete="handleResetComplete" />
            </DraggableDialogContent>
        </UiDialog>

        <!-- Modal de Edição -->
        <UiDialog v-model:open="isEditDialogOpen">
            <DraggableDialogContent class="w-full max-w-6xl max-h-[80vh] overflow-y-auto">
                <template #header>
                    <DialogTitle class="text-white text-lg font-semibold">editar categoria</DialogTitle>
                    <DialogDescription class="sr-only">edite os dados da categoria</DialogDescription>
                </template>
                <CreateCategoryForm
                    v-if="categoryToEdit"
                    :category="categoryToEdit"
                    @success="handleCategoryUpdated"
                    @cancel="closeEditModal"
                />
            </DraggableDialogContent>
        </UiDialog>

        <!-- Modal de Confirmação de Exclusão -->
        <UiDialog v-model:open="isDeleteDialogOpen">
            <DraggableDialogContent class="w-full max-w-md">
                <template #header>
                    <DialogTitle class="text-white text-lg font-semibold">confirmar exclusão</DialogTitle>
                    <DialogDescription class="sr-only">confirme a exclusão da categoria</DialogDescription>
                </template>
                <div class="flex flex-col gap-4">
                    <p class="text-[#B6B6B6] text-sm">
                        tem certeza que deseja excluir esta categoria? esta ação não pode ser desfeita.
                    </p>
                    <div class="flex items-center justify-end gap-2 pt-2">
                        <UiButton
                            variant="outline"
                            @click="isDeleteDialogOpen = false"
                            class="border-[#2F2F2F] bg-[#1E1E1E] text-white hover:bg-[#313131] p-2 rounded-md cursor-pointer"
                        >
                            cancelar
                        </UiButton>
                        <UiButton
                            variant="destructive"
                            @click="confirmDelete"
                            class="bg-red-600 text-white hover:bg-red-700 p-2 rounded-md cursor-pointer"
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
                    <DialogTitle class="text-white text-lg font-semibold">confirmar exclusão em lote</DialogTitle>
                    <DialogDescription class="sr-only">confirme a exclusão das categorias selecionadas</DialogDescription>
                </template>
                <div class="flex flex-col gap-4">
                    <p class="text-[#B6B6B6] text-sm">
                        tem certeza que deseja excluir {{ selectedIds.length }}
                        {{ selectedIds.length === 1 ? 'categoria' : 'categorias' }}? esta ação não pode ser desfeita.
                    </p>
                    <div class="flex items-center justify-end gap-2 pt-2">
                        <UiButton
                            variant="outline"
                            @click="isBulkDeleteDialogOpen = false"
                            class="border-[#2F2F2F] bg-[#1E1E1E] text-white hover:bg-[#313131] p-2 rounded-md cursor-pointer"
                        >
                            cancelar
                        </UiButton>
                        <UiButton
                            variant="destructive"
                            @click="confirmBulkDeleteAction"
                            class="bg-red-600 text-white hover:bg-red-700 p-2 rounded-md cursor-pointer"
                        >
                            excluir {{ selectedIds.length }}
                        </UiButton>
                    </div>
                </div>
            </DraggableDialogContent>
        </UiDialog>

        <Transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 translate-y-4"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 translate-y-4"
        >
            <div
                v-if="isSelectionMode && selectedIds.length > 0"
                class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50 bg-[#1E1E1E] border border-[#2F2F2F] rounded-lg shadow-2xl px-6 py-4 flex items-center gap-4"
            >
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-[#6965f2] flex items-center justify-center">
                        <span class="text-white text-sm font-semibold">{{ selectedIds.length }}</span>
                    </div>
                    <span class="text-white text-sm font-medium">
                        {{ selectedIds.length === 1 ? 'categoria selecionada' : 'categorias selecionadas' }}
                    </span>
                </div>
                <div class="h-6 w-px bg-[#2F2F2F]"></div>
                <button
                    @click="selectAllCategories"
                    class="text-sm text-[#B6B6B6] hover:text-white transition-colors"
                >
                    selecionar todas
                </button>
                <button
                    @click="clearSelection"
                    class="text-sm text-[#B6B6B6] hover:text-white transition-colors"
                >
                    limpar seleção
                </button>
                <div class="h-6 w-px bg-[#2F2F2F]"></div>
                <button
                    @click="confirmBulkDelete"
                    class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors"
                >
                    <Trash2 class="!h-4 !w-4" />
                    excluir ({{ selectedIds.length }})
                </button>
            </div>
        </Transition>
    </MainLayout>
</template>

<script lang="ts">
import type { Component } from 'vue';
import MainLayout from '@/layouts/MainLayout.vue';
import { Head as InertiaHead } from '@inertiajs/vue3';
import {
    Plus,
    Search,
    Pencil,
    Trash2,
    ChevronLeft,
    ChevronRight,
    ShoppingBag,
    Target,
} from 'lucide-vue-next';
import {
    Dialog as UiDialog,
    DialogTitle,
    DialogDescription,
} from '@/components/ui/dialog';
import { Button as UiButton } from '@/components/ui/button';
import DraggableDialogContent from '@/components/ui/dialog/DraggableDialogContent.vue';
import CreateCategoryForm from '@/components/categories/CreateCategoryForm.vue';
import { apiDelete, apiGet } from '@/utils/api';
import { useToast } from '@/components/ui/toast';
import * as icons from 'lucide-vue-next';
import selectionModeMixin from '@/mixins/selectionModeMixin';

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
        MainLayout,
        InertiaHead,
        Plus,
        Search,
        Pencil,
        Trash2,
        ChevronLeft,
        ChevronRight,
        ShoppingBag,
        Target,
        UiDialog,
        DialogTitle,
        DialogDescription,
        UiButton,
        DraggableDialogContent,
        CreateCategoryForm,
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
            searchTimeout: null as ReturnType<typeof setTimeout> | null,
        };
    },
    computed: {
        categoryToEdit(): Category | null {
            if (!this.categoryToEditId) return null;
            return this.categories.find(c => c.id === this.categoryToEditId) || null;
        },
        filteredCategories(): Category[] {
            return this.categories.filter(category => category.type === this.activeTab);
        },
    },
    watch: {
        activeTab() {
            if (this.isSelectionMode) {
                this.clearSelection();
            }
        },
    },
    mounted() {
        this.loadCategories();
    },
    beforeUnmount() {
        if (this.searchTimeout) {
            clearTimeout(this.searchTimeout);
        }
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
        getPageNumbers(): number[] {
            if (!this.pagination) return [];

            const current = this.pagination.current_page;
            const last = this.pagination.last_page;
            const pages: number[] = [];

            let start = Math.max(1, current - 2);
            const end = Math.min(last, start + 4);

            if (end - start < 4) {
                start = Math.max(1, end - 4);
            }

            for (let i = start; i <= end; i++) {
                pages.push(i);
            }

            return pages;
        },
        async handleCategoryCreated() {
            await this.loadCategories(this.currentPage);
            this.closeModal();
        },
        async handleCategoryUpdated() {
            await this.loadCategories(this.currentPage);
            this.closeEditModal();
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
        handleSearchInput() {
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }

            this.searchTimeout = setTimeout(() => {
                this.currentPage = 1;
                this.loadCategories(1);
            }, 500);
        },
        clearSearch() {
            this.searchQuery = '';
            this.currentPage = 1;
            this.loadCategories(1);
        },
        getIconComponent(iconName: string | null): Component | null {
            if (!iconName) return null;
            return (icons as unknown as Record<string, Component>)[iconName] || null;
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
            try {
                let successCount = 0;
                let errorCount = 0;

                for (const categoryId of idsToDelete) {
                    try {
                        const response = await apiDelete(`/api/categories/${categoryId}`);
                        if (response.ok) {
                            successCount++;
                        } else {
                            errorCount++;
                        }
                    } catch {
                        errorCount++;
                    }
                }

                if (successCount > 0) {
                    this.toast({
                        title: successCount === idsToDelete.length ? 'categorias deletadas' : 'exclusão parcial',
                        description: errorCount > 0
                            ? `${successCount} categoria(s) deletada(s). ${errorCount} falha(s).`
                            : `${successCount} categoria(s) deletada(s) com sucesso.`,
                        variant: successCount === idsToDelete.length ? 'default' : 'destructive',
                    });
                }

                if (errorCount > 0 && successCount === 0) {
                    this.toast({
                        title: 'erro ao deletar categorias',
                        description: 'não foi possível deletar as categorias selecionadas.',
                        variant: 'destructive',
                    });
                }

                this.exitSelectionMode();
                await this.loadCategories(this.currentPage);
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
            return this.filteredCategories.map(category => category.id);
        },
    },
};
</script>

<template>
    <InertiaHead title="Subcategorias" />
    <MainLayout :no-sub-nav="true">
        <div class="h-full w-full px-8 py-8">
            <div class="mb-4 flex w-full items-center justify-between">
                <div class="flex flex-col gap-1">
                    <div class="flex-inline flex items-center gap-2">
                        <h1 class="text-3xl font-bold text-white">subcategorias</h1>
                        <span class="rounded-md bg-white/10 px-2 py-1 text-xs font-semibold text-gray-300"> {{ totalSubcategories }} Total </span>
                    </div>
                    <span class="text-sm text-[#B6B6B6]">gerencie a subclassificação de suas categorias.</span>
                </div>
                <div class="flex flex-row items-center gap-2">
                    <div class="relative max-w-md">
                        <Search class="absolute left-3 top-1/2 !h-5 !w-5 -translate-y-1/2 transform text-[#767676]" />
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="buscar subcategoria..."
                            class="w-full rounded-md border border-[#2F2F2F] bg-[#131316] py-2.5 pl-10 pr-4 text-sm text-white placeholder-[#767676] transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-[#3800D8]"
                            @input="handleSearchInput"
                        />
                        <button
                            v-if="searchQuery"
                            @click="clearSearch"
                            class="absolute right-3 top-1/2 -translate-y-1/2 transform text-[#767676] transition-colors hover:text-white"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <button
                        v-if="!isSelectionMode"
                        @click="toggleSelectionMode"
                        class="flex items-center gap-x-2 rounded-md border border-[#2F2F2F] bg-[#1E1E1E] px-4 py-2 text-white transition-colors hover:bg-[#313131]"
                        title="selecionar múltiplas subcategorias (Ctrl + Espaço)"
                    >
                        <Target class="!h-4 !w-4" />
                        <span>selecionar</span>
                        <span class="hidden rounded border border-[#2F2F2F] px-1 py-0.5 text-[11px] uppercase text-[#8c8c8c] lg:inline-flex"
                            >Ctrl + Espaço</span
                        >
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
                        nova subcategoria
                    </button>
                </div>
            </div>
            <!-- Grid Content -->
            <div class="grid grid-cols-1 items-start gap-6 pb-4 md:grid-cols-2 lg:grid-cols-3">
                <!-- Skeleton para cards de categoria (quando categorias estão carregando) -->
                <template v-if="loadingCategories && categories.length === 0">
                    <CategoryCardSkeleton v-for="n in 3" :key="`category-skeleton-${n}`" />
                </template>
                <!-- Cards reais com skeleton de subcategorias (quando categorias já carregaram mas subcategorias estão carregando) -->
                <template v-else-if="!loadingCategories && loading && allSubcategories.length === 0 && categories.length > 0">
                    <div
                        v-for="category in categories"
                        :key="`category-loading-${category.id}`"
                        class="h-full w-full rounded-[20px] border border-[#2F2F2F] bg-[#181818] transition-all duration-300"
                    >
                        <!-- Card Header Content -->
                        <div
                            class="flex flex-row items-center justify-between space-x-3 rounded-t-[20px] border-b border-[#2F2F2F] bg-gradient-to-b from-[#FFFFFF08] to-transparent p-[20px]"
                        >
                            <div class="flex flex-row items-center space-x-3">
                                <!-- Icone da categoria -->
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
                                    <span class="text-sm font-semibold text-white">{{ category.name }}</span>
                                    <span class="text-xs font-medium text-[#767676]">carregando subcategorias...</span>
                                </div>
                            </div>
                            <span class="mb-1 whitespace-nowrap font-semibold" :style="{ color: category.color }">R$ 0,00</span>
                        </div>
                        <!-- Campo de busca dentro do card -->
                        <div class="border-b border-[#2F2F2F] p-3">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 !h-4 !w-4 -translate-y-1/2 transform text-[#767676]" />
                                <input
                                    type="text"
                                    :placeholder="`buscar em ${category.name.toLowerCase()}...`"
                                    disabled
                                    class="w-full rounded-md border border-[#2F2F2F] bg-[#131316] py-2 pl-9 pr-4 text-sm text-white placeholder-[#767676] opacity-50 cursor-not-allowed"
                                />
                            </div>
                        </div>
                        <!-- Lista de subcategorias skeleton -->
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
                <!-- Cards reais com dados -->
                <template v-else-if="groupedCategories.length > 0 || allSubcategories.length > 0">
                    <div
                        v-for="categoryGroup in groupedCategories"
                        :key="categoryGroup.category.id"
                        class="h-full w-full rounded-[20px] border border-[#2F2F2F] bg-[#181818] transition-all duration-300 hover:-translate-y-1 hover:border-[#525151]"
                    >
                        <!-- Card Header Content -->
                        <div
                            class="flex flex-row items-center justify-between space-x-3 rounded-t-[20px] border-b border-[#2F2F2F] bg-gradient-to-b from-[#FFFFFF08] to-transparent p-[20px]"
                        >
                            <div class="flex flex-row items-center space-x-3">
                                <!-- Icone da categoria -->
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
                                    <span class="text-sm font-semibold text-white">{{ categoryGroup.category.name }}</span>
                                    <span class="text-xs font-medium text-[#767676]">
                                        {{ categoryGroup.totalSubcategories }}
                                        {{ categoryGroup.totalSubcategories === 1 ? 'subcategoria' : 'subcategorias' }}
                                    </span>
                                </div>
                            </div>
                            <span class="mb-1 whitespace-nowrap font-semibold" :style="{ color: categoryGroup.category.color }">R$ 0,00</span>
                        </div>
                        <!-- Campo de busca dentro do card -->
                        <div class="border-b border-[#2F2F2F] p-3">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 !h-4 !w-4 -translate-y-1/2 transform text-[#767676]" />
                                <input
                                    :value="categorySearchQueries[categoryGroup.category.id] || ''"
                                    @input="handleCategorySearchInput(categoryGroup.category.id, ($event.target as HTMLInputElement).value)"
                                    type="text"
                                    :placeholder="`buscar em ${categoryGroup.category.name.toLowerCase()}...`"
                                    class="w-full rounded-md border border-[#2F2F2F] bg-[#131316] py-2 pl-9 pr-8 text-sm text-white placeholder-[#767676] transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-[#3800D8]"
                                />
                                <button
                                    v-if="categorySearchQueries[categoryGroup.category.id]"
                                    @click="clearCategorySearch(categoryGroup.category.id)"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 transform text-[#767676] transition-colors hover:text-white"
                                >
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Lista de subcategorias -->
                        <div class="flex flex-col gap-1 p-[8px]">
                            <!-- Subcategoria -->
                            <div
                                v-for="subcategory in categoryGroup.subcategories"
                                :key="subcategory.id"
                                class="group relative flex flex-row items-center space-x-3 rounded-[10px] p-[12px] transition-all duration-300 hover:bg-[#27272A]/60"
                                :class="{
                                    'ring-2 ring-[#6965f2] ring-offset-2 ring-offset-[#181818]':
                                        isSelectionMode && selectedIds.includes(subcategory.id),
                                    'cursor-pointer': isSelectionMode,
                                }"
                                @click="isSelectionMode ? toggleSubcategorySelection(subcategory.id) : null"
                            >
                                <!-- Checkbox de seleção -->
                                <div
                                    v-if="isSelectionMode"
                                    class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded border-2 transition-all"
                                    :class="selectedIds.includes(subcategory.id) ? 'border-[#6965f2] bg-[#6965f2]' : 'border-white/30 bg-[#181818]'"
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
                                <!-- Icone da subcategoria -->
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
                                        <span class="text-sm font-semibold text-white">{{ subcategory.name }}</span>
                                        <span class="text-xs font-medium text-gray-400">R$ 0,00</span>
                                    </div>
                                </div>
                                <div v-if="!isSelectionMode" class="flex gap-1 opacity-0 transition-opacity group-hover:opacity-100">
                                    <button
                                        @click.stop="handleEditSubcategory(subcategory.id)"
                                        class="rounded-[6px] p-[4px] text-[#a0a0a0] transition-all duration-300 hover:bg-[#3800D8] hover:text-white"
                                        title="Editar"
                                    >
                                        <Pencil class="!h-[14px] !w-[14px]" />
                                    </button>
                                    <button
                                        @click.stop="handleDeleteSubcategory(subcategory.id)"
                                        class="rounded-[6px] p-[4px] text-[#a0a0a0] transition-all duration-300 hover:bg-red-700 hover:text-red-400"
                                        title="Excluir"
                                    >
                                        <Trash2 class="!h-[14px] !w-[14px]" />
                                    </button>
                                </div>
                                <!-- Barra de Distribuição -->
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
                                class="mx-3 mt-2 flex items-center justify-between rounded-lg border border-[#2F2F2F] px-3 py-2 text-xs text-[#B6B6B6]"
                            >
                                <button
                                    class="rounded-md border border-[#2F2F2F] px-3 py-1 text-white transition-colors hover:bg-[#313131] disabled:cursor-not-allowed disabled:opacity-50"
                                    @click="changeCategoryPage(categoryGroup.category.id, 'prev')"
                                    :disabled="categoryGroup.pagination.currentPage === 1"
                                >
                                    anterior
                                </button>
                                <span class="text-white">
                                    página {{ categoryGroup.pagination.currentPage }} de {{ categoryGroup.pagination.totalPages }}
                                </span>
                                <button
                                    class="rounded-md border border-[#2F2F2F] px-3 py-1 text-white transition-colors hover:bg-[#313131] disabled:cursor-not-allowed disabled:opacity-50"
                                    @click="changeCategoryPage(categoryGroup.category.id, 'next')"
                                    :disabled="categoryGroup.pagination.currentPage === categoryGroup.pagination.totalPages"
                                >
                                    próxima
                                </button>
                            </div>
                            <button
                                @click="openModalForCategory(categoryGroup.category.id)"
                                class="mx-3 mb-3 mt-2 flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-dashed border-white/10 p-2.5 text-center text-sm font-medium text-[#666] transition-all duration-200 hover:border-indigo-500 hover:bg-indigo-500/5 hover:text-indigo-500"
                            >
                                <PlusCircle class="!h-4 !w-4" />
                                <span class="text-sm font-medium">adicionar em categoria</span>
                            </button>
                        </div>
                    </div>
                </template>
                <div
                    v-else
                    class="col-span-full flex flex-col items-center justify-center rounded-xl border border-dashed border-white/10 p-10 text-center text-gray-400"
                >
                    <p class="text-base font-medium">nenhuma subcategoria encontrada!</p>
                    <p class="text-sm text-gray-500">clique em "nova subcategoria" para criar sua primeira subcategoria.</p>
                </div>
            </div>
            <!-- Paginação -->
            <div v-if="pagination && pagination.last_page > 1" class="mt-6 flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm text-[#B6B6B6]">
                    <span>mostrando</span>
                    <span class="mx-1 font-medium text-white">{{ pagination.from || 0 }}</span>
                    <span>até</span>
                    <span class="mx-1 font-medium text-white">{{ pagination.to || 0 }}</span>
                    <span>de</span>
                    <span class="mx-1 font-medium text-white">{{ pagination.total }}</span>
                    <span>{{ pagination.total === 1 ? 'categoria' : 'categorias' }}</span>
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
                                    : 'border-[#2F2F2F] bg-[#1E1E1E] text-white hover:bg-[#313131]',
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
            <DraggableDialogContent class="max-h-[80vh] w-full max-w-6xl overflow-y-auto">
                <template #header>
                    <DialogTitle class="text-lg font-semibold text-white">criar nova subcategoria</DialogTitle>
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
                    <DialogTitle class="text-lg font-semibold text-white">editar subcategoria</DialogTitle>
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
                    <DialogTitle class="text-lg font-semibold text-white">confirmar exclusão</DialogTitle>
                    <DialogDescription class="sr-only">confirme a exclusão da subcategoria</DialogDescription>
                </template>
                <div class="flex flex-col gap-4">
                    <p class="text-sm text-[#B6B6B6]">tem certeza que deseja excluir esta subcategoria? esta ação não pode ser desfeita.</p>
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
                    <DialogDescription class="sr-only">confirme a exclusão das subcategorias selecionadas</DialogDescription>
                </template>
                <div class="flex flex-col gap-4">
                    <p class="text-sm text-[#B6B6B6]">
                        tem certeza que deseja excluir {{ selectedIds.length }} {{ selectedIds.length === 1 ? 'subcategoria' : 'subcategorias' }}?
                        esta ação não pode ser desfeita.
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
                class="fixed bottom-6 left-1/2 z-50 flex -translate-x-1/2 transform items-center gap-4 rounded-lg border border-[#2F2F2F] bg-[#1E1E1E] px-6 py-4 shadow-2xl"
            >
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#6965f2]">
                        <span class="text-sm font-semibold text-white">{{ selectedIds.length }}</span>
                    </div>
                    <span class="text-sm font-medium text-white">
                        {{ selectedIds.length === 1 ? 'subcategoria selecionada' : 'subcategorias selecionadas' }}
                    </span>
                </div>
                <div class="h-6 w-px bg-[#2F2F2F]"></div>
                <button @click="selectAllSubcategories" class="text-sm text-[#B6B6B6] transition-colors hover:text-white">selecionar todas</button>
                <button @click="clearSelection" class="text-sm text-[#B6B6B6] transition-colors hover:text-white">limpar seleção</button>
                <div class="h-6 w-px bg-[#2F2F2F]"></div>
                <button
                    @click="confirmBulkDelete"
                    class="flex items-center gap-2 rounded-md bg-red-600 px-4 py-2 text-white transition-colors hover:bg-red-700"
                >
                    <Trash2 class="!h-4 !w-4" />
                    excluir ({{ selectedIds.length }})
                </button>
            </div>
        </Transition>
    </MainLayout>
</template>

<script lang="ts">
import CreateSubcategoryForm from '@/components/subcategories/CreateSubcategoryForm.vue';
import SubcategoryCardSkeleton from '@/components/subcategories/SubcategoryCardSkeleton.vue';
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
    ChevronLeft,
    ChevronRight,
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
        SubcategoryCardSkeleton,
        CategoryCardSkeleton,
        ChevronLeft,
        ChevronRight,
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
            searchTimeout: null as ReturnType<typeof setTimeout> | null,
            preselectedCategoryId: null as number | null,
            currentPage: 1,
            groupsPerPage: 3,
            perCategoryPageSize: 4,
            categorySearchQueries: {} as Record<number, string>,
            categoryPagination: {} as Record<number, number>,
            allSubcategories: [] as Subcategory[],
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
            return this.allSubcategories.find((s) => s.id === this.subcategoryToEditId) || null;
        },
        allGroupedCategories(): CategoryGroup[] {
            const groups: Record<number, { category: Category; subcategories: Subcategory[] }> = {};

            // Primeiro, criar grupos para todas as categorias que têm subcategorias
            this.allSubcategories.forEach((subcategory) => {
                const categoryId = subcategory.category_id;
                if (!groups[categoryId]) {
                    const category = this.categories.find((c) => c.id === categoryId);
                    if (category) {
                        groups[categoryId] = {
                            category,
                            subcategories: [],
                        };
                    }
                }
                if (groups[categoryId]) {
                    groups[categoryId].subcategories.push(subcategory);
                }
            });

            return Object.values(groups).map((group) => {
                const searchQuery = this.categorySearchQueries[group.category.id] || '';
                const filtered = searchQuery.trim()
                    ? group.subcategories.filter((subcategory) =>
                        subcategory.name.toLowerCase().includes(searchQuery.toLowerCase())
                    )
                    : group.subcategories;

                const totalSubcategories = filtered.length;
                const totalPages = Math.max(1, Math.ceil(totalSubcategories / this.perCategoryPageSize));
                const requestedPage = this.categoryPagination[group.category.id] ?? 1;
                const currentPage = Math.min(Math.max(requestedPage, 1), totalPages);
                const start = (currentPage - 1) * this.perCategoryPageSize;
                const paginatedSubcategories = filtered.slice(start, start + this.perCategoryPageSize);

                return {
                    category: group.category,
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
            // Paginar os grupos de categoria
            const allGroups = this.allGroupedCategories;
            const start = (this.currentPage - 1) * this.groupsPerPage;
            const end = start + this.groupsPerPage;
            return allGroups.slice(start, end);
        },
        totalSubcategories(): number {
            return this.allSubcategories.length;
        },
    },
    mounted() {
        this.loadSubcategories(1);
        this.loadCategories();
    },
    beforeUnmount() {
        if (this.searchTimeout) {
            clearTimeout(this.searchTimeout);
        }
    },
    methods: {
        async loadSubcategories(page?: number) {
            try {
                this.loading = true;

                // Buscar todas as subcategorias (sem paginação do backend)
                let url = `/api/subcategories?per_page=1000`;
                if (this.searchQuery.trim()) {
                    url += `&search=${encodeURIComponent(this.searchQuery.trim())}`;
                }

                const response = await apiGet(url);

                if (!response.ok) {
                    throw new Error('Failed to load subcategories');
                }

                const data = await response.json();
                this.allSubcategories = data.data || [];
                this.categoryPagination = {};

                // Aguardar próximo tick para garantir que o computed seja atualizado
                await this.$nextTick();

                // Calcular paginação baseada em grupos de categoria
                this.updatePaginationInfo(page ?? 1);
            } catch (error) {
                console.error('Error loading subcategories:', error);
                this.toast({
                    title: 'erro ao carregar subcategorias',
                    description: 'não foi possível carregar as subcategorias. tente novamente.',
                    variant: 'destructive',
                });
            } finally {
                this.loading = false;
            }
        },
        goToPage(page: number) {
            this.updatePaginationInfo(page);
        },
        updatePaginationInfo(targetPage?: number) {
            const totalGroups = this.allGroupedCategories.length;
            const totalPages = Math.max(1, Math.ceil(totalGroups / this.groupsPerPage));
            const page = targetPage ? Math.min(Math.max(targetPage, 1), totalPages) : Math.min(Math.max(this.currentPage, 1), totalPages);
            const start = (page - 1) * this.groupsPerPage;
            const end = Math.min(start + this.groupsPerPage, totalGroups);

            this.currentPage = page;

            this.pagination = {
                total: totalGroups,
                per_page: this.groupsPerPage,
                current_page: page,
                last_page: totalPages,
                from: totalGroups > 0 ? start + 1 : null,
                to: totalGroups > 0 ? end : null,
            };
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
        async loadCategories() {
            try {
                this.loadingCategories = true;
                await new Promise((resolve) => setTimeout(resolve, 3000));
                const response = await apiGet('/api/categories?per_page=5');
                if (response.ok) {
                    const data = await response.json();
                    this.categories = data.data || [];
                }
            } catch (error) {
                console.error('Error loading categories:', error);
            } finally {
                this.loadingCategories = false;
            }
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

                await this.loadSubcategories(this.currentPage);
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
            await this.loadSubcategories(this.currentPage);
        },
        async handleSubcategoryUpdated() {
            this.closeEditModal();
            await this.loadSubcategories(this.currentPage);
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
        handleSearchInput() {
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }

            this.searchTimeout = setTimeout(() => {
                this.currentPage = 1;
                this.loadSubcategories(1);
            }, 500);
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
            this.$nextTick(() => {
                this.updatePaginationInfo();
            });
        },
        clearCategorySearch(categoryId: number) {
            const queries = { ...this.categorySearchQueries };
            delete queries[categoryId];
            this.categorySearchQueries = queries;
            this.categoryPagination = {
                ...this.categoryPagination,
                [categoryId]: 1,
            };
            this.$nextTick(() => {
                this.updatePaginationInfo();
            });
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
        clearSearch() {
            this.searchQuery = '';
            this.currentPage = 1;
            this.loadSubcategories(1);
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
                    await this.loadSubcategories(this.currentPage);
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
            return this.allSubcategories.map((subcategory) => subcategory.id);
        },
    },
};
</script>

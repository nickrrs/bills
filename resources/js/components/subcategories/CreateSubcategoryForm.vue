<template>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- === ESQUERDA: O Construtor === -->
        <div class="lg:col-span-7 space-y-8">
            <!-- 1. Seleção da Categoria Pai -->
            <div class="bg-[#181818] border border-white/10 rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">1. a qual categoria ela pertence?</h2>
                <p class="text-sm text-gray-400 mb-4">selecione a categoria principal para vincular esta subcategoria.</p>

                <!-- Mensagem quando não há categorias -->
                <div
                    v-if="emptyCategories && !loadingCategories"
                    class="flex flex-col items-center justify-center p-8 border border-dashed border-white/10 rounded-xl text-center"
                >
                    <Folder class="w-12 h-12 text-gray-500 mb-4" />
                    <p class="text-base font-medium text-white mb-2">nenhuma categoria encontrada</p>
                    <p class="text-sm text-gray-400 mb-4">você precisa criar pelo menos uma categoria antes de criar uma subcategoria.</p>
                    <InertiaLink
                        href="/categories"
                        class="px-4 py-2 bg-[#6366f1] hover:bg-[#4f46e5] text-white font-semibold rounded-xl transition-colors inline-flex items-center gap-2"
                    >
                        <Plus class="w-4 h-4" />
                        criar categoria
                    </InertiaLink>
                </div>

                <!-- Busca e Lista de Categorias -->
                <template v-else>
                    <!-- Campo de Busca -->
                    <div class="relative mb-4">
                        <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 !h-4 !w-4 text-[#767676]" />
                        <input
                            v-model="categorySearchQuery"
                            type="text"
                            placeholder="buscar categoria..."
                            class="w-full pl-10 pr-4 py-2.5 bg-[#161616] border border-white/10 rounded-xl text-white text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#6366f1] focus:border-[#6366f1] transition-all"
                            @input="handleCategorySearchInput"
                        />
                        <button
                            v-if="categorySearchQuery"
                            @click="clearCategorySearch"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-[#767676] hover:text-white transition-colors"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Loading -->
                    <div v-if="loadingCategories" class="mb-4">
                        <CategorySelectionSkeleton />
                    </div>

                    <!-- Grid de Categorias -->
                    <div v-else-if="categories.length > 0" class="grid grid-cols-2 gap-3 mb-4">
                        <button
                            v-for="category in categories"
                            :key="category.id"
                            type="button"
                            @click="selectCategory(category.id)"
                            :class="[
                                'flex items-center p-3 rounded-xl transition-all duration-200',
                                formData.category_id === category.id
                                    ? 'bg-opacity-15 border-2 shadow-[0_0_0_2px]'
                                    : 'bg-[#161616] border border-white/10 hover:bg-[#1a1a1a]'
                            ]"
                            :style="formData.category_id === category.id ? {
                                backgroundColor: `rgba(${hexToRgb(category.color)}, 0.15)`,
                                borderColor: category.color,
                                boxShadow: `0 0 0 2px ${category.color}`
                            } : {}"
                        >
                            <div
                                class="w-10 h-10 rounded-lg flex items-center justify-center mr-3 flex-shrink-0"
                                :style="{ backgroundColor: category.color + '33', color: category.color }"
                            >
                                <component
                                    v-if="getIconComponent(category.icon)"
                                    :is="getIconComponent(category.icon)"
                                    class="w-5 h-5"
                                />
                                <ShoppingBag
                                    v-else
                                    class="w-5 h-5"
                                />
                            </div>
                            <span class="font-medium text-white truncate">{{ category.name }}</span>
                        </button>
                    </div>

                    <!-- Mensagem quando busca não retorna resultados -->
                    <div
                        v-else-if="categorySearchQuery"
                        class="flex flex-col items-center justify-center p-6 border border-dashed border-white/10 rounded-xl text-center"
                    >
                        <Search class="w-8 h-8 text-gray-500 mb-2" />
                        <p class="text-sm text-gray-400">nenhuma categoria encontrada para "{{ categorySearchQuery }}"</p>
                    </div>

                    <!-- Paginação -->
                    <div v-if="categoryPagination && categoryPagination.last_page > 1" class="flex items-center justify-between mt-4 pt-4 border-t border-white/10">
                        <div class="text-xs text-gray-400">
                            <span>mostrando</span>
                            <span class="mx-1 font-medium text-white">{{ categoryPagination.from ?? 0 }}</span>
                            <span>até</span>
                            <span class="mx-1 font-medium text-white">{{ categoryPagination.to ?? 0 }}</span>
                            <span>de</span>
                            <span class="mx-1 font-medium text-white">{{ categoryPagination.total }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button
                                @click="goToCategoryPage(categoryPagination.current_page - 1)"
                                :disabled="categoryPagination.current_page === 1"
                                class="flex h-8 w-8 items-center justify-center rounded-md border border-white/10 bg-[#161616] text-white transition-colors hover:bg-[#1a1a1a] disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                <ChevronLeft class="h-4 w-4" />
                            </button>
                            <button
                                @click="goToCategoryPage(categoryPagination.current_page + 1)"
                                :disabled="categoryPagination.current_page === categoryPagination.last_page"
                                class="flex h-8 w-8 items-center justify-center rounded-md border border-white/10 bg-[#161616] text-white transition-colors hover:bg-[#1a1a1a] disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                <ChevronRight class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- 2. Informações da Subcategoria -->
            <div class="bg-[#181818] border border-white/10 rounded-2xl p-6" :class="{ 'opacity-50 pointer-events-none': emptyCategories }">
                <h2 class="text-lg font-semibold text-white mb-6">2. detalhes da subcategoria</h2>
                <div class="space-y-6">
                    <!-- Nome -->
                    <div>
                        <div class="flex flex-row items-baseline gap-1 mb-2">
                            <label class="text-sm font-medium text-gray-400">nome</label>
                            <span class="text-red-400 text-sm leading-none">*</span>
                        </div>
                        <input
                            v-model="formData.name"
                            type="text"
                            maxlength="30"
                            placeholder="ex: ifood, uber, assinaturas..."
                            class="w-full px-4 py-3 bg-[#161616] border border-white/10 rounded-xl text-lg font-medium text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#6366f1] focus:border-[#6366f1] transition-all"
                        />
                    </div>

                    <!-- Ícone -->
                    <div>
                        <div class="flex flex-row items-baseline gap-1 mb-2">
                            <label class="text-sm font-medium text-gray-400">ícone</label>
                            <span class="text-red-400 text-sm leading-none">*</span>
                        </div>
                        <div class="grid grid-cols-6 sm:grid-cols-8 gap-2">
                            <button
                                v-for="icon in iconOptions"
                                :key="icon.name"
                                type="button"
                                @click="setIcon(icon.name)"
                                :class="[
                                    'w-full aspect-square rounded-lg border-2 transition-all hover:scale-105 bg-[#0E0E10] flex items-center justify-center',
                                    formData.icon === icon.name
                                        ? 'border-[#6965f2] ring-2 ring-[#6965f2] ring-offset-2 ring-offset-[#131316] bg-[#6965f2]/20'
                                        : 'border-[#2F2F2F] hover:border-[#3800D8]'
                                ]"
                                :title="icon.label"
                            >
                                <component
                                    :is="icon.component"
                                    class="h-5 w-5"
                                    :style="formData.icon === icon.name ? { color: previewColor } : { color: '#767676' }"
                                />
                            </button>
                        </div>
                    </div>

                    <!-- Cor -->
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-4">cor</label>
                        <div class="flex flex-wrap gap-4">
                            <button
                                v-for="colorOption in colorOptions"
                                :key="colorOption.value"
                                type="button"
                                @click="setColor(colorOption.value)"
                                :class="[
                                    'w-9 h-9 rounded-full transition-all duration-200 relative',
                                    formData.color.toLowerCase() === colorOption.value.toLowerCase()
                                        ? 'scale-120 border-2 shadow-[0_0_0_4px_rgba(255,255,255,0.1)]'
                                        : 'border-2 border-transparent hover:scale-110'
                                ]"
                                :style="{
                                    backgroundColor: colorOption.value,
                                    borderColor: formData.color.toLowerCase() === colorOption.value.toLowerCase() ? 'white' : 'transparent'
                                }"
                                :title="colorOption.label"
                            >
                            </button>
                        </div>
                        <p class="text-xs text-gray-400 mt-3">por padrão, sugerimos a mesma cor da categoria pai.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- === DIREITA: O Preview (Sticky) === -->
        <div class="lg:col-span-5 sticky top-6">
            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Preview Hierárquico</h3>

            <!-- Card de Preview da Relação -->
            <div class="bg-[#181818] border border-white/10 rounded-2xl p-8 mb-6 relative overflow-hidden">
                <!-- Linha de Conexão Decorativa -->
                <div class="absolute left-6 top-10 bottom-10 w-0.5 bg-white/10 z-0"></div>

                <div class="space-y-6 relative z-10">
                    <!-- Pai (Estático/Selecionado) -->
                    <div class="flex items-center opacity-50">
                        <div
                            id="preview-parent-icon"
                            class="w-12 h-12 rounded-xl flex items-center justify-center mr-4 flex-shrink-0 transition-all duration-300"
                            :style="selectedCategory ? {
                                backgroundColor: `rgba(${hexToRgb(selectedCategory.color)}, 0.2)`,
                                color: selectedCategory.color
                            } : {
                                backgroundColor: '#2a2a2a',
                                color: '#9ca3af'
                            }"
                        >
                            <component
                                v-if="selectedCategory && getIconComponent(selectedCategory.icon)"
                                :is="getIconComponent(selectedCategory.icon)"
                                class="w-6 h-6"
                            />
                            <Folder
                                v-else
                                class="w-6 h-6"
                            />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs text-gray-400 uppercase font-bold tracking-wide">Categoria Principal</p>
                            <h3 class="text-xl font-semibold text-white truncate" :title="selectedCategory ? selectedCategory.name : 'selecione...'">
                                {{ selectedCategory ? selectedCategory.name : 'selecione...' }}
                            </h3>
                        </div>
                    </div>

                    <!-- Conector Curvo -->
                    <div class="absolute left-6 top-1/2 w-5 h-8 border-b-2 border-l-2 border-white/10 rounded-bl-xl z-0" style="transform: translateY(-50%);"></div>

                    <!-- Filho (Sendo Criado) -->
                    <div class="flex items-center pl-12">
                        <div
                            id="preview-child-icon"
                            class="w-14 h-14 rounded-2xl flex items-center justify-center mr-4 flex-shrink-0 transition-all duration-300 shadow-lg"
                            :style="{
                                backgroundColor: `rgba(${hexToRgb(previewColor)}, 0.2)`,
                                color: previewColor,
                                boxShadow: `0 0 20px -5px rgba(${hexToRgb(previewColor)}, 0.4)`
                            }"
                        >
                            <component
                                v-if="selectedIconComponent"
                                :is="selectedIconComponent"
                                class="w-7 h-7"
                            />
                            <Star
                                v-else
                                class="w-7 h-7"
                            />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs text-gray-400 uppercase font-bold tracking-wide">Nova Subcategoria</p>
                            <h2 class="text-2xl font-bold text-white truncate" :title="formData.name || 'nome...'">
                                {{ formData.name || 'nome...' }}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Contextual: Lista de Transações -->
            <div class="bg-[#181818] border border-white/10 rounded-2xl overflow-hidden mb-6">
                <div class="p-4 bg-[#181818] border-b border-white/5">
                    <p class="text-xs text-gray-500">como aparece em transações</p>
                </div>
                <div class="flex items-center p-4">
                    <div
                        id="preview-list-icon"
                        class="w-10 h-10 rounded-full flex items-center justify-center mr-4 flex-shrink-0"
                        :style="{
                            backgroundColor: `rgba(${hexToRgb(previewColor)}, 0.2)`,
                            color: previewColor
                        }"
                    >
                        <component
                            v-if="selectedIconComponent"
                            :is="selectedIconComponent"
                            class="w-5 h-5"
                        />
                        <Star
                            v-else
                            class="w-5 h-5"
                        />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-white">compra recente</p>
                        <p class="text-xs text-gray-400 flex items-center">
                            <span>{{ selectedCategory ? selectedCategory.name : 'categoria' }}</span>
                            <ChevronRight class="w-3 h-3 mx-1" />
                            <span :style="{ color: previewColor }">{{ formData.name || 'subcategoria' }}</span>
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-white text-sm">R$ 0,00</p>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex space-x-4">
                <button
                    type="button"
                    @click="$emit('cancel')"
                    class="flex-1 bg-transparent border border-white/10 hover:bg-white/5 text-gray-300 font-semibold py-3 rounded-xl transition-colors"
                >
                    Cancelar
                </button>
                <button
                    type="button"
                    @click="handleSubmit"
                    :disabled="loading || !formData.name || !formData.category_id || emptyCategories"
                    class="flex-1 bg-[#6366f1] hover:bg-[#4f46e5] text-white font-semibold py-3 rounded-xl shadow-lg shadow-indigo-900/20 transition-all hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 flex items-center justify-center gap-2"
                >
                    <LoaderCircle v-if="loading" class="h-4 w-4 animate-spin" />
                    {{ subcategory ? 'Atualizar Subcategoria' : 'Salvar Subcategoria' }}
                </button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { LoaderCircle, ShoppingBag, Star, Wallet, Coffee, Trash2, Bus, Gamepad2, Music, Gift, Briefcase, Send, Wifi, Zap, Folder, ChevronRight, ChevronLeft, UtensilsCrossed, Home, Heart, GraduationCap, Car, Film, Smartphone, Baby, Dumbbell, PawPrint, Search, Plus } from 'lucide-vue-next';
import type { Component } from 'vue';
import { apiPost, apiPut } from '@/utils/api';
import { useToast } from '@/components/ui/toast';
import { apiGet } from '@/utils/api';
import { Link as InertiaLink } from '@inertiajs/vue3';
import CategorySelectionSkeleton from '@/components/subcategories/CategorySelectionSkeleton.vue';

interface ColorOption {
    value: string;
    label: string;
}

interface IconOption {
    name: string;
    label: string;
    component: Component;
}

interface Category {
    id: number;
    name: string;
    icon: string | null;
    color: string;
    type: 'expense' | 'recept';
}

export default {
    name: 'CreateSubcategoryForm',
    components: {
        LoaderCircle,
        ShoppingBag,
        Star,
        Wallet,
        Coffee,
        Trash2,
        Bus,
        Gamepad2,
        Music,
        Gift,
        Briefcase,
        Send,
        Wifi,
        Zap,
        Folder,
        ChevronRight,
        ChevronLeft,
        UtensilsCrossed,
        Home,
        Heart,
        GraduationCap,
        Car,
        Film,
        Smartphone,
        PawPrint,
        Baby,
        Dumbbell,
        Search,
        Plus,
        InertiaLink,
        CategorySelectionSkeleton,
    },
    emits: ['success', 'cancel', 'reset-complete'],
    setup() {
        const { toast } = useToast();
        return { toast };
    },
    props: {
        resetForm: {
            type: Boolean,
            default: false,
        },
        subcategory: {
            type: Object as () => any,
            default: null,
        },
        preselectedCategoryId: {
            type: Number,
            default: null,
        },
    },
    data() {
        return {
            formData: {
                name: '',
                icon: 'ShoppingBag',
                color: '#ec4899',
                category_id: null as number | null,
            },
            loading: false,
            loadingCategories: false,
            categories: [] as Category[],
            categorySearchQuery: '',
            categorySearchTimeout: null as ReturnType<typeof setTimeout> | null,
            categoryPagination: null as {
                total: number;
                per_page: number;
                current_page: number;
                last_page: number;
                from: number | null;
                to: number | null;
            } | null,
            categoryCurrentPage: 1,
            categoryPerPage: 6,
            colorOptions: [
                { value: '#ec4899', label: 'rosa' },
                { value: '#8b5cf6', label: 'roxo' },
                { value: '#6366f1', label: 'indigo' },
                { value: '#3b82f6', label: 'azul' },
                { value: '#0ea5e9', label: 'ciano' },
                { value: '#10b981', label: 'verde' },
                { value: '#84cc16', label: 'verde limão' },
                { value: '#f59e0b', label: 'amarelo' },
                { value: '#f97316', label: 'laranja' },
                { value: '#ef4444', label: 'vermelho' },
                { value: '#737373', label: 'cinza' },
            ] as ColorOption[],
            iconOptions: [
                { name: 'ShoppingBag', label: 'sacola', component: ShoppingBag },
                { name: 'Coffee', label: 'café', component: Coffee },
                { name: 'Trash2', label: 'lixeira', component: Trash2 },
                { name: 'Bus', label: 'ônibus', component: Bus },
                { name: 'Send', label: 'avião', component: Send },
                { name: 'Wifi', label: 'wi-fi', component: Wifi },
                { name: 'Zap', label: 'raio', component: Zap },
                { name: 'Smartphone', label: 'smartphone', component: Smartphone },
                { name: 'Gamepad2', label: 'jogos', component: Gamepad2 },
                { name: 'Music', label: 'música', component: Music },
                { name: 'Gift', label: 'presente', component: Gift },
                { name: 'Briefcase', label: 'trabalho', component: Briefcase },
                { name: 'PawPrint', label: 'pet', component: PawPrint },
                { name: 'Baby', label: 'bebê', component: Baby },
                { name: 'Dumbbell', label: 'academia', component: Dumbbell },
                { name: 'Star', label: 'estrela', component: Star },
            ] as IconOption[],
            emptyCategories: false,
        };
    },
    computed: {
        selectedCategory(): Category | null {
            if (!this.formData.category_id) return null;
            return this.categories.find(c => c.id === this.formData.category_id) || null;
        },
        availableIcons(): Record<string, Component> {
            // Criar um objeto com os ícones disponíveis para acesso dinâmico
            // Isso permite tree-shaking e reduz o tamanho do bundle
            return {
                ShoppingBag, Coffee, Trash2, Bus, Send, Wifi, Zap, Smartphone, Gamepad2, Music, Gift, Briefcase,
                PawPrint, Baby, Dumbbell, Star, Wallet, UtensilsCrossed, Home, Heart, GraduationCap, Car, Film,
                Folder, ChevronRight, ChevronLeft, Search, Plus,
            };
        },
        selectedIconComponent(): Component | null {
            if (!this.formData.icon) return null;
            const iconName = this.formData.icon;
            return this.availableIcons[iconName] || null;
        },
        previewColor(): string {
            if (this.formData.color) {
                return this.formData.color;
            }
            if (this.selectedCategory && !this.subcategory) {
                return this.selectedCategory.color;
            }
            return '#ec4899'; // Primeira cor da lista
        },
    },
    watch: {
        resetForm(newValue) {
            if (newValue) {
                this.resetFormData();
                if (this.preselectedCategoryId) {
                    this.formData.category_id = this.preselectedCategoryId;
                }
                this.$emit('reset-complete');
            }
        },
        subcategory: {
            handler(newSubcategory) {
                if (newSubcategory) {
                    this.loadSubcategoryData(newSubcategory);
                } else {
                    this.resetFormData();
                    if (this.preselectedCategoryId) {
                        this.formData.category_id = this.preselectedCategoryId;
                    }
                }
            },
            immediate: true,
        },
        preselectedCategoryId(newValue) {
            if (newValue && !this.subcategory) {
                this.formData.category_id = newValue;
            }
        },
        'formData.category_id'(newCategoryId) {
            if (newCategoryId && !this.subcategory) {
                const category = this.categories.find(c => c.id === newCategoryId);
                if (category) {
                    // Herda a cor da categoria pai (normaliza para minúsculas)
                    this.formData.color = category.color.toLowerCase();
                }
            } else if (!newCategoryId && !this.subcategory) {
                // Se nenhuma categoria for selecionada, volta para a primeira cor
                this.formData.color = '#ec4899';
            }
        },
    },
    mounted() {
        this.loadCategories();
        if (this.preselectedCategoryId) {
            this.formData.category_id = this.preselectedCategoryId;
        }
    },
    beforeUnmount() {
        if (this.categorySearchTimeout) {
            clearTimeout(this.categorySearchTimeout);
        }
    },
    methods: {
        hexToRgb(hex: string): string {
            const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result
                ? `${parseInt(result[1], 16)}, ${parseInt(result[2], 16)}, ${parseInt(result[3], 16)}`
                : '115, 115, 115';
        },
        selectCategory(categoryId: number) {
            this.formData.category_id = categoryId;
            const category = this.categories.find(c => c.id === categoryId);
            if (category && !this.subcategory) {
                // Herda a cor da categoria pai (normaliza para minúsculas)
                this.formData.color = category.color.toLowerCase();
            }
        },
        setIcon(iconName: string) {
            this.formData.icon = iconName;
        },
        setColor(colorHex: string) {
            this.formData.color = colorHex;
        },
        async loadCategories(page: number = 1) {
            try {
                this.loadingCategories = true;

                let url = `/api/categories?page=${page}&per_page=${this.categoryPerPage}`;
                if (this.categorySearchQuery.trim()) {
                    url += `&search=${encodeURIComponent(this.categorySearchQuery.trim())}`;
                }

                const response = await apiGet(url);
                if (response.ok) {
                    const data = await response.json();
                    this.categories = data.data || [];
                    this.categoryPagination = {
                        total: data.total,
                        per_page: data.per_page,
                        current_page: data.current_page,
                        last_page: data.last_page,
                        from: data.from,
                        to: data.to,
                    };
                    this.categoryCurrentPage = data.current_page;

                    if (this.categories.length == 0 && !this.categorySearchQuery) {
                        this.emptyCategories = true;
                        // Se não há categorias, garante que tem os valores padrão
                        if (!this.subcategory && !this.formData.category_id) {
                            this.formData.icon = 'ShoppingBag';
                            this.formData.color = '#ec4899';
                        }
                    } else {
                        this.emptyCategories = false;

                        if (this.formData.category_id && !this.subcategory) {
                            const category = this.categories.find(c => c.id === this.formData.category_id);
                            if (category) {
                                this.formData.color = category.color.toLowerCase();
                            }
                        }
                    }
                }
            } catch (error) {
                console.error('Error loading categories:', error);
                // Em caso de erro, garante valores padrão
                if (!this.subcategory && !this.formData.category_id) {
                    this.formData.icon = 'ShoppingBag';
                    this.formData.color = '#ec4899';
                }
            } finally {
                this.loadingCategories = false;
            }
        },
        handleCategorySearchInput() {
            if (this.categorySearchTimeout) {
                clearTimeout(this.categorySearchTimeout);
            }

            this.categorySearchTimeout = setTimeout(() => {
                this.categoryCurrentPage = 1;
                this.loadCategories(1);
            }, 500);
        },
        clearCategorySearch() {
            this.categorySearchQuery = '';
            this.categoryCurrentPage = 1;
            this.loadCategories(1);
        },
        goToCategoryPage(page: number) {
            if (page >= 1 && page <= (this.categoryPagination?.last_page || 1)) {
                this.loadCategories(page);
            }
        },
        loadSubcategoryData(subcategory: any) {
            this.formData = {
                name: subcategory.name || '',
                icon: subcategory.icon || '',
                color: subcategory.color || '',
                category_id: subcategory.category_id || null,
            };
        },
        resetFormData() {
            this.formData = {
                name: '',
                icon: 'ShoppingBag',
                color: '#ec4899',
                category_id: null,
            };
        },
        getIconComponent(iconName: string | null): Component | null {
            if (!iconName) return null;
            return this.availableIcons[iconName] || null;
        },
        async handleSubmit() {
            if (!this.formData.name) {
                this.toast({
                    title: 'erro',
                    description: 'o nome da subcategoria é obrigatório',
                    variant: 'destructive',
                });
                return;
            }

            if (!this.formData.category_id) {
                this.toast({
                    title: 'erro',
                    description: 'selecione uma categoria',
                    variant: 'destructive',
                });
                return;
            }

            this.loading = true;

            try {
                const isEditing = !!this.subcategory;

                const slug = this.formData.name
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/(^-|-$)/g, '');

                const data = {
                    name: this.formData.name,
                    slug: slug,
                    icon: this.formData.icon || null,
                    color: this.formData.color,
                    category_id: this.formData.category_id,
                };

                const response = isEditing
                    ? await apiPut(`/api/subcategories/${this.subcategory.id}`, data)
                    : await apiPost('/api/subcategories', data);

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || `Failed to ${isEditing ? 'update' : 'create'} subcategory`);
                }

                this.toast({
                    title: isEditing ? 'subcategoria atualizada com sucesso!' : 'subcategoria criada com sucesso!',
                    description: `a subcategoria "${this.formData.name}" foi ${isEditing ? 'atualizada' : 'criada'} com sucesso.`,
                    variant: 'default',
                });

                this.$emit('success');

                if (!isEditing) {
                    this.resetFormData();
                }
            } catch (error: any) {
                const isEditing = !!this.subcategory;
                const errorMessage = error?.response?.data?.message ||
                                   error?.message ||
                                   `Erro ao ${isEditing ? 'atualizar' : 'criar'} subcategoria. Tente novamente.`;

                this.toast({
                    title: `erro ao ${isEditing ? 'atualizar' : 'criar'} subcategoria`,
                    description: errorMessage,
                    variant: 'destructive',
                });
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

<style scoped>
.scale-120 {
    transform: scale(1.2);
}
</style>


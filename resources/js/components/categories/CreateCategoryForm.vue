<template>
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Formulário -->
        <div class="flex-1 flex flex-col gap-6 w-full">
            <div class="flex flex-col gap-4">
                <!-- Tipo de Categoria -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-white">tipo de categoria</label>
                    <div class="flex gap-2">
                        <button
                            type="button"
                            @click="formData.type = 'expense'"
                            :class="[
                                'flex-1 px-4 py-3 rounded-lg font-medium transition-all',
                                formData.type === 'expense'
                                    ? 'bg-red-500 text-white'
                                    : 'bg-[#0E0E10] border border-[#2F2F2F] text-[#767676] hover:border-[#3800D8] hover:text-white'
                            ]"
                        >
                            despesa
                        </button>
                        <button
                            type="button"
                            @click="formData.type = 'recept'"
                            :class="[
                                'flex-1 px-4 py-3 rounded-lg font-medium transition-all',
                                formData.type === 'recept'
                                    ? 'bg-green-500 text-white'
                                    : 'bg-[#0E0E10] border border-[#2F2F2F] text-[#767676] hover:border-[#3800D8] hover:text-white'
                            ]"
                        >
                            receita
                        </button>
                    </div>
                </div>

                <!-- Nome da Categoria -->
                <div class="flex flex-col gap-2">
                    <div class="flex flex-row items-baseline gap-1">
                        <label class="text-sm font-medium text-white">nome da categoria</label>
                        <span class="text-red-400 text-sm leading-none">*</span>
                    </div>
                    <input
                        v-model="formData.name"
                        required
                        type="text"
                        maxlength="30"
                        placeholder="ex: viagens, streaming..."
                        class="w-full px-3 py-2 bg-[#0E0E10] border border-[#2F2F2F] rounded-md text-white placeholder-[#767676] focus:outline-none focus:ring-2 focus:ring-[#3800D8] focus:border-transparent"
                    />
                </div>

                <!-- Seletor de Ícones -->
                <CategoryIconSelector v-model="formData.icon" />

                <!-- Escolha uma Cor -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-white">escolha uma cor</label>
                    <div class="flex gap-2 flex-wrap">
                        <button
                            v-for="colorOption in colorOptions"
                            :key="colorOption.value"
                            type="button"
                            @click="formData.color = colorOption.value"
                            :class="[
                                'w-10 h-10 rounded-full border-2 transition-all hover:scale-110',
                                formData.color === colorOption.value
                                    ? 'border-white ring-2 ring-[#3800D8] ring-offset-2 ring-offset-[#131316]'
                                    : 'border-[#2F2F2F] hover:border-[#3800D8]'
                            ]"
                            :style="{ backgroundColor: colorOption.value }"
                            :title="colorOption.label"
                        >
                            <div v-if="formData.color === colorOption.value" class="w-full h-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#2F2F2F]">
                <button
                    type="button"
                    @click="$emit('cancel')"
                    class="px-4 py-2 text-sm font-medium text-[#B6B6B6] hover:text-white transition-colors"
                >
                    cancelar
                </button>
                <button
                    type="button"
                    @click="handleSubmit"
                    :disabled="loading || !formData.name"
                    class="px-4 py-2 text-sm font-medium bg-[#6965f2] text-white rounded-md hover:bg-[#3800D8] transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                >
                    <LoaderCircle v-if="loading" class="h-4 w-4 animate-spin" />
                    {{ category ? 'atualizar categoria' : 'criar categoria' }}
                </button>
            </div>
        </div>

        <!-- Preview -->
        <div class="lg:w-80 flex-shrink-0">
            <div class="sticky top-4">
                <h3 class="text-sm font-medium text-white mb-1">preview em tempo real</h3>
                <p class="text-xs text-[#767676] mb-4">é assim que a categoria vai aparecer</p>

                <!-- Card Preview -->
                <div class="w-full rounded-2xl bg-[#131316] border border-[#2F2F2F] p-8 relative overflow-hidden mb-6">
                    <!-- Background Glow -->
                    <div
                        class="absolute top-0 right-0 w-64 h-64 rounded-full blur-3xl opacity-10 transition-colors duration-500"
                        :style="{
                            backgroundColor: previewColor,
                            transform: 'translate(30%, -30%)'
                        }"
                    ></div>

                    <div class="relative z-10 flex flex-col items-center text-center">
                        <!-- Ícone Grande -->
                        <div
                            class="w-24 h-24 rounded-3xl flex items-center justify-center mb-4 transition-all duration-300 shadow-2xl"
                            :style="{
                                backgroundColor: previewColor + '33',
                                color: previewColor,
                                border: `1px solid ${previewColor}4D`
                            }"
                        >
                            <component
                                v-if="selectedIconComponent"
                                :is="selectedIconComponent"
                                class="w-10 h-10"
                            />
                            <ShoppingBag v-else class="w-10 h-10" />
                        </div>

                        <!-- Nome -->
                        <h2 class="text-3xl font-bold text-white mb-1 w-full" :class="formData.name ? 'text-ellipsis overflow-hidden whitespace-nowrap' : ''" :title="formData.name || 'nome da categoria'">
                            {{ formData.name || 'nome da categoria' }}
                        </h2>
                        <span class="text-sm text-gray-400 bg-white/5 px-3 py-1 rounded-full">
                            {{ formData.type === 'expense' ? 'despesa' : 'receita' }}
                        </span>
                    </div>
                </div>

                <!-- Visualização na Timeline -->
                <div class="mb-6">
                    <h4 class="text-xs font-medium text-white mb-3">visualização na timeline</h4>
                    <div class="bg-[#131316] border border-[#2F2F2F] rounded-lg p-3 flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                            :style="{ backgroundColor: previewColor + '20' }"
                        >
                            <component
                                v-if="selectedIconComponent"
                                :is="selectedIconComponent"
                                class="h-4 w-4"
                                :style="{ color: previewColor }"
                            />
                            <ShoppingBag v-else class="h-4 w-4" :style="{ color: previewColor }" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-white font-medium">amazon.com</p>
                            <p class="text-xs text-[#767676]" :title="formData.name || 'nome da categoria'">
                                {{ formData.name || 'nome da categoria' }}
                            </p>
                        </div>
                        <span
                            class="text-sm font-semibold flex-shrink-0 mb-3"
                            :style="{ color: formData.type === 'expense' ? '#ef4444' : '#10b981' }"
                        >
                            {{ formData.type === 'expense' ? '-' : '+' }} R$ 129,90
                        </span>
                    </div>
                </div>

                <!-- Visualização em Relatórios -->
                <div>
                    <h4 class="text-xs font-medium text-white mb-3">visualização em relatórios</h4>
                    <div class="bg-[#131316] border border-[#2F2F2F] rounded-lg p-3 flex items-center gap-3">
                        <div
                            class="w-3 h-3 rounded-full flex-shrink-0"
                            :style="{ backgroundColor: previewColor }"
                        ></div>
                        <p class="text-sm text-white flex-1" :title="formData.name || 'nome da categoria'">
                            {{ formData.name || 'nome da categoria' }}
                        </p>
                        <span class="text-sm text-[#767676]">15%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { LoaderCircle } from 'lucide-vue-next';
import type { Component } from 'vue';
import { apiPost, apiPut } from '@/utils/api';
import { useToast } from '@/components/ui/toast';
import CategoryIconSelector from './CategoryIconSelector.vue';
// Importar apenas os ícones que podem ser usados dinamicamente
import {
    ShoppingBag, ShoppingCart, Coffee, UtensilsCrossed, Building2, Home, Wifi, Zap, Gift, Heart, Wallet,
    GraduationCap, Plane, Bus, Car, Music, Film, Stethoscope, PawPrint, Clock, Gamepad2, Dumbbell,
    BookOpen, Camera, Palette, Trophy, Award, TreePine, Settings
} from 'lucide-vue-next';

interface ColorOption {
    value: string;
    label: string;
}

export default {
    name: 'CreateCategoryForm',
    components: {
        LoaderCircle,
        ShoppingBag,
        CategoryIconSelector,
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
        category: {
            type: Object as () => any,
            default: null,
        },
    },
    data() {
        return {
            formData: {
                name: '',
                icon: '',
                color: '#ec4899',
                type: 'expense' as 'expense' | 'recept',
            },
            loading: false,
            colorOptions: [
                { value: '#ec4899', label: 'rosa' },
                { value: '#a855f7', label: 'roxo' },
                { value: '#3b82f6', label: 'azul' },
                { value: '#06b6d4', label: 'ciano' },
                { value: '#10b981', label: 'verde' },
                { value: '#84cc16', label: 'verde limão' },
                { value: '#eab308', label: 'amarelo' },
                { value: '#f97316', label: 'laranja' },
                { value: '#ef4444', label: 'vermelho' },
                { value: '#6b7280', label: 'cinza' },
            ] as ColorOption[],
        };
    },
    computed: {
        availableIcons(): Record<string, Component> {
            // Objeto com os ícones disponíveis para acesso dinâmico
            return {
                ShoppingBag, ShoppingCart, Coffee, UtensilsCrossed, Building2, Home, Wifi, Zap, Gift, Heart, Wallet,
                GraduationCap, Plane, Bus, Car, Music, Film, Stethoscope, PawPrint, Clock, Gamepad2, Dumbbell,
                BookOpen, Camera, Palette, Trophy, Award, TreePine, Settings
            };
        },
        selectedIconComponent(): Component | null {
            if (!this.formData.icon) return null;
            const iconName = this.formData.icon;
            return this.availableIcons[iconName] || null;
        },
        previewColor(): string {
            return this.formData.color || '#ec4899';
        },
    },
    watch: {
        resetForm(newValue) {
            if (newValue) {
                this.resetFormData();
                this.$emit('reset-complete');
            }
        },
        category: {
            handler(newCategory) {
                if (newCategory) {
                    this.loadCategoryData(newCategory);
                } else {
                    this.resetFormData();
                }
            },
            immediate: true,
        },
    },
    methods: {
        loadCategoryData(category: any) {
            this.formData = {
                name: category.name || '',
                icon: category.icon || '',
                color: category.color || '#ec4899',
                type: category.type || 'expense',
            };
        },
        resetFormData() {
            this.formData = {
                name: '',
                icon: '',
                color: '#ec4899',
                type: 'expense',
            };
        },
        async handleSubmit() {
            if (!this.formData.name) {
                this.toast({
                    title: 'erro',
                    description: 'o nome da categoria é obrigatório',
                    variant: 'destructive',
                });
                return;
            }

            this.loading = true;

            try {
                const isEditing = !!this.category;

                const slug = this.formData.name
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/(^-|-$)/g, '');

                const data = {
                    name: this.formData.name,
                    slug: slug,
                    icon: this.formData.icon || null,
                    color: this.formData.color,
                    type: this.formData.type,
                };

                const response = isEditing
                    ? await apiPut(`/api/categories/${this.category.id}`, data)
                    : await apiPost('/api/categories', data);

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || `Failed to ${isEditing ? 'update' : 'create'} category`);
                }

                this.toast({
                    title: isEditing ? 'categoria atualizada com sucesso!' : 'categoria criada com sucesso!',
                    description: `a categoria "${this.formData.name}" foi ${isEditing ? 'atualizada' : 'criada'} com sucesso.`,
                    variant: 'default',
                });

                this.$emit('success');

                if (!isEditing) {
                    this.resetFormData();
                }
            } catch (error: any) {
                const isEditing = !!this.category;
                const errorMessage = error?.response?.data?.message ||
                                   error?.message ||
                                   `Erro ao ${isEditing ? 'atualizar' : 'criar'} categoria. Tente novamente.`;

                this.toast({
                    title: `erro ao ${isEditing ? 'atualizar' : 'criar'} categoria`,
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


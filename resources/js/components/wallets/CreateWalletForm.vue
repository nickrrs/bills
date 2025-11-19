<template>
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Formulário -->
        <div class="flex-1 flex flex-col gap-6 w-full">
            <div class="flex flex-col gap-4">
                <div class="flex flex-col gap-2">
                    <div class="flex flex-row items-baseline gap-1">
                        <label class="text-sm font-medium text-white">nome</label>
                        <span class="text-red-400 text-sm leading-none">*</span>
                    </div>
                    <input
                        v-model="formData.name"
                        required
                        type="text"
                        maxlength="20"
                        placeholder="escolha um nome para a carteira"
                        class="w-full px-3 py-2 bg-[#131316] border border-[#2F2F2F] rounded-md text-white placeholder-[#767676] focus:outline-none focus:ring-2 focus:ring-[#3800D8] focus:border-transparent"
                    />
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-white">tipo de carteira</label>
                    <div class="flex flex-col gap-2">
                        <label class="flex items-center gap-3 p-3 bg-[#131316] border border-[#2F2F2F] rounded-md cursor-pointer hover:border-[#3800D8] transition-colors">
                            <input
                                v-model="formData.type"
                                type="radio"
                                value="generic"
                                class="w-4 h-4 text-[#3800D8] focus:ring-2 focus:ring-[#3800D8]"
                            />
                            <div class="flex-1">
                                <span class="text-white font-medium">geral</span>
                                <p class="text-xs text-[#767676]">carteira para uso geral do dia a dia</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-[#131316] border border-[#2F2F2F] rounded-md cursor-pointer hover:border-[#3800D8] transition-colors">
                            <input
                                v-model="formData.type"
                                type="radio"
                                value="saving"
                                class="w-4 h-4 text-[#3800D8] focus:ring-2 focus:ring-[#3800D8]"
                            />
                            <div class="flex-1">
                                <span class="text-white font-medium">poupança / meta</span>
                                <p class="text-xs text-[#767676]">para economizar e alcançar seus objetivos</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-[#131316] border border-[#2F2F2F] rounded-md cursor-pointer hover:border-[#3800D8] transition-colors">
                            <input
                                v-model="formData.type"
                                type="radio"
                                value="investment"
                                class="w-4 h-4 text-[#3800D8] focus:ring-2 focus:ring-[#3800D8]"
                            />
                            <div class="flex-1">
                                <span class="text-white font-medium">investimento</span>
                                <p class="text-xs text-[#767676]">para acompanhar seus investimentos</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-white">cor do cartão</label>
                    <div class="grid grid-cols-5 gap-3">
                        <button
                            v-for="colorOption in colorOptions"
                            :key="colorOption.value"
                            type="button"
                            @click="formData.color = colorOption.value"
                            :class="[
                                'w-full aspect-square rounded-lg border-2 transition-all hover:scale-105',
                                formData.color === colorOption.value
                                    ? 'border-white ring-2 ring-[#3800D8] ring-offset-2 ring-offset-[#131316]'
                                    : 'border-[#2F2F2F] hover:border-[#3800D8]'
                            ]"
                            :style="{ background: colorOption.gradient }"
                            :title="colorOption.label"
                        >
                            <div v-if="formData.color === colorOption.value" class="w-full h-full flex items-center justify-center">
                                <div class="w-6 h-6 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>

                <WalletIconSelector v-model="formData.icon" :is-in-drawer="isInDrawer" />

                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-white">descrição</label>
                    <textarea
                        v-model="formData.description"
                        placeholder="qual é o objetivo dessa carteira?"
                        rows="3"
                        class="w-full px-3 py-2 bg-[#131316] border border-[#2F2F2F] rounded-md text-white placeholder-[#767676] focus:outline-none focus:ring-2 focus:ring-[#3800D8] focus:border-transparent resize-none"
                    ></textarea>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-white">saldo inicial</label>
                    <input
                        :value="formatBalanceInput(formData.balance)"
                        type="text"
                        placeholder="0,00"
                        @input="handleBalanceInput"
                        @keydown="preventInvalidKeys"
                        class="w-full px-3 py-2 bg-[#131316] border border-[#2F2F2F] rounded-md text-white placeholder-[#767676] focus:outline-none focus:ring-2 focus:ring-[#3800D8] focus:border-transparent"
                    />
                </div>

                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input
                            v-model="formData.active"
                            type="checkbox"
                            class="w-4 h-4 rounded border-[#2F2F2F] bg-[#131316] text-[#3800D8] focus:ring-2 focus:ring-[#3800D8]"
                        />
                        <span class="text-sm text-white">ativo</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input
                            v-model="formData.is_default"
                            type="checkbox"
                            class="w-4 h-4 rounded border-[#2F2F2F] bg-[#131316] text-[#3800D8] focus:ring-2 focus:ring-[#3800D8]"
                        />
                        <span class="text-sm text-white">selecionar como padrão?</span>
                    </label>
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
                    {{ wallet ? 'atualizar carteira' : 'criar carteira' }}
                </button>
            </div>
        </div>

        <!-- Prévia do Card -->
        <div class="lg:w-80 flex-shrink-0">
            <div class="sticky top-4">
                <h3 class="text-sm font-medium text-white">prévia da carteira</h3>
                <p class="text-xs text-[#767676] mb-4">é assim que a carteira vai aparecer na sua lista</p>
                <div class="w-full h-[260px] rounded-3xl shadow-[0_10px_30px_-10px_rgba(0,0,0,0.5)] border border-white/10 overflow-hidden" :style="{ background: previewTheme.gradient }">
                    <div class="flex h-full flex-col justify-between p-6">
                        <div class="flex items-start justify-between z-10">
                            <div class="flex items-center space-x-3">
                                <div class="rounded-lg bg-white/10 p-2 backdrop-blur-md flex items-center justify-center">
                                    <img
                                        v-if="formData.icon && formData.icon.startsWith('/images/')"
                                        :src="formData.icon"
                                        :alt="formData.name"
                                        class="h-6 w-6 object-contain"
                                    />
                                    <component
                                        v-else
                                        :is="previewTheme.icon"
                                        class="h-6 w-6"
                                        :class="previewTheme.iconColor"
                                    />
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">{{ formData.name || 'Nome da Carteira' }}</h3>
                                    <p class="text-xs font-medium uppercase tracking-wide" :class="previewTheme.tagColor">
                                        {{ formData.description || 'Sem descrição' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="z-10 flex flex-1 flex-col justify-center pr-4">
                            <p class="mb-1 text-xs uppercase tracking-wider" :class="previewTheme.mutedText">Saldo Atual</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-3xl font-bold text-white tracking-tight">R$</span>
                                <div
                                    class="overflow-hidden relative balance-container"
                                    :class="{ 'balance-container--scrollable': needsScrollAnimation }"
                                >
                                    <span
                                        :class="[
                                            'text-3xl font-bold text-white tracking-tight whitespace-nowrap',
                                            needsScrollAnimation ? 'balance-scroll-animation' : ''
                                        ]"
                                    >
                                        {{ formatCurrencyValue(formData.balance || 0) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <svg class="card-sparkline-preview" viewBox="0 0 100 25" preserveAspectRatio="none">
                            <path :d="previewSparkline" fill="none" stroke="rgba(255,255,255,0.6)" stroke-width="2" />
                        </svg>

                        <div class="z-10 mt-2 flex items-center justify-between border-t border-white/10 pt-4">
                            <div class="flex items-center space-x-2" :class="previewTheme.footerText">
                                <CreditCard class="h-4 w-4" />
                                <span class="text-xs font-medium">
                                    {{ formData.active ? 'Carteira ativa' : 'Carteira inativa' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { LoaderCircle, CreditCard, Wallet2, ShieldCheck, Smartphone, Banknote, PiggyBank, TrendingUp } from 'lucide-vue-next';
import type { Component } from 'vue';
import { apiPost, apiPut } from '@/utils/api';
import { useToast } from '@/components/ui/toast';
import WalletIconSelector from './WalletIconSelector.vue';

interface ColorOption {
    value: string;
    label: string;
    gradient: string;
}

interface WalletTheme {
    gradient: string;
    icon: Component;
    iconColor: string;
    tagColor: string;
    mutedText: string;
    footerText: string;
}

const sparkLines = {
    withMoney: 'M0 20 L15 15 L30 18 L45 10 L60 14 L75 5 L100 12',
    withoutMoney: 'M0 15 L20 15 L40 15 L60 15 L80 15 L100 15',
}

const COLOR_THEMES: Record<string, WalletTheme> = {
    indigo: {
        gradient: 'linear-gradient(180deg, #3730a3 0%, #312e81 100%)',
        icon: Wallet2,
        iconColor: 'text-indigo-200',
        tagColor: 'text-indigo-200',
        mutedText: 'text-indigo-300',
        footerText: 'text-indigo-200/80',
    },
    emerald: {
        gradient: 'linear-gradient(180deg, #065f46 0%, #064e3b 100%)',
        icon: ShieldCheck,
        iconColor: 'text-emerald-200',
        tagColor: 'text-emerald-200',
        mutedText: 'text-emerald-300',
        footerText: 'text-emerald-200/80',
    },
    purple: {
        gradient: 'linear-gradient(180deg, #7c3aed 0%, #5b21b6 100%)',
        icon: Smartphone,
        iconColor: 'text-purple-200',
        tagColor: 'text-purple-200',
        mutedText: 'text-purple-200/80',
        footerText: 'text-purple-200/70',
    },
    gray: {
        gradient: 'linear-gradient(180deg, #374151 0%, #1f2937 100%)',
        icon: Banknote,
        iconColor: 'text-gray-200',
        tagColor: 'text-gray-300',
        mutedText: 'text-gray-400',
        footerText: 'text-gray-400/80',
    },
    pink: {
        gradient: 'linear-gradient(180deg, #be185d 0%, #9f1239 100%)',
        icon: PiggyBank,
        iconColor: 'text-pink-200',
        tagColor: 'text-pink-200',
        mutedText: 'text-pink-300',
        footerText: 'text-pink-200/80',
    },
};

const TYPE_ICONS: Record<string, Component> = {
    generic: Wallet2,
    saving: PiggyBank,
    investment: TrendingUp,
};

export default {
    name: 'CreateWalletForm',
    components: {
        LoaderCircle,
        CreditCard,
        Wallet2,
        ShieldCheck,
        Smartphone,
        Banknote,
        PiggyBank,
        TrendingUp,
        WalletIconSelector,
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
        wallet: {
            type: Object as () => any,
            default: null,
        },
        isInDrawer: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            formData: {
                name: '',
                icon: '',
                description: '',
                balance: 0 as number | string,
                active: true,
                is_default: false,
                type: 'generic' as 'generic' | 'saving' | 'investment',
                color: 'indigo',
            },
            loading: false,
            colorOptions: [
                { value: 'indigo', label: 'Roxo', gradient: 'linear-gradient(180deg, #3730a3 0%, #312e81 100%)' },
                { value: 'emerald', label: 'Verde', gradient: 'linear-gradient(180deg, #065f46 0%, #064e3b 100%)' },
                { value: 'purple', label: 'Roxo Claro', gradient: 'linear-gradient(180deg, #7c3aed 0%, #5b21b6 100%)' },
                { value: 'gray', label: 'Cinza', gradient: 'linear-gradient(180deg, #374151 0%, #1f2937 100%)' },
                { value: 'pink', label: 'Rosa', gradient: 'linear-gradient(180deg, #be185d 0%, #9f1239 100%)' },
            ] as ColorOption[],
        };
    },
    computed: {
        previewTheme(): WalletTheme {
            const baseTheme = COLOR_THEMES[this.formData.color] || COLOR_THEMES.indigo;
            const typeIcon = TYPE_ICONS[this.formData.type] || Wallet2;
            return {
                ...baseTheme,
                icon: typeIcon,
            };
        },
        previewSparkline(): string {
            const balance = this.formData.balance || 0;
            const numBalance = typeof balance === 'string' ? parseFloat(balance.replace(',', '.')) || 0 : balance;
            return numBalance > 0 ? sparkLines.withMoney : sparkLines.withoutMoney;
        },
        needsScrollAnimation(): boolean {
            const formattedValue = this.formatCurrencyValue(this.formData.balance || 0);
            return formattedValue.length > 12;
        },
    },
    watch: {
        resetForm(newValue) {
            if (newValue) {
                this.resetFormData();
                this.$emit('reset-complete');
            }
        },
        wallet: {
            handler(newWallet) {
                if (newWallet) {
                    this.loadWalletData(newWallet);
                } else {
                    this.resetFormData();
                }
            },
            immediate: true,
        },
    },
    methods: {
        formatCurrency(value: number): string {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL',
            }).format(value);
        },
        formatCurrencyValue(value: number | string): string {
            let numValue: number;
            let valueStr: string;

            if (typeof value === 'string') {
                valueStr = value.replace(',', '.');
                if (valueStr.length > 15 && /^\d+(\.\d*)?$/.test(valueStr)) {
                    const parts = valueStr.split('.');
                    const integerPart = parts[0];
                    const decimalPart = parts[1] || '00';
                    const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    const formattedDecimal = decimalPart.padEnd(2, '0').substring(0, 2);
                    return `${formattedInteger},${formattedDecimal}`;
                }
                numValue = parseFloat(valueStr) || 0;
            } else {
                numValue = value || 0;
            }

            if (numValue >= 1e15 || isNaN(numValue)) {
                const str = numValue.toString();
                if (str.includes('e+') || str.includes('E+')) {
                    return str;
                }
                const parts = str.split('.');
                const integerPart = parts[0];
                const decimalPart = parts[1] || '00';
                const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                const formattedDecimal = decimalPart.padEnd(2, '0').substring(0, 2);
                return `${formattedInteger},${formattedDecimal}`;
            }

            return new Intl.NumberFormat('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(numValue);
        },
        formatBalanceInput(value: number | string): string {
            if (value === null || value === undefined || value === '' || value === 0) {
                return '';
            }

            let numValue: number;

            if (typeof value === 'string') {
                numValue = parseFloat(value.replace(',', '.')) || 0;
            } else {
                numValue = value;
            }

            if (isNaN(numValue) || !isFinite(numValue) || numValue < 0) {
                return '';
            }

            const formatted = numValue.toFixed(2).replace(/\.?0+$/, '');
            return formatted.replace('.', ',');
        },
        loadWalletData(wallet: any) {
            this.formData = {
                name: wallet.name || '',
                icon: wallet.icon || '',
                description: wallet.description || '',
                balance: wallet.balance || 0,
                active: wallet.active ?? true,
                is_default: wallet.is_default ?? false,
                type: wallet.type || 'generic',
                color: wallet.color || 'indigo',
            };
        },
        async handleSubmit() {
            this.loading = true;

            try {
                const isEditing = !!this.wallet;

                const slug = this.formData.name
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/(^-|-$)/g, '');

                let balanceValue = this.formData.balance;
                if (typeof balanceValue === 'string') {
                    balanceValue = parseFloat(balanceValue.replace(',', '.')) || 0;
                }
                balanceValue = Math.round((balanceValue as number) * 100) / 100;

                const data = {
                    ...this.formData,
                    slug,
                    icon: this.formData.icon || null,
                    description: this.formData.description || null,
                    balance: balanceValue,
                };

                const response = isEditing
                    ? await apiPut(`/api/wallets/${this.wallet.id}`, data)
                    : await apiPost('/api/wallets', data);

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || `Failed to ${isEditing ? 'update' : 'create'} wallet`);
                }

                this.toast({
                    title: isEditing ? 'carteira atualizada com sucesso!' : 'carteira criada com sucesso!',
                    description: `a carteira "${this.formData.name}" foi ${isEditing ? 'atualizada' : 'criada'} com sucesso.`,
                    variant: 'default',
                });

                if (this.formData.is_default) {
                    window.dispatchEvent(new CustomEvent('wallet-default-created-or-updated'));
                }

                this.$emit('success');

                if (!isEditing) {
                    this.resetFormData();
                }
            } catch (error: any) {
                const isEditing = !!this.wallet;
                const errorMessage = error?.response?.data?.message ||
                                   error?.message ||
                                   `Erro ao ${isEditing ? 'atualizar' : 'criar'} carteira. Tente novamente.`;

                this.toast({
                    title: `erro ao ${isEditing ? 'atualizar' : 'criar'} carteira`,
                    description: errorMessage,
                    variant: 'destructive',
                });
            } finally {
                this.loading = false;
            }
        },
        handleBalanceInput(event: Event) {
            const target = event.target as HTMLInputElement;
            let value = target.value;

            value = value.replace(/[^0-9.,]/g, '');
            value = value.replace(/,/g, '.');

            const parts = value.split('.');
            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }

            if (parts.length === 2 && parts[1].length > 2) {
                value = parts[0] + '.' + parts[1].substring(0, 2);
            }

            if (value.startsWith('-')) {
                value = value.replace('-', '');
            }

            if (value === '' || value === '.') {
                this.formData.balance = 0;
                target.value = '';
                return;
            }

            const isValidNumber = /^\d+(\.\d{0,2})?$/.test(value);

            if (!isValidNumber) {
                const lastValid = this.formData.balance || 0;
                target.value = this.formatBalanceInput(lastValid);
                return;
            }

            const numValue = parseFloat(value);

            if (!isNaN(numValue) && numValue >= 0 && isFinite(numValue)) {
                this.formData.balance = Math.round(numValue * 100) / 100;
            } else {
                const lastValid = this.formData.balance || 0;
                target.value = this.formatBalanceInput(lastValid);
                return;
            }

            const displayValue = this.formatBalanceInput(this.formData.balance);
            target.value = displayValue;
        },
        preventInvalidKeys(event: KeyboardEvent) {
            const key = event.key;
            const input = event.target as HTMLInputElement;
            const value = input.value;

            const allowedKeys = [
                'Backspace', 'Delete', 'Tab', 'Escape', 'Enter',
                'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown',
                'Home', 'End'
            ];

            if (event.ctrlKey || event.metaKey) {
                if (['a', 'c', 'v', 'x'].includes(key.toLowerCase())) {
                    return;
                }
            }

            if (allowedKeys.includes(key)) {
                return;
            }

            if (/[0-9]/.test(key)) {
                return;
            }

            if (key === '.' || key === ',') {
                if (value.includes('.') || value.includes(',')) {
                    event.preventDefault();
                    return;
                }
                return;
            }

            event.preventDefault();
        },
        resetFormData() {
            this.formData = {
                name: '',
                icon: '',
                description: '',
                balance: 0 as number | string,
                active: true,
                is_default: false,
                type: 'generic',
                color: 'indigo',
            };
        },
    },
};
</script>

<style scoped>
.card-sparkline-preview {
    position: absolute;
    bottom: 60px;
    left: 0;
    width: 100%;
    height: 60px;
    opacity: 0.4;
    pointer-events: none;
}

.card-sparkline-preview path {
    stroke-dasharray: 300;
    stroke-dashoffset: 300;
    animation: drawLine 2.5s ease-out forwards;
    filter: drop-shadow(0px 4px 6px rgba(0, 0, 0, 0.2));
}

@keyframes drawLine {
    to {
        stroke-dashoffset: 0;
    }
}

.balance-container {
    max-width: 100%;
    position: relative;
}

.balance-container--scrollable {
    max-width: 10rem;
}

.balance-scroll-animation {
    display: inline-block;
    animation: scrollBalanceHorizontal 8s ease-in-out infinite;
    will-change: transform;
}

@keyframes scrollBalanceHorizontal {
    0% {
        transform: translateX(0);
    }
    25% {
        transform: translateX(0);
    }
    50% {
        transform: translateX(calc(-100% + 10rem));
    }
    75% {
        transform: translateX(calc(-100% + 10rem));
    }
    100% {
        transform: translateX(0);
    }
}
</style>


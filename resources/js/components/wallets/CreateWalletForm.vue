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

                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-white">icone (opcional)</label>
                    <input
                        v-model="formData.icon"
                        type="text"
                        placeholder="ex: 💰"
                        maxlength="2"
                        class="w-full px-3 py-2 bg-[#131316] border border-[#2F2F2F] rounded-md text-white placeholder-[#767676] focus:outline-none focus:ring-2 focus:ring-[#3800D8] focus:border-transparent text-center text-2xl"
                    />
                    <span class="text-xs text-[#767676]">escolha um emoji ou icone</span>
                </div>

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
                        <span class="text-sm text-white">pre-selecionar como padrão?</span>
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
                    :disabled="loading"
                    class="px-4 py-2 text-sm font-medium bg-[#6965f2] text-white rounded-md hover:bg-[#3800D8] transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                >
                    <LoaderCircle v-if="loading" class="h-4 w-4 animate-spin" />
                    criar carteira
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
                                <div class="rounded-lg bg-white/10 p-2 backdrop-blur-md">
                                    <component :is="previewTheme.icon" class="h-6 w-6" :class="previewTheme.iconColor" />
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
import { apiPost } from '@/utils/api';
import { useToast } from '@/components/ui/toast';

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
    },
    emits: ['success', 'cancel'],
    setup() {
        const { toast } = useToast();
        return { toast };
    },
    props: {
        resetTrigger: {
            type: Number,
            default: 0,
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
            // Ajusta o ícone baseado no tipo
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
            // Considera animação necessária se o valor formatado tiver mais de 12 caracteres
            // (exemplo: "1.000.000,00" tem 12 caracteres)
            return formattedValue.length > 12;
        },
    },
    watch: {
        resetTrigger() {
            this.resetForm();
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
            // Retorna apenas o valor numérico sem o "R$"
            let numValue: number;
            let valueStr: string;

            if (typeof value === 'string') {
                valueStr = value.replace(',', '.');
                // Para strings muito longas (números gigantes), formata diretamente
                if (valueStr.length > 15 && /^\d+(\.\d*)?$/.test(valueStr)) {
                    const parts = valueStr.split('.');
                    const integerPart = parts[0];
                    const decimalPart = parts[1] || '00';

                    // Formata a parte inteira com separadores de milhar
                    const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                    // Garante 2 casas decimais
                    const formattedDecimal = decimalPart.padEnd(2, '0').substring(0, 2);

                    return `${formattedInteger},${formattedDecimal}`;
                }
                // Converte string para número, tratando vírgula
                numValue = parseFloat(valueStr) || 0;
            } else {
                numValue = value || 0;
            }

            // Para números muito grandes, formata manualmente para evitar notação científica
            if (numValue >= 1e15 || isNaN(numValue)) {
                // Usa formatação manual para números muito grandes
                const str = numValue.toString();
                // Se estiver em notação científica, retorna como está (não deveria acontecer com nossa lógica)
                if (str.includes('e+') || str.includes('E+')) {
                    return str;
                }
                const parts = str.split('.');
                const integerPart = parts[0];
                const decimalPart = parts[1] || '00';

                // Formata a parte inteira com separadores de milhar
                const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                // Garante 2 casas decimais
                const formattedDecimal = decimalPart.padEnd(2, '0').substring(0, 2);

                return `${formattedInteger},${formattedDecimal}`;
            }

            // Para números menores, usa Intl.NumberFormat
            return new Intl.NumberFormat('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(numValue);
        },
        formatBalanceInput(value: number | string): string {
            // Formata o valor para exibição no input, permitindo edição
            if (value === null || value === undefined || value === '' || value === 0) {
                return '';
            }

            // Se for string, retorna diretamente (já está no formato correto)
            if (typeof value === 'string') {
                return value.replace('.', ',');
            }

            // Se for número, converte para string sem notação científica
            // Para números grandes, usa toFixed(0) e depois remove zeros desnecessários
            if (typeof value === 'number' && value >= 1e15) {
                // Para números muito grandes, mantém como string sem decimais
                return value.toFixed(0).replace('.', ',');
            }

            // Para números menores, converte normalmente
            const str = value.toString();
            return str.replace('.', ',');
        },
        async handleSubmit() {
            this.loading = true;

            try {
                // Generate slug from name
                const slug = this.formData.name
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/(^-|-$)/g, '');

                const data = {
                    ...this.formData,
                    slug,
                    icon: this.formData.icon || null,
                    description: this.formData.description || null,
                };

                // Fazer a requisição de criação
                const response = await apiPost('/api/wallets', data);

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Failed to create wallet');
                }

                // Toast de sucesso
                this.toast({
                    title: 'Carteira criada com sucesso!',
                    description: `A carteira "${this.formData.name}" foi criada com sucesso.`,
                    variant: 'default',
                });

                // Emitir evento de sucesso para o componente pai
                this.$emit('success');

                // Resetar o formulário
                this.resetForm();
            } catch (error: any) {
                // Toast de erro
                const errorMessage = error?.response?.data?.message ||
                                   error?.message ||
                                   'Erro ao criar carteira. Tente novamente.';

                this.toast({
                    title: 'Erro ao criar carteira',
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

            // Remove qualquer caractere que não seja número ou ponto/vírgula
            value = value.replace(/[^0-9.,]/g, '');

            // Converte vírgula para ponto para processamento
            value = value.replace(/,/g, '.');

            // Remove múltiplos pontos, mantendo apenas o primeiro
            const parts = value.split('.');
            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }

            // Garante que não seja negativo
            if (value.startsWith('-')) {
                value = value.replace('-', '');
            }

            // Se estiver vazio, define como 0
            if (value === '' || value === '.') {
                this.formData.balance = 0;
                target.value = '';
                return;
            }

            // Valida se é um formato numérico válido
            const isValidNumber = /^\d+(\.\d*)?$/.test(value);

            if (!isValidNumber) {
                // Se não for válido, mantém o último valor válido
                const lastValid = this.formData.balance || 0;
                if (typeof lastValid === 'string') {
                    target.value = lastValid.replace('.', ',');
                } else {
                    target.value = lastValid === 0 ? '' : lastValid.toString().replace('.', ',');
                }
                return;
            }

            // Para números muito grandes (mais de 15 dígitos), mantém como string
            // para evitar conversão para notação científica
            const integerPart = parts[0];
            if (integerPart.length > 15) {
                // Mantém como string para preservar todos os dígitos
                this.formData.balance = value;
            } else {
                // Para números menores, converte para número
                const numValue = parseFloat(value);
                if (!isNaN(numValue) && numValue >= 0) {
                    this.formData.balance = numValue;
                } else {
                    const lastValid = this.formData.balance || 0;
                    if (typeof lastValid === 'string') {
                        target.value = lastValid.replace('.', ',');
                    } else {
                        target.value = lastValid === 0 ? '' : lastValid.toString().replace('.', ',');
                    }
                    return;
                }
            }

            // Atualiza o valor do input mantendo o formato com vírgula
            const displayValue = value.replace('.', ',');
            target.value = displayValue;
        },
        preventInvalidKeys(event: KeyboardEvent) {
            const key = event.key;
            const input = event.target as HTMLInputElement;
            const value = input.value;

            // Permite: números, ponto, vírgula, backspace, delete, tab, escape, enter, setas
            const allowedKeys = [
                'Backspace', 'Delete', 'Tab', 'Escape', 'Enter',
                'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown',
                'Home', 'End'
            ];

            // Permite Ctrl/Cmd + A, C, V, X
            if (event.ctrlKey || event.metaKey) {
                if (['a', 'c', 'v', 'x'].includes(key.toLowerCase())) {
                    return;
                }
            }

            // Se for uma tecla permitida, deixa passar
            if (allowedKeys.includes(key)) {
                return;
            }

            // Se for um número, deixa passar
            if (/[0-9]/.test(key)) {
                return;
            }

            // Se for ponto ou vírgula e ainda não tiver no valor, deixa passar
            if ((key === '.' || key === ',') && !value.includes('.') && !value.includes(',')) {
                return;
            }

            // Bloqueia qualquer outra tecla
            event.preventDefault();
        },
        resetForm() {
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

/* Container para números grandes */
.balance-container {
    max-width: 100%;
    position: relative;
}

.balance-container--scrollable {
    max-width: 10rem; /* Largura máxima visível */
}

/* Animação de scroll horizontal para números grandes */
.balance-scroll-animation {
    display: inline-block;
    animation: scrollBalanceHorizontal 8s ease-in-out infinite;
    will-change: transform;
}

@keyframes scrollBalanceHorizontal {
    0% {
        /* Mostra o início do número */
        transform: translateX(0);
    }
    25% {
        /* Pausa no início */
        transform: translateX(0);
    }
    50% {
        /* Move para mostrar o final do número */
        transform: translateX(calc(-100% + 10rem));
    }
    75% {
        /* Pausa no final */
        transform: translateX(calc(-100% + 10rem));
    }
    100% {
        /* Volta ao início suavemente */
        transform: translateX(0);
    }
}
</style>


<template>
    <div class="rounded-lg border border-border bg-card">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-border bg-muted/50">
                    <tr>
                        <th v-if="isSelectionMode" class="w-12 p-4">
                            <div class="flex items-center justify-center">
                                <div
                                    class="flex h-5 w-5 cursor-pointer items-center justify-center rounded border-2 transition-all"
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
                        <th class="p-4 text-left text-sm font-semibold text-foreground">carteira</th>
                        <th class="p-4 text-left text-sm font-semibold text-foreground">tipo</th>
                        <th class="p-4 text-right text-sm font-semibold text-foreground">saldo</th>
                        <th class="p-4 text-center text-sm font-semibold text-foreground">status</th>
                        <th class="p-4 text-center text-sm font-semibold text-foreground">padrão</th>
                        <th class="p-4 text-center text-sm font-semibold text-foreground">criada em</th>
                        <th class="p-4 text-center text-sm font-semibold text-foreground">atualizada em</th>
                        <th class="w-16 p-4 text-center text-sm font-semibold text-foreground">detalhes</th>
                        <th v-if="!isSelectionMode" class="w-32 p-4 text-center text-sm font-semibold text-foreground">ações</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="wallet in wallets" :key="wallet.id">
                        <tr
                            class="border-b border-border transition-colors hover:bg-muted/30"
                            :class="{
                                'bg-accent/20': isSelectionMode && selectedIds.includes(wallet.id),
                                'cursor-pointer': isSelectionMode,
                                'border-b-0': expandedWallet === wallet.id,
                            }"
                            @click="isSelectionMode ? $emit('toggle-selection', wallet.id) : null"
                        >
                        <td v-if="isSelectionMode" class="p-4">
                            <div class="flex items-center justify-center">
                                <div
                                    class="flex h-5 w-5 items-center justify-center rounded border-2 transition-all"
                                    :class="selectedIds.includes(wallet.id) ? 'border-accent-primary bg-accent-primary' : 'border-border bg-card'"
                                >
                                    <svg
                                        v-if="selectedIds.includes(wallet.id)"
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
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg"
                                    :style="{ background: getWalletGradient(wallet) }"
                                >
                                    <img
                                        v-if="wallet.icon && wallet.icon.startsWith('/images/')"
                                        :src="wallet.icon"
                                        :alt="wallet.name"
                                        class="h-6 w-6 object-contain"
                                    />
                                    <component
                                        v-else
                                        :is="getWalletIcon(wallet)"
                                        class="h-6 w-6 text-white"
                                    />
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-foreground">{{ wallet.name }}</span>
                                        <Star v-if="isDefaultWallet(wallet)" class="h-4 w-4 fill-yellow-400 text-yellow-400" />
                                    </div>
                                    <div class="text-sm text-muted-foreground">{{ wallet.description || 'Sem descrição' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <span
                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                :class="getTypeStyle(wallet.type)"
                            >
                                {{ getTypeLabel(wallet.type) }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex flex-col items-end">
                                <span class="text-lg font-bold text-foreground">
                                    R$ {{ formatCurrencyValue(wallet.balance ?? 0) }}
                                </span>
                                <div class="flex items-center gap-1 text-xs text-muted-foreground">
                                    <TrendingUp class="h-3 w-3 text-green-500" />
                                    <span>+12% este mês</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <span
                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                :class="wallet.active
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400'
                                    : 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400'"
                            >
                                {{ wallet.active ? 'ativa' : 'inativa' }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center">
                                <Star
                                    v-if="isDefaultWallet(wallet)"
                                    class="h-5 w-5 fill-yellow-400 text-yellow-400"
                                    title="Carteira padrão"
                                />
                                <span v-else class="text-muted-foreground">-</span>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <span class="text-sm text-muted-foreground">{{ formatDate(wallet.created_at) }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <span class="text-sm text-muted-foreground">{{ formatDate(wallet.updated_at) }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <button
                                @click.stop="toggleWalletDetails(wallet.id)"
                                class="rounded-md p-1.5 text-muted-foreground transition-colors hover:bg-accent hover:text-foreground"
                                :class="{ 'text-accent-primary bg-accent': expandedWallet === wallet.id }"
                                title="Ver detalhes"
                            >
                                <ChevronDown
                                    class="h-4 w-4 transition-transform duration-200"
                                    :class="{ 'rotate-180': expandedWallet === wallet.id }"
                                />
                            </button>
                        </td>
                        <td v-if="!isSelectionMode" class="p-4">
                            <div class="flex items-center justify-center gap-1">
                                <button
                                    @click.stop="$emit('manage-balance', wallet.id)"
                                    class="rounded-md p-1.5 text-muted-foreground transition-colors hover:bg-accent hover:text-foreground"
                                    title="Gerenciar Saldo"
                                >
                                    <DollarSign class="h-4 w-4" />
                                </button>
                                <button
                                    @click.stop="$emit('edit', wallet.id)"
                                    class="rounded-md p-1.5 text-muted-foreground transition-colors hover:bg-accent hover:text-foreground"
                                    title="Editar"
                                >
                                    <Settings2 class="h-4 w-4" />
                                </button>
                                <button
                                    @click.stop="$emit('delete', wallet.id)"
                                    class="rounded-md p-1.5 text-muted-foreground transition-colors hover:bg-destructive hover:text-destructive-foreground"
                                    title="Excluir"
                                >
                                    <Trash2 class="h-4 w-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Linha de detalhes expansível -->
                    <tr
                        v-if="expandedWallet === wallet.id"
                        class="border-b border-border bg-muted/20"
                    >
                        <td :colspan="isSelectionMode ? 10 : 9" class="p-0">
                            <div class="overflow-hidden">
                                <div
                                    class="animate-in slide-in-from-top-2 duration-200 p-6"
                                    @click.stop
                                >
                                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                        <!-- Atividade Financeira -->
                                        <div class="space-y-3">
                                            <h4 class="text-sm font-semibold text-foreground border-b border-border pb-2">
                                                atividade financeira
                                            </h4>
                                            <div class="space-y-3">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-muted-foreground">transações este mês</span>
                                                    <span class="text-sm font-bold text-accent-primary">0</span>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-muted-foreground">transferências realizadas</span>
                                                    <span class="text-sm font-bold text-accent-primary">0</span>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-muted-foreground">última movimentação</span>
                                                    <span class="text-xs text-muted-foreground">nenhuma</span>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-muted-foreground">maior transação</span>
                                                    <span class="text-xs font-medium text-foreground">R$ 0,00</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Configurações e Limites -->
                                        <div class="space-y-3">
                                            <h4 class="text-sm font-semibold text-foreground border-b border-border pb-2">
                                                configurações
                                            </h4>
                                            <div class="space-y-3">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-muted-foreground">cartões vinculados</span>
                                                    <span class="text-sm font-bold text-accent-primary">0</span>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-muted-foreground">limite de gastos</span>
                                                    <span class="text-xs text-muted-foreground">não definido</span>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-muted-foreground">notificações</span>
                                                    <span class="text-xs font-medium text-green-500">ativas</span>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-muted-foreground">backup automático</span>
                                                    <span class="text-xs font-medium text-green-500">ativo</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Gráfico de Atividade (Placeholder) -->
                                    <div class="mt-6 pt-4 border-t border-border">
                                        <div class="flex items-center justify-between mb-3">
                                            <h4 class="text-sm font-semibold text-foreground">
                                                atividade dos últimos 7 dias
                                            </h4>
                                            <span class="text-xs text-muted-foreground">sem movimentações</span>
                                        </div>
                                        <div class="h-16 w-full rounded-lg bg-muted/30 flex items-center justify-center">
                                            <div class="flex items-center gap-2 text-muted-foreground">
                                                <TrendingUp class="h-4 w-4" />
                                                <span class="text-xs">gráfico será exibido quando houver dados</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <div v-if="wallets.length === 0" class="flex flex-col items-center justify-center p-10 text-center">
            <p class="text-base font-medium text-muted-foreground">nenhuma carteira encontrada!</p>
            <p class="text-sm text-muted-foreground">clique em "nova carteira" para criar sua primeira carteira.</p>
        </div>
    </div>
</template>

<script lang="ts">
import { formatDate, formatCurrencyValue } from '@/utils/formatters';
import { DollarSign, Settings2, Trash2, Star, TrendingUp, Wallet2, PiggyBank, ChevronDown } from 'lucide-vue-next';
import type { Component } from 'vue';
import type { Wallet } from '@/types';

const TYPE_ICONS: Record<string, Component> = {
    generic: Wallet2,
    saving: PiggyBank,
    investment: TrendingUp,
};

const COLOR_GRADIENTS: Record<string, string> = {
    indigo: 'linear-gradient(180deg, #3730a3 0%, #312e81 100%)',
    emerald: 'linear-gradient(180deg, #065f46 0%, #064e3b 100%)',
    purple: 'linear-gradient(180deg, #7c3aed 0%, #5b21b6 100%)',
    gray: 'linear-gradient(180deg, #374151 0%, #1f2937 100%)',
    pink: 'linear-gradient(180deg, #be185d 0%, #9f1239 100%)',
};

export default {
    name: 'WalletsTable',
    components: {
        DollarSign,
        Settings2,
        Trash2,
        Star,
        TrendingUp,
        ChevronDown,
    },
    props: {
        wallets: {
            type: Array as () => Wallet[],
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
    },
    emits: ['edit', 'delete', 'manage-balance', 'toggle-selection', 'toggle-all'],
    data() {
        return {
            expandedWallet: null as number | null,
        };
    },
    methods: {
        formatDate,
        formatCurrencyValue,
        getWalletIcon(wallet: Wallet): Component {
            return TYPE_ICONS[wallet.type] || Wallet2;
        },
        getWalletGradient(wallet: Wallet): string {
            const walletColor = wallet.color && COLOR_GRADIENTS[wallet.color] ? wallet.color : 'gray';
            return COLOR_GRADIENTS[walletColor];
        },
        getTypeLabel(type: string): string {
            const labels: Record<string, string> = {
                generic: 'genérica',
                saving: 'poupança',
                investment: 'investimento',
            };
            return labels[type] || 'genérica';
        },
        getTypeStyle(type: string): string {
            const styles: Record<string, string> = {
                generic: 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400',
                saving: 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
                investment: 'bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-400',
            };
            return styles[type] || 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400';
        },
        isDefaultWallet(wallet: Wallet): boolean {
            return wallet.is_default === true || wallet.is_default === '1' || wallet.is_default === 'true';
        },
        toggleWalletDetails(walletId: number) {
            this.expandedWallet = this.expandedWallet === walletId ? null : walletId;
        },
    },
};
</script>

<style scoped>
@keyframes slide-in-from-top {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-in {
    animation-fill-mode: both;
}

.slide-in-from-top-2 {
    animation-name: slide-in-from-top;
}

.duration-200 {
    animation-duration: 200ms;
}
</style>

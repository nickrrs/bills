<template>
    <InertiaHead title="wallets" />
    <MainLayout :no-sub-nav="true">
        <div class="w-full h-full px-8 py-8">
            <div class="w-full flex items-center justify-between mb-4">
                <div class="flex flex-col gap-1">
                    <h1 class="text-white text-3xl font-bold">minhas carteiras</h1>
                    <span class="text-sm text-[#B6B6B6]">crie e personalize suas carteiras para suas necessidades</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span v-if="!isRefreshing" class="text-sm text-[#B6B6B6]">informações atualizadas a cada 45 segundos</span>
                        <div v-if="isRefreshing" class="flex items-center gap-2 text-[#6965f2]">
                            <LoaderCircle class="!h-4 !w-4 animate-spin" />
                            <span class="text-xs">atualizando...</span>
                        </div>
                    </div>
                    <button
                        @click="openModal"
                        class="flex items-center gap-x-2 px-4 py-2 bg-[#1E1E1E] border border-[#2F2F2F] text-white hover:bg-[#313131] rounded-md transition-colors"
                    >
                        <Plus class="!h-4 !w-4" />
                        nova carteira
                    </button>
                </div>
            </div>
            <div class="bg-gradient-to-r from-[#1e1b4b] to-[#0f172a] rounded-[20px] border-2 border-[#252c3e] p-8 mb-10 flex items-center justify-between">
                <div>
                    <p class="text-sm text-indigo-300 uppercase font-semibold tracking-wider mb-2">Patrimônio Total</p>
                    <div class="flex items-baseline gap-1">
                        <span
                            :class="[
                                'text-5xl font-bold transition-all duration-500',
                                balanceAnimationClass
                            ]"
                        >
                            R$
                        </span>
                        <div
                            class="overflow-hidden relative balance-container"
                            :class="{
                                'balance-container--scrollable': needsTotalBalanceScrollAnimation,
                                'balance-container--large': needsTotalBalanceScrollAnimation
                            }"
                        >
                            <h2
                                :class="[
                                    'text-5xl font-bold transition-all duration-500 whitespace-nowrap',
                                    balanceAnimationClass,
                                    needsTotalBalanceScrollAnimation ? 'balance-scroll-animation' : ''
                                ]"
                                :key="`balance-${totalBalance}`"
                            >
                                {{ formatCurrencyValue(totalBalance) }}
                            </h2>
                        </div>
                    </div>
                    <div class="flex items-center mt-3 space-x-4">
                        <span v-if="calculatePercentageChange() > 0" class="flex items-center text-emerald-400 text-sm font-medium bg-emerald-400/10 px-2 py-1 rounded">
                            <TrendingUp class="!h-4 !w-4 mr-1" /> {{ calculatePercentageChange() }}% este mês
                        </span>
                        <span v-else class="flex items-center text-red-400 text-sm font-medium bg-red-400/10 px-2 py-1 rounded">
                            <TrendingDown class="!h-4 !w-4 mr-1" /> {{ calculatePercentageChange() }}% este mês
                        </span>
                        <span class="text-gray-400 text-sm">disponível em {{ pagination?.total ?? wallets.length }} carteiras</span>
                    </div>
                </div>
                <div class="hidden md:block opacity-50">
                    <svg width="200" height="60" viewBox="0 0 200 60">
                        <path d="M0 50 Q 30 40, 50 45 T 100 20 T 150 30 T 200 5" fill="none" stroke="#6366f1" stroke-width="3" />
                        <path d="M0 50 Q 30 40, 50 45 T 100 20 T 150 30 T 200 5 V 60 H 0 Z" fill="url(#grad1)" opacity="0.3" />
                        <defs>
                            <linearGradient id="grad1" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" style="stop-color:#6366f1;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#6366f1;stop-opacity:0" />
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 pb-4">
                <template v-if="wallets.length">
                    <WalletFlipCard
                        v-for="wallet in wallets"
                        :key="wallet.id"
                        :flipped="flippedCards[wallet.id]"
                        :front-style="{ background: getWalletTheme(wallet).gradient }"
                        class="cursor-pointer hover:scale-105 transition-all duration-300"
                        @toggle="toggleCard(wallet.id)"
                    >
                        <template #front>
                            <div class="flex h-full flex-col justify-between" :style="{ background: getWalletTheme(wallet).gradient }">
                                <div class="flex items-start justify-between z-10">
                                    <div class="flex items-center space-x-3">
                                        <div class="rounded-lg bg-white/10 p-2 backdrop-blur-md">
                                            <component :is="getWalletTheme(wallet).icon" class="h-6 w-6" :class="getWalletTheme(wallet).iconColor" />
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h3 class="text-lg font-bold text-white">{{ wallet.name }}</h3>
                                                <Star v-if="isDefaultWallet(wallet)" class="h-4 w-4 text-yellow-400 fill-yellow-400" />
                                            </div>
                                            <p class="text-xs font-medium uppercase tracking-wide" :class="getWalletTheme(wallet).tagColor">
                                                {{ wallet.description || 'Sem descrição' }}
                                            </p>
                                        </div>
                                    </div>
                                    <button class="flex h-8 w-8 items-center justify-center rounded-full bg-white/15 backdrop-blur-sm transition-colors hover:bg-white/30" @click.stop="toggleCard(wallet.id)">
                                        <MoreHorizontal class="h-4 w-4 text-white" />
                                    </button>
                                </div>

                                <div class="z-10 flex flex-1 flex-col justify-center">
                                    <p class="mb-1 text-xs uppercase tracking-wider" :class="getWalletTheme(wallet).mutedText">Saldo Atual</p>
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-3xl font-bold text-white tracking-tight">R$</span>
                                        <div
                                            class="overflow-hidden relative balance-container"
                                            :class="{ 'balance-container--scrollable': needsWalletBalanceScrollAnimation(wallet) }"
                                        >
                                            <p
                                                :class="[
                                                    'text-3xl font-bold text-white tracking-tight whitespace-nowrap',
                                                    needsWalletBalanceScrollAnimation(wallet) ? 'balance-scroll-animation' : ''
                                                ]"
                                            >
                                                {{ formatCurrencyValue(wallet.balance ?? 0) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <svg class="card-sparkline" viewBox="0 0 100 25" preserveAspectRatio="none">
                                    <path :d="walletSparkline(wallet)" fill="none" stroke="rgba(255,255,255,0.6)" stroke-width="2" />
                                </svg>

                                <div class="z-10 mt-2 flex items-center justify-between border-t border-white/10 pt-4">
                                    <div class="flex items-center space-x-2" :class="getWalletTheme(wallet).footerText">
                                        <CreditCard class="h-4 w-4" />
                                        <span class="text-xs font-medium">
                                            {{ wallet.active ? 'Carteira ativa' : 'Carteira inativa' }}
                                        </span>
                                    </div>

                                    <div
                                        class="group relative flex cursor-help items-center space-x-2 text-white/70 transition-colors hover:text-white"
                                    >
                                        <Target class="h-4 w-4" />
                                        <span class="text-xs font-medium">Detalhes</span>
                                        <div class="track-tooltip absolute bottom-[calc(100%+8px)] right-0 w-[180px] max-w-[calc(100vw-2rem)] rounded-lg border border-white/10 bg-black/90 p-2 opacity-0 invisible pointer-events-none z-20 transition-all duration-200 shadow-[0_4px_12px_rgba(0,0,0,0.5)]">
                                            <div class="mb-1 flex items-center justify-between border-b border-white/10 pb-2">
                                                <span class="text-[10px] font-bold text-white">Criada em</span>
                                                <span class="text-[10px] text-indigo-400">{{ formatDate(wallet.created_at) }}</span>
                                            </div>
                                            <div class="tooltip-scroll-container max-h-12 overflow-hidden relative mt-2">
                                                <div class="tooltip-scroll-content flex flex-col">
                                                    <div class="min-h-4 flex items-center">
                                                        <div class="flex items-center justify-between w-full">
                                                            <span class="text-[10px] text-gray-400">Atualizada</span>
                                                            <span class="text-[10px] text-gray-200">{{ formatDate(wallet.updated_at) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="min-h-4 flex items-center">
                                                        <div class="flex items-center justify-between w-full">
                                                            <span class="text-[10px] text-gray-400">Transações</span>
                                                            <span class="text-[10px] text-indigo-400">0</span>
                                                        </div>
                                                    </div>
                                                    <div class="min-h-4 flex items-center">
                                                        <div class="flex items-center justify-between w-full">
                                                            <span class="text-[10px] text-gray-400">Transferências</span>
                                                            <span class="text-[10px] text-indigo-400">0</span>
                                                        </div>
                                                    </div>
                                                    <div class="min-h-4 flex items-center">
                                                        <div class="flex items-center justify-between w-full">
                                                            <span class="text-[10px] text-gray-400">Cartões</span>
                                                            <span class="text-[10px] text-indigo-400">0</span>
                                                        </div>
                                                    </div>
                                                    <div class="min-h-4 flex items-center">
                                                        <div class="flex items-center justify-between w-full">
                                                            <span class="text-[10px] text-gray-400">Status</span>
                                                            <span class="text-[10px]" :class="wallet.active ? 'text-emerald-400' : 'text-red-400'">
                                                                {{ wallet.active ? 'Ativa' : 'Inativa' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template #back>
                            <WalletActionsBack
                                :title="getWalletTheme(wallet).backTitle"
                                :actions="getWalletTheme(wallet).actions"
                                @close="flipToFront(wallet.id)"
                            >
                            </WalletActionsBack>
                        </template>
                    </WalletFlipCard>
                </template>
                <div
                    v-else
                    class="col-span-full flex flex-col items-center justify-center rounded-3xl border border-dashed border-white/10 p-10 text-center text-gray-400"
                >
                    <p class="text-base font-medium">nenhuma carteira encontrada !</p>
                    <p class="text-sm text-gray-500">clique abaixo para criar sua primeira carteira.</p>
                </div>

                <button
                    class="flex h-[260px] flex-col items-center justify-center rounded-3xl border-2 border-dashed border-white/10 text-gray-400 transition-all hover:border-indigo-500/50 hover:bg-white/5 hover:text-white"
                    type="button"
                    @click="openModal"
                >
                    <div
                        class="mb-4 flex h-16 w-16 items-center justify-center rounded-full border border-white/5 bg-white/5 transition-colors group-hover:bg-indigo-500/20"
                    >
                        <Plus class="h-8 w-8" />
                    </div>
                    <span class="text-sm font-medium">Adicionar Carteira</span>
                </button>
            </div>

            <div v-if="pagination && pagination.last_page > 1" class="mt-8 flex items-center justify-between">
                <div class="text-sm text-gray-400">
                    <span>mostrando</span>
                    <span class="mx-1 font-medium text-white">{{ pagination.from ?? 0 }}</span>
                    <span>até</span>
                    <span class="mx-1 font-medium text-white">{{ pagination.to ?? 0 }}</span>
                    <span>de</span>
                    <span class="mx-1 font-medium text-white">{{ pagination.total }}</span>
                    <span>carteiras</span>
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

        <UiDialog v-model:open="isModalOpen">
            <DraggableDialogContent class="w-full max-w-6xl max-h-[80vh] overflow-y-auto">
                <template #header>
                    <DialogTitle class="text-white text-lg font-semibold">criar nova carteira</DialogTitle>
                    <DialogDescription class="sr-only">preencha os dados para criar uma nova carteira</DialogDescription>
                </template>
                <CreateWalletForm :reset-form="shouldResetForm" @success="handleWalletCreated" @cancel="closeModal" @reset-complete="handleResetComplete" />
            </DraggableDialogContent>
        </UiDialog>

        <UiDialog v-model:open="isEditDialogOpen">
            <DraggableDialogContent class="w-full max-w-6xl max-h-[80vh] overflow-y-auto">
                <template #header>
                    <DialogTitle class="text-white text-lg font-semibold">editar carteira</DialogTitle>
                    <DialogDescription class="sr-only">edite os dados da carteira</DialogDescription>
                </template>
                <CreateWalletForm
                    v-if="walletToEdit"
                    :wallet="walletToEdit"
                    @success="handleWalletUpdated"
                    @cancel="closeEditModal"
                />
            </DraggableDialogContent>
        </UiDialog>

        <UiDialog v-model:open="isManageBalanceDialogOpen">
            <DraggableDialogContent class="w-full max-w-md">
                <template #header>
                    <DialogTitle class="text-white text-lg font-semibold">gerenciar saldo</DialogTitle>
                    <DialogDescription class="sr-only">atualize o saldo da carteira</DialogDescription>
                </template>
                <div v-if="walletToManageBalance" class="flex flex-col gap-4">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-white">carteira</label>
                        <p class="text-[#B6B6B6] text-sm">{{ walletToManageBalance.name }}</p>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-white">saldo atual</label>
                        <p class="text-white text-2xl font-bold">R$ {{ formatCurrencyValue(walletToManageBalance.balance ?? 0) }}</p>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-white">novo saldo</label>
                        <input
                            :value="newBalance"
                            type="text"
                            @input="handleBalanceInput"
                            placeholder="0,00"
                            class="w-full px-3 py-2 bg-[#131316] border border-[#2F2F2F] rounded-md text-white placeholder-[#767676] focus:outline-none focus:ring-2 focus:ring-[#3800D8] focus:border-transparent text-right text-lg font-semibold"
                        />
                        <span class="text-xs text-[#767676]">digite o novo valor do saldo</span>
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-2">
                        <UiButton
                            variant="outline"
                            @click="closeManageBalanceDialog"
                            class="border-[#2F2F2F] bg-[#1E1E1E] text-white hover:bg-[#313131] p-2 rounded-md cursor-pointer"
                        >
                            cancelar
                        </UiButton>
                        <UiButton
                            @click="updateBalance"
                            :disabled="isUpdatingBalance"
                            class="bg-indigo-600 text-white hover:bg-indigo-700 p-2 rounded-md cursor-pointer disabled:opacity-50"
                        >
                            <LoaderCircle v-if="isUpdatingBalance" class="!h-4 !w-4 animate-spin mr-2" />
                            {{ isUpdatingBalance ? 'atualizando...' : 'atualizar' }}
                        </UiButton>
                    </div>
                </div>
            </DraggableDialogContent>
        </UiDialog>

        <UiDialog v-model:open="isDeleteDialogOpen">
            <DraggableDialogContent class="w-full max-w-md">
                <template #header>
                    <DialogTitle class="text-white text-lg font-semibold">confirmar exclusão</DialogTitle>
                    <DialogDescription class="sr-only">confirme a exclusão da carteira</DialogDescription>
                </template>
                <div class="flex flex-col gap-4">
                    <p class="text-[#B6B6B6] text-sm">
                        tem certeza que deseja excluir esta carteira? esta ação não pode ser desfeita.
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
    </MainLayout>
</template>
<script lang="ts">
import type { Component } from 'vue';
import MainLayout from '@/layouts/MainLayout.vue';
import { Head as InertiaHead } from '@inertiajs/vue3';
import {
    Plus,
    LoaderCircle,
    TrendingUp,
    TrendingDown,
    Wallet2,
    MoreHorizontal,
    CreditCard,
    Target,
    Send,
    Settings2,
    DollarSign,
    Trash2,
    PiggyBank,
    ChevronLeft,
    ChevronRight,
    Star,
} from 'lucide-vue-next';
import type { Wallet } from '@/types';
import {
    Dialog as UiDialog,
    DialogTitle,
    DialogDescription,
} from '@/components/ui/dialog';
import { Button as UiButton } from '@/components/ui/button';
import DraggableDialogContent from '@/components/ui/dialog/DraggableDialogContent.vue';
import CreateWalletForm from '@/components/wallets/CreateWalletForm.vue';
import WalletFlipCard from '@/components/wallets/WalletFlipCard.vue';
import WalletActionsBack, { type WalletBackAction } from '@/components/wallets/WalletActionsBack.vue';
import { apiDelete, apiGet, apiPut } from '@/utils/api';
import { useToast } from '@/components/ui/toast';

interface WalletTheme {
    gradient: string;
    icon: Component;
    iconColor: string;
    tagColor: string;
    mutedText: string;
    footerText: string;
    backTitle: string;
    actions: WalletBackAction[];
}

const sparkLines = {
    withMoney: 'M0 20 L15 15 L30 18 L45 10 L60 14 L75 5 L100 12',
    withoutMoney: 'M0 15 L20 15 L40 15 L60 15 L80 15 L100 15',
}

const TYPE_ICONS: Record<string, Component> = {
    generic: Wallet2,
    saving: PiggyBank,
    investment: TrendingUp,
};

const COLOR_THEMES: Record<string, Omit<WalletTheme, 'icon' | 'backTitle' | 'actions'>> = {
    indigo: {
        gradient: 'linear-gradient(180deg, #3730a3 0%, #312e81 100%)',
        iconColor: 'text-indigo-200',
        tagColor: 'text-indigo-200',
        mutedText: 'text-indigo-300',
        footerText: 'text-indigo-200/80',
    },
    emerald: {
        gradient: 'linear-gradient(180deg, #065f46 0%, #064e3b 100%)',
        iconColor: 'text-emerald-200',
        tagColor: 'text-emerald-200',
        mutedText: 'text-emerald-300',
        footerText: 'text-emerald-200/80',
    },
    purple: {
        gradient: 'linear-gradient(180deg, #7c3aed 0%, #5b21b6 100%)',
        iconColor: 'text-purple-200',
        tagColor: 'text-purple-200',
        mutedText: 'text-purple-200/80',
        footerText: 'text-purple-200/70',
    },
    gray: {
        gradient: 'linear-gradient(180deg, #374151 0%, #1f2937 100%)',
        iconColor: 'text-gray-200',
        tagColor: 'text-gray-300',
        mutedText: 'text-gray-400',
        footerText: 'text-gray-400/80',
    },
    pink: {
        gradient: 'linear-gradient(180deg, #be185d 0%, #9f1239 100%)',
        iconColor: 'text-pink-200',
        tagColor: 'text-pink-200',
        mutedText: 'text-pink-300',
        footerText: 'text-pink-200/80',
    },
};


export default {
    name: 'Wallets',
    components: {
        MainLayout,
        InertiaHead,
        Plus,
        LoaderCircle,
        UiDialog,
        DialogTitle,
        DialogDescription,
        UiButton,
        DraggableDialogContent,
        CreateWalletForm,
        TrendingUp,
        TrendingDown,
        WalletFlipCard,
        WalletActionsBack,
        MoreHorizontal,
        CreditCard,
        Target,
        ChevronLeft,
        ChevronRight,
        Star,
    },
    data() {
        return {
            wallets: [] as Wallet[],
            isModalOpen: false,
            isEditDialogOpen: false,
            isDeleteDialogOpen: false,
            isManageBalanceDialogOpen: false,
            walletToEditId: null as number | null,
            walletToDeleteId: null as number | null,
            walletToManageBalanceId: null as number | null,
            newBalance: '' as string | number,
            isUpdatingBalance: false,
            loading: false,
            isRefreshing: false,
            refreshInterval: null as ReturnType<typeof setInterval> | null,
            shouldResetForm: false,
            pagination: null as {
                total: number;
                per_page: number;
                current_page: number;
                last_page: number;
                from: number | null;
                to: number | null;
                total_balance?: number;
            } | null,
            currentPage: 1,
            perPage: 10,
            flippedCards: {} as Record<number, boolean>,
            previousBalance: 0,
            balanceChangeDirection: null as 'up' | 'down' | null,
            isAnimating: false,
            openModalListener: null as (() => void) | null,
            walletDefaultChangedListener: null as (() => void) | null,
        };
    },
    computed: {
        totalBalance() {
            return this.pagination?.total_balance ?? 0;
        },
        walletToEdit() {
            if (!this.walletToEditId) return null;
            return this.wallets.find(w => w.id === this.walletToEditId) || null;
        },
        walletToManageBalance() {
            if (!this.walletToManageBalanceId) return null;
            return this.wallets.find(w => w.id === this.walletToManageBalanceId) || null;
        },
        balanceAnimationClass() {
            if (!this.isAnimating) {
                return 'text-white';
            }

            if (this.balanceChangeDirection === 'up') {
                return 'text-emerald-400 balance-pulse-up';
            } else if (this.balanceChangeDirection === 'down') {
                return 'text-red-400 balance-pulse-down';
            }

            return 'text-white';
        },
        needsTotalBalanceScrollAnimation(): boolean {
            const formattedValue = this.formatCurrencyValue(this.totalBalance);
            return formattedValue.length > 12;
        },
    },
    watch: {
        totalBalance(newValue) {
            const oldValue = this.previousBalance;

            if (oldValue === 0 && newValue >= 0) {
                this.previousBalance = newValue;
                return;
            }

            const difference = Math.abs(newValue - oldValue);
            if (difference > 0.01 && oldValue > 0) {
                const direction = newValue > oldValue ? 'up' : 'down';
                this.balanceChangeDirection = direction;
                this.isAnimating = true;
                this.previousBalance = newValue;

                setTimeout(() => {
                    this.isAnimating = false;
                    this.balanceChangeDirection = null;
                }, 1500);
            } else {
                this.previousBalance = newValue;
            }
        },
    },
    mounted() {
        this.loadWallets();
        this.refreshInterval = setInterval(() => {
            this.refreshWallets();
        }, 45000);

        // Listener para abrir o modal quando o evento for disparado do WalletSwitch
        this.openModalListener = () => {
            this.openModal();
        };
        window.addEventListener('open-wallet-modal', this.openModalListener);

        // Listener para recarregar wallets quando a wallet padrão é alterada no switch
        this.walletDefaultChangedListener = () => {
            this.refreshWallets();
        };
        window.addEventListener('wallet-default-changed', this.walletDefaultChangedListener);
    },
    beforeUnmount() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
        }
        if (this.openModalListener) {
            window.removeEventListener('open-wallet-modal', this.openModalListener);
        }
        if (this.walletDefaultChangedListener) {
            window.removeEventListener('wallet-default-changed', this.walletDefaultChangedListener);
        }
    },
    setup() {
        const { toast } = useToast();
        return { toast };
    },
    methods: {
        async fetchWallets(page?: number, showFullLoading: boolean = false) {
            const targetPage = page ?? this.currentPage;
            try {
                if (showFullLoading) {
                    this.loading = true;
                } else {
                    this.isRefreshing = true;
                }

                const url = `/api/wallets?page=${targetPage}&per_page=${this.perPage}`;
                const response = await apiGet(url);

                if (!response.ok) {
                    throw new Error('Failed to load wallets');
                }

                const data = await response.json();
                this.wallets = data.data;
                this.initializeFlipState();
                this.pagination = {
                    total: data.total,
                    per_page: data.per_page,
                    current_page: data.current_page,
                    last_page: data.last_page,
                    from: data.from,
                    to: data.to,
                    total_balance: data.total_balance ?? 0,
                };
                this.currentPage = data.current_page;
            } catch (error) {
                console.error('Error loading wallets:', error);
            } finally {
                if (showFullLoading) {
                    this.loading = false;
                } else {
                    this.isRefreshing = false;
                }
            }
        },
        async loadWallets(page: number = 1) {
            await this.fetchWallets(page, true);
        },
        async refreshWallets() {
            await this.fetchWallets(this.currentPage, false);
            this.resetAllCardsToFront();
        },
        async deleteWallet(walletId: number) {
            try {
                const response = await apiDelete(`/api/wallets/${walletId}`);

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || 'Erro ao deletar carteira');
                }

                this.toast({
                    title: 'carteira deletada',
                    description: 'a carteira foi deletada com sucesso.',
                    variant: 'default',
                });

                await this.loadWallets(this.currentPage);
                this.resetAllCardsToFront();
            } catch (error: any) {
                console.error('Error deleting wallet:', error);
                this.toast({
                    title: 'erro ao deletar carteira',
                    description: error.message || 'erro ao deletar carteira. tente novamente.',
                    variant: 'destructive',
                });
            }
        },
        goToPage(page: number) {
            if (page >= 1 && page <= (this.pagination?.last_page || 1)) {
                this.loadWallets(page);
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
        async handleWalletCreated() {
            await this.loadWallets(this.currentPage);
            this.resetAllCardsToFront();
            this.closeModal();
        },
        async handleWalletUpdated() {
            await this.loadWallets(this.currentPage);
            this.resetAllCardsToFront();
            this.closeEditModal();
        },
        openModal() {
            this.isModalOpen = true;
            // Garantir que o boolean está em false quando o modal abre
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
            this.walletToEditId = null;
        },
        formatCurrency(value: number): string {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL',
            }).format(value);
        },
        formatCurrencyValue(value: number): string {
            return new Intl.NumberFormat('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(value);
        },
        formatDate(dateString: string): string {
            return new Intl.DateTimeFormat('pt-BR', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
            }).format(new Date(dateString));
        },
        calculatePercentageChange(): number {
            return 12;
        },
        getWalletTheme(wallet: Wallet): WalletTheme {
            const walletColor = wallet.color && COLOR_THEMES[wallet.color] ? wallet.color : 'gray';
            const colorTheme = COLOR_THEMES[walletColor];
            const typeIcon = TYPE_ICONS[wallet.type] || Wallet2;

            const iconColorClass = walletColor === 'indigo' ? 'text-indigo-400' :
                                 walletColor === 'emerald' ? 'text-emerald-300' :
                                 walletColor === 'purple' ? 'text-purple-300' :
                                 walletColor === 'pink' ? 'text-pink-300' :
                                 'text-gray-300';

            return {
                gradient: colorTheme.gradient,
                icon: typeIcon,
                iconColor: colorTheme.iconColor,
                tagColor: colorTheme.tagColor,
                mutedText: colorTheme.mutedText,
                footerText: colorTheme.footerText,
                backTitle: 'Ações',
                actions: [
                    { label: 'Transferir', icon: Send, iconColor: iconColorClass, disabled: true },
                    { label: 'Saldo', icon: DollarSign, iconColor: iconColorClass, action: () => this.handleManageBalance(wallet.id) },
                    { label: 'Editar', icon: Settings2, iconColor: iconColorClass, action: () => this.handleEditWallet(wallet.id) },
                    { label: 'Excluir', icon: Trash2, iconColor: iconColorClass, action: () => this.handleDeleteWallet(wallet.id) },
                ],
            };
        },
        handleManageBalance(walletId: number) {
            const wallet = this.wallets.find(w => w.id === walletId);
            if (wallet) {
                this.walletToManageBalanceId = walletId;
                this.newBalance = this.formatBalanceInput(wallet.balance ?? 0);
                this.isManageBalanceDialogOpen = true;
            }
        },
        closeManageBalanceDialog() {
            this.isManageBalanceDialogOpen = false;
            this.walletToManageBalanceId = null;
            this.newBalance = '';
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

            // Limita a 2 casas decimais
            if (parts.length === 2 && parts[1].length > 2) {
                value = parts[0] + '.' + parts[1].substring(0, 2);
            }

            // Garante que não seja negativo
            if (value.startsWith('-')) {
                value = value.replace('-', '');
            }

            // Se estiver vazio, limpa o valor
            if (value === '' || value === '.') {
                this.newBalance = '';
                target.value = '';
                return;
            }

            // Valida se é um formato numérico válido (máximo 2 casas decimais)
            const isValidNumber = /^\d+(\.\d{0,2})?$/.test(value);

            if (!isValidNumber) {
                const lastValid = this.newBalance || '';
                target.value = typeof lastValid === 'string' ? lastValid : this.formatBalanceInput(lastValid);
                return;
            }

            // Converte para número e limita a 2 casas decimais
            const numValue = parseFloat(value);
            if (!isNaN(numValue) && numValue >= 0 && isFinite(numValue)) {
                this.newBalance = Math.round(numValue * 100) / 100;
            } else {
                const lastValid = this.newBalance || '';
                target.value = typeof lastValid === 'string' ? lastValid : this.formatBalanceInput(lastValid);
                return;
            }

            // Atualiza o valor do input mantendo o formato com vírgula
            target.value = this.formatBalanceInput(this.newBalance);
        },
        formatBalanceInput(value: number | string): string {
            if (value === null || value === undefined || value === '' || value === 0) {
                return '';
            }

            let numValue: number;

            // Converte string para número se necessário
            if (typeof value === 'string') {
                numValue = parseFloat(value.replace(',', '.')) || 0;
            } else {
                numValue = value;
            }

            // Se não for um número válido, retorna vazio
            if (isNaN(numValue) || !isFinite(numValue) || numValue < 0) {
                return '';
            }

            // Formata com até 2 casas decimais, removendo zeros desnecessários
            const formatted = numValue.toFixed(2).replace(/\.?0+$/, '');
            return formatted.replace('.', ',');
        },
        async updateBalance() {
            if (!this.walletToManageBalanceId || !this.walletToManageBalance) {
                return;
            }

            // Garantir que balance seja sempre um número com 2 casas decimais
            let balanceValue: number;
            if (typeof this.newBalance === 'string') {
                balanceValue = parseFloat(this.newBalance.replace(',', '.')) || 0;
            } else {
                balanceValue = this.newBalance || 0;
            }
            balanceValue = Math.round(balanceValue * 100) / 100; // Limita a 2 casas decimais

            if (balanceValue < 0) {
                this.toast({
                    title: 'erro',
                    description: 'o saldo não pode ser negativo',
                    variant: 'destructive',
                });
                return;
            }

            this.isUpdatingBalance = true;

            try {
                const response = await apiPut(`/api/wallets/${this.walletToManageBalanceId}`, {
                    ...this.walletToManageBalance,
                    balance: balanceValue,
                });

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || 'erro ao atualizar saldo');
                }

                this.toast({
                    title: 'saldo atualizado',
                    description: 'o saldo da carteira foi atualizado com sucesso.',
                    variant: 'default',
                });

                await this.loadWallets(this.currentPage);
                this.resetAllCardsToFront();
                this.closeManageBalanceDialog();
            } catch (error: any) {
                console.error('Error updating balance:', error);
                this.toast({
                    title: 'erro ao atualizar saldo',
                    description: error.message || 'erro ao atualizar saldo. tente novamente.',
                    variant: 'destructive',
                });
            } finally {
                this.isUpdatingBalance = false;
            }
        },
        handleEditWallet(walletId: number) {
            this.walletToEditId = walletId;
            this.isEditDialogOpen = true;
        },
        handleDeleteWallet(walletId: number) {
            this.walletToDeleteId = walletId;
            this.isDeleteDialogOpen = true;
        },
        async confirmDelete() {
            if (this.walletToDeleteId) {
                this.isDeleteDialogOpen = false;
                await this.deleteWallet(this.walletToDeleteId);
                this.walletToDeleteId = null;
            }
        },
        initializeFlipState() {
            const nextState: Record<number, boolean> = {};
            this.wallets.forEach((wallet) => {
                nextState[wallet.id] = this.flippedCards[wallet.id] || false;
            });
            this.flippedCards = nextState;
        },
        resetAllCardsToFront() {
            const resetState: Record<number, boolean> = {};
            this.wallets.forEach((wallet) => {
                resetState[wallet.id] = false;
            });
            this.flippedCards = resetState;
        },
        toggleCard(id: number) {
            this.flippedCards = {
                ...this.flippedCards,
                [id]: !this.flippedCards[id],
            };
        },
        flipToFront(id: number) {
            if (!this.flippedCards[id]) return;
            this.flippedCards = {
                ...this.flippedCards,
                [id]: false,
            };
        },
        walletSparkline(wallet: Wallet) {
            return wallet.balance > 0 ? sparkLines.withMoney : sparkLines.withoutMoney;
        },
        needsWalletBalanceScrollAnimation(wallet: Wallet): boolean {
            const formattedValue = this.formatCurrencyValue(wallet.balance ?? 0);
            return formattedValue.length > 12;
        },
        isDefaultWallet(wallet: Wallet): boolean {
            return wallet.is_default === true || wallet.is_default === '1' || wallet.is_default === 'true';
        },
    },
};
</script>
<style scoped>
.card-sparkline {
    position: absolute;
    bottom: 60px;
    left: 0;
    width: 100%;
    height: 60px;
    opacity: 0.4;
    pointer-events: none;
}

.card-sparkline path {
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

.tooltip-scroll-container {
    mask-image: linear-gradient(to bottom, transparent 0%, black 15%, black 85%, transparent 100%);
    -webkit-mask-image: linear-gradient(to bottom, transparent 0%, black 15%, black 85%, transparent 100%);
}

.tooltip-scroll-content {
    transform: translateY(0);
    transition: transform 0.3s ease-out;
}

.group:hover .track-tooltip {
    opacity: 1;
    visibility: visible;
}

.group:hover .tooltip-scroll-content {
    animation: scrollLoop 10s ease-in-out infinite;
    transition: none;
}

.group:not(:hover) .tooltip-scroll-content {
    animation: none;
    transform: translateY(0) !important;
    transition: transform 0.3s ease-out;
}

@keyframes scrollLoop {
    0% {
        transform: translateY(0);
    }
    30% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-50%);
    }
    80% {
        transform: translateY(-50%);
    }
    100% {
        transform: translateY(0);
    }
}

.balance-pulse-up {
    animation: pulseUp 1.5s ease-out;
    filter: drop-shadow(0 0 8px rgba(16, 185, 129, 0.4));
}

.balance-pulse-down {
    animation: pulseDown 1.5s ease-out;
    filter: drop-shadow(0 0 8px rgba(239, 68, 68, 0.4));
}

@keyframes pulseUp {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    20% {
        transform: scale(1.08);
        opacity: 1;
    }
    40% {
        transform: scale(1.05);
        opacity: 0.95;
    }
    60% {
        transform: scale(1.02);
        opacity: 0.9;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes pulseDown {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    20% {
        transform: scale(0.95);
        opacity: 1;
    }
    40% {
        transform: scale(0.98);
        opacity: 0.95;
    }
    60% {
        transform: scale(1);
        opacity: 0.9;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

/* Container para números grandes */
.balance-container {
    max-width: 100%;
    position: relative;
}

.balance-container--scrollable {
    max-width: 10rem;
}

.balance-container--large {
    max-width: 30rem;
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

.balance-container--large .balance-scroll-animation {
    animation: scrollBalanceHorizontalLarge 8s ease-in-out infinite;
}

@keyframes scrollBalanceHorizontalLarge {
    0% {
        transform: translateX(0);
    }
    25% {
        transform: translateX(0);
    }
    50% {
        transform: translateX(calc(-100% + 30rem));
    }
    75% {
        transform: translateX(calc(-100% + 30rem));
    }
    100% {
        transform: translateX(0);
    }
}
</style>

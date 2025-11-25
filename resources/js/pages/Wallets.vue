<template>
    <InertiaHead title="wallets" />
    <MainLayout :no-sub-nav="true">
        <div class="h-full w-full px-8 py-8">
            <PageHeader title="minhas carteiras" description="crie e personalize suas carteiras para suas necessidades">
                <template #actions>
                    <div class="flex flex-col gap-3 md:flex-row md:items-center">
                        <div class="flex items-center gap-2">
                            <span v-if="!isRefreshing" class="text-sm light:text-[#6e6e6e] dark:text-[#B6B6B6]">informações atualizadas a cada 45 segundos</span>
                            <div v-else class="flex items-center gap-2 text-[#6965f2]">
                                <LoaderCircle class="!h-4 !w-4 animate-spin" />
                                <span class="text-xs">atualizando...</span>
                            </div>
                        </div>
                        <SearchInput
                            v-model="searchQuery"
                            placeholder="buscar carteiras por nome..."
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
                                title="selecionar múltiplas carteiras (Ctrl + Espaço)"
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
                                <span class="text-sm">cancelar seleção</span>
                            </button>
                            <button
                                v-if="!isSelectionMode"
                                @click="openModal"
                                class="flex items-center gap-x-2 rounded-md border border-border bg-card px-4 py-2 text-foreground transition-colors hover:bg-accent"
                            >
                                <Plus class="!h-4 !w-4" />
                                nova carteira
                            </button>
                        </div>
                    </div>
                </template>
            </PageHeader>

            <div
                class="mb-10 flex items-center justify-between rounded-[20px] border-2 light:border-[#e2e8f0] dark:border-[#252c3e] bg-gradient-to-r dark:from-[#1e1b4b] light:from-[#fcfcfc] light:via-[#a8a4d1] dark:to-[#0f172a] light:to-[#201b5a] p-8"
            >
                <div>
                    <div class="flex flex-row items-baseline gap-1">
                        <p class="mb-2 text-sm font-semibold uppercase tracking-wider dark:text-indigo-300 light:text-indigo-600">Patrimônio Total</p>
                        <TooltipProvider :delay-duration="200">
                            <Tooltip>
                                <TooltipTrigger>
                                    <HelpCircle class="!h-4 !w-4 dark:text-indigo-300 light:text-indigo-600" />
                                </TooltipTrigger>
                                <TooltipContent side="right">
                                    <p class="text-sm text-foreground">
                                        o patrimônio total não considera os valores armazenados na caixinha de reserva, ou destinados para seus
                                        objetivos.
                                    </p>
                                </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                    </div>
                    <div class="flex items-baseline gap-1">
                        <span :class="['text-5xl font-bold transition-all duration-500', balanceAnimationClass]"> R$ </span>
                        <div
                            class="balance-container relative overflow-hidden"
                            :class="{
                                'balance-container--scrollable': needsTotalBalanceScrollAnimation,
                                'balance-container--large': needsTotalBalanceScrollAnimation,
                            }"
                        >
                            <h2
                                :class="[
                                    'whitespace-nowrap text-5xl font-bold transition-all duration-500',
                                    balanceAnimationClass,
                                    needsTotalBalanceScrollAnimation ? 'balance-scroll-animation' : '',
                                ]"
                                :key="`balance-${totalBalance}`"
                            >
                                {{ formatCurrencyValue(totalBalance) }}
                            </h2>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center space-x-4">
                        <span
                            v-if="calculatePercentageChange() > 0"
                            class="flex items-center rounded dark:bg-emerald-400/10 light:bg-emerald-400/40 px-2 py-1 text-sm font-medium dark:text-emerald-400 light:text-emerald-600"
                        >
                            <TrendingUp class="mr-1 !h-4 !w-4" /> {{ calculatePercentageChange() }}% este mês
                        </span>
                        <span v-else class="flex items-center rounded bg-red-400/10 px-2 py-1 text-sm font-medium text-red-400">
                            <TrendingDown class="mr-1 !h-4 !w-4" /> {{ calculatePercentageChange() }}% este mês
                        </span>
                        <span class="text-sm dark:text-gray-400 light:text-[#080808]">disponível em {{ pagination?.total ?? wallets.length }} carteiras</span>
                    </div>
                </div>
                <div class="hidden opacity-50 md:block">
                    <svg width="200" height="60" viewBox="0 0 200 60">
                        <path d="M0 50 Q 30 40, 50 45 T 100 20 T 150 30 T 200 5" fill="none" stroke="#6366f1" stroke-width="3" />
                        <path d="M0 50 Q 30 40, 50 45 T 100 20 T 150 30 T 200 5 V 60 H 0 Z" fill="url(#grad1)" opacity="0.3" />
                        <defs>
                            <linearGradient id="grad1" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" style="stop-color: #6366f1; stop-opacity: 1" />
                                <stop offset="100%" style="stop-color: #6366f1; stop-opacity: 0" />
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
            </div>

            <div v-if="viewMode === 'grid'" class="grid grid-cols-1 gap-8 pb-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <template v-if="loading">
                    <WalletCardSkeleton v-for="n in 3" :key="`skeleton-${n}`" />
                </template>
                <template v-else-if="wallets.length">
                    <div
                        v-for="wallet in wallets"
                        :key="wallet.id"
                        class="relative"
                        :class="{
                            'rounded-3xl ring-2 ring-accent-primary ring-offset-2 dark:ring-offset-[#131316] light:ring-offset-[#fcfcfc]':
                                isSelectionMode && selectedIds.includes(wallet.id),
                        }"
                    >
                        <div v-if="isSelectionMode" class="absolute left-3 top-3 z-20" @click.stop="toggleWalletSelection(wallet.id)">
                            <div
                                :class="[
                                    'flex h-6 w-6 cursor-pointer items-center justify-center rounded-full border-2 transition-all',
                                    selectedIds.includes(wallet.id)
                                        ? 'border-accent-primary bg-accent-primary'
                                        : 'border-white/30 bg-[#131316]/80 backdrop-blur-sm hover:border-white/50',
                                ]"
                            >
                                <svg
                                    v-if="selectedIds.includes(wallet.id)"
                                    class="h-4 w-4 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        <WalletFlipCard
                            :key="wallet.id"
                            :flipped="flippedCards[wallet.id]"
                            :front-style="{ background: getWalletTheme(wallet).gradient }"
                            :class="[isSelectionMode ? 'cursor-pointer' : 'cursor-pointer transition-all duration-300 hover:-translate-y-2']"
                            @toggle="isSelectionMode ? toggleWalletSelection(wallet.id) : toggleCard(wallet.id)"
                        >
                            <template #front>
                                <div class="flex h-full flex-col justify-between" :style="{ background: getWalletTheme(wallet).gradient }">
                                    <div class="z-10 flex items-start justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex items-center justify-center rounded-lg bg-white/10 p-2 backdrop-blur-md">
                                                <img
                                                    v-if="wallet.icon && wallet.icon.startsWith('/images/')"
                                                    :src="wallet.icon"
                                                    :alt="wallet.name"
                                                    class="h-6 w-6 object-contain"
                                                />
                                                <component
                                                    v-else
                                                    :is="getWalletTheme(wallet).icon"
                                                    class="h-6 w-6"
                                                    :class="getWalletTheme(wallet).iconColor"
                                                />
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <h3 class="text-lg font-bold text-white">{{ wallet.name }}</h3>
                                                    <Star v-if="isDefaultWallet(wallet)" class="h-4 w-4 fill-yellow-400 text-yellow-400" />
                                                </div>
                                                <p class="text-xs font-medium uppercase tracking-wide" :class="getWalletTheme(wallet).tagColor">
                                                    {{ wallet.description || 'Sem descrição' }}
                                                </p>
                                            </div>
                                        </div>
                                        <button
                                            class="flex h-8 w-8 items-center justify-center rounded-full bg-white/15 backdrop-blur-sm transition-colors hover:bg-white/30"
                                            @click.stop="toggleCard(wallet.id)"
                                        >
                                            <MoreHorizontal class="h-4 w-4 text-white" />
                                        </button>
                                    </div>

                                    <div class="z-10 flex flex-1 flex-col justify-center">
                                        <p class="mb-1 text-xs uppercase tracking-wider" :class="getWalletTheme(wallet).mutedText">Saldo Atual</p>
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-3xl font-bold tracking-tight text-white">R$</span>
                                            <div
                                                class="balance-container relative overflow-hidden"
                                                :class="{ 'balance-container--scrollable': needsWalletBalanceScrollAnimation(wallet) }"
                                            >
                                                <p
                                                    :class="[
                                                        'whitespace-nowrap text-3xl font-bold tracking-tight text-white',
                                                        needsWalletBalanceScrollAnimation(wallet) ? 'balance-scroll-animation' : '',
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
                                            <span class="mb-1 text-xs font-medium">
                                                {{ wallet.active ? 'carteira ativa' : 'carteira inativa' }}
                                            </span>
                                        </div>

                                        <div
                                            class="group relative flex cursor-help items-center space-x-2 text-white/70 transition-colors hover:text-white"
                                        >
                                            <Target class="h-4 w-4" />
                                            <span class="text-xs font-medium">detalhes</span>
                                            <div
                                                class="track-tooltip pointer-events-none invisible absolute bottom-[calc(100%+8px)] right-0 z-20 w-[180px] max-w-[calc(100vw-2rem)] rounded-lg border border-white/10 bg-black/90 p-2 opacity-0 shadow-[0_4px_12px_rgba(0,0,0,0.5)] transition-all duration-200"
                                            >
                                                <div class="mb-1 flex items-center justify-between border-b border-white/10 pb-2">
                                                    <span class="text-[10px] font-bold text-white">Criada em</span>
                                                    <span class="text-[10px] text-accent-primary">{{ formatDate(wallet.created_at) }}</span>
                                                </div>
                                                <div class="tooltip-scroll-container relative mt-2 max-h-12 overflow-hidden">
                                                    <div class="tooltip-scroll-content flex flex-col">
                                                        <div class="flex min-h-4 items-center">
                                                            <div class="flex w-full items-center justify-between">
                                                                <span class="text-[10px] text-gray-400">Atualizada</span>
                                                                <span class="text-[10px] text-gray-200">{{ formatDate(wallet.updated_at) }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="flex min-h-4 items-center">
                                                            <div class="flex w-full items-center justify-between">
                                                                <span class="text-[10px] text-gray-400">Transações</span>
                                                                <span class="text-[10px] text-accent-primary">0</span>
                                                            </div>
                                                        </div>
                                                        <div class="flex min-h-4 items-center">
                                                            <div class="flex w-full items-center justify-between">
                                                                <span class="text-[10px] text-gray-400">Transferências</span>
                                                                <span class="text-[10px] text-accent-primary">0</span>
                                                            </div>
                                                        </div>
                                                        <div class="flex min-h-4 items-center">
                                                            <div class="flex w-full items-center justify-between">
                                                                <span class="text-[10px] text-gray-400">Cartões</span>
                                                                <span class="text-[10px] text-accent-primary">0</span>
                                                            </div>
                                                        </div>
                                                        <div class="flex min-h-4 items-center">
                                                            <div class="flex w-full items-center justify-between">
                                                                <span class="text-[10px] text-gray-400">Status</span>
                                                                <span
                                                                    class="text-[10px]"
                                                                    :class="wallet.active ? 'text-emerald-400' : 'text-red-400'"
                                                                >
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
                    </div>
                </template>
                <div
                    v-else
                    class="col-span-full flex flex-col items-center justify-center rounded-3xl border border-dashed border-white/10 p-10 text-center text-gray-400"
                >
                    <p class="text-base font-medium">nenhuma carteira encontrada !</p>
                    <p class="text-sm text-gray-500">clique abaixo para criar sua primeira carteira.</p>
                </div>

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
                        class="mb-4 flex h-16 w-16 items-center justify-center rounded-full border border-white/5 dark:bg-white/5 light:bg-black/5 transition-colors wallet-add-icon"
                        :style="walletAddIconStyle"
                    >
                        <Plus class="h-8 w-8" />
                    </div>
                    <span class="text-sm font-medium">adicionar carteira</span>
                </button>
            </div>

            <!-- Tabela de Carteiras -->
            <div v-else-if="viewMode === 'table'" class="pb-4">
                <template v-if="loading">
                    <div class="rounded-lg border border-border bg-card p-8">
                        <div class="flex items-center justify-center">
                            <div class="text-center">
                                <div class="h-8 w-8 animate-spin rounded-full border-2 border-accent-primary border-t-transparent mx-auto mb-4"></div>
                                <p class="text-muted-foreground">carregando carteiras...</p>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <WalletsTable
                        :wallets="wallets"
                        :is-selection-mode="isSelectionMode"
                        :selected-ids="selectedIds"
                        :all-selected="selectedIds.length > 0 && selectedIds.length === wallets.length"
                        @edit="handleEditWallet"
                        @delete="handleDeleteWallet"
                        @manage-balance="handleManageBalance"
                        @toggle-selection="toggleWalletSelection"
                        @toggle-all="selectedIds.length === wallets.length ? clearSelection() : selectAllWallets()"
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
                entity-label="carteiras"
                @change="goToPage"
            />
        </div>

        <UiDialog v-model:open="isModalOpen">
            <DraggableDialogContent class="max-h-[80vh] w-full max-w-6xl overflow-y-auto">
                <template #header>
                    <DialogTitle class="text-lg font-semibold text-foreground">criar nova carteira</DialogTitle>
                    <DialogDescription class="sr-only">preencha os dados para criar uma nova carteira</DialogDescription>
                </template>
                <CreateWalletForm
                    :reset-form="shouldResetForm"
                    @success="handleWalletCreated"
                    @cancel="closeModal"
                    @reset-complete="handleResetComplete"
                />
            </DraggableDialogContent>
        </UiDialog>

        <UiDialog v-model:open="isEditDialogOpen">
            <DraggableDialogContent class="max-h-[80vh] w-full max-w-6xl overflow-y-auto">
                <template #header>
                    <DialogTitle class="text-lg font-semibold text-foreground">editar carteira</DialogTitle>
                    <DialogDescription class="sr-only">edite os dados da carteira</DialogDescription>
                </template>
                <CreateWalletForm v-if="walletToEdit" :wallet="walletToEdit" @success="handleWalletUpdated" @cancel="closeEditModal" />
            </DraggableDialogContent>
        </UiDialog>

        <UiDialog v-model:open="isManageBalanceDialogOpen">
            <DraggableDialogContent class="w-full max-w-md">
                <template #header>
                    <DialogTitle class="text-lg font-semibold text-foreground">gerenciar saldo</DialogTitle>
                    <DialogDescription class="sr-only">atualize o saldo da carteira</DialogDescription>
                </template>
                <div v-if="walletToManageBalance" class="flex flex-col gap-4">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-foreground">carteira</label>
                        <p class="text-sm text-muted-foreground">{{ walletToManageBalance.name }}</p>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-foreground">saldo atual</label>
                        <p class="text-2xl font-bold text-foreground">R$ {{ formatCurrencyValue(walletToManageBalance.balance ?? 0) }}</p>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-foreground">novo saldo</label>
                        <input
                            :value="newBalance"
                            type="text"
                            @input="handleBalanceInput"
                            placeholder="0,00"
                            class="w-full rounded-md border border-border bg-input px-3 py-2 text-right text-lg font-semibold text-foreground placeholder-muted-foreground focus:border-transparent focus:outline-none focus:ring-2 focus:ring-accent-primary"
                        />
                        <span class="text-xs text-muted-foreground">digite o novo valor do saldo</span>
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-2">
                        <UiButton
                            variant="outline"
                            @click="closeManageBalanceDialog"
                            class="cursor-pointer rounded-md border-border bg-card p-2 text-foreground hover:bg-accent"
                        >
                            cancelar
                        </UiButton>
                        <UiButton
                            @click="updateBalance"
                            :disabled="isUpdatingBalance"
                            class="cursor-pointer rounded-md bg-accent-primary p-2 text-white hover:bg-accent-primary/90 disabled:opacity-50"
                        >
                            <LoaderCircle v-if="isUpdatingBalance" class="mr-2 !h-4 !w-4 animate-spin" />
                            {{ isUpdatingBalance ? 'atualizando...' : 'atualizar' }}
                        </UiButton>
                    </div>
                </div>
            </DraggableDialogContent>
        </UiDialog>

        <UiDialog v-model:open="isDeleteDialogOpen">
            <DraggableDialogContent class="w-full max-w-md">
                <template #header>
                    <DialogTitle class="text-lg font-semibold text-foreground">confirmar exclusão</DialogTitle>
                    <DialogDescription class="sr-only">confirme a exclusão da carteira</DialogDescription>
                </template>
                <div class="flex flex-col gap-4">
                    <p class="text-sm text-muted-foreground">tem certeza que deseja excluir esta carteira? esta ação não pode ser desfeita.</p>
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

        <UiDialog v-model:open="isBulkDeleteDialogOpen">
            <DraggableDialogContent class="w-full max-w-md">
                <template #header>
                    <DialogTitle class="text-lg font-semibold text-foreground">confirmar exclusão em lote</DialogTitle>
                    <DialogDescription class="sr-only">confirme a exclusão das carteiras selecionadas</DialogDescription>
                </template>
                <div class="flex flex-col gap-4">
                    <p class="text-sm text-muted-foreground">
                        tem certeza que deseja excluir {{ selectedIds.length }} {{ selectedIds.length === 1 ? 'carteira' : 'carteiras' }}? esta ação
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
            singular-label="carteira selecionada"
            plural-label="carteiras selecionadas"
            @select-all="selectAllWallets"
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
<style scoped>
.wallet-add-button:hover {
    border-color: var(--hover-border-color) !important;
}

.wallet-add-icon:hover {
    background-color: var(--hover-icon-bg) !important;
}
</style>
<script lang="ts">
import PageHeader from '@/components/common/PageHeader.vue';
import PaginationControls from '@/components/common/PaginationControls.vue';
import SelectionToolbar from '@/components/common/SelectionToolbar.vue';
import SearchInput from '@/components/common/SearchInput.vue';
import ViewToggle from '@/components/common/ViewToggle.vue';
import { Button as UiButton } from '@/components/ui/button';
import { DialogDescription, DialogTitle, Dialog as UiDialog } from '@/components/ui/dialog';
import DraggableDialogContent from '@/components/ui/dialog/DraggableDialogContent.vue';
import { useToast } from '@/components/ui/toast';
import Tooltip from '@/components/ui/tooltip/Tooltip.vue';
import TooltipContent from '@/components/ui/tooltip/TooltipContent.vue';
import TooltipProvider from '@/components/ui/tooltip/TooltipProvider.vue';
import TooltipTrigger from '@/components/ui/tooltip/TooltipTrigger.vue';
import CreateWalletForm from '@/components/wallets/CreateWalletForm.vue';
import WalletActionsBack, { type WalletBackAction } from '@/components/wallets/WalletActionsBack.vue';
import WalletCardSkeleton from '@/components/wallets/WalletCardSkeleton.vue';
import WalletFlipCard from '@/components/wallets/WalletFlipCard.vue';
import WalletsTable from '@/components/wallets/WalletsTable.vue';
import MainLayout from '@/layouts/MainLayout.vue';
import selectionModeMixin from '@/mixins/selectionModeMixin';
import type { Wallet } from '@/types';
import { apiBulkDelete, apiDelete, apiGet, apiPut } from '@/utils/api';
import { formatCurrencyValue, formatDate } from '@/utils/formatters';
import { getAccentPrimaryWithOpacity } from '@/utils/colors';
import { useUserSettings } from '@/composables/useUserSettings';
import { Head as InertiaHead } from '@inertiajs/vue3';
import {
    CreditCard,
    DollarSign,
    HelpCircle,
    LoaderCircle,
    MoreHorizontal,
    PiggyBank,
    Plus,
    Send,
    Settings2,
    Star,
    Target,
    Trash2,
    TrendingDown,
    TrendingUp,
    Wallet2,
} from 'lucide-vue-next';
import type { Component } from 'vue';
import { computed, ref, onMounted, onBeforeUnmount } from 'vue';

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
};

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
    mixins: [selectionModeMixin],
    components: {
        PageHeader,
        PaginationControls,
        SearchInput,
        SelectionToolbar,
        ViewToggle,
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
        Star,
        Trash2,
        HelpCircle,
        TooltipProvider,
        Tooltip,
        TooltipTrigger,
        TooltipContent,
        WalletCardSkeleton,
        WalletsTable,
        MainLayout,
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
            isBulkDeleteDialogOpen: false,
            searchQuery: '',
            viewMode: 'grid' as 'grid' | 'table',
        };
    },
    computed: {
        totalBalance() {
            return this.pagination?.total_balance ?? 0;
        },
        walletToEdit() {
            if (!this.walletToEditId) return null;
            return this.wallets.find((w) => w.id === this.walletToEditId) || null;
        },
        walletToManageBalance() {
            if (!this.walletToManageBalanceId) return null;
            return this.wallets.find((w) => w.id === this.walletToManageBalanceId) || null;
        },
        balanceAnimationClass() {
            if (!this.isAnimating) {
                return 'dark:text-white light:text-black';
            }

            if (this.balanceChangeDirection === 'up') {
                return 'text-emerald-400 balance-pulse-up';
            } else if (this.balanceChangeDirection === 'down') {
                return 'text-red-400 balance-pulse-down';
            }

            return 'dark:text-white light:text-black';
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
        const { settings } = useUserSettings();

        // Ref para forçar atualização do computed
        const colorUpdateKey = ref(0);

        // Computed reativo que observa mudanças na cor de destaque
        const hoverBorderStyle = computed(() => {
            // Força recálculo observando settings.accent_color, tema e colorUpdateKey
            void settings.accent_color;
            void document.documentElement.classList.contains('dark');
            void colorUpdateKey.value; // Força dependência reativa

            // Garante que a cor esteja disponível antes de usar
            const accentColor = getComputedStyle(document.documentElement)
                .getPropertyValue('--accent-primary')
                .trim();

            // Se a cor não estiver disponível ainda, usa fallback
            if (!accentColor || accentColor === '#6366f1') {
                return {
                    '--hover-border-color': getAccentPrimaryWithOpacity(0.5, 'rgba(99, 102, 241, 0.5)'),
                };
            }

            return {
                '--hover-border-color': getAccentPrimaryWithOpacity(1),
            };
        });

        // Computed para o estilo do ícone do botão adicionar carteira
        const walletAddIconStyle = computed(() => {
            void settings.accent_color;
            void colorUpdateKey.value;
            return {
                '--hover-icon-bg': getAccentPrimaryWithOpacity(0.2),
            };
        });

        // Listener para evento de mudança de cor
        const handleAccentColorChange = () => {
            colorUpdateKey.value++;
        };

        onMounted(() => {
            window.addEventListener('accent-color-changed', handleAccentColorChange);
            // Força atualização inicial após montagem
            // Pequeno delay para garantir que as cores foram aplicadas
            setTimeout(() => {
                colorUpdateKey.value++;
            }, 100);
        });

        onBeforeUnmount(() => {
            window.removeEventListener('accent-color-changed', handleAccentColorChange);
        });

        return { toast, hoverBorderStyle, walletAddIconStyle };
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

                let url = `/api/wallets?page=${targetPage}&per_page=${this.perPage}`;
                if (this.searchQuery.trim()) {
                    url += `&search=${encodeURIComponent(this.searchQuery.trim())}`;
                }

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
        async handleWalletCreated() {
            this.closeModal();
            await this.loadWallets(this.currentPage);
            this.resetAllCardsToFront();
        },
        async handleWalletUpdated() {
            this.closeEditModal();
            await this.loadWallets(this.currentPage);
            this.resetAllCardsToFront();
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
        formatCurrencyValue,
        formatDate,
        calculatePercentageChange(): number {
            return 12;
        },
        getWalletTheme(wallet: Wallet): WalletTheme {
            const walletColor = wallet.color && COLOR_THEMES[wallet.color] ? wallet.color : 'gray';
            const colorTheme = COLOR_THEMES[walletColor];
            const typeIcon = TYPE_ICONS[wallet.type] || Wallet2;

            const iconColorClass =
                walletColor === 'indigo'
                    ? 'text-indigo-400'
                    : walletColor === 'emerald'
                      ? 'text-emerald-300'
                      : walletColor === 'purple'
                        ? 'text-purple-300'
                        : walletColor === 'pink'
                          ? 'text-pink-300'
                          : 'text-gray-300';

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
            const wallet = this.wallets.find((w) => w.id === walletId);
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
        toggleWalletSelection(walletId: number) {
            this.toggleSelectionItem(walletId);
        },
        selectAllWallets() {
            this.selectAllItems();
        },
        confirmBulkDelete() {
            if (this.selectedIds.length === 0) return;
            this.isBulkDeleteDialogOpen = true;
        },
        async confirmBulkDeleteAction() {
            this.isBulkDeleteDialogOpen = false;
            const walletIdsToDelete = [...this.selectedIds];

            if (walletIdsToDelete.length === 0) return;

            try {
                const response = await apiBulkDelete('/api/wallets/bulk', walletIdsToDelete);

                if (response.ok) {
                    const result = await response.json();
                    const deletedCount = result.deleted_count || walletIdsToDelete.length;

                    this.toast({
                        title: 'carteiras deletadas',
                        description: `${deletedCount} carteira(s) deletada(s) com sucesso.`,
                        variant: 'default',
                    });

                    this.exitSelectionMode();
                    await this.loadWallets(this.currentPage);
                    this.resetAllCardsToFront();
                } else {
                    this.toast({
                        title: 'erro ao deletar carteiras',
                        description: 'não foi possível deletar as carteiras selecionadas.',
                        variant: 'destructive',
                    });
                }
            } catch (error) {
                console.error('Error deleting wallets:', error);
                this.toast({
                    title: 'erro ao deletar carteiras',
                    description: 'ocorreu um erro ao tentar deletar as carteiras.',
                    variant: 'destructive',
                });
            }
        },
        getSelectionIds() {
            return this.wallets.map((wallet) => wallet.id);
        },
        onSelectionModeEnter() {
            this.resetAllCardsToFront();
        },
        onSelectionModeExit() {
            this.resetAllCardsToFront();
        },
        handleSearch() {
            this.currentPage = 1;
            this.loadWallets(1);
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

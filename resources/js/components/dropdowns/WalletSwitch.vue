<template>
    <DropdownMenu v-model:open="isOpen">
        <DropdownMenuTrigger as-child>
            <UiButton
                id="wallet-switch"
                size="sm"
                class="text-white border border-[#2F2F2F] bg-[#1E1E1E] hover:bg-[#313131] !rounded-md w-[193px] flex items-center justify-between overflow-hidden"
            >
                <LoaderCircle v-if="loading && wallets.length === 0" class="!h-4 !w-4 animate-spin text-[#767676] flex-shrink-0" />
                <div v-else class="flex items-center gap-x-1 flex-1 min-w-0 overflow-hidden">
                    <span class="text-sm font-medium mt-[3px] flex-shrink-0" :class="selectedWalletId ? 'text-[#f02ce6]' : 'text-[#6a64f2]'">@</span>
                    <span class="flex-1 text-left truncate" :class="selectedWalletId ? 'text-white' : 'text-[#b8b8b8]'">{{ displayWalletName ? `${displayWalletName} wallet` : 'selecionar carteira' }}</span>
                </div>
                <ChevronsUpDown v-if="!isOpen && (!loading || wallets.length > 0)" class="!h-4 !w-4 text-[#767676] flex-shrink-0 ml-1" />
                <ChevronsDownUp v-else-if="isOpen && (!loading || wallets.length > 0)" class="!h-4 !w-4 text-[#767676] flex-shrink-0 ml-1" />
            </UiButton>
        </DropdownMenuTrigger>
        <DropdownMenuContent class="w-80 bg-[#1E1E1E] border-[#2F2F2F] !rounded-md">
            <DropdownMenuLabel>my wallets</DropdownMenuLabel>
            <DropdownMenuSeparator class="bg-[#2F2F2F]"/>

            <!-- Campo de busca -->
            <div class="px-2 py-2">
                <div class="relative">
                    <Search class="absolute left-2 top-1/2 transform -translate-y-1/2 !h-4 !w-4 text-[#767676]" />
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="buscar carteira..."
                        class="w-full pl-8 pr-3 py-2 bg-[#131316] border border-[#2F2F2F] rounded-md text-white text-sm placeholder-[#767676] focus:outline-none focus:ring-2 focus:ring-[#3800D8] focus:border-transparent"
                        @input="handleSearchInput"
                    />
                </div>
            </div>

            <DropdownMenuSeparator class="bg-[#2F2F2F]"/>

            <!-- Lista de wallets -->
            <DropdownMenuGroup class="max-h-[200px] overflow-y-auto">
                <div v-if="loading" class="flex items-center justify-center py-4">
                    <LoaderCircle class="!h-4 !w-4 animate-spin text-[#767676]" />
                </div>
                <div v-else-if="wallets.length === 0" class="flex items-center justify-center py-4">
                    <span class="text-sm text-[#767676]">nenhuma carteira encontrada</span>
                </div>
                <DropdownMenuItem
                    v-for="wallet in wallets"
                    :key="wallet.id"
                    :class="[
                        '!rounded-md cursor-pointer pl-2 text-[#b8b8b8]',
                        selectedWalletId === wallet.id ? '!bg-[#4338CA] hover:!bg-[#4338CA]' : 'hover:!bg-[#38353C]'
                    ]"
                    @select.prevent="selectWallet(wallet)"
                >
                    <div class="flex items- gap-x-0.5 justify-center w-full">
                        <span class="text-sm font-medium mt-[1px]" :class="selectedWalletId === wallet.id ? 'text-[#f02ce6]' : 'text-[#6a64f2]'">@</span>
                        <span class="text-sm flex-1" :class="selectedWalletId === wallet.id ? 'text-white' : 'text-[#b8b8b8]'">{{ wallet.name }}</span>
                        <span v-if="selectedWalletId === wallet.id" class="text-white font-bold text-xs">✓</span>
                    </div>
                </DropdownMenuItem>
            </DropdownMenuGroup>

            <!-- Controles de paginação -->
            <div v-if="pagination && pagination.last_page > 1" class="flex items-center justify-between px-2 py-2 border-t border-[#2F2F2F]">
                <button
                    @click="goToPreviousPage"
                    :disabled="pagination.current_page === 1"
                    class="flex items-center gap-1 px-2 py-1 text-xs text-[#b8b8b8] hover:text-white disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <ChevronLeft class="!h-3 !w-3" />
                    anterior
                </button>
                <span class="text-xs text-[#767676]">
                    página {{ pagination.current_page }} de {{ pagination.last_page }}
                </span>
                <button
                    @click="goToNextPage"
                    :disabled="pagination.current_page === pagination.last_page"
                    class="flex items-center gap-1 px-2 py-1 text-xs text-[#b8b8b8] hover:text-white disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    próxima
                    <ChevronRight class="!h-3 !w-3" />
                </button>
            </div>

            <DropdownMenuSeparator class="bg-[#2F2F2F]"/>
            <DropdownMenuGroup>
                <DropdownMenuItem
                    class="!rounded-md cursor-pointer hover:!bg-[#38353C] text-[#b8b8b8]"
                    @click="handleAddWallet"
                >
                    <Plus class="!h-4 !w-4" />
                    add wallet
                </DropdownMenuItem>
            </DropdownMenuGroup>
        </DropdownMenuContent>
    </DropdownMenu>

    <!-- Drawer para criar wallet quando não estiver na rota wallets -->
    <Sheet v-model:open="isDrawerOpen" v-if="!isOnWalletsPage">
        <SheetContent side="right" class="w-full sm:max-w-2xl bg-[#131316] border-[#2F2F2F] overflow-y-auto">
            <SheetHeader>
                <SheetTitle class="text-white text-lg font-semibold">criar nova carteira</SheetTitle>
            </SheetHeader>
            <div class="mt-6">
                <CreateWalletForm
                    :reset-form="shouldResetForm"
                    @success="handleWalletCreated"
                    @cancel="closeDrawer"
                    @reset-complete="handleResetComplete"
                />
            </div>
        </SheetContent>
    </Sheet>
</template>
<script lang="ts">
import { Button as UiButton } from '@/components/ui/button'
import { ChevronsUpDown, ChevronsDownUp, Plus, Search, LoaderCircle, ChevronLeft, ChevronRight } from 'lucide-vue-next'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuGroup,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { Sheet, SheetContent, SheetHeader, SheetTitle } from '@/components/ui/sheet'
import CreateWalletForm from '@/components/wallets/CreateWalletForm.vue'
import { apiGet, apiPut } from '@/utils/api'
import { useToast } from '@/components/ui/toast'
import { usePage } from '@inertiajs/vue3'
import type { Wallet } from '@/types'

export default {
    components: {
        UiButton,
        DropdownMenu,
        DropdownMenuContent,
        DropdownMenuItem,
        DropdownMenuLabel,
        DropdownMenuSeparator,
        DropdownMenuTrigger,
        DropdownMenuGroup,
        ChevronsUpDown,
        ChevronsDownUp,
        Plus,
        Search,
        LoaderCircle,
        ChevronLeft,
        ChevronRight,
        Sheet,
        SheetContent,
        SheetHeader,
        SheetTitle,
        CreateWalletForm,
    },
    setup() {
        const { toast } = useToast();
        const page = usePage();
        return { toast, page };
    },
    data() {
        return {
            wallets: [] as Wallet[],
            isOpen: false,
            isDrawerOpen: false,
            shouldResetForm: false,
            selectedWalletId: null as number | null,
            selectedWalletName: null as string | null,
            loading: false,
            searchQuery: '',
            searchTimeout: null as ReturnType<typeof setTimeout> | null,
            currentPage: 1,
            perPage: 10,
            pagination: null as {
                total: number;
                per_page: number;
                current_page: number;
                last_page: number;
                from: number | null;
                to: number | null;
            } | null,
            walletDefaultCreatedListener: null as (() => void) | null,
        }
    },
    computed: {
        isOnWalletsPage(): boolean {
            return this.page.url === '/wallets' || this.page.url.startsWith('/wallets?');
        },
        displayWalletName(): string | null {
            if (!this.selectedWalletId) return null;
            // Primeiro tenta encontrar na lista atual
            const wallet = this.wallets.find(w => w.id === this.selectedWalletId);
            if (wallet) return wallet.name;

            // Se não encontrar, usa o nome armazenado
            return this.selectedWalletName;
        }
    },
    mounted() {
        this.loadWallets();

        // Listener para recarregar wallets quando uma wallet padrão é criada/editada
        this.walletDefaultCreatedListener = () => {
            this.loadWallets();
        };
        window.addEventListener('wallet-default-created-or-updated', this.walletDefaultCreatedListener);
    },
    beforeUnmount() {
        if (this.searchTimeout) {
            clearTimeout(this.searchTimeout);
        }
        if (this.walletDefaultCreatedListener) {
            window.removeEventListener('wallet-default-created-or-updated', this.walletDefaultCreatedListener);
        }
    },
    watch: {
        isOpen(newValue) {
            if (newValue && !this.selectedWalletId) {
                // Quando o dropdown abre e não há seleção, buscar a wallet padrão
                this.findAndSelectDefaultWallet();
            }
        },
        isDrawerOpen(newValue) {
            if (newValue) {
                // Garantir que o boolean está em false quando o drawer abre
                this.shouldResetForm = false;
            }
        }
    },
    methods: {
        isWalletDefault(wallet: Wallet): boolean {
            const isDefault = wallet.is_default;
            return isDefault === true || isDefault === '1' || String(isDefault) === '1';
        },
        async loadWallets(page?: number) {
            try {
                this.loading = true;
                const targetPage = page ?? this.currentPage;

                let url = `/api/wallets?page=${targetPage}&per_page=${this.perPage}`;
                if (this.searchQuery.trim()) {
                    url += `&search=${encodeURIComponent(this.searchQuery.trim())}`;
                }

                const response = await apiGet(url);
                if (!response.ok) {
                    throw new Error('Failed to load wallets');
                }

                const data = await response.json();
                this.wallets = data.data || [];

                this.pagination = {
                    total: data.total,
                    per_page: data.per_page,
                    current_page: data.current_page,
                    last_page: data.last_page,
                    from: data.from,
                    to: data.to,
                };
                this.currentPage = data.current_page;

                // Se não há seleção, buscar wallet padrão
                if (!this.selectedWalletId) {
                    const defaultWallet = this.wallets.find(w => this.isWalletDefault(w));
                    if (defaultWallet) {
                        this.selectedWalletId = defaultWallet.id;
                        this.selectedWalletName = defaultWallet.name;
                    } else {
                        await this.findAndSelectDefaultWallet();
                    }
                } else {
                    // Atualizar nome se a wallet selecionada estiver na lista
                    const selectedWallet = this.wallets.find(w => w.id === this.selectedWalletId);
                    if (selectedWallet) {
                        this.selectedWalletName = selectedWallet.name;
                        // Se não for mais padrão, buscar a padrão
                        if (!this.isWalletDefault(selectedWallet)) {
                            await this.findAndSelectDefaultWallet();
                        }
                    }
                }
            } catch (error) {
                console.error('Error loading wallets:', error);
                this.toast({
                    title: 'erro ao carregar carteiras',
                    description: 'não foi possível carregar as carteiras. tente novamente.',
                    variant: 'destructive',
                });
            } finally {
                this.loading = false;
            }
        },
        async findAndSelectDefaultWallet() {
            try {
                const response = await apiGet('/api/wallets?per_page=100&order_by=is_default&order_direction=desc');
                if (response.ok) {
                    const data = await response.json();
                    const defaultWallet = data.data?.find((w: Wallet) => this.isWalletDefault(w));
                    if (defaultWallet) {
                        this.selectedWalletId = defaultWallet.id;
                        this.selectedWalletName = defaultWallet.name;
                    } else {
                        this.selectedWalletId = null;
                        this.selectedWalletName = null;
                    }
                }
            } catch (error) {
                console.error('Error finding default wallet:', error);
            }
        },
        handleSearchInput() {
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }

            this.searchTimeout = setTimeout(() => {
                this.currentPage = 1;
                this.loadWallets(1);
            }, 500);
        },
        goToPreviousPage() {
            if (this.pagination && this.pagination.current_page > 1) {
                this.loadWallets(this.pagination.current_page - 1);
            }
        },
        goToNextPage() {
            if (this.pagination && this.pagination.current_page < this.pagination.last_page) {
                this.loadWallets(this.pagination.current_page + 1);
            }
        },
        async selectWallet(wallet: Wallet) {
            // Se já for a wallet padrão, apenas atualizar seleção
            if (this.isWalletDefault(wallet)) {
                this.selectedWalletId = wallet.id;
                this.selectedWalletName = wallet.name;
                return;
            }

            // Se não for padrão, atualizar para is_default = true
            try {
                const updateData = {
                    name: wallet.name,
                    type: wallet.type,
                    color: wallet.color || 'indigo',
                    icon: wallet.icon || null,
                    description: wallet.description || null,
                    balance: wallet.balance,
                    active: wallet.active,
                    is_default: true,
                };

                const response = await apiPut(`/api/wallets/${wallet.id}`, updateData);
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || 'Erro ao atualizar carteira');
                }

                // Atualizar estado local
                this.wallets = this.wallets.map(w => ({
                    ...w,
                    is_default: w.id === wallet.id ? true : false,
                }));

                this.selectedWalletId = wallet.id;
                this.selectedWalletName = wallet.name;

                // Emitir evento para notificar que a wallet padrão foi alterada
                window.dispatchEvent(new CustomEvent('wallet-default-changed'));
            } catch (error: any) {
                console.error('Error updating wallet:', error);
                this.toast({
                    title: 'erro ao atualizar carteira',
                    description: error.message || 'não foi possível definir a carteira como padrão. tente novamente.',
                    variant: 'destructive',
                });
            }
        },
        handleAddWallet() {
            this.isOpen = false;

            if (this.isOnWalletsPage) {
                // Se estiver na página de wallets, emitir evento para abrir o modal
                window.dispatchEvent(new CustomEvent('open-wallet-modal'));
            } else {
                // Se não estiver na página de wallets, abrir o drawer
                this.isDrawerOpen = true;
            }
        },
        async handleWalletCreated() {
            this.searchQuery = '';
            this.currentPage = 1;
            await this.loadWallets(1);
            await this.findAndSelectDefaultWallet();
            this.closeDrawer();
        },
        closeDrawer() {
            this.isDrawerOpen = false;
            this.shouldResetForm = true;
        },
        handleResetComplete() {
            this.shouldResetForm = false;
        }
    },
}

</script>

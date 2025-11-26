<template>
    <DropdownMenu v-model:open="isOpen">
        <DropdownMenuTrigger as-child>
            <UiButton
                id="wallet-switch"
                size="sm"
                class="text-foreground border border-border bg-card hover:bg-accent !rounded-md w-[193px] flex items-center justify-between overflow-hidden"
            >
                <LoaderCircle v-if="loading && wallets.length === 0" class="!h-4 !w-4 animate-spin text-muted-foreground flex-shrink-0" />
                <div v-else class="flex items-center gap-x-1 flex-1 min-w-0 overflow-hidden">
                    <span class="text-sm font-medium mt-[3px] flex-shrink-0" :style="{ color: currentAccentColor }">@</span>
                    <span class="flex-1 text-left truncate" :class="selectedWalletId ? 'text-foreground' : 'text-muted-foreground'">{{ displayWalletName ? `${displayWalletName} wallet` : 'selecionar carteira' }}</span>
                </div>
                <ChevronsUpDown v-if="!isOpen && (!loading || wallets.length > 0)" class="!h-4 !w-4 text-muted-foreground flex-shrink-0 ml-1" />
                <ChevronsDownUp v-else-if="isOpen && (!loading || wallets.length > 0)" class="!h-4 !w-4 text-muted-foreground flex-shrink-0 ml-1" />
            </UiButton>
        </DropdownMenuTrigger>
        <DropdownMenuContent class="w-80 bg-popover border-border !rounded-md">
            <DropdownMenuLabel>my wallets</DropdownMenuLabel>
            <DropdownMenuSeparator class="bg-border"/>

            <!-- Campo de busca -->
            <div class="px-2 py-2">
                <div class="relative">
                    <Search class="absolute left-2 top-1/2 transform -translate-y-1/2 !h-4 !w-4 text-muted-foreground" />
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="buscar carteira..."
                        class="w-full pl-8 pr-3 py-2 bg-card border border-border rounded-md text-foreground text-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:border-transparent"
                        :style="{ '--tw-ring-color': currentAccentColor } as any"
                        @input="handleSearchInput"
                    />
                </div>
            </div>

            <DropdownMenuSeparator class="bg-border"/>

            <!-- Lista de wallets -->
            <DropdownMenuGroup class="max-h-[200px] overflow-y-auto">
                <div v-if="loading" class="flex items-center justify-center py-4">
                    <LoaderCircle class="!h-4 !w-4 animate-spin text-muted-foreground" />
                </div>
                <div v-else-if="wallets.length === 0" class="flex items-center justify-center py-4">
                    <span class="text-sm text-muted-foreground">nenhuma carteira encontrada</span>
                </div>
                <DropdownMenuItem
                    v-for="wallet in wallets"
                    :key="wallet.id"
                    :class="[
                        '!rounded-md cursor-pointer pl-2 text-muted-foreground',
                        selectedWalletId === wallet.id ? 'text-white' : 'hover:!bg-accent hover:text-accent-foreground'
                    ]"
                    :style="selectedWalletId === wallet.id ? { backgroundColor: currentAccentColor } : {}"
                    @select.prevent="selectWallet(wallet)"
                >
                    <div class="flex items-center gap-x-0.5 justify-center w-full">
                        <span class="text-sm font-medium mt-[1px]" :style="{ color: currentAccentColor }">{{ selectedWalletId === wallet.id ? '' : '@' }}</span>
                        <span class="text-sm flex-1" :class="selectedWalletId === wallet.id ? 'text-white font-bold' : 'text-muted-foreground'">{{ wallet.name }}</span>
                        <span v-if="selectedWalletId === wallet.id" class="text-white font-bold text-xs">
                            <Check class="!h-4 !w-4" />
                        </span>
                    </div>
                </DropdownMenuItem>
            </DropdownMenuGroup>

            <!-- Controles de paginação -->
            <div v-if="pagination && pagination.last_page > 1" class="flex items-center justify-between px-2 py-2 border-t border-border">
                <button
                    @click="goToPreviousPage"
                    :disabled="pagination.current_page === 1"
                    class="flex items-center gap-1 px-2 py-1 text-xs text-muted-foreground hover:text-foreground disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <ChevronLeft class="!h-3 !w-3" />
                    anterior
                </button>
                <span class="text-xs text-muted-foreground">
                    página {{ pagination.current_page }} de {{ pagination.last_page }}
                </span>
                <button
                    @click="goToNextPage"
                    :disabled="pagination.current_page === pagination.last_page"
                    class="flex items-center gap-1 px-2 py-1 text-xs text-muted-foreground hover:text-foreground disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    próxima
                    <ChevronRight class="!h-3 !w-3" />
                </button>
            </div>

            <DropdownMenuSeparator class="bg-border"/>
            <DropdownMenuGroup>
                <DropdownMenuItem
                    class="!rounded-md cursor-pointer hover:!bg-accent text-muted-foreground"
                    @click="handleAddWallet"
                >
                    <Plus class="!h-4 !w-4" />
                    adicionar carteira
                </DropdownMenuItem>
            </DropdownMenuGroup>
        </DropdownMenuContent>
    </DropdownMenu>

    <!-- Drawer para criar wallet quando não estiver na rota wallets -->
    <Sheet v-model:open="isDrawerOpen" v-if="!isOnWalletsPage">
        <SheetContent side="right" class="w-full sm:max-w-2xl bg-card border-border overflow-y-auto">
            <SheetHeader>
                <SheetTitle class="text-foreground text-lg font-semibold">criar nova carteira</SheetTitle>
            </SheetHeader>
            <div class="mt-6">
                <CreateWalletForm
                    :reset-form="shouldResetForm"
                    :is-in-drawer="true"
                    @success="handleWalletCreated"
                    @cancel="closeDrawer"
                    @reset-complete="handleResetComplete"
                />
            </div>
        </SheetContent>
    </Sheet>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted, onBeforeUnmount, nextTick } from 'vue'
import { Button as UiButton } from '@/components/ui/button'
import { ChevronsUpDown, ChevronsDownUp, Plus, Search, LoaderCircle, ChevronLeft, ChevronRight, Check } from 'lucide-vue-next'
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
import { useUserSettings } from '@/composables/useUserSettings'
import { useThemeColors } from '@/composables/useThemeColors'
import type { Wallet } from '@/types'

// Composables
const { toast } = useToast()
const page = usePage()
const { settings } = useUserSettings()
const { applyThemeColors } = useThemeColors()

// Estado reativo
const wallets = ref<Wallet[]>([])
const isOpen = ref(false)
const isDrawerOpen = ref(false)
const shouldResetForm = ref(false)
const selectedWalletId = ref<number | null>(null)
const selectedWalletName = ref<string | null>(null)
const loading = ref(false)
const searchQuery = ref('')
const searchTimeout = ref<ReturnType<typeof setTimeout> | null>(null)
const currentPage = ref(1)
const perPage = ref(10)
const pagination = ref<{
    total: number
    per_page: number
    current_page: number
    last_page: number
    from: number | null
    to: number | null
} | null>(null)

// Ref reativo para a cor atual
const currentAccentColor = ref('#6366f1')

// Computed
const isOnWalletsPage = computed(() => {
    return page.url === '/wallets' || page.url.startsWith('/wallets?')
})

const displayWalletName = computed(() => {
    if (!selectedWalletId.value) return null
    // Primeiro tenta encontrar na lista atual
    const wallet = wallets.value.find(w => w.id === selectedWalletId.value)
    if (wallet) return wallet.name
    // Se não encontrar, usa o nome armazenado
    return selectedWalletName.value
})

// Função para atualizar a cor
const updateAccentColor = () => {
    const isDark = document.documentElement.classList.contains('dark')
    const colorMode = isDark ? 'dark' : 'light'
    const colorMap: Record<string, { light: string; dark: string }> = {
        indigo: { light: '#6366f1', dark: '#6366f1' },
        lime: { light: '#65a30d', dark: '#84cc16' },
        red: { light: '#ef4444', dark: '#dc2626' },
        orange: { light: '#f97316', dark: '#f97316' },
        cyan: { light: '#0891b2', dark: '#06b6d4' },
        purple: { light: '#a855f7', dark: '#a855f7' },
    }
    const color = settings.accent_color as string
    const newColor = colorMap[color]?.[colorMode] || colorMap.indigo[colorMode]
    currentAccentColor.value = newColor
}

// Atualiza a cor inicialmente
updateAccentColor()

// Listener para evento de mudança de cor
const handleAccentColorChange = () => {
    nextTick(() => {
        updateAccentColor()
    })
}

// Watch para settings.accent_color
watch(() => settings.accent_color, (newColor, oldColor) => {
    if (newColor !== oldColor) {
        applyThemeColors()
        nextTick(() => {
            updateAccentColor()
        })
    }
}, { immediate: true, deep: true })

// Watch para tema dark/light
watch(() => document.documentElement.classList.contains('dark'), () => {
    applyThemeColors()
    nextTick(() => {
        updateAccentColor()
    })
})

// Watch para isDrawerOpen
watch(isDrawerOpen, (newValue) => {
    if (newValue) {
        shouldResetForm.value = false
    }
})

// Métodos
const isWalletDefault = (wallet: Wallet): boolean => {
    const isDefault = wallet.is_default
    return isDefault === true || isDefault === '1' || String(isDefault) === '1'
}

const loadWallets = async (pageNum?: number) => {
    try {
        loading.value = true
        const targetPage = pageNum ?? currentPage.value

        let url = `/api/wallets?page=${targetPage}&per_page=${perPage.value}`
        // Se não há seleção, ordenar por is_default para garantir que a padrão venha primeiro
        if (!selectedWalletId.value) {
            url += `&order_by=is_default&order_direction=desc`
        }
        if (searchQuery.value.trim()) {
            url += `&search=${encodeURIComponent(searchQuery.value.trim())}`
        }

        const response = await apiGet(url)
        if (!response.ok) {
            throw new Error('Failed to load wallets')
        }

        const data = await response.json()
        wallets.value = data.data || []

        pagination.value = {
            total: data.total,
            per_page: data.per_page,
            current_page: data.current_page,
            last_page: data.last_page,
            from: data.from,
            to: data.to,
        }
        currentPage.value = data.current_page

        // Se não há seleção, buscar wallet padrão na lista retornada
        if (!selectedWalletId.value) {
            const defaultWallet = wallets.value.find(w => isWalletDefault(w))
            if (defaultWallet) {
                selectedWalletId.value = defaultWallet.id
                selectedWalletName.value = defaultWallet.name
            }
        } else {
            // Atualizar nome se a wallet selecionada estiver na lista
            const selectedWallet = wallets.value.find(w => w.id === selectedWalletId.value)
            if (selectedWallet) {
                selectedWalletName.value = selectedWallet.name
                // Se não for mais padrão, buscar a padrão
                if (!isWalletDefault(selectedWallet)) {
                    await findAndSelectDefaultWallet()
                }
            }
        }
    } catch (error) {
        console.error('Error loading wallets:', error)
        toast({
            title: 'erro ao carregar carteiras',
            description: 'não foi possível carregar as carteiras. tente novamente.',
            variant: 'destructive',
        })
    } finally {
        loading.value = false
    }
}

const findAndSelectDefaultWallet = async () => {
    try {
        const response = await apiGet('/api/wallets?per_page=5&order_by=is_default&order_direction=desc')
        if (response.ok) {
            const data = await response.json()
            const defaultWallet = data.data?.find((w: Wallet) => isWalletDefault(w))
            if (defaultWallet) {
                selectedWalletId.value = defaultWallet.id
                selectedWalletName.value = defaultWallet.name
            } else {
                selectedWalletId.value = null
                selectedWalletName.value = null
            }
        }
    } catch (error) {
        console.error('Error finding default wallet:', error)
    }
}

const handleSearchInput = () => {
    if (searchTimeout.value) {
        clearTimeout(searchTimeout.value)
    }

    searchTimeout.value = setTimeout(() => {
        currentPage.value = 1
        loadWallets(1)
    }, 500)
}

const goToPreviousPage = () => {
    if (pagination.value && pagination.value.current_page > 1) {
        loadWallets(pagination.value.current_page - 1)
    }
}

const goToNextPage = () => {
    if (pagination.value && pagination.value.current_page < pagination.value.last_page) {
        loadWallets(pagination.value.current_page + 1)
    }
}

const selectWallet = async (wallet: Wallet) => {
    // Se já for a wallet padrão, apenas atualizar seleção
    if (isWalletDefault(wallet)) {
        selectedWalletId.value = wallet.id
        selectedWalletName.value = wallet.name
        return
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
        }

        const response = await apiPut(`/api/wallets/${wallet.id}`, updateData)
        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}))
            throw new Error(errorData.message || 'Erro ao atualizar carteira')
        }

        // Atualizar estado local
        wallets.value = wallets.value.map(w => ({
            ...w,
            is_default: w.id === wallet.id ? true : false,
        }))

        selectedWalletId.value = wallet.id
        selectedWalletName.value = wallet.name

        // Emitir evento para notificar que a wallet padrão foi alterada
        window.dispatchEvent(new CustomEvent('wallet-default-changed'))
    } catch (error: any) {
        console.error('Error updating wallet:', error)
        toast({
            title: 'erro ao atualizar carteira',
            description: error.message || 'não foi possível definir a carteira como padrão. tente novamente.',
            variant: 'destructive',
        })
    }
}

const handleAddWallet = () => {
    isOpen.value = false

    if (isOnWalletsPage.value) {
        // Se estiver na página de wallets, emitir evento para abrir o modal
        window.dispatchEvent(new CustomEvent('open-wallet-modal'))
    } else {
        // Se não estiver na página de wallets, abrir o drawer
        isDrawerOpen.value = true
    }
}

const handleWalletCreated = async () => {
    searchQuery.value = ''
    currentPage.value = 1
    await loadWallets(1)
    await findAndSelectDefaultWallet()
    closeDrawer()
}

const closeDrawer = () => {
    isDrawerOpen.value = false
    shouldResetForm.value = true
}

const handleResetComplete = () => {
    shouldResetForm.value = false
}

// Lifecycle hooks
onMounted(() => {
    loadWallets()

    // Listener para recarregar wallets quando uma wallet padrão é criada/editada
    const walletDefaultCreatedListener = () => {
        loadWallets()
    }
    window.addEventListener('wallet-default-created-or-updated', walletDefaultCreatedListener)

    // Listener para evento de mudança de cor
    window.addEventListener('accent-color-changed', handleAccentColorChange)

    // Observa mudanças na variável CSS diretamente
    const observer = new MutationObserver(() => {
        const cssColor = getComputedStyle(document.documentElement).getPropertyValue('--accent-primary').trim()
        if (cssColor && cssColor !== currentAccentColor.value) {
            currentAccentColor.value = cssColor
        }
    })
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['style']
    })

    // Armazena listeners e observer para limpar depois
    ;(window as any).__walletSwitchObserver = observer
    ;(window as any).__walletSwitchListener = walletDefaultCreatedListener
    ;(window as any).__walletSwitchColorListener = handleAccentColorChange

    // Aplica cores na inicialização
    applyThemeColors()
})

onBeforeUnmount(() => {
    if (searchTimeout.value) {
        clearTimeout(searchTimeout.value)
    }

    if ((window as any).__walletSwitchListener) {
        window.removeEventListener('wallet-default-created-or-updated', (window as any).__walletSwitchListener)
    }

    if ((window as any).__walletSwitchColorListener) {
        window.removeEventListener('accent-color-changed', (window as any).__walletSwitchColorListener)
    }

    if ((window as any).__walletSwitchObserver) {
        ;(window as any).__walletSwitchObserver.disconnect()
    }
})
</script>

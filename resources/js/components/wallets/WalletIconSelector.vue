<template>
    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <label class="text-sm font-medium text-white">ícone da carteira</label>
            <button
                v-if="selectedIcon"
                type="button"
                @click="clearSelection"
                class="text-xs text-[#767676] hover:text-white transition-colors"
            >
                remover ícone
            </button>
        </div>

        <div class="flex flex-col gap-3">
            <div
                v-for="category in iconCategories"
                :key="category.name"
                class="flex flex-col gap-2"
            >
                <h4 class="text-xs font-medium text-[#767676] uppercase tracking-wide">
                    {{ category.label }}
                </h4>
                <div :class="['grid gap-2', gridColsClass]">
                    <button
                        v-for="icon in category.icons"
                        :key="icon.path"
                        type="button"
                        @click="selectIcon(icon.path)"
                        :class="[
                            'w-full aspect-square rounded-full border-2 transition-all hover:scale-105 bg-[#131316] flex items-center justify-center overflow-hidden relative',
                            selectedIcon === icon.path
                                ? 'border-[#6965f2] ring-2 ring-[#6965f2] ring-offset-2 ring-offset-[#131316]'
                                : 'border-[#2F2F2F] hover:border-[#3800D8]'
                        ]"
                        style="min-height: 56px;"
                        :title="icon.name"
                    >
                        <img
                            :src="icon.path"
                            :alt="icon.name"
                            class="w-full h-full object-contain p-2.5"
                            style="display: block; width: 100%; height: 100%; object-fit: contain;"
                            loading="lazy"
                            @error="handleImageError"
                            @load="handleImageLoad"
                        />
                    </button>
                </div>
            </div>
        </div>

        <div v-if="!selectedIcon" class="text-xs text-[#767676]">
            <p>se nenhum ícone for selecionado, será usado o ícone padrão do tipo de carteira.</p>
        </div>
    </div>
</template>

<script lang="ts">
export default {
    name: 'WalletIconSelector',
    props: {
        modelValue: {
            type: String,
            default: null,
        },
        isInDrawer: {
            type: Boolean,
            default: false,
        },
    },
    emits: ['update:modelValue'],
    data() {
        return {
            iconCategories: [
                {
                    name: 'banks',
                    label: 'bancos',
                    icons: [
                        { name: 'Banco da Amazônia', path: '/images/wallets-icons/banks/banco-da-amazonia-icon.png' },
                        { name: 'Banco do Brasil', path: '/images/wallets-icons/banks/banco-do-brasil-icon.png' },
                        { name: 'Banco do Nordeste', path: '/images/wallets-icons/banks/banco-do-nordeste-icon.png' },
                        { name: 'Banestes', path: '/images/wallets-icons/banks/banestes-icon.png' },
                        { name: 'Banif', path: '/images/wallets-icons/banks/banif-icon.png' },
                        { name: 'Banpara', path: '/images/wallets-icons/banks/banpara-icon.png' },
                        { name: 'Banrisul', path: '/images/wallets-icons/banks/banrisul-icon.png' },
                        { name: 'BBM', path: '/images/wallets-icons/banks/bbm-icon.png' },
                        { name: 'Bradesco', path: '/images/wallets-icons/banks/bradesco-icon.png' },
                        { name: 'BRB', path: '/images/wallets-icons/banks/brb-icon.png' },
                        { name: 'Caixa', path: '/images/wallets-icons/banks/caixa-icon.png' },
                        { name: 'Citibank', path: '/images/wallets-icons/banks/citibank-icon.png' },
                        { name: 'HSBC', path: '/images/wallets-icons/banks/hsbc-icon.png' },
                        { name: 'Inter', path: '/images/wallets-icons/banks/inter-icon.png' },
                        { name: 'Itaú', path: '/images/wallets-icons/banks/itau-icon.png' },
                        { name: 'Neon', path: '/images/wallets-icons/banks/neon-icon.png' },
                        { name: 'Next', path: '/images/wallets-icons/banks/next-icon.png' },
                        { name: 'Nubank', path: '/images/wallets-icons/banks/nuconta-icon.png' },
                        { name: 'Original', path: '/images/wallets-icons/banks/original-icon.png' },
                        { name: 'Safra', path: '/images/wallets-icons/banks/safra-icon.png' },
                        { name: 'Santander', path: '/images/wallets-icons/banks/santander-icon.png' },
                        { name: 'Sicoob', path: '/images/wallets-icons/banks/sicoob-icon.png' },
                        { name: 'Sicredi', path: '/images/wallets-icons/banks/sicredi-icon.png' },
                        { name: 'Votorantim', path: '/images/wallets-icons/banks/votorantim-icon.png' },
                    ],
                },
                {
                    name: 'brokers',
                    label: 'corretoras',
                    icons: [
                        { name: 'BMG', path: '/images/wallets-icons/brokers/bmg-icon.png' },
                        { name: 'Clear', path: '/images/wallets-icons/brokers/clear-icon.png' },
                        { name: 'Easynvest', path: '/images/wallets-icons/brokers/easynvest-icon.png' },
                        { name: 'Rico Investimentos', path: '/images/wallets-icons/brokers/rico-investimentos-icon.png' },
                        { name: 'Unicred', path: '/images/wallets-icons/brokers/unicred-icon.png' },
                        { name: 'Urbe.me', path: '/images/wallets-icons/brokers/urbe.me-icon.png' },
                        { name: 'Warren', path: '/images/wallets-icons/brokers/warren-icon.png' },
                        { name: 'XP Investimentos', path: '/images/wallets-icons/brokers/xp-icon.png' },
                    ],
                },
                {
                    name: 'platforms',
                    label: 'plataformas de pagamento',
                    icons: [
                        { name: 'Mercado Pago', path: '/images/wallets-icons/platforms/mercadopago-icon.png' },
                        { name: 'Moip', path: '/images/wallets-icons/platforms/moip-icon.png' },
                        { name: 'PagSeguro', path: '/images/wallets-icons/platforms/pagseguro-icon.png' },
                        { name: 'PayPal', path: '/images/wallets-icons/platforms/paypal-icon.png' },
                        { name: 'PayU', path: '/images/wallets-icons/platforms/payu-icon.png' },
                    ],
                },
            ],
        };
    },
    computed: {
        selectedIcon: {
            get(): string | null {
                return this.modelValue;
            },
            set(value: string | null) {
                this.$emit('update:modelValue', value);
            },
        },
        gridColsClass(): string {
            return this.isInDrawer
                ? 'grid-cols-8 sm:grid-cols-9 md:grid-cols-4'
                : 'grid-cols-8 sm:grid-cols-9 md:grid-cols-8';
        },
    },
    methods: {
        selectIcon(iconPath: string) {
            if (this.selectedIcon === iconPath) {
                this.clearSelection();
            } else {
                this.selectedIcon = iconPath;
            }
        },
        clearSelection() {
            this.selectedIcon = null;
        },
        handleImageError(event: Event) {
            const img = event.target as HTMLImageElement;
            console.error('Failed to load icon:', img.src);
            const button = img.closest('button');
            if (button) {
                button.classList.add('opacity-30');
                button.setAttribute('title', `${button.getAttribute('title')} (erro ao carregar)`);
            }
        },
        handleImageLoad(event: Event) {
            const img = event.target as HTMLImageElement;
            img.style.opacity = '1';
            img.style.visibility = 'visible';
        },
    },
};
</script>

<style scoped>
button img {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

button:hover img {
    transform: scale(1.05);
    transition: transform 0.2s ease;
}
</style>


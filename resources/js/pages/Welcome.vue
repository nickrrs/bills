<script setup lang="ts">
import BillsLogo from '@/components/assets/BillsLogo.vue';
import HeroOrb from '@/components/assets/HeroOrb.vue';
import { Head, Link } from '@inertiajs/vue3';
import { CreditCard, Target } from 'lucide-vue-next';
import { onMounted, onUnmounted, ref } from 'vue';

let cursorLight: HTMLElement | null = null;
let observer: IntersectionObserver | null = null;

const isVideoModalOpen = ref(false);
const videoPlayer = ref<HTMLVideoElement | null>(null);

const handleEscapeKey = (e: KeyboardEvent) => {
    if (e.key === 'Escape' && isVideoModalOpen.value) {
        closeVideoModal();
    }
};

const openVideoModal = () => {
    isVideoModalOpen.value = true;
    document.body.style.overflow = 'hidden';
    document.addEventListener('keydown', handleEscapeKey);
};

const closeVideoModal = () => {
    isVideoModalOpen.value = false;
    document.body.style.overflow = '';
    document.removeEventListener('keydown', handleEscapeKey);
    if (videoPlayer.value) {
        videoPlayer.value.pause();
        videoPlayer.value.currentTime = 0;
    }
};

const handleMouseMove = (e: MouseEvent) => {
    if (cursorLight) {
        requestAnimationFrame(() => {
            if (cursorLight) {
                cursorLight.style.left = e.clientX + 'px';
                cursorLight.style.top = e.clientY + 'px';
            }
        });
    }
};

const handleMouseEnter = () => {
    if (cursorLight) {
        cursorLight.classList.add('cursor-light-hover');
    }
};

const handleMouseLeave = () => {
    if (cursorLight) {
        cursorLight.classList.remove('cursor-light-hover');
    }
};

onMounted(() => {
    cursorLight = document.getElementById('cursor-light');
    if (cursorLight) {
        document.addEventListener('mousemove', handleMouseMove);

        const hoverTargets = document.querySelectorAll('.hover-target, a, button, .tech-card, .sys-mockup-container, .real-tx-card');
        hoverTargets.forEach((el) => {
            el.addEventListener('mouseenter', handleMouseEnter);
            el.addEventListener('mouseleave', handleMouseLeave);
        });
    }

    const revealElements = document.querySelectorAll('.reveal-on-scroll');

    observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                }
            });
        },
        {
            threshold: 0.1,
        },
    );

    revealElements.forEach((el) => {
        observer?.observe(el);
    });
});

onUnmounted(() => {
    document.removeEventListener('mousemove', handleMouseMove);
    document.removeEventListener('keydown', handleEscapeKey);

    const hoverTargets = document.querySelectorAll('.hover-target, a, button, .tech-card, .sys-mockup-container, .real-tx-card');
    hoverTargets.forEach((el) => {
        el.removeEventListener('mouseenter', handleMouseEnter);
        el.removeEventListener('mouseleave', handleMouseLeave);
    });

    if (observer) {
        observer.disconnect();
    }
});
</script>

<template>
    <Head title="bills - Controle Financeiro Total" />

    <div class="welcome-page">
        <div class="tech-grid-bg"></div>
        <div id="cursor-light" class="cursor-light"></div>

        <!-- Navbar -->
        <header class="fixed left-0 top-0 z-50 w-full border-b border-white/5 bg-[#050505]/80 backdrop-blur-md">
            <nav class="container mx-auto flex h-20 items-center justify-between px-6">
                <Link href="#" class="hover-target flex items-center space-x-3">
                    <BillsLogo :width="40" :height="40" gradient-id="logo-header" class="welcome-logo-icon" />
                    <span class="font-display text-xl font-bold tracking-tight text-white">bills</span>
                </Link>
                <div class="flex items-center gap-6">
                    <a href="#features" class="hover-target text-sm text-gray-400 transition hover:text-white">Funcionalidades</a>
                    <Link
                        href="/login"
                        class="hover-target rounded-full border border-white/10 bg-white/10 px-5 py-2 text-sm font-medium transition hover:bg-white/20"
                        >Entrar</Link
                    >
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <section class="relative z-10 flex min-h-screen items-center overflow-hidden pb-20 pt-20">
            <div class="container relative z-10 mx-auto px-6">
                <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
                    <div class="reveal-on-scroll text-left">
                        <h1 class="font-display mb-6 text-6xl font-bold leading-tight tracking-tight text-white md:text-7xl">
                            Controle o presente,<br />
                            <span class="bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">projete seu futuro.</span>
                        </h1>
                        <p class="mb-10 max-w-lg text-xl font-light leading-relaxed text-gray-400">
                            Tenha clareza sobre como você gasta seu dinheiro e ferramentas poderosas para planejar onde você quer chegar.
                        </p>
                        <div class="flex flex-col justify-start gap-4 sm:flex-row">
                            <Link href="/register" class="cta-button hover-target rounded-full px-8 py-4 text-center text-lg font-bold"
                                >Criar conta grátis</Link
                            >
                            <button
                                @click="openVideoModal"
                                class="hover-target flex items-center justify-center gap-2 rounded-full border border-white/10 px-8 py-4 font-medium text-gray-300 transition hover:bg-white/5"
                            >
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg> Ver na prática
                            </button>
                        </div>
                    </div>
                    <div class="reveal-on-scroll flex justify-center lg:justify-end">
                        <HeroOrb />
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 1: OVERVIEW DASHBOARD -->
        <section id="features" class="relative z-10 border-t border-white/5 py-32">
            <div class="container mx-auto px-6">
                <div class="reveal-on-scroll mb-16 text-center">
                    <h2 class="font-display mb-4 text-4xl font-bold text-white">Visão completa e simples</h2>
                    <p class="text-gray-400">Saiba exatamente como você gastou seu dinheiro e como está seu saldo hoje.</p>
                </div>

                <div class="reveal-on-scroll mx-auto max-w-6xl">
                    <div class="sys-mockup-container relative bg-[#09090b] p-6">
                        <!-- Header -->
                        <div class="mb-8 flex items-center justify-between border-b border-[#27272a] pb-4">
                            <div class="flex items-center gap-6">
                                <div class="text-xl font-bold tracking-tight text-white">Visão Geral</div>
                                <div class="hidden gap-4 text-sm md:flex">
                                    <span class="-mb-4 border-b-2 border-white pb-4 font-medium text-white">Overview</span>
                                    <span class="text-gray-500">Relatórios</span>
                                    <span class="text-gray-500">Lançamentos</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs uppercase text-gray-500">Julho, 2024</span>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-4">
                            <div class="mockup-card">
                                <div class="mb-2 flex items-start justify-between">
                                    <span class="text-xs text-gray-400">patrimônio</span>
                                    <span class="text-xs text-gray-500">$</span>
                                </div>
                                <div class="mb-1 text-xl font-bold text-white">R$ 23.045,32</div>
                                <div class="text-[10px] font-medium text-sys-green">+20% este mês</div>
                            </div>
                            <div class="mockup-card">
                                <div class="mb-2 flex items-start justify-between">
                                    <span class="text-xs text-gray-400">receita</span>
                                    <span class="text-xs text-gray-500">↗</span>
                                </div>
                                <div class="mb-1 text-xl font-bold text-white">R$ 23.045,32</div>
                                <div class="text-[10px] font-medium text-sys-green">+20% este mês</div>
                            </div>
                            <div class="mockup-card">
                                <div class="mb-2 flex items-start justify-between">
                                    <span class="text-xs text-gray-400">despesa</span>
                                    <span class="text-xs text-gray-500">↘</span>
                                </div>
                                <div class="mb-1 text-xl font-bold text-white">R$ 23.045,32</div>
                                <div class="text-[10px] font-medium text-sys-green">+20% este mês</div>
                            </div>
                            <div class="mockup-card">
                                <div class="mb-2 flex items-start justify-between">
                                    <span class="text-xs text-gray-400">pagamentos</span>
                                    <div
                                        class="flex h-3 w-3 items-center justify-center rounded-full border border-blue-500 text-[8px] text-blue-500"
                                    >
                                        ✓
                                    </div>
                                </div>
                                <div class="mb-1 text-xl font-bold text-white">12/15</div>
                                <div class="text-[10px] font-medium text-gray-500">3 pendentes</div>
                            </div>
                        </div>

                        <!-- Main Content -->
                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                            <div class="flex flex-col gap-6 lg:col-span-2">
                                <div class="mockup-card">
                                    <div class="mb-1 flex items-center justify-between">
                                        <span class="text-xs font-bold text-white">seus cartões</span>
                                        <span class="text-xs text-gray-500">↗</span>
                                    </div>
                                    <span class="mb-4 block text-[10px] text-gray-500">cartões com faturas ativas</span>

                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                        <div
                                            class="group relative rounded border border-[#27272a] bg-[#18181b] p-4 transition hover:border-sys-purple/50"
                                        >
                                            <div class="mb-3 flex items-center gap-3">
                                                <div
                                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-sys-purple text-xs font-bold text-white"
                                                >
                                                    Nu
                                                </div>
                                                <div>
                                                    <div class="text-[10px] text-gray-400">@nubank card</div>
                                                    <div class="text-sm font-bold text-white">R$ 2.045,32</div>
                                                </div>
                                            </div>
                                            <div class="mt-2 flex items-center justify-between">
                                                <span class="text-[10px] text-gray-500">venc: 09/08</span>
                                                <span class="rounded bg-sys-green/10 px-1.5 py-0.5 text-[9px] text-sys-green">aberta</span>
                                            </div>
                                        </div>
                                        <div
                                            class="group relative rounded border border-[#27272a] bg-[#18181b] p-4 transition hover:border-sys-purple/50"
                                        >
                                            <div class="mb-3 flex items-center gap-3">
                                                <div
                                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-white text-xs font-bold text-black"
                                                >
                                                    C6
                                                </div>
                                                <div>
                                                    <div class="text-[10px] text-gray-400">@c6 black</div>
                                                    <div class="text-sm font-bold text-white">R$ 5.300,00</div>
                                                </div>
                                            </div>
                                            <div class="mt-2 flex items-center justify-between">
                                                <span class="text-[10px] text-gray-500">venc: 15/08</span>
                                                <span class="rounded bg-sys-red/10 px-1.5 py-0.5 text-[9px] text-sys-red">fechada</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mockup-card flex-1">
                                    <div class="mb-1 flex items-center justify-between">
                                        <span class="text-xs font-bold text-white">seus rastreios</span>
                                        <span class="text-xs text-gray-500">↗</span>
                                    </div>
                                    <span class="mb-4 block text-[10px] text-gray-500">limites de gastos e objetivos</span>

                                    <div class="grid grid-cols-1 gap-x-6 gap-y-6 md:grid-cols-2">
                                        <div>
                                            <div class="mb-2 flex items-start gap-3">
                                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-400/20 text-xs text-red-400">
                                                    🚗
                                                </div>
                                                <div class="flex-1">
                                                    <div class="text-[11px] font-medium text-white">Manutenção Carro</div>
                                                    <div class="mt-1 flex justify-between text-[10px]">
                                                        <span class="text-gray-400">R$ 1.250</span>
                                                        <span class="font-bold text-white">Meta: R$ 5k</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="h-1.5 w-full overflow-hidden rounded-full bg-[#27272a]">
                                                <div class="h-full w-[25%] bg-indigo-500"></div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="mb-2 flex items-start gap-3">
                                                <div
                                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-cyan-400/20 text-xs text-cyan-400"
                                                >
                                                    🍔
                                                </div>
                                                <div class="flex-1">
                                                    <div class="text-[11px] font-medium text-white">Limite iFood</div>
                                                    <div class="mt-1 flex justify-between text-[10px]">
                                                        <span class="text-gray-400">R$ 450,00</span>
                                                        <span class="font-bold text-white">Max: R$ 600</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="h-1.5 w-full overflow-hidden rounded-full bg-[#27272a]">
                                                <div class="h-full w-[75%] bg-yellow-500"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mockup-card flex h-full flex-col">
                                <div class="mb-4">
                                    <span class="block text-xs font-bold text-white">Últimas movimentações</span>
                                    <span class="text-[10px] text-gray-500">15 transações este mês</span>
                                </div>

                                <div class="mockup-scroll flex-1 space-y-6 overflow-y-auto pr-2">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded bg-[#27272a] text-xs text-pink-500"
                                        >
                                            ☕
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="truncate text-[11px] font-medium text-white">Café da Tarde</div>
                                            <div class="text-[10px] text-gray-500">alimentação</div>
                                            <div class="mt-0.5 text-[9px] text-gray-600">Hoje, 15:30</div>
                                        </div>
                                        <div class="whitespace-nowrap text-xs font-bold text-sys-red">-R$ 15,00</div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded bg-[#27272a] text-xs text-blue-400"
                                        >
                                            🛒
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="truncate text-[11px] font-medium text-white">Supermercado Semanal</div>
                                            <div class="text-[10px] text-gray-500">casa</div>
                                            <div class="mt-0.5 text-[9px] text-gray-600">Ontem</div>
                                        </div>
                                        <div class="whitespace-nowrap text-xs font-bold text-sys-red">-R$ 450,00</div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded bg-[#27272a] text-xs text-green-500"
                                        >
                                            💰
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="truncate text-[11px] font-medium text-white">Freelance Design</div>
                                            <div class="text-[10px] text-gray-500">extra</div>
                                            <div class="mt-0.5 text-[9px] text-gray-600">05/07</div>
                                        </div>
                                        <div class="whitespace-nowrap text-xs font-bold text-sys-green">+R$ 2.500</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 2: ORGANIZE & PLAN FLOW -->
        <section class="relative z-10 border-t border-white/5 bg-white/[0.02] py-32">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 gap-16 lg:grid-cols-2">
                    <!-- Left: Text Sticky -->
                    <div class="reveal-on-scroll h-fit lg:sticky lg:top-32 lg:col-span-1">
                        <h2 class="font-display mb-8 text-5xl font-bold leading-tight text-white">
                            Do caos ao <br />
                            <span class="text-gray-500">controle</span> total
                        </h2>

                        <div class="relative space-y-10 pl-8">
                            <div class="absolute bottom-2 left-0 top-2 w-px bg-gradient-to-b from-indigo-500 to-transparent"></div>

                            <div class="relative">
                                <div class="absolute -left-[36px] top-1 h-4 w-4 rounded-full bg-indigo-500 shadow-[0_0_10px_#6366f1]"></div>
                                <h3 class="mb-2 text-xl font-bold text-white">1. Organize</h3>
                                <p class="text-sm text-gray-400">
                                    Crie <strong>Wallets</strong> customizáveis para facilitar a gestão de suas finanças.
                                </p>
                            </div>
                            <div class="relative">
                                <div class="absolute -left-[36px] top-1 h-4 w-4 rounded-full border border-indigo-500 bg-[#1e293b]"></div>
                                <h3 class="mb-2 text-xl font-bold text-white">2. Classifique</h3>
                                <p class="text-sm text-gray-400">Use categorias personalizadas para saber exatamente para onde vai cada centavo.</p>
                            </div>
                            <div class="relative">
                                <div class="absolute -left-[36px] top-1 h-4 w-4 rounded-full border border-indigo-500 bg-[#1e293b]"></div>
                                <h3 class="mb-2 text-xl font-bold text-white">3. Planeje e Projete</h3>
                                <p class="text-sm text-gray-400">
                                    Visualize relatórios de tendências e projeções futuras baseadas no seu comportamento.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Feature Visuals -->
                    <div class="reveal-on-scroll relative space-y-12 pt-10">
                        <!-- Step 1: Real Wallet Card (Green) -->
                        <div class="tech-card border-indigo-500/30 p-6">
                            <div class="mb-4 text-xs font-bold uppercase tracking-wider text-gray-500">Carteiras customizáveis</div>

                            <div
                                class="relative mx-auto max-w-sm overflow-hidden rounded-2xl bg-real-greenCard p-5 text-white shadow-xl transition duration-300 hover:scale-[1.02]"
                            >
                                <div class="relative z-10 mb-4 flex items-start justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#facc15] text-lg font-bold text-black">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                ></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-lg font-bold leading-tight">uma nova carteira</div>
                                            <div class="text-[10px] uppercase opacity-70">SEM DESCRIÇÃO</div>
                                        </div>
                                    </div>
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-white/10">•••</div>
                                </div>

                                <div class="relative z-10 mb-6">
                                    <div class="mb-1 text-[10px] uppercase opacity-70">SALDO ATUAL</div>
                                    <div class="text-3xl font-bold">R$ 5.000,00</div>
                                </div>

                                <div class="absolute bottom-16 left-0 right-0 h-12 opacity-30">
                                    <svg class="h-full w-full" preserveAspectRatio="none" viewBox="0 0 100 20">
                                        <path d="M0 20 L10 15 L30 18 L50 10 L70 14 L90 5 L100 8 V20 H0 Z" fill="white" />
                                    </svg>
                                </div>
                                <div class="relative z-10 mb-4 h-px w-full bg-white/20"></div>

                                <div class="relative z-10 flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-2">
                                        <CreditCard class="h-4 w-4" />
                                        <span>carteira ativa</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <Target class="h-4 w-4" />
                                        <span>Detalhes</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mx-auto h-8 w-px bg-white/10"></div>

                        <!-- Step 2: Categorization -->
                        <div class="tech-card p-6">
                            <div class="mb-4 text-xs font-bold uppercase tracking-wider text-gray-500">Transações classificadas</div>
                            <div class="mx-auto max-w-sm space-y-3">
                                <div class="real-tx-card">
                                    <div class="flex items-center gap-4">
                                        <div class="real-icon-box bg-real-iconPinkBg text-real-iconPink">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                                                ></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-base font-bold text-white">amazon.com</div>
                                            <div class="text-xs text-gray-500">comida</div>
                                        </div>
                                    </div>
                                    <div class="text-base font-bold text-real-redText">- R$ 129,90</div>
                                </div>

                                <div class="real-tx-card">
                                    <div class="flex items-center gap-4">
                                        <div class="real-icon-box bg-real-iconGreenBg text-real-iconGreen">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
                                                ></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-base font-bold text-white">salario</div>
                                            <div class="text-xs text-gray-500">receita</div>
                                        </div>
                                    </div>
                                    <div class="text-base font-bold text-real-greenText">+ R$ 5.000,00</div>
                                </div>
                            </div>
                        </div>

                        <div class="mx-auto h-8 w-px bg-white/10"></div>

                        <!-- Step 3: Report/Projection -->
                        <div class="tech-card border-indigo-500/50 p-6 shadow-[0_0_30px_rgba(99,102,241,0.1)]">
                            <div class="mb-4 flex items-center justify-between">
                                <div class="text-xs font-bold uppercase tracking-wider text-indigo-400">Projeção de Futuro</div>
                                <div class="text-[10px] text-gray-500">Próximos 6 meses</div>
                            </div>
                            <div class="relative flex h-24 w-full items-end gap-1 border-b border-white/10 px-2">
                                <div class="h-[40%] w-1/6 rounded-t-sm bg-gray-800"></div>
                                <div class="h-[45%] w-1/6 rounded-t-sm bg-gray-800"></div>
                                <div class="h-[30%] w-1/6 rounded-t-sm bg-gray-800"></div>
                                <div class="group relative h-[55%] w-1/6 rounded-t-sm border-t border-indigo-500 bg-indigo-900/50">
                                    <div
                                        class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-indigo-600 px-1 text-[9px] text-white opacity-0 transition group-hover:opacity-100"
                                    >
                                        Previsto
                                    </div>
                                </div>
                                <div class="h-[60%] w-1/6 rounded-t-sm border-t border-indigo-500 bg-indigo-900/50"></div>
                                <div class="h-[75%] w-1/6 rounded-t-sm border-t border-indigo-500 bg-indigo-900/50"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 3: TRACKINGS -->
        <section class="relative z-10 border-t border-white/5 py-32">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 items-center gap-20 lg:grid-cols-2">
                    <div class="reveal-on-scroll">
                        <div class="mb-2 text-xs font-bold uppercase tracking-wider text-real-trackYellow">Planeje com Antecedência</div>
                        <h2 class="font-display mb-6 text-5xl font-bold leading-tight text-white">
                            Defina, Acompanhe e <br />Bata Suas Metas.
                        </h2>
                        <p class="mb-8 text-lg leading-relaxed text-gray-400">
                            Seja um carro novo, férias ou a entrada de um imóvel, o bills ajuda você a visualizar seu progresso.
                        </p>
                        <ul class="space-y-4 text-gray-300">
                            <li class="flex items-center gap-3">
                                <svg class="h-5 w-5 text-real-trackYellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Crie metas de economia com prazos.
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="h-5 w-5 text-real-trackYellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Contribuições automáticas.
                            </li>
                        </ul>
                    </div>
                    <div class="reveal-on-scroll">
                        <div class="space-y-10">
                            <div class="track-container">
                                <div class="mb-2 flex items-center gap-3 font-medium text-white">
                                    <span class="text-xl">✈️</span> Meta de Férias
                                </div>
                                <div class="track-bar-bg">
                                    <div class="track-bar-fill" style="width: 75%">
                                        <div class="track-handle"></div>
                                    </div>
                                </div>
                                <div class="mt-2 flex justify-between font-mono text-xs text-gray-400">
                                    <span>$0</span>
                                    <span class="text-white">$1,500</span>
                                </div>
                            </div>
                            <div class="track-container">
                                <div class="mb-2 flex items-center gap-3 font-medium text-white">
                                    <span class="text-xl">🚗</span> Fundo Carro Novo
                                </div>
                                <div class="track-bar-bg">
                                    <div class="track-bar-fill" style="width: 35%">
                                        <div class="track-handle"></div>
                                    </div>
                                </div>
                                <div class="mt-2 flex justify-between font-mono text-xs text-gray-400">
                                    <span>$0</span>
                                    <span class="text-white">$5,000</span>
                                </div>
                            </div>
                            <div class="track-container">
                                <div class="mb-2 flex items-center gap-3 font-medium text-white">
                                    <span class="text-xl">🏠</span> Entrada Imóvel
                                </div>
                                <div class="track-bar-bg">
                                    <div class="track-bar-fill" style="width: 22%">
                                        <div class="track-handle"></div>
                                    </div>
                                </div>
                                <div class="mt-2 flex justify-between font-mono text-xs text-gray-400">
                                    <span>$0</span>
                                    <span class="text-white">$50,000</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 4: CARDS DETAILED -->
        <section class="relative z-10 border-t border-white/5 bg-white/[0.02] py-32">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 items-center gap-20 lg:grid-cols-2">
                    <div class="reveal-on-scroll flex justify-center">
                        <div class="group relative w-full max-w-md">
                            <div
                                class="relative overflow-hidden rounded-2xl bg-real-nuPurple p-8 text-white shadow-2xl transition-transform duration-500 hover:scale-[1.03] hover:rotate-1"
                            >
                                <div class="mb-12 flex items-start justify-between">
                                    <span class="text-xl font-bold tracking-tight">bills</span>
                                    <div class="relative h-9 w-12 overflow-hidden rounded-md bg-white/20">
                                        <div class="absolute left-0 top-1/2 h-px w-full bg-black/20"></div>
                                        <div class="absolute left-1/2 top-0 h-full w-px bg-black/20"></div>
                                        <div
                                            class="absolute left-1/2 top-1/2 h-6 w-6 -translate-x-1/2 -translate-y-1/2 rounded-full border border-black/20"
                                        ></div>
                </div>
                                </div>
                                <div class="mb-6 font-mono text-2xl tracking-widest opacity-90">**** **** **** 5678</div>
                                <div class="flex items-end justify-between">
                                    <div class="text-sm font-medium opacity-80">@nubank card</div>
                                    <div class="flex h-8 w-8 items-center justify-center rounded bg-white font-bold text-real-nuPurple">N</div>
                                </div>
                                <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
                            </div>
                            <div class="mt-8 w-full rounded-xl border border-white/10 bg-[#111113] p-4">
                                <div class="mb-2 flex justify-between text-xs text-gray-400">
                                    <span>Limite Usado</span>
                                    <span class="text-white">R$ 2.340 / 10.000</span>
                                </div>
                                <div class="h-2 w-full overflow-hidden rounded-full bg-gray-800">
                                    <div class="h-full w-[23%] bg-real-nuPurple"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="reveal-on-scroll lg:pl-10">
                        <div class="mb-2 text-xs font-bold uppercase tracking-wider text-real-nuPurple">Gestão de Crédito</div>
                        <h2 class="font-display mb-6 text-4xl font-bold leading-tight text-white md:text-5xl">
                            Cartões Virtuais e <br /> Controle de Limites.
                        </h2>
                        <p class="mb-6 text-lg leading-relaxed text-gray-400">
                            Crie cartões fictícios para espelhar seus cartões reais (Nubank, XP, Inter) dentro do sistema.
                        </p>
                        <div class="space-y-6">
                            <div class="flex gap-4">
                                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-real-nuPurple/20 text-lg text-real-nuPurple">
                                    💳
                                </div>
                                <div>
                                    <h4 class="mb-1 font-bold text-white">Múltiplos Cartões</h4>
                                    <p class="text-sm text-gray-500">
                                        Associe diferentes cartões a diferentes carteiras para organizar gastos pessoais e de trabalho.
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-real-nuPurple/20 text-lg text-real-nuPurple">
                                    📊
                                </div>
                                <div>
                                    <h4 class="mb-1 font-bold text-white">Status de Fatura</h4>
                                    <p class="text-sm text-gray-500">
                                        Marque faturas como "Aberta" ou "Fechada" e acompanhe o consumo do limite em tempo real.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 5: CAIXINHAS (RESERVAS & INVESTIMENTOS) -->
        <section class="relative z-10 border-t border-white/5 py-32">
            <div class="container mx-auto px-6">
                <div class="reveal-on-scroll mb-16 text-center">
                    <h2 class="font-display mb-4 text-4xl font-bold text-white">Caixinhas Inteligentes</h2>
                    <p class="mx-auto max-w-xl text-gray-400">
                        Separe seu dinheiro em "Caixinhas" dedicadas para reservas ou investimentos de longo prazo. Visualize o rendimento
                        projetado.
                    </p>
                </div>
                <div class="reveal-on-scroll mx-auto grid max-w-6xl grid-cols-1 gap-8 md:grid-cols-3">
                    <!-- Caixinha 1: Reserva -->
                    <div
                        class="real-tx-card h-full !flex-col !items-start !gap-4 border border-real-greenText/20 !p-6 transition-all hover:border-real-greenText/50 bg-[#09090b]"
                    >
                        <div class="flex w-full justify-between">
                            <div class="real-icon-box bg-real-greenText/10 text-real-greenText">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                    ></path>
                                </svg>
                            </div>
                            <span class="rounded bg-real-greenText/10 px-2 py-1 text-[10px] font-bold uppercase text-real-greenText"
                                >Reserva</span
                            >
                        </div>
                        <div class="mt-2">
                            <h4 class="text-lg font-bold text-white">Emergência</h4>
                            <p class="mt-1 text-xs text-gray-500">Fundo de segurança</p>
                        </div>
                        <div class="mt-auto w-full">
                            <div class="mb-2 flex justify-between text-xs text-gray-300">
                                <span>R$ 15.000</span>
                                <span class="text-gray-500">Meta: R$ 30k</span>
                            </div>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-gray-800">
                                <div class="h-full w-[50%] bg-real-greenText"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Caixinha 2: Investimento -->
                    <div
                        class="real-tx-card h-full !flex-col !items-start !gap-4 border border-real-blueVault/20 !p-6 transition-all hover:border-real-blueVault/50 bg-[#09090b]"
                    >
                        <div class="flex w-full justify-between">
                            <div class="real-icon-box bg-real-blueVault/10 text-real-blueVault">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                                    ></path>
                                </svg>
                            </div>
                            <span class="rounded bg-real-blueVault/10 px-2 py-1 text-[10px] font-bold uppercase text-real-blueVault"
                                >Renda Fixa</span
                            >
                        </div>
                        <div class="mt-2">
                            <h4 class="text-lg font-bold text-white">Tesouro Direto</h4>
                            <p class="mt-1 text-xs text-gray-500">IPCA+ 2035</p>
                            </div>
                        <div class="mt-auto w-full">
                            <div class="mb-2 flex justify-between text-xs text-gray-300">
                                <span>R$ 5.430</span>
                                <span class="font-bold text-real-blueVault">+1.2% este mês</span>
                            </div>
                            <!-- Fake Graph Line -->
                            <svg class="h-8 w-full opacity-50 text-real-blueVault" preserveAspectRatio="none" viewBox="0 0 100 20">
                                <path d="M0 15 Q 20 18, 40 10 T 100 5" fill="none" stroke="currentColor" stroke-width="2" />
                            </svg>
                        </div>
                    </div>

                    <!-- Caixinha 3: Objetivo -->
                    <div
                        class="real-tx-card h-full !flex-col !items-start !gap-4 border border-sys-purple/20 !p-6 transition-all hover:border-sys-purple/50 bg-[#09090b]"
                    >
                        <div class="flex w-full justify-between">
                            <div class="real-icon-box bg-sys-purple/10 text-sys-purple">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"
                                    ></path>
                                </svg>
                            </div>
                            <span class="rounded bg-sys-purple/10 px-2 py-1 text-[10px] font-bold uppercase text-sys-purple">Viagem</span>
                        </div>
                        <div class="mt-2">
                            <h4 class="text-lg font-bold text-white">Eurotrip 2026</h4>
                            <p class="mt-1 text-xs text-gray-500">Reserva em Moeda Forte</p>
                        </div>
                        <div class="mt-auto w-full">
                            <div class="mb-2 flex justify-between text-xs text-gray-300">
                                <span>R$ 2.000</span>
                                <span class="text-gray-500">Meta: R$ 25k</span>
                            </div>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-gray-800">
                                <div class="h-full w-[8%] bg-sys-purple"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 6: PROJEÇÃO AUTOMÁTICA (AI / FUTURE) -->
        <section class="relative z-10 border-t border-white/5 bg-white/[0.02] py-32">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 items-center gap-16 lg:grid-cols-2">
                    <!-- Text -->
                    <div class="reveal-on-scroll">
                        <div class="mb-2 text-xs font-bold uppercase tracking-wider text-indigo-400">Futuro Financeiro</div>
                        <h2 class="font-display mb-6 text-4xl font-bold leading-tight text-white md:text-5xl">
                            Automação que <br /> prevê o amanhã.
                        </h2>
                        <p class="mb-6 text-lg leading-relaxed text-gray-400">
                            O sistema analisa seu histórico de gastos recorrentes, entradas fixas e investimentos para projetar sua saúde
                            financeira.
                        </p>
                        <p class="mb-8 text-lg leading-relaxed text-gray-400">
                            Saiba exatamente onde você estará daqui a 1 ano e receba sugestões inteligentes de economia para atingir seus
                            objetivos mais rápido.
                        </p>
                    </div>
                    <!-- Visual: Projection Graph & Insights -->
                    <div class="reveal-on-scroll relative">
                        <div class="tech-card projection-card border-indigo-500/40 bg-[#0B0F19] p-6 w-[500px] max-w-full mx-auto">
                            <div class="mb-6 flex items-center justify-between">
                                <h4 class="text-sm font-bold text-white">Projeção Patrimonial (12 Meses)</h4>
                                <span
                                    class="flex items-center gap-1 rounded border border-indigo-500/30 bg-indigo-500/20 px-2 py-1 text-[10px] text-indigo-300"
                                >
                                    <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-indigo-400"></span> AI Live
                                </span>
                            </div>
                            <!-- Chart Area -->
                            <div class="relative h-48 w-[452px] max-w-full border-b border-l border-gray-700">
                                <!-- Historical (Solid) -->
                                <svg class="absolute inset-0 h-full w-full" viewBox="0 0 400 100" preserveAspectRatio="none">
                                    <path
                                        d="M0 80 L50 75 L100 60 L150 65"
                                        fill="none"
                                        stroke="#3b82f6"
                                        stroke-width="2"
                                    />
                                    <!-- Projected (Dotted) -->
                                    <path
                                        d="M150 65 L200 50 L250 40 L300 25 L350 15 L400 5"
                                        fill="none"
                                        stroke="#8B5CF6"
                                        stroke-width="2"
                                        stroke-dasharray="5,5"
                                    />
                                    <!-- Fill -->
                                    <path
                                        d="M150 65 L200 50 L250 40 L300 25 L350 15 L400 5 V 100 H 150 Z"
                                        fill="url(#gradProj)"
                                        opacity="0.2"
                                    />
                                    <defs>
                                        <linearGradient id="gradProj" x1="0%" y1="0%" x2="0%" y2="100%">
                                            <stop offset="0%" style="stop-color:#8B5CF6;stop-opacity:1" />
                                            <stop offset="100%" style="stop-color:#8B5CF6;stop-opacity:0" />
                                        </linearGradient>
                                    </defs>
                                </svg>
                                <!-- Current Point -->
                                <div
                                    class="absolute left-[170px] top-[123px] z-10 h-3 w-3 -translate-x-1/2 -translate-y-1/2 rounded-full border-2 border-indigo-500 bg-white shadow-lg"
                                ></div>
                                <div class="absolute left-[170px] top-[90px] -translate-x-1/2 text-[10px] text-gray-400">Hoje</div>
                            </div>
                            <!-- Insight Cards Floating -->
                            <div
                                class="absolute -bottom-8 -right-4 max-w-[200px] rotate-2 rounded-lg border border-indigo-500/30 bg-[#161b22] p-4 shadow-xl transition duration-300 hover:rotate-0"
                            >
                                <div class="flex items-start gap-3">
                                    <div class="text-xl">💡</div>
                                    <div>
                                        <p class="mb-1 text-[10px] font-bold text-white">Sugestão de Economia</p>
                                        <p class="text-[9px] leading-snug text-gray-400">
                                            Se você reduzir 10% em "iFood", atingirá a meta do Carro 2 meses antes.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 7: REPORTS -->
        <section class="relative z-10 border-t border-white/5 py-32">
            <div class="container mx-auto px-6">
                <div class="reveal-on-scroll mb-16 text-center">
                    <h2 class="font-display mb-4 text-4xl font-bold text-white">Insights que geram riqueza</h2>
                    <p class="text-gray-400">Relatórios detalhados para entender seus hábitos e evoluir.</p>
                </div>
                <div class="reveal-on-scroll mx-auto grid max-w-5xl grid-cols-1 gap-8 md:grid-cols-2">
                    <!-- Report 1: Categorias (Pie) -->
                    <div class="sys-mockup-container bg-[#09090b] p-6">
                        <h3 class="mb-6 text-sm font-bold text-white">Gastos por Categoria</h3>
                        <div class="flex items-center justify-around">
                            <!-- CSS Pie Chart -->
                            <div
                                class="relative h-40 w-40 rounded-full"
                                style="background: conic-gradient(#3b82f6 0% 40%, #10b981 40% 65%, #f59e0b 65% 85%, #ec4899 85% 100%);"
                            >
                                <div class="absolute inset-4 flex items-center justify-center rounded-full bg-[#09090b]">
                                    <span class="text-xs text-gray-500"
                                        >Total<br /><strong class="text-lg text-white">100%</strong></span
                                    >
                                </div>
                            </div>
                            <!-- Legend -->
                            <div class="space-y-3 text-sm">
                                <div class="flex items-center gap-2">
                                    <div class="h-3 w-3 rounded-full bg-blue-500"></div>
                                    <span class="text-gray-400">Mercado (40%)</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="h-3 w-3 rounded-full bg-emerald-500"></div>
                                    <span class="text-gray-400">Casa (25%)</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="h-3 w-3 rounded-full bg-orange-500"></div>
                                    <span class="text-gray-400">Lazer (20%)</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="h-3 w-3 rounded-full bg-pink-500"></div>
                                    <span class="text-gray-400">Transp. (15%)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Report 2: Evolution (Line) -->
                    <div class="sys-mockup-container bg-[#09090b] p-6">
                        <h3 class="mb-6 text-sm font-bold text-white">Evolução Patrimonial</h3>
                        <div class="relative flex h-40 w-full items-end gap-2 px-2">
                            <!-- Grid Lines -->
                            <div class="pointer-events-none absolute inset-0 flex flex-col justify-between opacity-10">
                                <div class="h-px w-full bg-white"></div>
                                <div class="h-px w-full bg-white"></div>
                                <div class="h-px w-full bg-white"></div>
                                <div class="h-px w-full bg-white"></div>
                            </div>
                            <!-- Line Graph (SVG) -->
                            <svg class="absolute inset-0 h-full w-full" preserveAspectRatio="none">
                                <path
                                    d="M0 150 L50 130 L100 140 L150 100 L200 90 L250 110 L300 60 L350 50 L400 20"
                                    fill="none"
                                    stroke="#8B5CF6"
                                    stroke-width="3"
                                />
                                <path
                                    d="M0 150 L50 130 L100 140 L150 100 L200 90 L250 110 L300 60 L350 50 L400 20 V 160 H 0 Z"
                                    fill="url(#gradPurple)"
                                    opacity="0.2"
                                />
                                <defs>
                                    <linearGradient id="gradPurple" x1="0%" y1="0%" x2="0%" y2="100%">
                                        <stop offset="0%" style="stop-color:#8B5CF6;stop-opacity:1" />
                                        <stop offset="100%" style="stop-color:#8B5CF6;stop-opacity:0" />
                                    </linearGradient>
                                </defs>
                            </svg>
                            <!-- Points -->
                            <div class="absolute bottom-8 left-[20%] h-2 w-2 rounded-full border-2 border-indigo-500 bg-white"></div>
                            <div class="absolute bottom-20 left-[50%] h-2 w-2 rounded-full border-2 border-indigo-500 bg-white"></div>
                            <div class="absolute right-0 top-4 h-2 w-2 rounded-full border-2 border-indigo-500 bg-white"></div>
                        </div>
                        <div class="mt-4 flex justify-between text-xs text-gray-500">
                            <span>Jan</span><span>Fev</span><span>Mar</span><span>Abr</span><span>Mai</span><span>Jun</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 8: TRUST & ACCESS -->
        <section class="relative z-10 border-t border-white/5 bg-white/[0.02] py-32">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 items-center gap-16 lg:grid-cols-2">
                    <!-- Visuals -->
                    <div class="reveal-on-scroll order-2 grid grid-cols-2 gap-4 lg:order-1">
                        <div class="sys-mockup-container flex flex-col items-center text-center p-6">
                            <div class="security-icon-box">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                    ></path>
                                </svg>
                            </div>
                            <h4 class="mb-2 text-sm font-bold text-white">Dados Locais</h4>
                            <p class="text-xs text-gray-400">Você é o dono dos seus dados. Sem compartilhamento oculto.</p>
                        </div>
                        <div class="sys-mockup-container mt-8 flex flex-col items-center text-center p-6">
                            <div class="security-icon-box">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                    ></path>
                                </svg>
                            </div>
                            <h4 class="mb-2 text-sm font-bold text-white">Controle Manual</h4>
                            <p class="text-xs text-gray-400">Input manual para precisão total, sem erros de sincronização bancária.</p>
                        </div>
                        <div class="sys-mockup-container flex flex-col items-center text-center p-6">
                            <div class="security-icon-box">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H4a3 3 0 00-3 3v8a3 3 0 003 3z"
                                    ></path>
                                </svg>
                            </div>
                            <h4 class="mb-2 text-sm font-bold text-white">Cartões Fictícios</h4>
                            <p class="text-xs text-gray-400">Simule suas contas reais com segurança usando entidades virtuais.</p>
                        </div>
                    </div>
                    <!-- Text -->
                    <div class="reveal-on-scroll order-1 lg:order-2">
                        <div class="mb-2 text-xs font-bold uppercase tracking-wider text-indigo-400">Segurança e Privacidade</div>
                        <h2 class="font-display mb-6 text-4xl font-bold leading-tight text-white md:text-5xl">
                            Confiança e Acesso.
                        </h2>
                        <p class="mb-6 text-lg leading-relaxed text-gray-400">
                            O bills foi desenhado com a privacidade em mente. Sem dados confidenciais e sensíveis, apenas o que é preciso para
                            sua gestão financeira.
                        </p>
                        <p class="mb-6 text-lg leading-relaxed text-gray-400">
                            A princípio, todos os dados financeiros são inseridos e gerenciados por você. Utilizamos
                            <strong>Wallets fictícias</strong> para representar suas contas e cartões, garantindo que nenhuma informação
                            bancária real trafegue pelo sistema.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 9: CTA GREETINGS -->
        <section class="relative z-10 overflow-hidden border-t border-white/5 py-32 text-center">
            <div class="pointer-events-none absolute left-1/2 top-1/2 h-[600px] w-[600px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-indigo-600/10 blur-[120px]"></div>

            <div class="container relative z-10 mx-auto px-6 reveal-on-scroll">
                <h2 class="font-display mb-8 text-5xl font-bold tracking-tight text-white md:text-6xl">Pronto para começar?</h2>
                <p class="mx-auto mb-10 max-w-2xl text-xl text-gray-400">
                    Junte-se a milhares de usuários que estão retomando o controle de suas vidas financeiras. Simples, seguro e grátis.
                </p>
                <div class="flex flex-col justify-center gap-4 sm:flex-row">
                    <Link
                        href="/register"
                        class="cta-button hover-target rounded-full bg-white px-12 py-5 text-lg font-bold text-black shadow-[0_0_30px_rgba(255,255,255,0.1)] transition hover:bg-gray-200"
                    >
                        Criar Conta Grátis
                    </Link>
                </div>
            </div>
        </section>

        <footer class="relative z-10 border-t border-white/5 bg-[#050505] py-12 text-center">
            <div class="container mx-auto px-6">
                <div class="text-xs text-gray-600">&copy; 2025 bills Inc. System Status: Operational</div>
            </div>
        </footer>

        <!-- Video Modal -->
        <Teleport to="body">
            <Transition name="modal">
                <div
                    v-if="isVideoModalOpen"
                    class="video-modal-overlay"
                    @click.self="closeVideoModal"
                >
                    <div class="video-modal-container">
                        <button @click="closeVideoModal" class="video-modal-close hover-target" aria-label="Fechar">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <div class="video-modal-content">
                            <video
                                ref="videoPlayer"
                                class="video-player"
                                controls
                                autoplay
                                src="/video/bills-23.mp4"
                                @ended="closeVideoModal"
                            >
                                Seu navegador não suporta a reprodução de vídeo.
                            </video>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<style scoped>
/* --- ESTILOS GERAIS --- */
.welcome-page {
    font-family: 'Inter', sans-serif;
    background-color: #050505;
    color: #e0e0e0;
    overflow-x: hidden;
    min-height: 100vh;
    cursor: none;
    position: relative;
    z-index: 1;
}

/* Garantir que o sticky funcione na SECTION 2 */
section:nth-of-type(2) {
    overflow: visible;
}

.sticky-content {
    transition: none;
}

.welcome-page * {
    cursor: none !important;
}

.tech-grid-bg {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: 50px 50px;
    background-image:
        linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
    pointer-events: none;
    z-index: 0;
    mask-image: radial-gradient(circle at 50% 50%, black, transparent 85%);
    -webkit-mask-image: radial-gradient(circle at 50% 50%, black, transparent 85%);
}

.cursor-light {
    position: fixed;
    width: 20px;
    height: 20px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.05);
    pointer-events: none;
    transform: translate(-50%, -50%);
    transition:
        width 0.3s,
        height 0.3s,
        background 0.3s,
        border 0.3s;
    z-index: 9999;
    backdrop-filter: blur(2px);
    display: none;
}

@media (min-width: 1024px) {
    .cursor-light {
        display: block;
    }
}

.cursor-light-hover {
    width: 60px;
    height: 60px;
    background: rgba(99, 102, 241, 0.1);
    border-color: rgba(99, 102, 241, 0.6);
}

.welcome-logo-icon {
    display: inline-block;
    overflow: visible;
    flex-shrink: 0;
}

.welcome-logo-icon :deep(svg) {
    display: block;
    overflow: visible;
    shape-rendering: geometricPrecision;
}

.reveal-on-scroll {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s ease;
}

.reveal-on-scroll.is-visible {
    opacity: 1;
    transform: translateY(0);
}

.tech-card {
    background: rgba(11, 15, 25, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 8px;
    position: relative;
    overflow: hidden;
    transition: border-color 0.3s;
}

.tech-card:hover {
    border-color: rgba(99, 102, 241, 0.4);
}

.tech-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
}

.real-tx-card {
    background-color: #0d0d0e;
    border: 1px solid #27272a;
    border-radius: 12px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: transform 0.2s;
}

.real-tx-card:hover {
    transform: scale(1.02);
    border-color: #3f3f46;
}

.real-icon-box {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.sys-mockup-container {
    background: #09090b;
    border: 1px solid #27272a;
    border-radius: 12px;
    box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.5);
    font-family: 'Inter', sans-serif;
    overflow: hidden;
}

.mockup-card {
    background-color: #111113;
    border: 1px solid #27272a;
    border-radius: 8px;
    padding: 16px;
}

.mockup-scroll::-webkit-scrollbar {
    height: 6px;
    width: 6px;
}

.mockup-scroll::-webkit-scrollbar-track {
    background: transparent;
}

.mockup-scroll::-webkit-scrollbar-thumb {
    background: #3f3f46;
    border-radius: 3px;
}

.cta-button {
    background: white;
    color: black;
    transition: all 0.3s;
}

.cta-button:hover {
    background: #e2e8f0;
    transform: translateY(-1px);
    box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
}

.track-container {
    width: 100%;
    margin-bottom: 30px;
}

.track-bar-bg {
    width: 100%;
    height: 12px;
    background-color: #27272a;
    border-radius: 999px;
    position: relative;
    margin-top: 12px;
}

.track-bar-fill {
    height: 100%;
    background-color: #FFB800;
    border-radius: 999px;
    position: relative;
    display: flex;
    align-items: center;
}

.track-handle {
    width: 20px;
    height: 20px;
    background-color: #FFB800;
    border: 3px solid #0B0F19;
    border-radius: 50%;
    position: absolute;
    right: -6px;
    box-shadow: 0 0 10px rgba(255, 184, 0, 0.5);
}

.security-icon-box {
    width: 64px;
    height: 64px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    color: #8B5CF6;
}

/* Video Modal Styles */
.video-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.9);
    backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    padding: 20px;
}

.video-modal-container {
    position: relative;
    max-width: 90vw;
    max-height: 90vh;
    width: 100%;
    aspect-ratio: 16 / 9;
}

@media (max-width: 768px) {
    .video-modal-container {
        max-width: 95vw;
        max-height: 85vh;
    }
}

.video-modal-content {
    position: relative;
    width: 100%;
    height: 100%;
    border-radius: 12px;
    overflow: hidden;
    background: #000;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.video-player {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.video-modal-close {
    position: absolute;
    top: -50px;
    right: 0;
    z-index: 10001;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    transition: all 0.3s;
    cursor: pointer;
    backdrop-filter: blur(10px);
}

.video-modal-close:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.4);
    transform: scale(1.1);
}

@media (max-width: 768px) {
    .video-modal-close {
        top: -40px;
        width: 36px;
        height: 36px;
    }
}

/* Modal Transitions */
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

.modal-enter-active .video-modal-container,
.modal-leave-active .video-modal-container {
    transition: transform 0.3s ease, opacity 0.3s ease;
}

.modal-enter-from .video-modal-container,
.modal-leave-to .video-modal-container {
    transform: scale(0.9);
    opacity: 0;
}

/* Projection Card Fixed Size */
.projection-card {
    min-width: 500px;
    width: 500px;
}

.projection-card .relative.h-48 {
    width: 452px;
}

@media (max-width: 640px) {
    .projection-card {
        min-width: 100%;
        width: 100%;
        max-width: 500px;
    }

    .projection-card .relative.h-48 {
        width: 100% !important;
        max-width: 452px;
    }
}
</style>

<script setup lang="ts">
import { onMounted, onUnmounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import BillsLogo from '@/components/assets/BillsLogo.vue';

let cursorLight: HTMLElement | null = null;
let observer: IntersectionObserver | null = null;

const handleMouseMove = (e: MouseEvent) => {
    if (cursorLight) {
        cursorLight.style.left = e.clientX + 'px';
        cursorLight.style.top = e.clientY + 'px';
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
    // Cursor Light
    cursorLight = document.getElementById('cursor-light');
    if (cursorLight) {
        document.addEventListener('mousemove', handleMouseMove);

        const hoverTargets = document.querySelectorAll('.hover-target, a, button');
        hoverTargets.forEach(el => {
            el.addEventListener('mouseenter', handleMouseEnter);
            el.addEventListener('mouseleave', handleMouseLeave);
        });
    }

    // Scroll Reveal
    const revealElements = document.querySelectorAll('.reveal-on-scroll');

    observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
            }
        });
    }, {
        threshold: 0.1
    });

    revealElements.forEach(el => {
        observer?.observe(el);
    });
});

onUnmounted(() => {
    document.removeEventListener('mousemove', handleMouseMove);

    const hoverTargets = document.querySelectorAll('.hover-target, a, button');
    hoverTargets.forEach(el => {
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
    <!-- Cursor Follower Light -->
    <div class="cursor-light" id="cursor-light"></div>

    <!-- Header -->
    <header class="absolute top-0 left-0 w-full z-50 py-6">
        <nav class="container mx-auto px-6 flex justify-between items-center">
                <!-- LOGO SECTION -->
            <div>
                <a href="#" class="flex items-center space-x-2 hover-target welcome-logo-link">
                    <BillsLogo :width="30" :height="30" gradient-id="logo-header" class="welcome-logo-icon" />
                    <span class="text-3xl font-bold text-white">bills</span>
                </a>
            </div>
            <!-- END LOGO SECTION -->

            <div class="hidden md:flex items-center space-x-8">
                <a href="#features" class="text-gray-300 hover:text-white transition duration-300 hover-target">Recursos</a>
                <a href="#security" class="text-gray-300 hover:text-white transition duration-300 hover-target">Segurança</a>
                <a href="#pricing" class="text-gray-300 hover:text-white transition duration-300 hover-target">Preços</a>
            </div>
            <div>
                <a href="login" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition duration-300 hover-target">
                    Comece Agora
                </a>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="relative h-screen flex items-center justify-center overflow-hidden pt-16">
        <div class="container mx-auto px-6 z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Text Content -->
                <div class="text-center lg:text-left">
                    <span class="text-indigo-400 font-semibold uppercase tracking-wider">Conheça o bills</span>
                    <h1 class="text-5xl md:text-7xl font-extrabold text-white mt-4 mb-6">
                        Controle Total da Sua Vida Financeira.
                    </h1>
                    <p class="text-xl text-gray-300 mb-10">
                        Pare de adivinhar, comece a saber. O bills é a plataforma tudo-em-um para rastrear despesas, gerenciar receitas e alcançar seus objetivos financeiros.
                    </p>
                    <a href="#pricing" class="cta-button inline-block px-10 py-4 bg-indigo-600 text-white text-lg font-semibold rounded-lg shadow-lg hover:bg-indigo-700 transition duration-300 transform hover:scale-105 hover-target">
                        Comece de Graça
                    </a>
                </div>
                <!-- Animated Visual: Orb -->
                <div class="hidden lg:flex items-center justify-center h-[500px]">
                    <div class="hero-orb">
                        <div class="hero-orb-layer"></div>
                        <div class="hero-orb-layer"></div>
                        <div class="hero-orb-layer"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Background Gradient -->
        <div class="absolute inset-0 z-0 opacity-30">
            <div class="absolute bottom-0 left-0 w-full h-full bg-gradient-to-t from-[#0a041c] via-transparent to-transparent"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-indigo-900 rounded-full blur-[150px] opacity-50"></div>
            <div class="absolute top-1/4 left-1/4 w-[600px] h-[600px] bg-purple-900 rounded-full blur-[120px] opacity-40"></div>
        </div>
    </section>

    <!-- Main Content Area -->
    <main id="features">

        <!-- Feature Section 1: All-in-one Dashboard -->
        <section class="py-24 sm:py-32 overflow-hidden">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                    <!-- Animated Visual: Dashboard -->
                    <div class="reveal-on-scroll flex justify-center">
                        <div class="w-full max-w-md p-6 rounded-lg animated-dashboard">
                            <h4 class="text-white font-semibold mb-4">Resumo Mensal</h4>
                            <div class="flex items-end justify-center h-48 space-x-1">
                                <!-- Animating Bars -->
                                <div class="dashboard-bar w-8 rounded-t" style="animation-delay: 0.1s; height: 60%;"></div>
                                <div class="dashboard-bar w-8 rounded-t" style="animation-delay: 0.3s; height: 80%;"></div>
                                <div class="dashboard-bar w-8 rounded-t" style="animation-delay: 0.5s; height: 50%;"></div>
                                <div class="dashboard-bar w-8 rounded-t" style="animation-delay: 0.2s; height: 90%;"></div>
                                <div class="dashboard-bar w-8 rounded-t" style="animation-delay: 0.4s; height: 70%;"></div>
                                <div class="dashboard-bar w-8 rounded-t" style="animation-delay: 0.1s; height: 60%;"></div>
                                <div class="dashboard-bar w-8 rounded-t" style="animation-delay: 0.6s; height: 85%;"></div>
                            </div>
                            <div class="mt-4 p-4 bg-white/10 rounded-lg">
                                <span class="text-gray-300 text-sm">Receita Líquida</span>
                                <p class="text-white text-2xl font-bold">$2,450.78</p>
                            </div>
                        </div>
                    </div>

                    <!-- Text Content -->
                    <div class="reveal-on-scroll">
                        <span class="text-blue-400 font-semibold uppercase">Um só Lugar</span>
                        <h3 class="text-4xl md:text-5xl font-bold text-white mt-4">
                            Suas Finanças, Tudo em Um Só Lugar.
                        </h3>
                        <p class="text-lg text-gray-300 mt-6">
                            Veja receitas, despesas e metas em um piscar de olhos. Nosso painel intuitivo reúne todos os seus dados financeiros, facilitando o entendimento de sua situação.
                        </p>
                        <ul class="mt-8 space-y-4">
                            <li class="flex items-center text-lg">
                                <svg class="w-6 h-6 text-blue-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Conecte todas as suas contas com segurança.
                            </li>
                            <li class="flex items-center text-lg">
                                <svg class="w-6 h-6 text-blue-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Sincronização de transações em tempo real.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Feature Section 2: Categorization -->
        <section class="py-24 sm:py-32 overflow-hidden">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                    <!-- Text Content -->
                    <div class="reveal-on-scroll lg:order-last">
                        <span class="text-emerald-400 font-semibold uppercase">Rastreamento Inteligente</span>
                        <h3 class="text-4xl md:text-5xl font-bold text-white mt-4">
                            Veja Para Onde Vai Cada Centavo.
                        </h3>
                        <p class="text-lg text-gray-300 mt-6">
                            O bills categoriza seus gastos automaticamente. De compras de supermercado a assinaturas, você finalmente terá uma visão clara de seus hábitos financeiros.
                        </p>
                        <ul class="mt-8 space-y-4">
                            <li class="flex items-center text-lg">
                                <svg class="w-6 h-6 text-emerald-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Categorização automática com IA.
                            </li>
                            <li class="flex items-center text-lg">
                                <svg class="w-6 h-6 text-emerald-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Crie categorias e tags personalizadas.
                            </li>
                        </ul>
                    </div>

                        <!-- Animated Visual: Flowing Categories -->
                    <div class="reveal-on-scroll flex justify-center items-center min-h-[300px]">
                        <svg class="w-full max-w-lg" viewBox="0 0 400 250" xmlns="http://www.w3.org/2000/svg">
                            <!-- Paths -->
                            <path d="M 40 125 C 100 125, 150 50, 200 50 L 350 50" stroke="#10b981" stroke-width="2" fill="none" class="flowing-line-svg" style="animation-delay: 0.5s;"></path>
                            <path d="M 40 125 C 100 125, 150 100, 200 100 L 350 100" stroke="#f59e0b" stroke-width="2" fill="none" class="flowing-line-svg" style="animation-delay: 1s;"></path>
                            <path d="M 40 125 C 100 125, 150 150, 200 150 L 350 150" stroke="#3b82f6" stroke-width="2" fill="none" class="flowing-line-svg" style="animation-delay: 0s;"></path>
                            <path d="M 40 125 C 100 125, 150 200, 200 200 L 350 200" stroke="#ec4899" stroke-width="2" fill="none" class="flowing-line-svg" style="animation-delay: 1.5s;"></path>

                            <!-- Start Node (Income) -->
                            <circle cx="40" cy="125" r="25" fill="#a78bfa"/>
                            <text x="40" y="127" font-size="24" fill="white" text-anchor="middle" dominant-baseline="central">💰</text>

                            <!-- End Nodes (Categories) -->
                            <circle cx="370" cy="50" r="20" fill="#10b981"/>
                            <text x="370" y="52" font-size="24" fill="white" text-anchor="middle" dominant-baseline="central">🍔</text>

                            <circle cx="370" cy="100" r="20" fill="#f59e0b"/>
                            <text x="370" y="102" font-size="24" fill="white" text-anchor="middle" dominant-baseline="central">🏠</text>

                            <circle cx="370" cy="150" r="20" fill="#3b82f6"/>
                            <text x="370" y="152" font-size="24" fill="white" text-anchor="middle" dominant-baseline="central">🚗</text>

                            <circle cx="370" cy="200" r="20" fill="#ec4899"/>
                            <text x="370" y="202" font-size="24" fill="white" text-anchor="middle" dominant-baseline="central">🎬</text>
                        </svg>
                    </div>

                </div>
            </div>
        </section>

        <!-- Feature Section 3: Goals -->
        <section class="py-24 sm:py-32 overflow-hidden">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                    <!-- Text Content -->
                    <div class="reveal-on-scroll">
                        <span class="text-amber-400 font-semibold uppercase">Planeje com Antecedência</span>
                        <h3 class="text-4xl md:text-5xl font-bold text-white mt-4">
                            Defina, Acompanhe e Bata Suas Metas.
                        </h3>
                        <p class="text-lg text-gray-300 mt-6">
                            Seja um carro novo, férias ou a entrada de um imóvel, o bills ajuda você a visualizar seu progresso. Defina uma meta e nós o ajudaremos a visualizar o caminho para alcançá-la.
                        </p>
                        <ul class="mt-8 space-y-4">
                            <li class="flex items-center text-lg">
                                <svg class="w-6 h-6 text-amber-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Crie metas de economia com prazos.
                            </li>
                            <li class="flex items-center text-lg">
                                <svg class="w-6 h-6 text-amber-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Contribuições automáticas e acompanhamento de progresso.
                            </li>
                        </ul>
                    </div>

                    <!-- Animated Visual: Goal Stack -->
                    <div class="reveal-on-scroll flex flex-col justify-center items-center min-h-[300px] w-full">

                            <!-- HORIZONTAL GOAL TRACKER 1 -->
                        <div class="goal-tracker-horizontal goal-tracker-1">
                            <svg viewBox="0 0 300 100">
                                <text class="goal-emoji" x="13" y="25" dominant-baseline="central" text-anchor="middle">✈️</text>
                                <text class="goal-text" x="35" y="25" dominant-baseline="central">Meta de Férias</text>
                                <!-- Track Background -->
                                <rect class="goal-track-bg" x="30" y="50" width="220" height="12" rx="6" />
                                <!-- Track Fill -->
                                <rect class="goal-track-fill-rect" x="30" y="50" height="12" rx="6" />
                                <!-- Marker -->
                                <circle class="goal-marker-circle" cy="56" r="8" />
                                <!-- Text -->
                                <text class="goal-text" x="30" y="80">$0</text>
                                <text class="goal-text" x="250" y="80" text-anchor="end">$1,500</text>
                            </svg>
                        </div>

                            <!-- HORIZONTAL GOAL TRACKER 2 -->
                        <div class="goal-tracker-horizontal goal-tracker-2">
                            <svg viewBox="0 0 300 100">
                                <text class="goal-emoji" x="13" y="25" dominant-baseline="central" text-anchor="middle">🚗</text>
                                <text class="goal-text" x="35" y="25" dominant-baseline="central">Fundo Carro Novo</text>
                                <rect class="goal-track-bg" x="30" y="50" width="220" height="12" rx="6" />
                                <rect class="goal-track-fill-rect" x="30" y="50" height="12" rx="6" />
                                <circle class="goal-marker-circle" cy="56" r="8" />
                                <text class="goal-text" x="30" y="80">$0</text>
                                <text class="goal-text" x="250" y="80" text-anchor="end">$5,000</text>
                            </svg>
                        </div>

                            <!-- HORIZONTAL GOAL TRACKER 3 -->
                        <div class="goal-tracker-horizontal goal-tracker-3">
                            <svg viewBox="0 0 300 100">
                                <text class="goal-emoji" x="13" y="25" dominant-baseline="central" text-anchor="middle">🏠</text>
                                <text class="goal-text" x="35" y="25" dominant-baseline="central">Entrada Imóvel</text>
                                <rect class="goal-track-bg" x="30" y="50" width="220" height="12" rx="6" />
                                <rect class="goal-track-fill-rect" x="30" y="50" height="12" rx="6" />
                                <circle class="goal-marker-circle" cy="56" r="8" />
                                <text class="goal-text" x="30" y="80">$0</text>
                                <text class="goal-text" x="250" y="80" text-anchor="end">$50,000</text>
                            </svg>
                        </div>

                    </div>

                </div>
            </div>
        </section>

            <!-- Feature Section 4: Wallets -->
        <section class="py-24 sm:py-32 overflow-hidden">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                        <!-- Animated Visual: Card Stack -->
                    <div class="reveal-on-scroll lg:order-first order-last flex justify-center items-center min-h-[300px]">
                        <div class="card-stack">
                            <!-- Card 1 -->
                            <div class="wallet-card card-1">
                                <span class="card-logo">bills</span>
                                <div class="card-chip mt-4"></div>
                                <div class="card-number">**** **** **** 1234</div>
                                <div class="card-wallet-name">Carteira Pessoal</div>
                            </div>
                            <!-- Card 2 -->
                            <div class="wallet-card card-2">
                                <span class="card-logo">bills</span>
                                <div class="card-chip mt-4"></div>
                                <div class="card-number">**** **** **** 5678</div>
                                <div class="card-wallet-name">Carteira Empresarial</div>
                            </div>
                            <!-- Card 3 -->
                            <div class="wallet-card card-3">
                                <span class="card-logo">bills</span>
                                <div class="card-chip mt-4"></div>
                                <div class="card-number">**** **** **** 9012</div>
                                <div class="card-wallet-name">Fundo de Férias</div>
                            </div>
                        </div>
                    </div>

                    <!-- Text Content -->
                    <div class="reveal-on-scroll">
                        <span class="text-purple-400 font-semibold uppercase">Mantenha-se Organizado</span>
                        <h3 class="text-4xl md:text-5xl font-bold text-white mt-4">
                            Personalize Carteiras e Cartões
                        </h3>
                        <p class="text-lg text-gray-300 mt-6">
                            A vida não é padronizada, e seu dinheiro também não. Crie carteiras separadas para "Pessoal", "Negócios" ou "Projetos". Você também pode criar cartões virtuais para ver os dados de gastos segmentados exatamente como você deseja.
                        </p>
                        <ul class="mt-8 space-y-4">
                            <li class="flex items-center text-lg">
                                <svg class="w-6 h-6 text-purple-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Crie "carteiras" ilimitadas
                            </li>
                            <li class="flex items-center text-lg">
                                <svg class="w-6 h-6 text-purple-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Cartões virtuais estilosos para rastreamento
                            </li>
                            <li class="flex items-center text-lg">
                                <svg class="w-6 h-6 text-purple-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Filtre relatórios por carteira e cartão
                            </li>
                            <li class="flex items-center text-lg">
                                <svg class="w-6 h-6 text-purple-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Organize os seus gastos por carteira ou cartão
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

            <!-- Feature Section 5: Reports -->
        <section class="py-24 sm:py-32 overflow-hidden">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                    <!-- Text Content -->
                    <div class="reveal-on-scroll">
                        <span class="text-pink-400 font-semibold uppercase">Insights Profundos</span>
                        <h3 class="text-4xl md:text-5xl font-bold text-white mt-4">
                            Relatórios Poderosos e Simples
                        </h3>
                        <p class="text-lg text-gray-300 mt-6">
                            Vá além do rastreamento básico. O painel de relatórios do bills visualiza seu fluxo de caixa, patrimônio líquido e tendências de gastos ao longo do tempo. Gere relatórios bonitos e fáceis de ler para impostos ou planejamento financeiro.
                        </p>
                         <ul class="mt-8 space-y-4">
                            <li class="flex items-center text-lg">
                                <svg class="w-6 h-6 text-pink-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Resumos mensais e anuais
                            </li>
                            <li class="flex items-center text-lg">
                                <svg class="w-6 h-6 text-pink-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Calculadora de patrimônio líquido
                            </li>
                            <li class="flex items-center text-lg">
                                <svg class="w-6 h-6 text-pink-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Dados exportáveis (PDF, CSV)
                            </li>
                        </ul>
                    </div>

                    <!-- Animated Visual: Report -->
                    <div class="reveal-on-scroll flex justify-center items-center min-h-[300px]">
                        <div class="report-visual">
                            <h4 class="text-white font-semibold text-lg mb-4">Gastos Trimestrais</h4>

                                <!-- Pie Chart -->
                            <svg class="report-pie-chart w-32 h-32 mx-auto" viewBox="0 0 40 40">
                                <!-- Desenhado primeiro (fundo) -->
                                <circle class="pie-slice pie-3" cx="20" cy="20" r="15"></circle>
                                <!-- Desenhado em segundo -->
                                <circle class="pie-slice pie-2" cx="20" cy="20" r="15"></circle>
                                <!-- Desenhado por último (topo) -->
                                <circle class="pie-slice pie-1" cx="20" cy="20" r="15"></circle>
                            </svg>

                            <!-- Legend -->
                            <div class="mt-4 w-full max-w-xs mx-auto">
                                <div class="flex items-center text-xs">
                                    <span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span>
                                    <span class="text-gray-300">Supermercado (60%)</span>
                                </div>
                                <div class="flex items-center text-xs mt-1">
                                    <span class="w-3 h-3 rounded-full bg-teal-600 mr-2"></span>
                                    <span class="text-gray-300">Assinaturas (25%)</span>
                                </div>
                                <div class="flex items-center text-xs mt-1">
                                    <span class="w-3 h-3 rounded-full bg-amber-500 mr-2"></span>
                                    <span class="text-gray-300">Restaurantes (15%)</span>
                                </div>
                            </div>
                            <!-- Line Graph -->
                            <svg class="w-full h-24 mt-4" viewBox="0 0 100 30" preserveAspectRatio="none">
                                <path d="M 0 25 L 20 10 L 40 18 L 60 5 L 80 15 L 100 10" fill="none" stroke="#ec4899" stroke-width="2" class="report-line-graph" />
                                <path d="M 0 30 L 100 30" stroke="rgba(255,255,255,0.2)" stroke-width="1" />
                            </svg>
                        </div>
                    </div>

                </div>
            </div>
        </section>

            <!-- Security Section -->
        <section id="security" class="py-24 sm:py-32 overflow-hidden">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                    <!-- Animated Visual: Security Shield -->
                    <div class="reveal-on-scroll flex justify-center items-center min-h-[350px]">
                        <div class="security-visual">
                            <!-- Orbiting Device Icons -->
                            <div class="device-icon">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="device-icon">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="device-icon">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2-2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4l-3-3m0 0l-3 3m3-3v11"></path></svg>
                            </div>
                            <div class="device-icon">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H4a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>

                            <!-- Central Shield -->
                            <div class="security-shield">
                                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Text Content -->
                    <div class="reveal-on-scroll">
                        <span class="text-indigo-400 font-semibold uppercase">Confiança e Acesso</span>
                        <h3 class="text-4xl md:text-5xl font-bold text-white mt-4">
                            Sem dados confidenciais, apenas o que é preciso para sua gestão financeira.
                        </h3>
                        <p class="text-lg text-gray-300 mt-6">
                            A princípio	todos os dados financeiros são inseridos e gerenciados por você, sem compartilhamento com terceiros, e sem uso de informação sensíveis reais.
                        </p>
                        <ul class="mt-8 space-y-4">
                            <li class="flex items-center text-lg">
                                <svg class="w-6 h-6 text-indigo-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Wallets fictícias para gerenciamento de contas bancárias.
                            </li>
                            <li class="flex items-center text-lg">
                                <svg class="w-6 h-6 text-indigo-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Cartões fictícios associados às contas para multiplos gerenciamentos de gastos.
                            </li>
                            <li class="flex items-center text-lg">
                                <svg class="w-6 h-6 text-indigo-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Metas financeiras fictícias para alcançar objetivos.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Open Finance Section -->
        <section id="open-finance" class="py-24 sm:py-32 overflow-hidden">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                    <!-- Text Content -->
                    <div class="reveal-on-scroll">
                        <span class="text-emerald-400 font-semibold uppercase">Integração Real</span>
                        <h3 class="text-4xl md:text-5xl font-bold text-white mt-4">
                            Conecte-se com Seus Bancos Reais via Open Finance
                        </h3>
                        <p class="text-lg text-gray-300 mt-6">
                            No plano Pro, você pode conectar suas contas bancárias reais através do Open Finance. Em vez de carteiras e cartões fictícios, gerencie suas wallets e cartões de bancos reais em um só lugar, e tudo isso de forma automática.
                        </p>
                        <ul class="mt-8 space-y-4">
                            <li class="flex items-center text-lg">
                                <svg class="w-6 h-6 text-emerald-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Conecte contas de múltiplos bancos com segurança
                            </li>
                            <li class="flex items-center text-lg">
                                <svg class="w-6 h-6 text-emerald-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Visualize seus cartões e saldos reais em tempo real
                            </li>
                            <li class="flex items-center text-lg">
                                <svg class="w-6 h-6 text-emerald-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Sincronização automática de transações bancárias
                            </li>
                            <li class="flex items-center text-lg">
                                <svg class="w-6 h-6 text-emerald-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Padrão Open Finance com criptografia de ponta
                            </li>
                        </ul>
                    </div>

                    <!-- Animated Visual: Bank Connections -->
                    <div class="reveal-on-scroll flex justify-center items-center min-h-[400px]">
                        <div class="open-finance-visual">
                            <!-- Central bills logo -->
                            <div class="open-finance-center">
                                <div class="open-finance-logo">
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            </div>

                            <!-- Bank Icons orbiting -->
                            <div class="bank-icon bank-icon-1">
                                <div class="bank-icon-inner">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H4a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>
                            </div>
                            <div class="bank-icon bank-icon-2">
                                <div class="bank-icon-inner">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H4a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>
                            </div>
                            <div class="bank-icon bank-icon-3">
                                <div class="bank-icon-inner">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H4a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>
                            </div>
                            <div class="bank-icon bank-icon-4">
                                <div class="bank-icon-inner">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H4a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <!-- <section id="pricing" class="py-24 sm:py-32">
            <div class="container mx-auto px-6">
                <div class="text-center max-w-3xl mx-auto">
                    <span class="text-indigo-400 font-semibold uppercase">Preços Simples</span>
                    <h2 class="text-4xl md:text-5xl font-bold text-white mt-4">
                        Escolha o plano ideal para você.
                    </h2>
                    <p class="text-lg text-gray-300 mt-6">
                        Comece gratuitamente e atualize quando estiver pronto. Sem taxas ocultas, sem necessidade de cartão de crédito.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 max-w-4xl mx-auto mt-16 gap-8">

                    <div class="reveal-on-scroll p-8 bg-white/5 border border-white/10 rounded-xl shadow-2xl flex flex-col">
                        <h3 class="text-2xl font-semibold text-white">Grátis</h3>
                        <p class="text-gray-300 mt-2">Para quem está começando.</p>
                        <div class="mt-6">
                            <span class="text-5xl font-extrabold text-white">R$0</span>
                            <span class="text-gray-400 text-lg ml-1">/ mês</span>
                        </div>
                        <ul class="mt-8 space-y-4 text-gray-300 flex-grow">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-emerald-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                1 Conta Conectada
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-emerald-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Rastreamento Básico de Despesas
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-emerald-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                1 Meta de Economia
                            </li>
                        </ul>
                        <a href="#" class="mt-10 block w-full text-center px-6 py-3 bg-white/10 text-white font-semibold rounded-lg hover:bg-white/20 transition duration-300 hover-target">
                            Comece de Graça
                        </a>
                    </div>

                    <div class="reveal-on-scroll p-8 bg-indigo-600 rounded-xl shadow-2xl flex flex-col relative overflow-hidden">
                        <span class="absolute top-0 right-0 m-4 px-3 py-1 text-xs text-indigo-700 bg-white rounded-full font-bold">Mais Popular</span>
                        <h3 class="text-2xl font-semibold text-white">Pro</h3>
                        <p class="text-indigo-200 mt-2">Para usuários avançados.</p>
                        <div class="mt-6">
                            <span class="text-5xl font-extrabold text-white">R$20</span>
                            <span class="text-indigo-200 text-lg ml-1">/ mês</span>
                        </div>
                        <ul class="mt-8 space-y-4 text-indigo-100 flex-grow">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Contas Conectadas Ilimitadas
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Categorização Automática com IA
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Metas de Economia Ilimitadas
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Carteiras e Relatórios Personalizados
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Saia do fictício e comece a gerenciar suas finanças reais.
                            </li>
                        </ul>
                        <a href="#" class="mt-10 block w-full text-center px-6 py-3 bg-white text-indigo-700 font-semibold rounded-lg hover:bg-gray-100 transition duration-300 hover-target">
                            Teste Grátis por 14 Dias
                        </a>
                    </div>
                </div>
            </div>
        </section> -->

        <!-- Final CTA Section -->
        <section class="py-24 sm:py-32">
            <div class="container mx-auto px-6 text-center">
                <div class="max-w-3xl mx-auto">
                    <h2 class="text-4xl md:text-5xl font-bold text-white">
                        Pronto para ter clareza financeira?
                    </h2>
                    <p class="text-lg text-gray-300 mt-6 mb-10">
                        Junte-se a milhares de usuários que estão assumindo o controle de seu futuro financeiro.
                        Inscreva-se gratuitamente em menos de um minuto.
                        </p>
                    <a href="#" class="cta-button inline-block px-12 py-5 bg-indigo-600 text-white text-xl font-semibold rounded-lg shadow-lg hover:bg-indigo-700 transition duration-300 transform hover:scale-105 hover-target">
                        Comece Seu Teste Grátis
                    </a>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="py-16 bg-white/5 border-t border-white/10">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-3 md:grid-cols-3 gap-8">
                <div class="md:col-span-1">
                        <!-- Footer Logo -->
                    <a href="#" class="flex items-center space-x-2">
                        <BillsLogo :width="24" :height="24" gradient-id="logo-footer" class="welcome-logo-icon" />
                        <span class="text-2xl font-bold text-white">bills</span>
                    </a>
                    <p class="text-gray-400 mt-4">Controle total da sua vida financeira.</p>
                </div>
                <div class="md:col-start-3">
                    <h4 class="font-semibold text-white uppercase">Produto</h4>
                    <ul class="mt-4 space-y-2">
                        <li><a href="#features" class="text-gray-400 hover:text-white">Recursos</a></li>
                        <li><a href="#pricing" class="text-gray-400 hover:text-white">Preços</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Entrar</a></li>
                    </ul>
                </div>
            </div>
                <div class="mt-16 pt-8 border-t border-white/10 text-center text-gray-500">
                &copy; 2025 bills Inc. Todos os direitos reservados.
            </div>
        </div>
    </footer>
    </div>
</template>

<style scoped>
/* Custom styles for animations, fonts, and cursor light */
.welcome-page {
    font-family: 'Inter', sans-serif;
    background-color: #0a041c;
    color: #e0e0e0;
    overflow-x: hidden;
    min-height: 100vh;
    cursor: none;
}

/* Hide cursor on all interactive elements */
.welcome-page * {
    cursor: none !important;
}

/* Cursor Follower Light */
.cursor-light {
    position: fixed;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.4) 0%, rgba(99, 102, 241, 0) 70%);
    pointer-events: none;
    transform: translate(-50%, -50%);
    transition: width 0.3s ease, height 0.3s ease, background 0.3s ease;
    z-index: 9999;
    display: none; /* Hidden by default, shown with JS */
}

@media (min-width: 1024px) {
    .cursor-light {
        display: block;
    }
}

/* Hover effect for cursor light */
.cursor-light-hover {
    width: 80px;
    height: 80px;
    background: radial-gradient(circle, rgba(139, 92, 246, 0.3) 0%, rgba(139, 92, 246, 0) 70%);
}

/* Logo icon styles to prevent clipping */
.welcome-logo-link {
    overflow: visible;
}

.welcome-logo-icon {
    display: inline-block;
    overflow: visible;
    flex-shrink: 0;
}

.welcome-logo-icon :deep(svg) {
    display: block;
    overflow: visible;
    /* Garante que o stroke não seja cortado nas bordas */
    shape-rendering: geometricPrecision;
}

/* Hero Orb Animation */
.hero-orb {
    width: 400px;
    height: 400px;
    position: relative;
    transform-style: preserve-3d;
    animation: rotate-orb 20s infinite linear;
}
.hero-orb-layer {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border: 2px solid;
    background-position: center;
    background-size: cover;
    animation: morph-shape 8s infinite alternate;
}
.hero-orb-layer:nth-child(1) {
    border-color: rgba(99, 102, 241, 0.5);
    animation-delay: -4s;
    transform: translateZ(-50px) scale(0.8);
}
.hero-orb-layer:nth-child(2) {
    border-color: rgba(168, 85, 247, 0.5);
    animation-delay: -2s;
    transform: translateZ(0px) scale(0.9);
}
.hero-orb-layer:nth-child(3) {
    border-color: rgba(236, 72, 153, 0.5);
    transform: translateZ(50px) scale(1);
}

@keyframes rotate-orb {
    from { transform: rotateY(0deg) rotateX(10deg); }
    to { transform: rotateY(360deg) rotateX(10deg); }
}

@keyframes morph-shape {
    0% { border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%; }
    50% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
    100% { border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%; }
}

/* Scroll Reveal Animation */
.reveal-on-scroll {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.8s cubic-bezier(0.215, 0.610, 0.355, 1), transform 0.8s cubic-bezier(0.215, 0.610, 0.355, 1);
    transition-delay: 0.1s;
}
.reveal-on-scroll.is-visible {
    opacity: 1;
    transform: translateY(0);
}

/* Animated Dashboard Visual */
.animated-dashboard {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
}
.dashboard-bar {
    background-color: #4f46e5;
    animation: grow-bar 3s infinite ease-in-out;
    transform-origin: bottom;
    margin: 0 4px;
}
/* Different colors for bars */
.dashboard-bar:nth-child(1) { background-color: #22c55e; animation-delay: 0s; }
.dashboard-bar:nth-child(2) { background-color: #3b82f6; animation-delay: 0.4s; }
.dashboard-bar:nth-child(3) { background-color: #8b5cf6; animation-delay: 0.8s; }
.dashboard-bar:nth-child(4) { background-color: #ec4899; animation-delay: 0.2s; }
.dashboard-bar:nth-child(5) { background-color: #f59e0b; animation-delay: 0.6s; }
.dashboard-bar:nth-child(6) { background-color: #22c55e; animation-delay: 0.1s; }
.dashboard-bar:nth-child(7) { background-color: #3b82f6; animation-delay: 0.5s; }

@keyframes grow-bar {
    0%, 100% { transform: scaleY(0.2); }
    50% { transform: scaleY(1); }
}

/* Flowing Categories Visual */
.flowing-line-svg {
    animation: draw-line 5s infinite cubic-bezier(0.4, 0, 0.2, 1);
    stroke-dasharray: 1000;
    stroke-dashoffset: 1000;
}
@keyframes draw-line {
    0% { stroke-dashoffset: 1000; }
    50% { stroke-dashoffset: 0; }
    100% { stroke-dashoffset: -1000; }
}

/* HORIZONTAL Goal Tracker Visual */
.goal-tracker-horizontal {
    width: 100%;
    margin-bottom: 2rem;
}
.goal-tracker-horizontal svg {
    width: 100%;
    max-width: 400px;
    height: auto;
    display: block;
    margin: 0 auto;
}
.goal-track-bg {
    fill: rgba(255, 255, 255, 0.1);
}
.goal-track-fill-rect {
    fill: #f59e0b;
    width: 0;
    animation: fill-goal-bar 5s infinite ease-in-out;
}
.goal-marker-circle {
    fill: #f59e0b;
    stroke: #0a041c;
    stroke-width: 2px;
    animation: move-goal-marker-x 5s infinite ease-in-out;
}
.goal-text {
    font-size: 14px;
    fill: #e0e0e0;
}
.goal-emoji {
    font-size: 24px;
}

/* Keyframes for Horizontal Goal Tracker */
@keyframes fill-goal-bar {
    0% { width: 0px; }
    80%, 100% { width: 220px; }
}
@keyframes move-goal-marker-x {
    0% { transform: translateX(30px); }
    80%, 100% { transform: translateX(250px); }
}

/* Staggered animation delays */
.goal-tracker-2 .goal-track-fill-rect, .goal-tracker-2 .goal-marker-circle {
    animation-delay: 0.5s;
}
.goal-tracker-3 .goal-track-fill-rect, .goal-tracker-3 .goal-marker-circle {
    animation-delay: 1s;
}

/* Wallet Card Stack Visual */
.card-stack {
    perspective: 1000px;
    width: 300px;
    height: 250px;
    margin: 0 auto;
}
.wallet-card {
    position: absolute;
    width: 240px;
    height: 150px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--card-color-1), var(--card-color-2));
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    transform-origin: center;
    transition: transform 0.6s cubic-bezier(0.215, 0.610, 0.355, 1);
    font-size: 14px;
    color: white;
    padding: 20px;
    box-sizing: border-box;
}
.card-1 {
    --card-color-1: #3b82f6; --card-color-2: #60a5fa;
    z-index: 3;
    transform: translateY(0) rotate(-5deg);
    animation: shuffle-1 8s infinite cubic-bezier(0.4, 0, 0.2, 1);
}
.card-2 {
    --card-color-1: #10b981; --card-color-2: #34d399;
    z-index: 2;
    transform: translateY(20px) rotate(0deg);
    animation: shuffle-2 8s infinite cubic-bezier(0.4, 0, 0.2, 1);
}
.card-3 {
    --card-color-1: #8b5cf6; --card-color-2: #a78bfa;
    z-index: 1;
    transform: translateY(40px) rotate(5deg);
    animation: shuffle-3 8s infinite cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes shuffle-1 {
    0%, 100% { transform: translateY(0) rotate(-5deg); z-index: 3; }
    33% { transform: translateY(40px) rotate(5deg); z-index: 1; }
    66% { transform: translateY(20px) rotate(0deg); z-index: 2; }
}
@keyframes shuffle-2 {
    0%, 100% { transform: translateY(20px) rotate(0deg); z-index: 2; }
    33% { transform: translateY(0) rotate(-5deg); z-index: 3; }
    66% { transform: translateY(40px) rotate(5deg); z-index: 1; }
}
@keyframes shuffle-3 {
    0%, 100% { transform: translateY(40px) rotate(5deg); z-index: 1; }
    33% { transform: translateY(20px) rotate(0deg); z-index: 2; }
    66% { transform: translateY(0) rotate(-5deg); z-index: 3; }
}
.card-logo {
    font-weight: 700;
    font-size: 16px;
}
.card-chip {
    width: 30px;
    height: 24px;
    background: #f59e0b;
    border-radius: 4px;
}
.card-number {
    font-family: monospace;
    font-size: 16px;
    letter-spacing: 2px;
    position: absolute;
    bottom: 45px;
}
.card-wallet-name {
    font-size: 12px;
    position: absolute;
    bottom: 20px;
}

/* Report Visual */
.report-visual {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 24px;
    border-radius: 12px;
    backdrop-filter: blur(5px);
    width: 100%;
    max-width: 450px;
    margin: 0 auto;
}
.report-pie-chart {
    transform: rotate(-90deg);
}
.pie-slice {
    fill: none;
    stroke-width: 10;
    stroke-dasharray: 94.2;
    stroke-dashoffset: 94.2;
    animation: draw-pie 4s infinite ease-in-out;
}

/* Animation: Draw pie from 0-50%, Erase from 50-100% */
@keyframes draw-pie {
    0% { stroke-dashoffset: 94.2; }
    50% { stroke-dashoffset: var(--dash-offset, 0); }
    60%, 100% { stroke-dashoffset: 94.2; }
}

.pie-1 { stroke: #3b82f6; --dash-offset: 37.7; animation-delay: 0s; }
.pie-2 { stroke: #10b981; --dash-offset: 14.2; animation-delay: 0.1s; }
.pie-3 { stroke: #f59e0b; --dash-offset: 0; animation-delay: 0.2s; }

.report-line-graph {
    stroke-dasharray: 200;
    stroke-dashoffset: 200;
    animation: draw-line-graph 4s infinite ease-in-out;
}
@keyframes draw-line-graph {
    0% { stroke-dashoffset: 200; }
    50% { stroke-dashoffset: 0; }
    100% { stroke-dashoffset: 200; }
}

/* Security Section Visual */
.security-visual {
    position: relative;
    width: 300px;
    height: 300px;
    margin: 0 auto;
}
.security-shield {
    width: 200px;
    height: 200px;
    background: #3b82f6;
    clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    display: flex;
    align-items: center;
    justify-content: center;
}
.security-shield svg {
    animation: pulse-lock 2s infinite ease-in-out;
}
@keyframes pulse-lock {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}
.device-icon {
    position: absolute;
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: orbit 10s infinite linear;
}
.device-icon:nth-child(1) { top: 0; left: 50%; transform: translateX(-50%); animation-delay: 0s; }
.device-icon:nth-child(2) { top: 50%; left: 0; transform: translateY(-50%); animation-delay: -5s; }
.device-icon:nth-child(3) { bottom: 0; left: 50%; transform: translateX(-50%); animation-delay: -2.5s; }
.device-icon:nth-child(4) { top: 50%; right: 0; transform: translateY(-50%); animation-delay: -7.5s; }

@keyframes orbit {
    from { transform: rotate(0deg) translateX(150px) rotate(0deg); }
    to { transform: rotate(360deg) translateX(150px) rotate(-360deg); }
}

/* Open Finance Section Visual */
.open-finance-visual {
    position: relative;
    width: 400px;
    height: 400px;
    margin: 0 auto;
}
.open-finance-center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 10;
}
.open-finance-logo {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #10b981, #059669);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
    animation: pulse-finance 2s infinite ease-in-out;
}
@keyframes pulse-finance {
    0%, 100% { transform: scale(1); box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3); }
    50% { transform: scale(1.1); box-shadow: 0 15px 40px rgba(16, 185, 129, 0.5); }
}
.bank-icon {
    position: absolute;
    width: 70px;
    height: 70px;
    background: rgba(16, 185, 129, 0.1);
    border: 2px solid rgba(16, 185, 129, 0.3);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: orbit-bank 12s infinite linear;
}
.bank-icon-inner {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(16, 185, 129, 0.2);
    border-radius: 8px;
}
.bank-icon-1 {
    top: 50%;
    left: 50%;
    animation-delay: 0s;
}
.bank-icon-2 {
    top: 50%;
    left: 50%;
    animation-delay: -3s;
}
.bank-icon-3 {
    top: 50%;
    left: 50%;
    animation-delay: -6s;
}
.bank-icon-4 {
    top: 50%;
    left: 50%;
    animation-delay: -9s;
}
@keyframes orbit-bank {
    from { transform: translate(-50%, -50%) rotate(0deg) translateX(160px) rotate(0deg); }
    to { transform: translate(-50%, -50%) rotate(360deg) translateX(160px) rotate(-360deg); }
}
.open-finance-connections {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}
.connection-line {
    stroke-dasharray: 200;
    stroke-dashoffset: 200;
    animation: draw-connection 3s infinite ease-in-out;
}
@keyframes draw-connection {
    0% { stroke-dashoffset: 200; opacity: 0; }
    20% { opacity: 1; }
    50% { stroke-dashoffset: 0; opacity: 1; }
    80% { opacity: 1; }
    100% { stroke-dashoffset: -200; opacity: 0; }
}

/* Button Glow Effect */
.cta-button {
    position: relative;
    overflow: hidden;
    z-index: 1;
}
.cta-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s ease;
    z-index: -1;
}
.cta-button:hover::before {
    left: 100%;
}
</style>

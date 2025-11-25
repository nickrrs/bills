<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import BillsLogo from '@/components/assets/BillsLogo.vue';

const letters = ['b', 'i', 'l', 'l', 's'];
const animationStarted = ref(false);

onMounted(() => {
    // Pequeno delay para garantir que o componente está renderizado
    setTimeout(() => {
        animationStarted.value = true;
    }, 300);
});
</script>

<template>
    <div class="relative flex min-h-svh flex-col items-center justify-center gap-6 bg-gradient-to-br from-indigo-950 via-black to-black p-6 md:p-10">
        <div class="w-full max-w-sm">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4">
                    <Link :href="route('home')" class="flex flex-col items-center gap-2 font-medium">
                        <div class="mb-1 flex items-center justify-center rounded-md gap-3 relative logo-container">
                            <BillsLogo
                                :width="65"
                                :height="65"
                                gradient-id="logo-header"
                                class="logo-icon"
                                :class="{ 'logo-icon-moved': animationStarted }"
                            />
                            <span
                                class="text-5xl font-bold text-white logo-text-container"
                                :class="{ 'logo-text-container-visible': animationStarted }"
                            >
                                <span
                                    v-for="(letter, index) in letters"
                                    :key="index"
                                    class="logo-letter"
                                    :class="{ 'logo-letter-visible': animationStarted }"
                                    :style="{ '--letter-index': index }"
                                >
                                    {{ letter }}
                                </span>
                            </span>
                            <!-- <img src="/images/bills-logo.svg" alt="bills-logo"> -->
                        </div>
                    </Link>
                    <div class="inline-flex text-center gap-1">
                        <h1 class="text-xl font-medium text-white">the efficient way to track your</h1>
                        <h1 class="text-xl font-medium text-[#865df5]">money</h1>
                    </div>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>

<style scoped>
.background-blur {
  position: absolute;
  inset: 0;
  z-index: 0;
  overflow: hidden;
}
.background-blur::before,
.background-blur::after {
  content: '';
  position: absolute;
  border-radius: 50%;
  filter: blur(120px);
  opacity: 0.7;
}
.background-blur::before {
  width: 600px;
  height: 600px;
  left: -200px;
  top: -200px;
  background: #1e1b4b;
}
.background-blur::after {
  width: 400px;
  height: 400px;
  right: -200px;
  bottom: -200px;
  background: #060514;
}

.logo-container {
  width: auto;
  min-width: 250px; /* Espaço suficiente para logo + gap + texto */
  overflow: visible; /* Garante que o ícone não seja cortado */
}

.logo-icon {
  position: relative;
  z-index: 10;
  flex-shrink: 0; /* Previne que o ícone seja comprimido */
  transition: transform 1.2s cubic-bezier(0.34, 1.56, 0.64, 1);
  transition-delay: 0.3s;
  transform: translateX(0);
  overflow: visible; /* Garante que o SVG não seja cortado */
}

.logo-icon :deep(svg) {
  display: block;
  overflow: visible;
  /* Garante que o stroke não seja cortado nas bordas */
  shape-rendering: geometricPrecision;
}

.logo-icon-moved {
  /* Move para a esquerda: metade da largura da logo (32.5px) */
  transform: translateX(-32.5px);
}

.logo-text-container {
  display: inline-flex;
  position: absolute;
  /* Posição final: metade da largura da logo (32.5px) + gap (12px) = 44.5px do centro */
  left: calc(50% + 10.5px);
  top: 50%;
  transform: translate(-100px, -50%);
  overflow: visible;
  transition: transform 1s cubic-bezier(0.34, 1.56, 0.64, 1);
  transition-delay: 0.3s;
  z-index: 5;
}

.logo-text-container.logo-text-container-visible {
  transform: translate(0, -50%);
}

.logo-letter {
  display: inline-block;
  opacity: 0;
  transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  transition-delay: calc(0.4s + var(--letter-index) * 0.1s);
  will-change: opacity;
}

.logo-letter-visible {
  opacity: 1;
}
</style>

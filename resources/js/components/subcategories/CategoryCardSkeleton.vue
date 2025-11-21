<template>
    <div class="w-full h-full bg-[#181818] border border-[#2F2F2F] rounded-[20px] transition-all duration-300">
        <!-- Card Header Content -->
        <div class="flex flex-row items-center justify-between space-x-3 bg-gradient-to-b from-[#FFFFFF08] rounded-t-[20px] to-transparent p-[20px] border-b border-[#2F2F2F]">
            <div class="flex flex-row items-center space-x-3">
                <!-- Icone da categoria -->
                <Skeleton class="h-10 w-10 rounded-md bg-white/10" />
                <div class="flex flex-col gap-2">
                    <Skeleton class="h-4 w-24 bg-white/10" />
                    <Skeleton class="h-3 w-32 bg-white/10" />
                </div>
            </div>
            <Skeleton class="h-5 w-16 bg-white/10" />
        </div>
        <!-- Campo de busca skeleton -->
        <div class="border-b border-[#2F2F2F] p-3">
            <Skeleton class="h-9 w-full rounded-md bg-white/10" />
        </div>
        <!-- Lista de subcategorias skeleton -->
        <div class="flex flex-col p-[8px] gap-1">
            <!-- Subcategoria skeleton -->
            <div
                v-for="n in 3"
                :key="`subcategory-skeleton-${n}`"
                class="relative flex flex-row items-center space-x-3 p-[12px] rounded-[10px]"
            >
                <Skeleton class="h-8 w-8 rounded-full bg-white/10 flex-shrink-0" />
                <div class="flex-1">
                    <div class="flex flex-row items-center justify-between">
                        <Skeleton class="h-4 w-32 bg-white/10" />
                        <Skeleton class="h-3 w-16 bg-white/10" />
                    </div>
                </div>
                <!-- Barra de Distribuição -->
                <div class="absolute bottom-0 left-0 right-[12px] h-[2px] rounded-[2px] overflow-hidden bg-[#ffffff21]">
                    <Skeleton class="h-full w-[70%] rounded-[2px] bg-white/10" />
                </div>
            </div>
            <!-- Botão adicionar -->
            <Skeleton class="h-10 mx-3 mb-3 mt-2 rounded-xl bg-white/5" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { Skeleton } from '@/components/ui/skeleton';
</script>


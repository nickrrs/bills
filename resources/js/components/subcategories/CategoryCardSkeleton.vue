<template>
    <div class="w-full h-full light:bg-[#f5f5f5e7] light:border-white/10 dark:bg-[#181818] dark:border-[#2F2F2F] border rounded-[20px] transition-all duration-300">
        <!-- Card Header Content -->
        <div class="flex flex-row items-center justify-between space-x-3 bg-gradient-to-b from-muted/30 rounded-t-[20px] to-transparent p-[20px] border-b border-border">
            <div class="flex flex-row items-center space-x-3">
                <!-- Icone da categoria -->
                <Skeleton class="h-10 w-10 rounded-md dark:bg-white/10 light:bg-[#cececee7]" />
                <div class="flex flex-col gap-2">
                    <Skeleton class="h-4 w-24 dark:bg-white/10 light:bg-[#cececee7]" />
                    <Skeleton class="h-3 w-32 dark:bg-white/10 light:bg-[#cececee7]" />
                </div>
            </div>
            <Skeleton class="h-5 w-16 dark:bg-white/10 light:bg-[#cececee7]" />
        </div>
        <!-- Campo de busca skeleton -->
        <div class="border-b border-border p-3">
            <Skeleton class="h-9 w-full rounded-md dark:bg-white/10 light:bg-[#cececee7]" />
        </div>
        <!-- Lista de subcategorias skeleton -->
        <div class="flex flex-col p-[8px] gap-1">
            <!-- Subcategoria skeleton -->
            <div
                v-for="n in 3"
                :key="`subcategory-skeleton-${n}`"
                class="relative flex flex-row items-center space-x-3 p-[12px] rounded-[10px]"
            >
                <Skeleton class="h-8 w-8 rounded-full dark:bg-white/10 light:bg-[#cececee7] flex-shrink-0" />
                <div class="flex-1">
                    <div class="flex flex-row items-center justify-between">
                        <Skeleton class="h-4 w-32 dark:bg-white/10 light:bg-[#cececee7]" />
                        <Skeleton class="h-3 w-16 dark:bg-white/10 light:bg-[#cececee7]" />
                    </div>
                </div>
                <!-- Barra de Distribuição -->
                <div class="absolute bottom-0 left-0 right-[12px] h-[2px] rounded-[2px] overflow-hidden bg-muted/30">
                    <Skeleton class="h-full w-[70%] rounded-[2px] dark:bg-white/10 light:bg-[#cececee7]" />
                </div>
            </div>
            <!-- Botão adicionar -->
            <Skeleton class="h-10 mx-3 mb-3 mt-2 rounded-xl dark:bg-white/10 light:bg-[#cececee7]" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { Skeleton } from '@/components/ui/skeleton';
</script>


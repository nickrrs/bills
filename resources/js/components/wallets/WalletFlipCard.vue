<template>
    <div class="w-full h-[260px] bg-transparent cursor-pointer flip-card-container" :class="{ 'flip-card-container--flipped': flipped }" @click="handleToggle">
        <div class="relative w-full h-full text-left rounded-3xl shadow-[0_10px_30px_-10px_rgba(0,0,0,0.5)] flip-card-inner">
            <div
                class="absolute w-full h-full rounded-3xl p-6 flex flex-col justify-between border overflow-hidden flip-card-face flip-card-face--front bg-card text-foreground border-border"
                :class="frontClass"
                :style="frontStyle"
            >
                <slot name="front" />
            </div>
            <div
                class="absolute w-full h-full rounded-3xl p-6 flex flex-col justify-between border overflow-hidden flip-card-face flip-card-face--back bg-card text-foreground border-border"
                :class="backClass"
                :style="backStyle"
            >
                <slot name="back" />
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
defineProps<{
    flipped: boolean;
    frontClass?: string;
    backClass?: string;
    frontStyle?: Record<string, string>;
    backStyle?: Record<string, string>;
}>();

const emit = defineEmits<{
    toggle: [];
}>();

const handleToggle = () => {
    emit('toggle');
};
</script>

<style scoped>
.flip-card-container {
    perspective: 1200px;
}

.flip-card-inner {
    transition: transform 0.6s;
    transform-style: preserve-3d;
}

.flip-card-container--flipped .flip-card-inner {
    transform: rotateY(180deg);
}

.flip-card-face {
    backface-visibility: hidden;
    transform-style: preserve-3d;
}

.flip-card-face--front {
    transform: rotateY(0deg);
}

.flip-card-face--back {
    transform: rotateY(180deg);
}
</style>


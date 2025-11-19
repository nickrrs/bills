<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    width?: number | string;
    height?: number | string;
    className?: HTMLAttributes['class'];
    gradientId?: string;
}

const props = withDefaults(defineProps<Props>(), {
    width: 30,
    height: 30,
    gradientId: 'bills-logo-grad',
});

const grad1Id = `${props.gradientId}-1`;
const grad2Id = `${props.gradientId}-2`;
</script>

<template>
    <svg
        :width="width"
        :height="height"
        viewBox="0 0 40 40"
        xmlns="http://www.w3.org/2000/svg"
        :class="className"
        v-bind="$attrs"
    >
        <defs>
            <linearGradient :id="grad1Id" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#EC4899;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#D946EF;stop-opacity:1" />
            </linearGradient>
            <linearGradient :id="grad2Id" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#8B5CF6;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#6366F1;stop-opacity:1" />
            </linearGradient>
        </defs>
        <!-- Outer Shape -->
        <path
            d="M20 0C31.046 0 40 8.954 40 20C40 31.046 31.046 40 20 40C8.954 40 0 31.046 0 20C0 8.954 8.954 0 20 0Z"
            fill="none"
            :stroke="`url(#${grad1Id})`"
            stroke-width="2"
        />
        <!-- Inner Shape -->
        <path
            d="M20.000,5.000 C30.379,5.000 35.000,10.000 35.000,20.000 C35.000,30.000 30.379,35.000 20.000,35.000 C9.621,35.000 5.000,30.000 5.000,20.000 C5.000,10.000 9.621,5.000 20.000,5.000 Z"
            transform="rotate(15 20 20)"
            fill="none"
            :stroke="`url(#${grad2Id})`"
            stroke-width="2.5"
        />
    </svg>
</template>


<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { ref } from 'vue';
import BillsLogo from '@/components/assets/BillsLogo.vue';
import BillsLogoAnimated from '@/components/assets/BillsLogoAnimated.vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
const isHovered = ref(false);
</script>

<template>
   <div
      class="app-logo-icon-wrapper"
      @mouseenter="isHovered = true"
      @mouseleave="isHovered = false"
   >
      <BillsLogo
         v-if="!isHovered"
         :width="30"
         :height="30"
         :class="['app-logo-icon', className]"
         v-bind="$attrs"
      />
      <BillsLogoAnimated
         v-else
         :width="30"
         :height="30"
         :class="['app-logo-icon', className]"
         v-bind="$attrs"
      />
   </div>
</template>

<style scoped>
.app-logo-icon-wrapper {
  display: inline-block;
  cursor: pointer;
}

.app-logo-icon {
  display: inline-block;
  overflow: visible;
  flex-shrink: 0;
}

.app-logo-icon :deep(svg) {
  display: block;
  overflow: visible;
  /* Garante que o stroke não seja cortado nas bordas */
  shape-rendering: geometricPrecision;
  transform-origin: center;
}

@keyframes rotate-clockwise {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

@keyframes rotate-counter-clockwise {
  from {
    transform: rotate(15deg);
  }
  to {
    transform: rotate(-345deg);
  }
}
</style>

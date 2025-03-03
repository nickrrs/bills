<template>
    <UiButton
        :disabled="disabled"
        :tabindex="tabindex"
        :class="`${buttonClass} w-full backdrop-blur-xl rounded-md border-2 bg-[#0B0B0E] hover:bg-[#0F0F15] font-semibold text-white ${disabled ? 'opacity-50 cursor-not-allowed' : ''}`"
        @click="handleClick"
    >
        <LoaderCircle v-if="loading" class="h-4 w-4 animate-spin" />
        {{ label }}
    </UiButton>
</template>

<script lang="ts">
import { Button as UiButton } from '@/components/ui/button';
import { LoaderCircle } from 'lucide-vue-next';
export default {
    components: {
        UiButton,
        LoaderCircle
    },
    props: {
        label: {
            type: String,
            required: true
        },
        disabled: {
            type: Boolean,
            default: false
        },
        tabindex: {
            type: Number,
            default: 0
        },
        buttonClass: {
            type: String,
            default: ''
        },
        onClick: {
            type: Function,
            default: function() {}
        },
        loading: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        handleClick() {
            this.onClick();
        }
    }
}
</script>

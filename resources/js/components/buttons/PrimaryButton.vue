<template>
    <UiButton
        :disabled="disabled"
        :tabindex="tabindex"
        :class="buttonClass"
        @click="handleClick"
    >
        {{ label }}
    </UiButton>
</template>

<script lang="ts">
import { Button as UiButton } from '@/components/ui/button';
export default {
    components: {
        UiButton
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
        }
    },
    methods: {
        handleClick() {
            this.onClick();
        }
    }
}
</script>

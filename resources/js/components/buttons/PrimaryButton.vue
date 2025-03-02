<template>
    <UiButton
        :disabled="disabled"
        :tabindex="tabindex"
        :class="buttonClass"
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

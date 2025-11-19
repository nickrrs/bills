<template>
<div class="flex flex-col gap-2">
    <UIInput
        id="email"
        :name="name"
        type="email"
        :tabindex="tabindex"
        autocomplete="email"
        :model-value="email"
        @update:model-value="updateEmail"
        :class="`${inputClass} ${error ? 'border-red-500' : 'border-[#444444]'} bg-[#1E1E1E]`"
        :placeholder="placeholder"
        :disabled="disabled"
        :required="required"
        :readonly="readonly"
    />
    <InputError :message="error" />
</div>
</template>
<script lang="ts">
import { Input as UIInput } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';
export default {
    name: 'EmailInput',
    components: {
        UIInput,
        InputError
    },
    props: {
        modelValue: {
            type: String,
            required: true
        },
        name: {
            type: String,
            required: true
        },
        inputClass: {
            type: String,
            default: ''
        },
        placeholder: {
            type: String,
            default: 'email@example.com'
        },
        disabled: {
            type: Boolean,
            default: false
        },
        readonly: {
            type: Boolean,
            default: false
        },
        autocomplete: {
            type: String,
            default: 'email'
        },
        autofocus: {
            type: Boolean,
            default: false
        },
        tabindex: {
            type: Number,
            default: 1
        },
        required: {
            type: Boolean,
            default: true
        },
        error: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            email: this.modelValue
        }
    },
    methods: {
        updateEmail(value: string | number) {
            this.$emit('update:modelValue', value);
        }
    }
}
</script>

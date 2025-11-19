<template>
    <div class="flex flex-col gap-2">
        <UIInput
            :id="id"
            type="password"
            autofocus
            :placeholder="placeholder"
            :autocomplete="autocomplete"
            :disabled="disabled"
            :required="required"
            :readonly="readonly"
            :tabindex="tabindex"
            :class="`${inputClass} ${error ? 'border-red-500' : 'border-[#444444]'} bg-[#1E1E1E]`"
            :model-value="password"
            @update:model-value="updatePassword"
        />
        <InputError :message="error" />
    </div>
</template>

<script lang="ts">
import { Input as UIInput } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';
export default {
    name: 'PasswordInput',
    components: {
        UIInput,
        InputError
    },
    props: {
        id: {
            type: String,
            required: true
        },
        placeholder: {
            type: String,
            required: false
        },
        autocomplete: {
            type: String,
            required: false
        },
        disabled: {
            type: Boolean,
            required: false
        },
        required: {
            type: Boolean,
            required: false
        },
        readonly: {
            type: Boolean,
            required: false
        },
        tabindex: {
            type: Number,
            required: false
        },
        inputClass: {
            type: String,
            required: false
        },
        error: {
            type: String,
            required: false
        },
        modelValue: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            password: this.modelValue
        }
    },
    methods: {
        updatePassword(value: string | number) {
            this.$emit('update:modelValue', value);
        }
    }
}
</script>

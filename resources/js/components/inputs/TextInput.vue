<template>
    <div class="flex flex-col gap-2">
        <UIInput
            :id="id"
            type="text"
            autofocus
            :placeholder="placeholder"
            :autocomplete="autocomplete"
            :disabled="disabled"
            :required="required"
            :readonly="readonly"
            :tabindex="tabindex"
            :class="`${inputClass} ${error ? 'border-red-500' : 'border-[#444444]'} bg-[#1E1E1E]`"
            :model-value="value"
            @update:model-value="updateValue"
        />
        <InputError :message="error" />
    </div>
</template>
<script lang="ts">
import { Input as UIInput } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';
export default {
    name: 'TextInput',
    components: {
        UIInput,
        InputError
    },
    props: {
        modelValue: {
            type: String,
            required: true
        },
        placeholder: {
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
        id: {
            type: String,
            required: false
        },
        autocomplete: {
            type: String,
            required: false
        }
    },
    data() {
        return {
            value: this.modelValue
        }
    },
    methods: {
        updateValue(value: string | number) {
            this.$emit('update:modelValue', value);
        }
    }
}
</script>

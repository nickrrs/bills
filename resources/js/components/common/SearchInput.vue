<template>
    <div class="relative" :class="wrapperClass">
        <SearchIcon class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 transform text-[#767676]" />
        <input
            :value="internalValue"
            :placeholder="placeholder"
            :disabled="disabled"
            :aria-label="ariaLabel || placeholder"
            class="w-full rounded-md border border-[#2F2F2F] bg-[#131316] py-2.5 pl-10 pr-10 text-sm text-white placeholder-[#767676] transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-[#3800D8]"
            :class="inputClass"
            @input="handleInput"
        />
        <button
            v-if="clearable && internalValue"
            class="absolute right-3 top-1/2 -translate-y-1/2 transform text-[#767676] transition-colors hover:text-white"
            type="button"
            @click="clearValue"
        >
            <X class="h-4 w-4" />
        </button>
    </div>
</template>

<script setup lang="ts">
import { onBeforeUnmount, ref, watch } from 'vue';
import { Search as SearchIcon, X } from 'lucide-vue-next';

interface Props {
    modelValue: string;
    placeholder?: string;
    debounce?: number;
    disabled?: boolean;
    clearable?: boolean;
    inputClass?: string;
    wrapperClass?: string;
    ariaLabel?: string;
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: '',
    placeholder: 'buscar...',
    debounce: 0,
    disabled: false,
    clearable: true,
    inputClass: '',
    wrapperClass: 'w-full',
    ariaLabel: undefined,
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
    (e: 'search', value: string): void;
    (e: 'clear'): void;
}>();

const internalValue = ref(props.modelValue);
let timeout: ReturnType<typeof setTimeout> | null = null;

watch(
    () => props.modelValue,
    (value) => {
        if (value !== internalValue.value) {
            internalValue.value = value;
        }
    }
);

const fireSearch = (value: string) => {
    if (props.debounce && props.debounce > 0) {
        if (timeout) {
            clearTimeout(timeout);
        }
        timeout = setTimeout(() => {
            emit('search', value);
        }, props.debounce);
        return;
    }

    emit('search', value);
};

const handleInput = (event: Event) => {
    const { value } = event.target as HTMLInputElement;
    internalValue.value = value;
    emit('update:modelValue', value);
    fireSearch(value);
};

const clearValue = () => {
    internalValue.value = '';
    emit('update:modelValue', '');
    fireSearch('');
    emit('clear');
};

onBeforeUnmount(() => {
    if (timeout) {
        clearTimeout(timeout);
    }
});
</script>


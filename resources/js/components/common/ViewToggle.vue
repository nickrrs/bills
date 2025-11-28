<template>
    <div class="flex items-center gap-2 rounded-md border border-border bg-card p-1">
        <button
            @click="$emit('update:view', 'grid')"
            :class="[
                'flex items-center gap-2 rounded px-3 py-1.5 text-sm font-medium transition-colors',
                view === 'grid'
                    ? 'bg-accent-primary text-white shadow-sm'
                    : 'text-muted-foreground hover:text-foreground hover:bg-accent'
            ]"
            :title="gridLabel"
        >
            <Grid3X3 class="!h-4 !w-4" />
            <span class="hidden sm:inline">{{ gridLabel }}</span>
        </button>
        <button
            @click="$emit('update:view', 'table')"
            :class="[
                'flex items-center gap-2 rounded px-3 py-1.5 text-sm font-medium transition-colors',
                view === 'table'
                    ? 'bg-accent-primary text-white shadow-sm'
                    : 'text-muted-foreground hover:text-foreground hover:bg-accent'
            ]"
            :title="tableLabel"
        >
            <TableIcon class="!h-4 !w-4" />
            <span class="hidden sm:inline">{{ tableLabel }}</span>
        </button>
    </div>
</template>

<script lang="ts">
import { Grid3X3, Table as TableIcon } from 'lucide-vue-next';

export default {
    name: 'ViewToggle',
    components: {
        Grid3X3,
        TableIcon,
    },
    props: {
        view: {
            type: String as () => 'grid' | 'table',
            required: true,
        },
        gridLabel: {
            type: String,
            default: 'grade',
        },
        tableLabel: {
            type: String,
            default: 'tabela',
        },
    },
    emits: ['update:view'],
};
</script>

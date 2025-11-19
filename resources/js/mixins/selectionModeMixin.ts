import type { ComponentPublicInstance } from 'vue';

type SelectionModeContract = {
    getSelectionIds?: () => number[];
    onSelectionModeEnter?: () => void;
    onSelectionModeExit?: () => void;
};

type SelectionModeState = {
    isSelectionMode: boolean;
    selectedIds: number[];
    selectionHotkeyListener: ((event: KeyboardEvent) => void) | null;
};

type SelectionModeMethods = {
    toggleSelectionMode(): void;
    exitSelectionMode(): void;
    toggleSelectionItem(id: number): void;
    selectAllItems(): void;
    clearSelection(): void;
    handleSelectionHotkey(event: KeyboardEvent): void;
};

type SelectionModeInstance = ComponentPublicInstance &
    SelectionModeContract &
    SelectionModeState &
    SelectionModeMethods & {
        selectionIds: number[];
    };

export default {
    data(): SelectionModeState {
        return {
            isSelectionMode: false,
            selectedIds: [],
            selectionHotkeyListener: null,
        };
    },
    computed: {
        selectionIds(this: SelectionModeInstance): number[] {
            if (typeof this.getSelectionIds === 'function') {
                return this.getSelectionIds();
            }
            return [];
        },
    },
    watch: {
        selectionIds(this: SelectionModeInstance) {
            if (!this.isSelectionMode) return;
            const validIds = new Set(this.selectionIds);
            this.selectedIds = this.selectedIds.filter(id => validIds.has(id));
        },
    },
    mounted(this: SelectionModeInstance) {
        this.selectionHotkeyListener = (event: KeyboardEvent) => this.handleSelectionHotkey(event);
        window.addEventListener('keydown', this.selectionHotkeyListener);
    },
    beforeUnmount(this: SelectionModeInstance) {
        if (this.selectionHotkeyListener) {
            window.removeEventListener('keydown', this.selectionHotkeyListener);
            this.selectionHotkeyListener = null;
        }
    },
    methods: {
        toggleSelectionMode(this: SelectionModeInstance) {
            if (this.isSelectionMode) {
                return;
            }

            this.isSelectionMode = true;
            this.selectedIds = [];

            if (typeof this.onSelectionModeEnter === 'function') {
                this.onSelectionModeEnter();
            }
        },
        exitSelectionMode(this: SelectionModeInstance) {
            if (!this.isSelectionMode) {
                return;
            }

            this.isSelectionMode = false;
            this.selectedIds = [];

            if (typeof this.onSelectionModeExit === 'function') {
                this.onSelectionModeExit();
            }
        },
        toggleSelectionItem(this: SelectionModeInstance, id: number) {
            const index = this.selectedIds.indexOf(id);
            if (index > -1) {
                this.selectedIds.splice(index, 1);
            } else {
                this.selectedIds.push(id);
            }
        },
        selectAllItems(this: SelectionModeInstance) {
            this.selectedIds = [...this.selectionIds];
        },
        clearSelection(this: SelectionModeInstance) {
            this.selectedIds = [];
        },
        handleSelectionHotkey(this: SelectionModeInstance, event: KeyboardEvent) {
            if (!event.ctrlKey || event.code !== 'Space') {
                return;
            }

            const target = event.target as HTMLElement | null;
            if (target) {
                const tagName = target.tagName?.toLowerCase();
                if (['input', 'textarea', 'select'].includes(tagName) || target.isContentEditable) {
                    return;
                }
            }

            event.preventDefault();

            if (this.isSelectionMode) {
                this.exitSelectionMode();
            } else {
                this.toggleSelectionMode();
            }
        },
    },
};


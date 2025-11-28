<script setup lang="ts">
import { cn } from '@/lib/utils';
import { GripVertical, X } from 'lucide-vue-next';
import {
    DialogClose,
    DialogContent,
    DialogOverlay,
    DialogPortal,
    useForwardPropsEmits,
    type DialogContentEmits,
    type DialogContentProps,
} from 'radix-vue';
import { computed, nextTick, onMounted, onUnmounted, ref, watch, type HTMLAttributes } from 'vue';

const props = defineProps<DialogContentProps & { class?: HTMLAttributes['class'] }>();
const emits = defineEmits<DialogContentEmits>();

const delegatedProps = computed(() => {
    const { class: _, ...delegated } = props;
    return delegated;
});

const forwarded = useForwardPropsEmits(delegatedProps, emits);

const dialogWrapperRef = ref<any>(null);
const isDragging = ref(false);
const position = ref<{ x: number; y: number } | null>(null);
const dragOffset = ref({ x: 0, y: 0 });
const elementSize = ref({ width: 0, height: 0 });

// Get the actual DOM element from the component ref
const getDialogElement = async (): Promise<HTMLElement | null> => {
    await nextTick();

    if (!dialogWrapperRef.value) {
        return document.querySelector('[data-radix-dialog-content]') as HTMLElement;
    }

    const ref = dialogWrapperRef.value;

    if (ref instanceof HTMLElement) {
        return ref;
    }

    if (ref && typeof ref === 'object') {
        if (ref.$el && ref.$el instanceof HTMLElement) {
            return ref.$el;
        }
        if (ref.el && ref.el instanceof HTMLElement) {
            return ref.el;
        }
    }

    return document.querySelector('[data-radix-dialog-content]') as HTMLElement;
};

// Reset position when dialog closes
const resetPosition = () => {
    position.value = null;
};

// Calculate position with viewport boundaries
const calculateBoundedPosition = (mouseX: number, mouseY: number): { x: number; y: number } => {
    const viewportWidth = window.innerWidth;
    const viewportHeight = window.innerHeight;

    // Get element dimensions (use cached or default)
    const width = elementSize.value.width || 512; // max-w-lg is typically 512px
    const height = elementSize.value.height || 400; // approximate height

    // Calculate desired position
    let x = mouseX - dragOffset.value.x;
    let y = mouseY - dragOffset.value.y;

    // Constrain to viewport boundaries
    // Left boundary
    x = Math.max(0, x);
    // Right boundary
    x = Math.min(viewportWidth - width, x);
    // Top boundary
    y = Math.max(0, y);
    // Bottom boundary
    y = Math.min(viewportHeight - height, y);

    return { x, y };
};

const handleMouseDown = async (e: MouseEvent) => {
    const element = await getDialogElement();
    if (!element) return;

    isDragging.value = true;
    const rect = element.getBoundingClientRect();

    // Cache element size for boundary calculations
    elementSize.value = {
        width: rect.width,
        height: rect.height,
    };

    // Calculate offset: where in the element the mouse was clicked
    dragOffset.value = {
        x: e.clientX - rect.left,
        y: e.clientY - rect.top,
    };

    // Initialize position with boundaries applied
    const boundedPos = calculateBoundedPosition(e.clientX, e.clientY);
    position.value = boundedPos;

    // Use capture phase for immediate response
    document.addEventListener('mousemove', handleMouseMove, { passive: false, capture: true });
    document.addEventListener('mouseup', handleMouseUp, { capture: true });
    e.preventDefault();
    e.stopPropagation();
};

const handleMouseMove = (e: MouseEvent) => {
    if (!isDragging.value) return;

    // Calculate bounded position - instant update, no delay
    const boundedPos = calculateBoundedPosition(e.clientX, e.clientY);
    position.value = boundedPos;

    e.preventDefault();
};

const handleMouseUp = () => {
    isDragging.value = false;
    document.removeEventListener('mousemove', handleMouseMove, { capture: true });
    document.removeEventListener('mouseup', handleMouseUp, { capture: true });
};

// Watch for when dialog element appears/disappears to reset position
let observer: MutationObserver | null = null;

onMounted(() => {
    // Reset position when modal opens
    resetPosition();

    // Watch for when dialog closes by observing DOM changes
    observer = new MutationObserver(() => {
        const element = document.querySelector('[data-radix-dialog-content]');
        if (!element) {
            // Dialog was removed from DOM, reset position
            resetPosition();
        }
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true,
    });
});

onUnmounted(() => {
    if (observer) {
        observer.disconnect();
    }
    document.removeEventListener('mousemove', handleMouseMove, { capture: true });
    document.removeEventListener('mouseup', handleMouseUp, { capture: true });
    // Reset position when component is unmounted (dialog closes)
    resetPosition();
});
</script>

<template>
    <DialogPortal>
        <DialogOverlay
            class="fixed inset-0 z-50 bg-black/80 data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0"
        />
        <DialogContent
            v-bind="forwarded"
            :class="
                cn(
                    'fixed z-50 flex flex-col w-full max-w-lg border bg-card border-border p-0 shadow-lg data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 sm:rounded-lg',
                    isDragging ? 'cursor-grabbing' : 'cursor-default',
                    props.class,
                )
            "
            :style="{
                left: position ? `${position.x}px` : '50%',
                top: position ? `${position.y}px` : '50%',
                transform: position ? 'none' : 'translate(-50%, -50%)',
                transition: isDragging ? 'none' : undefined,
                willChange: isDragging ? 'transform' : undefined,
            }"
            ref="dialogWrapperRef"
        >
            <!-- Header Draggable - Fixed -->
            <div
                class="flex items-center justify-between px-6 py-4 border-b border-border cursor-grab active:cursor-grabbing flex-shrink-0 bg-popover"
                @mousedown="handleMouseDown"
            >
                <div class="flex items-center gap-2">
                    <GripVertical class="h-4 w-4 text-muted-foreground" />
                    <slot name="header" />
                </div>
                <DialogClose
                    class="rounded-sm opacity-70 ring-offset-background transition-opacity hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:pointer-events-none"
                >
                    <X class="h-4 w-4 text-muted-foreground hover:text-foreground" />
                    <span class="sr-only">Close</span>
                </DialogClose>
            </div>

            <!-- Content - Scrollable -->
            <div class="px-6 pb-6 pt-2 overflow-y-auto flex-1 min-h-0">
                <slot />
            </div>
        </DialogContent>
    </DialogPortal>
</template>


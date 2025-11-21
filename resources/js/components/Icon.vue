<script setup lang="ts">
import { cn } from '@/lib/utils';
import { computed } from 'vue';
// Importar apenas os ícones mais comuns usados no sistema
// Se precisar de mais ícones, adicione-os aqui
import {
    AlertCircle,
    AlertTriangle,
    Award,
    Baby,
    Bell,
    BookOpen,
    Briefcase,
    Building2,
    Bus,
    Calendar,
    Camera,
    Car,
    Check,
    ChevronDown,
    ChevronLeft,
    ChevronRight,
    ChevronUp,
    Clock as ClockIcon,
    Coffee,
    CreditCard,
    DollarSign,
    Dumbbell,
    Film,
    Folder,
    Gamepad2,
    Gift,
    GraduationCap,
    Heart,
    HelpCircle,
    Home,
    Info,
    LoaderCircle,
    Mail,
    MapPin,
    MoreHorizontal,
    Music,
    Palette,
    PawPrint,
    Pencil,
    Phone,
    PiggyBank,
    Plane,
    Plus,
    PlusCircle,
    Search,
    Send,
    Settings,
    Settings2,
    ShoppingBag,
    ShoppingCart,
    Smartphone,
    Star,
    Stethoscope,
    Target,
    Trash2,
    TreePine,
    TrendingDown,
    TrendingUp,
    Trophy,
    User,
    Users,
    UtensilsCrossed,
    Wallet,
    Wallet2,
    Wifi,
    X,
    Zap,
} from 'lucide-vue-next';

interface Props {
    name: string;
    class?: string;
    size?: number | string;
    color?: string;
    strokeWidth?: number | string;
}

const props = withDefaults(defineProps<Props>(), {
    class: '',
    size: 16,
    strokeWidth: 2,
});

const className = computed(() => cn('h-4 w-4', props.class));

// Objeto com os ícones disponíveis para acesso dinâmico
const availableIcons: Record<string, any> = {
    ShoppingBag,
    ShoppingCart,
    Coffee,
    UtensilsCrossed,
    Building2,
    Home,
    Wifi,
    Zap,
    Gift,
    Heart,
    Wallet,
    GraduationCap,
    Plane,
    Bus,
    Car,
    Music,
    Film,
    Stethoscope,
    PawPrint,
    Clock: ClockIcon,
    Gamepad2,
    Dumbbell,
    BookOpen,
    Camera,
    Palette,
    Trophy,
    Award,
    TreePine,
    Settings,
    Star,
    Trash2,
    Pencil,
    Plus,
    PlusCircle,
    Search,
    Target,
    LoaderCircle,
    TrendingUp,
    TrendingDown,
    Wallet2,
    MoreHorizontal,
    CreditCard,
    Settings2,
    DollarSign,
    PiggyBank,
    ChevronLeft,
    ChevronRight,
    HelpCircle,
    Folder,
    ChevronDown,
    ChevronUp,
    Send,
    Smartphone,
    Baby,
    Briefcase,
    Mail,
    Phone,
    MapPin,
    Calendar,
    User,
    Users,
    Bell,
    Check,
    X,
    AlertCircle,
    Info,
    AlertTriangle,
};

const icon = computed(() => {
    const iconName = props.name.charAt(0).toUpperCase() + props.name.slice(1);
    return availableIcons[iconName] || null;
});
</script>

<template>
    <component :is="icon" :class="className" :size="size" :stroke-width="strokeWidth" :color="color" />
</template>

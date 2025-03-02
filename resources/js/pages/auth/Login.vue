<script setup lang="ts">
import PrimaryButton from '@/components/buttons/PrimaryButton.vue';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const formEnabled = ref(false);

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const showForm = () => {
    formEnabled.value = !formEnabled.value;
};
</script>

<template>
    <div>
        <Transition name="slide-up-enter-active">
            <AuthBase title="Log in to your account" description="Enter your email and password below to log in">
                <Transition name="slide-up">
                    <form v-if="formEnabled" @submit.prevent="submit" class="flex flex-col gap-6">
                        <div class="grid gap-6">
                            <div class="grid gap-2">
                                <Label for="email">e-mail</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    required
                                    autofocus
                                    :tabindex="1"
                                    autocomplete="email"
                                    v-model="form.email"
                                    placeholder="email@example.com"
                                />
                                <InputError :message="form.errors.email" />
                            </div>

                            <div class="grid gap-2">
                                <div class="flex items-center justify-between">
                                    <Label for="password">password</Label>
                                    <TextLink v-if="canResetPassword" :href="route('password.request')" class="text-sm" :tabindex="5">
                                        forgot password?
                                    </TextLink>
                                </div>
                                <Input
                                    id="password"
                                    type="password"
                                    required
                                    :tabindex="2"
                                    autocomplete="current-password"
                                    v-model="form.password"
                                    placeholder="password"
                                />
                                <InputError :message="form.errors.password" />
                            </div>

                            <div class="flex items-center justify-between" :tabindex="3">
                                <Label for="remember" class="flex items-center space-x-3">
                                    <Checkbox id="remember" v-model:checked="form.remember" :tabindex="4" />
                                    <span>remember me</span>
                                </Label>
                            </div>

                            <PrimaryButton type="submit" label="sign in" class="mt-4 w-full h-[42px] border-2 font-semibold border-zinc-800 text-white backdrop-blur-xl bg-[#0B0B0E] hover:bg-[#0F0F15]" :tabindex="4" :disabled="form.processing">
                                <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                            </PrimaryButton>
                        </div>

                        <div class="text-center text-sm text-muted-foreground">
                            don't have an account?
                            <TextLink :href="route('register')" :tabindex="5">sign up</TextLink>
                        </div>
                    </form>
                </Transition>
                <div v-if="!formEnabled" class="flex justify-center items-center">
                    <div class="relative overflow-hidden rounded-md bg-[#0B0B0E] shadow border-2 border-zinc-800 group p-0.5 w-full">
                        <span class="absolute inset-[-1000%] opacity-0 group-hover:opacity-100 animate-[spin_5s_linear_infinite_reverse] dark:bg-[conic-gradient(from_90deg_at_50%_50%,#6366f1_0%,#0B0B0E_7%)] bg-[conic-gradient(from_90deg_at_50%_50%,#000_0%,#6366f1_5%)] transition-opacity"></span>
                        <PrimaryButton
                            label="sign in"
                            :tabindex="4"
                            :disabled="form.processing"
                            :class="'mt-0 w-full rounded-md font-semibold text-white backdrop-blur-xl bg-[#0B0B0E] hover:bg-[#0F0F15] transition-all'"
                            @click="showForm"
                        />
                    </div>
                </div>
            </AuthBase>
        </Transition>
    </div>
</template>

<style scoped>
@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.slide-up-enter-active,
.slide-up-leave-active {
  transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.slide-up-enter-from {
  opacity: 0;
  transform: translateY(30px);
}

.slide-up-leave-to {
  opacity: 0;
  transform: translateY(30px);
}
</style>

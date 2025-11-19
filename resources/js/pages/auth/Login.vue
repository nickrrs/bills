<template>
    <div>
        <Transition name="slide-up-enter-active">
            <AuthBase description="Enter your email and password below to log in">
                <InertiaHead title="login" />
                <Transition name="slide-up">
                    <form v-if="formEnabled || loginState" @submit.prevent="submit" class="flex flex-col gap-6">
                        <div class="grid gap-5">
                            <div class="grid gap-2">
                                <UILabel for="email">e-mail</UILabel>
                                <EmailInput :name="'email'" :tabindex="2" :model-value="form.email" @update:model-value="form.email = $event" :error="form.errors.email" />
                            </div>

                            <div class="grid gap-2">
                                <div class="flex items-center justify-between">
                                    <UILabel for="password">password</UILabel>
                                    <TextLink v-if="canResetPassword" :href="route('password.request')" class="text-sm hover:text-[#6366f1]" :tabindex="5">
                                        forgot password?
                                    </TextLink>
                                </div>
                                <PasswordInput :id="'password'" :name="'password'" :placeholder="'password'" :tabindex="3" :model-value="form.password" @update:model-value="form.password = $event" :error="form.errors.password" />
                                <InputError :message="form.errors.password" />
                            </div>

                            <div class="flex items-center justify-between" :tabindex="3">
                                <UILabel for="remember" class="flex items-center space-x-3">
                                    <Checkbox id="remember" v-model:checked="form.remember" :tabindex="4" />
                                    <span>remember me</span>
                                </UILabel>
                            </div>

                            <PrimaryButton type="submit" label="sign in" class="mt-4 h-[42px]" :tabindex="4" :disabled="form.processing">
                                <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                            </PrimaryButton>
                        </div>

                        <div class="text-center text-sm text-muted-foreground">
                            don't have an account?
                            <TextLink :href="route('register')" class="hover:text-[#6366f1]" :tabindex="5">sign up</TextLink>
                        </div>
                    </form>
                </Transition>
                <div v-if="!formEnabled && !loginState" class="flex justify-center items-center">
                    <div class="relative overflow-hidden rounded-md bg-[#0B0B0E] shadow border-2 border-zinc-800 group p-0.5 w-full">
                        <span class="absolute inset-[-1000%] opacity-0 group-hover:opacity-100 animate-[spin_5s_linear_infinite_reverse] dark:bg-[conic-gradient(from_90deg_at_50%_50%,#6366f1_0%,#0B0B0E_7%)] bg-[conic-gradient(from_90deg_at_50%_50%,#000_0%,#6366f1_5%)] transition-opacity"></span>
                        <PrimaryButton
                            label="sign in"
                            :tabindex="4"
                            :disabled="form.processing"
                            :class="'mt-0 transition-all !border-none'"
                            @click="showForm"
                        />
                    </div>
                </div>
            </AuthBase>
        </Transition>
    </div>
</template>
<script lang="ts">
import PrimaryButton from '@/components/buttons/PrimaryButton.vue';
import InputError from '@/components/InputError.vue';
import EmailInput from '@/components/inputs/EmailInput.vue';
import PasswordInput from '@/components/inputs/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Checkbox } from '@/components/ui/checkbox';
import { Label as UILabel } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head as InertiaHead, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { Transition } from 'vue';

export default {
    components: {
        PrimaryButton,
        InputError,
        EmailInput,
        TextLink,
        Checkbox,
        AuthBase,
        InertiaHead,
        LoaderCircle,
        UILabel,
        Transition,
        PasswordInput
    },
    props: {
        status: {
            type: String,
            default: '',
        },
        canResetPassword: {
            type: Boolean,
            default: false,
        },
        loginState: {
            type: Boolean,
            default: false,
        }
    },
    data() {
        return {
            formEnabled: false,
            form: useForm({
                email: '',
                password: '',
                remember: false,
            }),
        };
    },
    methods: {
        submit() {
            this.form.post(route('login'), {
                onFinish: () => this.form.reset('password'),
            });
        },
        showForm() {
            this.formEnabled = !this.formEnabled;
        },
    },
};
</script>

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

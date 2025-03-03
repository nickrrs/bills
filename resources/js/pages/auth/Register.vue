<script lang="ts">
import PrimaryButton from '@/components/buttons/PrimaryButton.vue';
import TextLink from '@/components/TextLink.vue';
import TextInput from '@/components/inputs/TextInput.vue';
import { Label as UILabel } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head as InertiaHead, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import EmailInput from '@/components/inputs/EmailInput.vue';
import PasswordInput from '@/components/inputs/PasswordInput.vue';

export default {
    name: 'Register',
    components: {
        AuthBase,
        TextLink,
        UILabel,
        TextInput,
        PrimaryButton,
        InertiaHead,
        EmailInput,
        PasswordInput,
        LoaderCircle
    },
    data() {
        return {
            form: useForm({
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
            })
        }
    },
    methods: {
        submit() {
            this.form.post(route('register'), {
                onFinish: () => this.form.reset('password', 'password_confirmation'),
            });
        },
    }
}
</script>

<template>
    <AuthBase description="enter your details below to create your account">
        <InertiaHead title="register" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <UILabel for="name">name</UILabel>
                    <TextInput :id="'name'" :error="form.errors.name" :required="true" :tabindex="1" :autocomplete="'name'" v-model="form.name" :placeholder="'full name'" />
                </div>

                <div class="grid gap-2">
                    <UILabel for="email">email address</UILabel>
                    <EmailInput :id="'email'" :error="form.errors.email" :required="true" :tabindex="2" :autocomplete="'email'" v-model="form.email" :placeholder="'email@example.com'" />
                </div>

                <div class="grid gap-2">
                    <UILabel for="password">password</UILabel>
                    <PasswordInput :id="'password'" :error="form.errors.password" :required="true" :tabindex="3" :autocomplete="'new-password'" v-model="form.password" :placeholder="'password'" />
                </div>

                <div class="grid gap-2">
                    <UILabel for="password_confirmation">confirm password</UILabel>
                    <PasswordInput :id="'password_confirmation'" :error="form.errors.password_confirmation" :required="true" :tabindex="3" :autocomplete="'new-password'" v-model="form.password_confirmation" placeholder="confirm password"/>
                </div>

                <PrimaryButton type="submit" label="create account" class="mt-2 w-full h-[42px] border-2 font-semibold border-zinc-800 text-white backdrop-blur-xl bg-[#0B0B0E] hover:bg-[#0F0F15]" :tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                </PrimaryButton>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                already have an account?
                <TextLink :href="route('login', { loginState: true })" class="underline underline-offset-4" :tabindex="6">log in</TextLink>
            </div>
        </form>
    </AuthBase>
</template>

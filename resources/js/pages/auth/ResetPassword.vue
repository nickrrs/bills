<template>
    <AuthLayout>
        <InertiaHead title="reset password" />

        <form @submit.prevent="submit">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <UILabel for="email">Email</UILabel>
                    <EmailInput :name="'email'" :tabindex="2" :model-value="form.email" @update:model-value="form.email = $event" :error="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <UILabel for="password">Password</UILabel>
                    <PasswordInput :id="'password'" :name="'password'" :placeholder="'new password'" :tabindex="3" :model-value="form.password" @update:model-value="form.password = $event" :error="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <UILabel for="password_confirmation"> Confirm Password </UILabel>
                    <PasswordInput :id="'password_confirmation'" :name="'password_confirmation'" :placeholder="'confirm password'" :tabindex="4" :model-value="form.password_confirmation" @update:model-value="form.password_confirmation = $event" :error="form.errors.password_confirmation" />
                </div>

                <PrimaryButton :disabled="form.processing" :loading="form.processing" label="reset password" :class="'w-full h-[42px]'"/>
            </div>
        </form>
    </AuthLayout>
</template>
<script lang="ts">
import PrimaryButton from '@/components/buttons/PrimaryButton.vue';
import EmailInput from '@/components/inputs/EmailInput.vue';
import PasswordInput from '@/components/inputs/PasswordInput.vue';
import { Label as UILabel } from '@/components/ui/label';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Head as InertiaHead, useForm } from '@inertiajs/vue3';

export default {
    name: 'ResetPassword',
    components: {
        AuthLayout,
        UILabel,
        InertiaHead,
        EmailInput,
        PasswordInput,
        PrimaryButton
    },
    props: {
        token: String,
        email: String,
    },
    data() {
        return {
            form: useForm({
                token: this.token,
                email: this.email as string,
                password: '',
                password_confirmation: '',
            })
        }
    },
    methods: {
        submit() {
            this.form.post(route('password.store'), {
                onFinish: () => {
                    this.form.reset('password', 'password_confirmation');
                },
            });
        }
    }
}
</script>


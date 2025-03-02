<script lang="ts">
import PrimaryButton from '@/components/buttons/PrimaryButton.vue';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Input as UIInput } from '@/components/ui/input';
import { Label as UILabel } from '@/components/ui/label';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Head as InertiaHead, useForm } from '@inertiajs/vue3';
export default {
    components: {
        AuthLayout,
        InertiaHead,
        InputError,
        TextLink,
        UIInput,
        UILabel,
        PrimaryButton
    },
    props: {
        status: {
            type: String,
            default: '',
        },
    },
    data() {
        return {
            form: useForm({
                email: '' as string | number,
            }),
        };
    },
    methods: {
        submit() {
            this.form.post(route('password.email'));
        },
    },
};
</script>

<template>
    <AuthLayout title="Forgot password" description="Enter your email to receive a password reset link">
        <InertiaHead title="Forgot password" />

        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <div class="space-y-6">
            <form @submit.prevent="submit">
                <div class="grid gap-2">
                    <UILabel for="email">email address</UILabel>
                    <UIInput id="email" type="email" name="email" autocomplete="off" v-model="form.email" autofocus placeholder="email@example.com" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="my-6 flex items-center justify-start">
                    <PrimaryButton :disabled="form.processing" :loading="form.processing" label="email password reset link" :class="'w-full'"/>
                </div>
            </form>

            <div class="space-x-1 text-center text-sm text-muted-foreground">
                <span>or, return to</span>
                <TextLink :href="route('login', { loginState: true })">log in</TextLink>
            </div>
        </div>
    </AuthLayout>
</template>

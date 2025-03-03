<script lang="ts">
import PrimaryButton from '@/components/buttons/PrimaryButton.vue';
import EmailInput from '@/components/inputs/EmailInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Label as UILabel } from '@/components/ui/label';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Head as InertiaHead, useForm } from '@inertiajs/vue3';
export default {
    components: {
        AuthLayout,
        InertiaHead,
        TextLink,
        UILabel,
        PrimaryButton,
        EmailInput
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
                email: '' as string,
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
    <AuthLayout>
        <InertiaHead title="forgot password" />

        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <div class="space-y-6">
            <form @submit.prevent="submit">
                <div class="grid gap-2">
                    <UILabel for="email">email address</UILabel>
                    <EmailInput :name="'email'" :tabindex="2" :model-value="form.email" @update:model-value="form.email = $event" :error="form.errors.email" />
                </div>

                <div class="my-6 flex items-center justify-start">
                    <PrimaryButton :disabled="form.processing" :loading="form.processing" label="email password reset link" :class="'h-[42px]'"/>
                </div>
            </form>

            <div class="space-x-1 text-center text-sm text-muted-foreground">
                <span>or, return to</span>
                <TextLink :href="route('login', { loginState: true })">log in</TextLink>
            </div>
        </div>
    </AuthLayout>
</template>

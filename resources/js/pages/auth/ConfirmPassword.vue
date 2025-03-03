<script lang="ts">
import { Label as UiLabel } from '@/components/ui/label';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Head as InertiaHead, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import PrimaryButton from '@/components/buttons/PrimaryButton.vue';
import PasswordInput from '@/components/inputs/PasswordInput.vue';

export default {
    components: {
        AuthLayout,
        UiLabel,
        PrimaryButton,
        PasswordInput,
        InertiaHead,
        LoaderCircle
    },
    data() {
        return {
            form: useForm({
                password: '',
            }),
        };
    },
    methods: {
        submit() {
            this.form.post(route('password.confirm'), {
                onFinish: () => {
                    this.form.reset();
                },
            });
        }
    }
}
</script>

<template>
    <AuthLayout>
        <InertiaHead title="confirm password" />

        <form @submit.prevent="submit">
            <div class="space-y-6">
                <div class="grid gap-2">
                    <UiLabel htmlFor="password">password</UiLabel>
                    <PasswordInput :id="'password'" :name="'password'" :placeholder="'password'" :tabindex="3" :model-value="form.password" @update:model-value="form.password = $event" :error="form.errors.password" />
                </div>

                <div class="flex items-center">
                    <PrimaryButton :disabled="form.processing" :loading="form.processing" label="confirm password" :class="'w-full h-[42px]'">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    </PrimaryButton>
                </div>
            </div>
        </form>
    </AuthLayout>
</template>

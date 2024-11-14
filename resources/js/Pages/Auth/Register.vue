<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    firstname: '',
    lastname: '',
    name: '',
    email: '',
    invitation_code: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    if(form.password !== form.password_confirmation) {
        return form.setError('password_confirmation', 'Passwords do not match!');
    }
    
    form.name = form.firstname + " " + form.lastname;
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <form @submit.prevent="submit">
            <div class="tw-flex tw-flex-row tw-gap-4">
                <div>
                    <InputLabel for="firstname" value="First Name" />
                    <TextInput
                        id="firstname"
                        v-model="form.firstname"
                        type="text"
                        class="tw-mt-1 tw-block tw-w-full"
                        maxlength="50"
                        required
                        autofocus
                        autocomplete="given-name"
                    />
                </div>
                <div>
                    <InputLabel for="lastname" value="Last Name" />
                    <TextInput
                        id="lastname"
                        v-model="form.lastname"
                        type="text"
                        class="tw-mt-1 tw-block tw-w-full"
                        maxlength="48"
                        required
                        autofocus
                        autocomplete="family-name"
                    />
                </div>
            </div>
            <InputError class="tw-mt-2" :message="form.errors.name" />

            <div class="tw-mt-4">
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    maxlength="100"
                    class="tw-mt-1 tw-block tw-w-full"
                    required
                    autocomplete="email"
                />
                <InputError class="tw-mt-2" :message="form.errors.email" />
            </div>
            
            <div class="tw-mt-4">
                <InputLabel for="invitation_code" value="Invitation Code" />
                <TextInput
                    id="invitation_code"
                    v-model="form.invitation_code"
                    type="text"
                    minlength="16"
                    maxlength="16"
                    class="tw-mt-1 tw-block tw-w-full tw-uppercase"
                    required
                />
                <InputError class="tw-mt-2" :message="form.errors.invitation_code" />
            </div>

            <div class="tw-mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="tw-mt-1 tw-block tw-w-full"
                    minlength="10"
                    required
                    autocomplete="new-password"
                />
                <InputError class="tw-mt-2" :message="form.errors.password" />
            </div>

            <div class="tw-mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />
                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="tw-mt-1 tw-block tw-w-full"
                    minlength="10"
                    required
                    autocomplete="new-password"
                />
                <InputError class="tw-mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="tw-mt-4">
                <InputLabel for="terms">
                    <div class="tw-flex tw-items-center">
                        <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />

                        <div class="tw-ml-2">
                            I agree to the <a target="_blank" :href="route('terms.show')" class="tw-underline tw-text-sm tw-text-gray-600 hover:tw-text-gray-900">Terms of Service</a> and <a target="_blank" :href="route('policy.show')" class="tw-underline tw-text-sm tw-text-gray-600 hover:tw-text-gray-900">Privacy Policy</a>
                        </div>
                    </div>
                    <InputError class="tw-mt-2" :message="form.errors.terms" />
                </InputLabel>
            </div>

            <div class="tw-flex tw-items-center tw-justify-end tw-mt-4">
                <Link :href="route('login')" class="tw-underline tw-text-sm tw-text-gray-600 hover:tw-text-gray-900">
                    Already registered?
                </Link>

                <PrimaryButton class="tw-ml-4" :class="{ 'tw-opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>

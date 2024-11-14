<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

import { onMounted, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { useForm } from '@inertiajs/inertia-vue3';
import SelectMenu from '@/Components/SelectMenu.vue';
import FormSection from '@/Components/FormSection.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    invitations: Array,
});

const inviteUserForm = useForm({
    email: '',
});

const inviteUser = () => {
    inviteUserForm.post(route('users.store'), {
        preserveState: true,
        onSuccess: () => inviteUserForm.reset()
    })
};

const revokeInvitationForm = useForm();
const invitationBeingRevoked = ref(null);
const confirmInvitationRevocation = (invitationId) => {
    invitationBeingRevoked.value = invitationId;
};
const revokeInvitation = () => {
    revokeInvitationForm.delete(route('invitations.destroy', [invitationBeingRevoked.value]), {
        errorBag: 'revokeInvitation',
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => invitationBeingRevoked.value = null,
    });
};
</script>

<template>
    <AppLayout title="Manage Users">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">
                Add Users
            </h2>
        </template>
        
        <div class="tw-max-w-7xl tw-mx-auto tw-py-10 sm:tw-px-6 lg:tw-px-8">
            <!-- Add Team Member -->
            <FormSection @submitted="inviteUser">
                <template #title>
                    Add User
                </template>

                <template #description>
                    Enter the email address of the person you'd like to add. The person will receive an invitation with a unique code to complete their registration. Please note that registration is invitation-only. After registration, you will have the option to assign a role to the new user.
                </template>

                <template #form>
                    <div class="tw-col-span-6 sm:tw-col-span-6">
                        <InputLabel for="email" value="Email" />
                        <TextInput
                            id="email"
                            v-model="inviteUserForm.email"
                            type="email"
                            maxlength="100"
                            class="tw-mt-1 tw-block tw-w-full"
                            required
                        />
                        <InputError :message="inviteUserForm.errors.email" class="tw-mt-2" />
                    </div>
                </template>

                <template #actions>
                    <PrimaryButton :class="{ 'tw-opacity-25': inviteUserForm.processing }" :disabled="inviteUserForm.processing">
                        Invite
                    </PrimaryButton>
                </template>
            </FormSection>
            
            <div v-if="props.invitations.length > 0">
                <SectionBorder />
                
                <div class="tw-shadow tw-overflow-hidden tw-border-b tw-border-gray-200 sm:tw-rounded-lg">
                    <div class="tw-px-4 tw-py-3 tw-bg-gray-500">
                        <h3 class="tw-text-xl tw-font-semibold tw-leading-6 tw-text-gray-100 tw-mb-0">
                            Invited &amp; Awaiting Registration
                        </h3>
                    </div>
                    <table class="tw-min-w-full tw-divide-y tw-divide-gray-200 tw-w-full">
                        <thead>
                            <tr>
                                <th scope="col" class="tw-px-6 tw-py-3 tw-bg-gray-100 tw-text-left tw-text-gray-500 tw-uppercase tw-tracking-wider">
                                    Email
                                </th>
                                <th scope="col" class="tw-px-6 tw-py-3 tw-bg-gray-100 tw-text-left tw-text-gray-500 tw-uppercase tw-tracking-wider">
                                    Sent Timestamp
                                </th>
                                <th scope="col" class="tw-px-6 tw-py-3 tw-bg-gray-100 tw-text-left tw-text-gray-500 tw-uppercase tw-tracking-wider">
                                    Invited By
                                </th>
                                <th scope="col" width="100" class="tw-px-6 tw-py-3 tw-bg-gray-100 tw-text-left tw-text-gray-500 tw-uppercase tw-tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="tw-bg-white tw-divide-y tw-divide-gray-200">
                            <tr v-for="invitation in props.invitations">
                                <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-gray-900">
                                    {{ invitation.invitee }}
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-gray-900">
                                    {{ invitation.invited_at }}
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-font-medium">
                                    {{ invitation.inviter }}
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-font-medium">
                                    <DangerButton @click="confirmInvitationRevocation(invitation.id)" class="tw-min-w-full">Revoke</DangerButton>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <ConfirmationModal :show="invitationBeingRevoked" @close="invitationBeingRevoked = null">
            <template #title>
                Revoke Invitation
            </template>

            <template #content>
                Are you sure you would like to revoke this invitation?
            </template>

            <template #footer>
                <SecondaryButton @click="invitationBeingRevoked = null">
                    Cancel
                </SecondaryButton>

                <DangerButton
                    class="tw-ml-3"
                    :class="{ 'tw-opacity-25': revokeInvitationForm.processing }"
                    :disabled="revokeInvitationForm.processing"
                    @click="revokeInvitation"
                >
                    Revoke
                </DangerButton>
            </template>
        </ConfirmationModal>
    </AppLayout>
</template>

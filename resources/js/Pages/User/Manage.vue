<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { useForm } from '@inertiajs/inertia-vue3';
import SelectMenu from '@/Components/SelectMenu.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';

const props = defineProps({
    users: Array,
    roles: Array,
});

const roleOptions = props.roles.reduce((options, role) => {
    options[role] = role.replace(/([A-Z])/g, ' $1').replace(/^./, str => str.toUpperCase());
    return options;
}, {});

const updateRole = (userId, role) => {
    axios.put(route('users.update', userId), {
        role: role
    }).catch(err => {
        console.error(err);
    });
}

const removeUserForm = useForm();
const userBeingRemoved = ref(null);
const confirmUserRemoval = (userId) => {
    userBeingRemoved.value = userId;
};
const removeUser = () => {
    removeUserForm.delete(route('users.destroy', [userBeingRemoved.value]), {
        errorBag: 'removeUser',
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => userBeingRemoved.value = null,
    });
};
</script>

<template>
    <AppLayout title="Manage Users">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">
                Manage Users
            </h2>
        </template>
        
        <div class="tw-max-w-7xl tw-mx-auto tw-py-10 sm:tw-px-6 lg:tw-px-8">
            <div class="tw-shadow tw-overflow-hidden tw-border-b tw-bg-white tw-border-gray-200 sm:tw-rounded-lg">
                <h2 v-if="props.users.length < 1" class="tw-m-4 tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">
                    No other users registered on the site. Be the first to invite!
                </h2>
                <table v-else class="tw-min-w-full tw-divide-y tw-divide-gray-200 tw-w-full">
                    <thead>
                        <tr>
                            <th scope="col" width="80" class="tw-bg-gray-50"></th>
                            <th scope="col" class="tw-pr-6 tw-py-3 tw-bg-gray-50 tw-text-left tw-text-gray-500 tw-uppercase tw-tracking-wider">
                                Name
                            </th>
                            <th scope="col" class="tw-px-6 tw-py-3 tw-bg-gray-50 tw-text-left tw-text-gray-500 tw-uppercase tw-tracking-wider">
                                Email
                            </th>
                            <th scope="col" width="300" class="tw-px-6 tw-py-3 tw-bg-gray-50 tw-text-left tw-text-gray-500 tw-uppercase tw-tracking-wider">
                                Role
                            </th>
                            <th scope="col" width="100" class="tw-px-6 tw-py-3 tw-bg-gray-50 tw-text-left tw-text-gray-500 tw-uppercase tw-tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="tw-bg-white tw-divide-y tw-divide-gray-200">
                        <tr v-for="user in props.users">
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-gray-900">
                                <img class="tw-h-8 tw-w-8 tw-rounded-full tw-object-cover" :src="user.profile_photo_url" :alt="user.name">
                            </td>
                            <td class="tw-pr-6 tw-py-4 tw-whitespace-nowrap tw-text-gray-900">
                                {{ user.name }}
                            </td>
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-gray-900">
                                {{ user.email }}
                            </td>
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-font-medium">
                                <SelectMenu :options="roleOptions" :selected="user.role" :data-user-id="user.id" @change="updateRole(user.id, $event.target.value)"></SelectMenu>
                            </td>
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-font-medium">
                                <DangerButton @click="confirmUserRemoval(user.id)" class="tw-min-w-full">Remove</DangerButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <ConfirmationModal :show="userBeingRemoved" @close="userBeingRemoved = null">
            <template #title>
                Remove User
            </template>

            <template #content>
                Are you sure you would like to remove this user?
            </template>

            <template #footer>
                <SecondaryButton @click="userBeingRemoved = null">
                    Cancel
                </SecondaryButton>

                <DangerButton
                    class="tw-ml-3"
                    :class="{ 'tw-opacity-25': removeUserForm.processing }"
                    :disabled="removeUserForm.processing"
                    @click="removeUser"
                >
                    Remove
                </DangerButton>
            </template>
        </ConfirmationModal>
    </AppLayout>
</template>

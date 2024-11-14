<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import SelectMenu from '@/Components/SelectMenu.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
// import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps({
    datasets: Array,
    actions: Object
});

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
    <AppLayout title="Datasets">
        <template #header>
            <div class="tw-flex">
                <h2 class="tw-my-0 tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-8">
                    Datasets
                </h2>
                <div v-if="can('add-dataset')" class="tw-grow tw-flex tw-justify-end tw-gap-x-4">
                    <Dropdown>
                        <template #trigger>
                            <span class="tw-inline-flex tw-rounded-md">
                                <button type="button" class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-transparent tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-900 tw-bg-white hover:tw-bg-gray-50 hover:tw-text-gray-700 focus:tw-outline-none focus:tw-bg-gray-50 active:tw-bg-gray-50 tw-transition">
                                    Add Dataset

                                    <svg 
                                        class="tw-ml-2 tw--mr-0.5 tw-h-4 tw-w-4"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>
                        </template>

                        <template #content>
                            <DropdownLink :href="route('dataset.import')">
                                Import Dataset
                            </DropdownLink>
                            <DropdownLink :href="route('dataset.import')">
                                Create Dataset
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </div>
        </template>
        
        <div class="tw-max-w-7xl tw-mx-auto tw-py-10 sm:tw-px-6 lg:tw-px-8">
            <div class="tw-shadow tw-border-b tw-bg-white tw-border-gray-200 sm:tw-rounded-lg">
                <h2 v-if="props.datasets.length < 1" class="tw-m-0 tw-px-5 tw-py-4 tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">
                    No datasets found. Start by creating a new dataset.
                </h2>
                <table v-else class="tw-min-w-full tw-divide-y tw-divide-gray-200 tw-w-full">
                    <thead>
                        <tr>
                            <th scope="col" class="sm:tw-rounded-tl-lg tw-px-6 tw-py-3 tw-bg-gray-50 tw-text-left tw-text-gray-500 tw-uppercase tw-tracking-wider">
                                Course
                            </th>
                            <th scope="col" class="sm:tw-rounded-tl-lg tw-px-6 tw-py-3 tw-bg-gray-50 tw-text-left tw-text-gray-500 tw-uppercase tw-tracking-wider">
                                Activity
                            </th>
                            <th scope="col" class="tw-px-6 tw-py-3 tw-bg-gray-50 tw-text-left tw-text-gray-500 tw-uppercase tw-tracking-wider">
                                Year
                            </th>
                            <th scope="col" class="tw-px-6 tw-py-3 tw-bg-gray-50 tw-text-left tw-text-gray-500 tw-uppercase tw-tracking-wider">
                                Semester
                            </th>
                            <th scope="col" class="tw-px-6 tw-py-3 tw-bg-gray-50 tw-text-right tw-text-gray-500 tw-uppercase tw-tracking-wider">
                                # Responses
                            </th>
                            <th scope="col" class="tw-px-6 tw-py-3 tw-bg-gray-50 tw-text-right tw-text-gray-500 tw-uppercase tw-tracking-wider">
                                # Labels
                            </th>
                            <th scope="col" class="tw-px-6 tw-py-3 tw-bg-gray-50 tw-text-left tw-text-gray-500 tw-uppercase tw-tracking-wider">
                                Created By
                            </th>
                            <th scope="col" width="100" class="sm:tw-rounded-tr-lg tw-px-6 tw-py-3 tw-bg-gray-50 tw-text-center tw-text-gray-500 tw-uppercase tw-tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="tw-bg-white tw-divide-y tw-divide-gray-200">
                        <template v-for="(dataset, index) in props.datasets">
                        <tr>
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-gray-900" :class="{'sm:tw-rounded-bl-lg': dataset.name == null && index + 1 == props.datasets.length}">
                                {{ dataset.course }}
                            </td>
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-gray-900">
                                {{ dataset.activity }}
                            </td>
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-gray-900">
                                {{ dataset.year }}
                            </td>
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-gray-900">
                                {{ dataset.semester }}
                            </td>
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-gray-900 tw-text-right">
                                {{ dataset.num_responses }}
                            </td>
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-gray-900 tw-text-right">
                                {{ dataset.num_labels }}
                            </td>
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-gray-900">
                                {{ dataset.creator }}
                            </td>
                            <td class="tw-px-3 tw-py-4 tw-whitespace-nowrap tw-font-medium" :class="{'sm:tw-rounded-br-lg': dataset.name == null && index + 1 == props.datasets.length}">
                                <Dropdown v-if="Object.keys(actions).length > 1" class="tw-min-w-full">
                                    <template #trigger>
                                        <span class="tw-inline-flex tw-rounded-md">
                                            <button type="button" class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-transparent tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-900 tw-bg-white hover:tw-bg-gray-50 hover:tw-text-gray-700 focus:tw-outline-none focus:tw-bg-gray-50 active:tw-bg-gray-50 tw-transition">
                                                Manage

                                                <svg 
                                                    class="tw-ml-2 tw--mr-0.5 tw-h-4 tw-w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink v-for="(action_title, action) in props.actions" :href="route('dataset.' + action, dataset.id)">
                                            {{ action_title }}
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                                <Link v-else :href="route('dataset.' + Object.keys(props.actions)[0], dataset.id)" class="tw-no-underline">
                                    <PrimaryButton class="tw-mx-2">{{ Object.values(props.actions)[0] }}</PrimaryButton>
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="dataset.name" class="tw-border-none">
                            <td colspan="7" class="tw-px-6 tw-italic tw-pb-4 tw-whitespace-nowrap tw-text-gray-900" :class="{'sm:tw-rounded-b-lg': index + 1 == props.datasets.length}">
                                Name: {{ dataset.name }}
                            </td>
                        </tr>
                        </template>
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

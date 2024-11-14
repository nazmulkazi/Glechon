<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import SelectMenu from '@/Components/SelectMenu.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';

const props = defineProps({
    dataset: Object,
    responses: Object,
    annotations: Object,
    annotators: Object,
    selected_annotator: {
        type: Number,
        default: null
    }
});
</script>

<template>
    <AppLayout title="View Dataset">
        <template #header>
            <div class="tw-flex">
                <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">
                    Viewing Dataset: {{props.dataset.course}}, {{props.dataset.semester}} {{props.dataset.year}}{{ props.dataset.name ? ' (' + props.dataset.name + ')' : '' }}
                </h2>
                <template v-if="$attrs.user.role === 'admin' && Object.keys(props.annotators).length > 0">
                    <div class="tw-grow tw-flex tw-justify-end tw-gap-x-4">
                        <Dropdown>
                            <template #trigger>
                                <span class="tw-inline-flex tw-rounded-md">
                                    <button type="button" class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-transparent tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-900 tw-bg-white hover:tw-bg-gray-50 hover:tw-text-gray-700 focus:tw-outline-none focus:tw-bg-gray-50 active:tw-bg-gray-50 tw-transition">
                                        {{ props.annotators.hasOwnProperty(props.selected_annotator) ? props.annotators[props.selected_annotator] : "Select Annotator" }}

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
                                <template v-for="(name, uid) in props.annotators">
                                <DropdownLink :href="route('dataset.show', {dataset: props.dataset.id, annotator_id: uid})">
                                    <span :class="{ 'tw-text-red-500 tw-font-semibold': props.selected_annotator === parseInt(uid) }">{{ name }}</span>
                                </DropdownLink>
                                </template>
                            </template>
                        </Dropdown>
                    </div>
                </template>
            </div>
        </template>
        
        <div class="tw-max-w-7xl tw-mx-auto tw-py-10 sm:tw-px-6 lg:tw-px-8">
            <div v-if="props.responses.length < 1" class="tw-shadow tw-overflow-hidden tw-border-b tw-bg-white tw-border-gray-200 sm:tw-rounded-lg">
                <h2 class="tw-m-4 tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">
                    Dataset has no responses.
                </h2>
            </div>
            <template v-else>
                <div v-for="(sentences, response_id) in props.responses" class="tw-mb-4 tw-shadow tw-overflow-hidden tw-border-b tw-bg-white tw-border-gray-200 sm:tw-rounded-lg">
                    <table class="tw-min-w-full tw-divide-y tw-divide-gray-200 tw-w-full">
                        <thead>
                            <tr>
                                <th scope="col" class="tw-px-6 tw-py-3 tw-bg-gray-50 tw-text-left tw-text-gray-500 tw-uppercase tw-tracking-wider">
                                    Response {{ response_id }}
                                </th>
                                <th scope="col" class="tw-px-6 tw-py-3 tw-bg-gray-50 tw-text-center tw-text-gray-500 tw-uppercase tw-tracking-wider" style="width:30%;">
                                    Labels
                                </th>
                                <th scope="col" class="sm:tw-rounded-tr-lg tw-px-6 tw-py-3 tw-bg-gray-50 tw-text-center tw-text-gray-500 tw-uppercase tw-tracking-wider" style="width:15%;">
                                    Context
                                </th>
                            </tr>
                        </thead>
                        <tbody class="tw-bg-white tw-divide-y tw-divide-gray-200">
                            <tr v-for="(sentence, sent_index) in sentences">
                                <td class="tw-px-4 tw-py-4 tw-text-gray-900">
                                    {{ sent_index + 1 }}. {{ sentence }}
                                </td>
                                <template v-if="props.annotations.context_sentence_indices?.[response_id]?.[sent_index] && Object.keys(props.annotations.context_sentence_indices[response_id][sent_index]).length > 1">
                                    <!-- sentences with context of multiple labels -->
                                    <td class="tw-px-4 tw-py-4 tw-text-gray-900">
                                        <span v-for="label in props.annotations.labels[response_id][sent_index]" class="tw-inline-block tw-bg-gray-200 tw-rounded tw-px-3 tw-py-1 tw-font-semibold tw-text-gray-600 tw-mr-2 tw-mb-2">{{ props.dataset.labels[label] }} ({{ label.toUpperCase() }})</span>
                                    </td>
                                    <td class="tw-px-4 tw-py-4 tw-text-gray-900 tw-text-center">
                                        <template v-for="(csi_list, label) in props.annotations.context_sentence_indices[response_id][sent_index]">
                                            <span v-for="csi in csi_list" class="tw-inline-block tw-bg-gray-200 tw-rounded tw-px-3 tw-py-1 tw-font-semibold tw-text-gray-600 tw-mr-2 tw-mb-2">{{ parseInt(csi) + 1 }} ({{ label.toUpperCase() }})</span>
                                        </template>
                                    </td>
                                </template>
                                <template v-else>
                                    <td class="tw-px-4 tw-py-4 tw-text-gray-900">
                                        <span v-if="props.annotations.labels?.[response_id]?.[sent_index]" v-for="label in props.annotations.labels[response_id][sent_index]" class="tw-inline-block tw-bg-gray-200 tw-rounded tw-px-3 tw-py-1 tw-font-semibold tw-text-gray-600 tw-mr-2 tw-mb-2">{{ props.dataset.labels[label] }}</span>
                                    </td>
                                    <td class="tw-px-4 tw-py-4 tw-text-gray-900 tw-text-center">
                                        <template v-if="props.annotations.context_sentence_indices?.[response_id]?.[sent_index]" v-for="(csi_list, label) in props.annotations.context_sentence_indices[response_id][sent_index]">
                                            <span v-for="csi in csi_list" class="tw-inline-block tw-bg-gray-200 tw-rounded tw-px-3 tw-py-1 tw-font-semibold tw-text-gray-600 tw-mr-2 tw-mb-2">{{ parseInt(csi) + 1 }}</span>
                                        </template>
                                    </td>
                                </template>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>
        </div>
    </AppLayout>
</template>

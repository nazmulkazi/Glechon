<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    dataset: Object,
    statistics: Array
});
</script>

<style>
    /*table tbody tr:nth-child(2n){
        background-color: rgb(225 245 254 / 0.3);
    }*/
</style>

<template>
    <AppLayout title="Dataset Statistics">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">
                Viewing Dataset: {{props.dataset.course}}, {{props.dataset.semester}} {{props.dataset.year}}{{ props.dataset.name !== null ? ' (' + props.dataset.name + ')' : '' }}
            </h2>
        </template>
        
        <div class="tw-max-w-5xl tw-mx-auto tw-p-10">
            <div class="tw-overflow-hidden tw-bg-white tw-shadow sm:tw-rounded-lg">
                <div class="tw-p-5 tw-text-xl tw-font-semibold tw-leading-6 tw-bg-zinc-800 tw-text-white">About Dataset</div>
                <div class="tw-border-t tw-border-gray-200">
                    <table class="tw-w-full tw-divide-y tw-divide-gray-200">
                        <tbody>
                            <tr>
                                <td class="tw-w-1/3 tw-py-2 tw-px-4">Number of responses</td>
                                <td class="tw-py-2 tw-px-4">{{ props.dataset.num_responses }}</td>
                            </tr>
                            <tr>
                                <td class="tw-w-1/3 tw-py-2 tw-px-4">Number of annotators</td>
                                <td class="tw-py-2 tw-px-4">{{ props.statistics.length }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div v-for="user_statistics in props.statistics" class="tw-max-w-5xl tw-mx-auto tw-my-10 tw-overflow-hidden tw-bg-white tw-shadow sm:tw-rounded-lg">
                <div class="tw-p-5 tw-text-xl tw-font-semibold tw-leading-6 tw-bg-zinc-800 tw-text-white">
                    {{user_statistics.name}}
                    <span v-if="user_statistics.num_annotated === props.dataset.num_responses" class="tw-text-green-500">
                        (Completed)
                    </span>
                    <span v-else class="tw-text-red-400">
                        (Progress: {{ Math.floor(user_statistics.num_annotated / props.dataset.num_responses * 100) }}%)
                    </span>
                </div>
                <table class="tw-w-full tw-divide-y tw-divide-gray-200">
                    <thead class="tw-uppercase tw-shadow">
                        <tr class="tw-bg-gray-100">
                            <th scope="col" class="tw-w-1/2"></th>
                            <th scope="col" class="tw-w-1/4 tw-py-2 tw-px-4 tw-text-right">
                                #
                            </th>
                            <th scope="col" class="tw-w-1/4 tw-py-2 tw-px-4 tw-text-right">
                                %
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="tw-py-2 tw-px-4">
                                Number of responses with misconceptions
                            </td>
                            <td class="tw-py-2 tw-px-4 tw-text-right">
                                {{ user_statistics.num_res_mc }}
                            </td>
                            <td class="tw-py-2 tw-px-4 tw-text-right">
                                {{ Math.floor(user_statistics.num_res_mc / user_statistics.num_annotated * 100) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="tw-w-full tw-bg-sky-400 tw-h-2"></div>
                <table class="tw-w-full tw-divide-y tw-divide-gray-200">
                    <thead class="tw-uppercase tw-shadow">
                        <tr>
                            <th colspan="4" class="tw-bg-gray-100 tw-border-b-2 tw-text-center tw-py-2 tw-px-4">Label and Context Distribution</th>
                        </tr>
                        <tr class="tw-bg-gray-100">
                            <th scope="col" class="tw-py-2 tw-px-4">
                                Label
                            </th>
                            <th scope="col" class="tw-py-2 tw-px-4 tw-text-right">
                                # Sentences
                            </th>
                            <th scope="col" class="tw-py-2 tw-px-4 tw-text-right">
                                # Responses
                            </th>
                            <th scope="col" class="tw-py-2 tw-px-4 tw-text-right">
                                # Context Sentences
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(value, label, index) in user_statistics.dist_label_snt" :key="label" :class="index % 2 === 1 ? 'tw-bg-sky-50' : ''">
                            <td class="tw-py-2 tw-px-4">
                                {{ props.dataset.labels[label] }}
                            </td>
                            <td class="tw-py-2 tw-px-4 tw-text-right">
                                {{ value }}
                            </td>
                            <td class="tw-py-2 tw-px-4 tw-text-right">
                                {{ user_statistics.dist_label_res[label] }}
                            </td>
                            <td class="tw-py-2 tw-px-4 tw-text-right">
                                {{ user_statistics.dist_num_ctx[label] }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <template v-if="Object.keys(user_statistics.dist_mlabel_snt).length > 0">
                    <div class="tw-w-full tw-bg-sky-400 tw-h-2"></div>
                    <table class="tw-w-full tw-divide-y tw-divide-gray-200 tw-text-right">
                        <thead class="tw-uppercase tw-shadow">
                            <tr>
                                <th colspan="4" class="tw-bg-gray-100 tw-border-b-2 tw-text-center tw-py-2 tw-px-4">Distribution of Multiple Labels</th>
                            </tr>
                            <tr class="tw-bg-gray-100">
                                <th scope="col" class="tw-py-2 tw-px-4 tw-text-left">
                                    # Labels
                                </th>
                                <th scope="col" class="tw-py-2 tw-px-4">
                                    # Sentences
                                </th>
                                <th scope="col" class="tw-py-2 tw-px-4">
                                    # Responses
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(value, label, index) in user_statistics.dist_mlabel_snt" :key="label" :class="index % 2 === 1 ? 'tw-bg-sky-50' : ''">
                                <td class="tw-py-2 tw-px-4 tw-text-left">
                                    {{ label.slice(1) }}
                                </td>
                                <td class="tw-py-2 tw-px-4 tw-text-right">
                                    {{ value }}
                                </td>
                                <td class="tw-py-2 tw-px-4 tw-text-right">
                                    {{ user_statistics.dist_mlabel_res[label] }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </template>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

import { ref, onBeforeMount, onMounted, reactive } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { useForm } from '@inertiajs/inertia-vue3';
import SelectMenu from '@/Components/SelectMenu.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Multiselect from '@vueform/multiselect';

const props = defineProps({
    dataset: Object,
    responses: Object,
    annotations: Object,
});

const errors = reactive({});
const multiSelectStatus = reactive({});
const multiSelectStatusColor = {'fresh': 'tw-ring-amber-100', 'dirty': 'tw-ring-red-100', 'saved': 'tw-ring-emerald-100'};

// Sort the labels in alphabetical order
const dataset_labels_sorted = Object.entries(props.dataset.labels).map(([value, label]) => ({ value, label: `${label} (${value.toUpperCase()})` })).sort((a, b) => a.label.localeCompare(b.label));

function sent_csi_d2l (csi) {
    const out = [];
    for (const label of Object.keys(csi)) {
        for (const idx of csi[label]) {
            out.push(`${label}.${idx}`);
        }
    }
    return out;
}

function sent_csi_l2d (csi) {
    const out = {};
    for (const val of csi) {
        const [label, idx] = val.split('.');
        if (!out[label]) { out[label] = []; }
        out[label].push(idx);
    }
    return out;
}

function export_labels (labels) {
    const out = {};
    for (const [key, value] of Object.entries(labels)) {
        if (value.length > 0) { out[key] = value; }
    }
    return out;
}

function export_csi (csi) {
    const out = {};
    for (const [sent_idx, sent_ctx_idx] of Object.entries(csi)) {
        if (sent_ctx_idx.length > 0) {
            out[sent_idx] = sent_csi_l2d(sent_ctx_idx);
        }
    }
    return out;
}

function gen_csi_sel_options (res_id, sent_idx) {
    let options = {};
    const labels = props.annotations.labels[res_id][sent_idx];
    
    for (const label of labels) {
        for (let idx=0; idx < sent_idx; idx++) {
            const opt_key = `${label}.${idx}`;
            const opt_label = `${idx + 1} (${label.toUpperCase()})`;
            options[opt_key] = opt_label;
        }
    }
    return options;
}

function deselectLinkedCSIs (response_id, sent_idx, removed_tag) {
    props.annotations.context_sentence_indices[response_id][sent_idx] =
      props.annotations.labels[response_id][sent_idx].length > 0
        ? props.annotations.context_sentence_indices[response_id][sent_idx].filter(tag => !tag.startsWith(removed_tag))
        : [];
}

function clear (response_id, sent_idx) {
    props.annotations.context_sentence_indices[response_id][sent_idx] = [];
}

function save (res_id) {
    errors[res_id] = '';
    axios.put(route('dataset.storeAnnotation', [props.dataset.id, res_id]), {
        labels: export_labels(props.annotations.labels[res_id]),
        context_sentence_indices: export_csi(props.annotations.context_sentence_indices[res_id])
    }).then(res => {
        if (res.data.status == 'success') {
            multiSelectStatus[res_id] = 'saved';
        } else {
            errors[res_id] = res.data.message;
        }
    }).catch(res => {
        console.error(res);
    });
}

onBeforeMount(() => {
    // convert arrays to objects
    if (props.annotations.labels.length === 0) {
        props.annotations.labels = {};
        props.annotations.context_sentence_indices = {};
    }
    for (const id in props.responses) {
        errors[id] = '';
        if (id in props.annotations.labels) {
            multiSelectStatus[id] = 'saved';
            
            for (let sent_idx = 0; sent_idx < props.responses[id].length; sent_idx++) {
                if (!(sent_idx in props.annotations.labels[id])) {
                    props.annotations.labels[id][sent_idx] = [];
                }
            }
            
            // sent_idx must start from 1 since the first sentence cannot have any context
            let csi = props.annotations.context_sentence_indices[id];
            for (let sent_idx = 1; sent_idx < props.responses[id].length; sent_idx++) {
                csi[sent_idx] = sent_idx in csi ? sent_csi_d2l(csi[sent_idx]) : [];
            }
            
            // import_labels(props.annotations.labels[id], props.responses[id].length);
            // import_csi(props.annotations.context_sentence_indices[id], props.responses[id].length);
        } else {
            multiSelectStatus[id] = 'fresh';
            props.annotations.labels[id] = {0: []};
            props.annotations.context_sentence_indices[id] = {};
            for (let sent_idx = 1; sent_idx < props.responses[id].length; sent_idx++) {
                props.annotations.labels[id][sent_idx] = [];
                props.annotations.context_sentence_indices[id][sent_idx] = [];
            }
        }
    }
});
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style>
    :root {
        --ms-ring-color: #29B6F630;
        --ms-dropdown-border-color: #29B6F630;
        --ms-dropdown-border-width: 3px;
        --ms-dropdown-radius: 8px;
    }
    
    .multiselect-tag {
        white-space: break-spaces;
    }
</style>

<template>
    <AppLayout title="Annotate Dataset">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">
                Annotating Dataset: {{props.dataset.course}}, {{props.dataset.semester}} {{props.dataset.year}}{{ props.dataset.name !== null ? ' (' + props.dataset.name + ')' : '' }}
            </h2>
        </template>
        
        <div class="tw-max-w-7xl tw-mx-auto tw-py-10 sm:tw-px-6 lg:tw-px-8">
            <div v-if="props.responses.length < 1" class="tw-shadow tw-overflow-hidden tw-border-b tw-bg-white tw-border-gray-200 sm:tw-rounded-lg">
                <h2 class="tw-m-4 tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">
                    Dataset has no responses.
                </h2>
            </div>
            <template v-else>
                <div class="tw-mb-8 tw-p-4 tw-shadow tw-overflow-hidden tw-border-b tw-bg-white tw-border-gray-200 sm:tw-rounded-lg">
                    The dataset consists of multiple responses, and each response is presented in an individual table with a unique id indicated in the header. Each response can contain multiple sentences, with each sentence numbered and displayed in a separate row. To label the sentence in each row, use the select box in the <span class="tw-font-bold tw-text-gray-500 tw-uppercase tw-text-sm tw-rounded-md tw-border-gray-300 tw-border-2 tw-px-1">labels</span> column, which provides a list of pre-defined labels that can be selected, and in cases where multiple labels are applicable, they can be selected together. If any previous sentences provide context on depicting the label, use the select box in the <span class="tw-font-bold tw-text-gray-500 tw-uppercase tw-text-sm tw-rounded-md tw-border-gray-300 tw-border-2 tw-px-1">context</span> column to select the corresponding sentence numbers.<br><br>
                    
                    Once a response is fully labeled, save the annotations of the response by clicking the <span class="tw-bg-gray-800 tw-text-white tw-font-bold tw-uppercase tw-text-sm tw-rounded-md tw-px-2 tw-py-0.5">save</span> button in the footer. In the event that no labels are applicable for any of the sentences in a response, clicking the <span class="tw-bg-gray-800 tw-text-white tw-font-bold tw-uppercase tw-text-sm tw-rounded-md tw-px-2 tw-py-0.5">save</span> button is still necessary to confirm the null annotation. Annotations for each sentence in a response should be completed before saving to avoid the incomplete part being interpreted as null annotation, although the task can be paused and resumed later between any responses.<br><br>

                    To help users keep track of their progress, the select box in the <span class="tw-font-bold tw-text-gray-500 tw-uppercase tw-text-sm tw-rounded-md tw-border-gray-300 tw-border-2 tw-px-1">labels</span> column is color-coded to display <span class="tw-text-amber-500">amber</span>, <span class="tw-text-red-400">red</span>, and <span class="tw-text-emerald-400">green</span> borders, which represent <span class="tw-text-amber-500">never annotated</span>, <span class="tw-text-red-400">unsaved</span>, and <span class="tw-text-emerald-400">successfully saved</span> responses, respectively.
                </div>
                <div v-for="(sentences, response_id) in props.responses" class="tw-mb-4 tw-shadow tw-border-b tw-bg-white tw-border-gray-200 sm:tw-rounded-lg">
                    <table class="tw-min-w-full tw-divide-y tw-divide-gray-200 tw-w-full">
                        <thead>
                            <tr>
                                <th scope="col" class="sm:tw-rounded-tl-lg tw-px-6 tw-py-3 tw-bg-gray-50 tw-text-left tw-text-gray-500 tw-uppercase tw-tracking-wider">
                                    Response {{ response_id }}
                                </th>
                                <th scope="col" class="sm:tw-rounded-tr-lg tw-py-3 tw-bg-gray-50 tw-text-center tw-text-gray-500 tw-uppercase tw-tracking-wider" style="width:30%;">
                                    Labels
                                </th>
                                <th scope="col" class="sm:tw-rounded-tr-lg tw-px-6 tw-py-3 tw-bg-gray-50 tw-text-center tw-text-gray-500 tw-uppercase tw-tracking-wider" style="width:20%;">
                                    Context
                                </th>
                            </tr>
                        </thead>
                        <tbody class="tw-bg-white tw-divide-y tw-divide-gray-200">
                            <tr v-for="(sentence, sent_index) in sentences">
                                <td class="tw-px-4 tw-py-4 tw-text-gray-900">
                                    {{ sent_index + 1 }}. {{ sentence }}
                                </td>
                                <td class="tw-py-4 tw-text-gray-900">
                                    <Multiselect
                                        v-model="props.annotations.labels[response_id][sent_index]"
                                        mode="tags"
                                        :options="dataset_labels_sorted"
                                        :searchable="true"
                                        class="tw-ring-4"
                                        :class="multiSelectStatusColor[multiSelectStatus[response_id]]"
                                        @deselect="deselectLinkedCSIs(response_id, sent_index, $event)"
                                        @clear="clear(response_id, sent_index)"
                                        @change="multiSelectStatus[response_id] = 'dirty'"
                                    />
                                </td>
                                <td class="tw-px-4 tw-py-4 tw-text-gray-900">
                                    <Multiselect
                                        v-if="sent_index > 0"
                                        v-model="props.annotations.context_sentence_indices[response_id][sent_index]"
                                        mode="tags"
                                        placeholder="Sentence Ids"
                                        :options="gen_csi_sel_options(response_id, sent_index)"
                                        :disabled="props.annotations.labels[response_id][sent_index].length == 0"
                                        :searchable="true"
                                        @change="multiSelectStatus[response_id] = 'dirty'"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="sm:tw-rounded-b-lg tw-px-4 tw-py-4 tw-text-right">
                                    <div class="tw-mr-3 tw-inline-block tw-align-middle tw-text-red-500">{{ errors[response_id] }}</div>
                                    <PrimaryButton @click="save(response_id)">Save</PrimaryButton>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>
        </div>
    </AppLayout>
</template>

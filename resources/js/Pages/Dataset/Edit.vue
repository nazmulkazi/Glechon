<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

/* bgn: import form */
import { useForm } from '@inertiajs/inertia-vue3';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputSuccess from '@/Components/InputSuccess.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SectionBorder from '@/Components/SectionBorder.vue';

const props = defineProps({
    dataset: Object,
});

const form = useForm({
    course: props.dataset.course,
    year: props.dataset.year,
    semester: props.dataset.semester,
    name: props.dataset.name,
    labels: JSON.stringify(props.dataset.labels, null, 2)
});

// const submitFormUpload = () => {
//     formUploadSuccessMessage = null;
//     formUpload.post(route('dataset.store'), {
//         errorBag: 'formUpload',
//         preserveScroll: true,
//         onSuccess: () => {
//             formUpload.reset();
//             formUploadSuccessMessage = "Dataset has been imported successfully.";
//         },
//     })
// }

// const formDownload = useForm({
//     link: null,
// });
// const submitFormDownload = () => {
//     formUpload.post(route('dataset.store'), {
//         errorBag: 'formDownload',
//         preserveScroll: true,
//     })
// }

// const createTeam = () => {
//     form.post(route('teams.store'), {
//         errorBag: 'createTeam',
//         preserveScroll: true,
//     });
// };


/* end: import form */
</script>

<template>
    <AppLayout title="Edit Dataset">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">
                Editing Dataset: {{props.dataset.course}}, {{props.dataset.semester}} {{props.dataset.year}}{{ props.dataset.name ? ' (' + props.dataset.name + ')' : '' }}
            </h2>
        </template>

        <div>
            <div class="tw-max-w-7xl tw-mx-auto tw-py-10 sm:tw-px-6 lg:tw-px-8">
                                
                <FormSection @submitted="submitFormUpload">
                    <template #title>
                        <span class="tw-font-bold">Metadata</span>
                    </template>

                    <template #description>
                        
                    </template>

                    <template #form>
                        <div class="tw-col-span-6 sm:tw-col-span-3">
                            <InputLabel for="course" value="Course" />
                            <TextInput
                                id="course"
                                v-model="props.dataset.course"
                                type="text"
                                class="tw-block tw-w-full tw-mt-1"
                                autofocus
                            />
                            <InputError class="tw-mt-2" :message="form.errors.course" />
                        </div>
                        <div class="tw-col-span-6 sm:tw-col-span-3">
                            <InputLabel for="activity" value="Activity" />
                            <TextInput
                                id="activity"
                                v-model="props.dataset.activity"
                                type="text"
                                class="tw-block tw-w-full tw-mt-1"
                            />
                            <InputError class="tw-mt-2" :message="form.errors.activity" />
                        </div>
                        <div class="tw-col-span-6 sm:tw-col-span-3">
                            <InputLabel for="semester" value="Semester" />
                            <TextInput
                                id="semester"
                                v-model="props.dataset.semester"
                                type="text"
                                class="tw-block tw-w-full tw-mt-1"
                            />
                            <InputError class="tw-mt-2" :message="form.errors.semester" />
                        </div>
                        <div class="tw-col-span-6 sm:tw-col-span-3">
                            <InputLabel for="year" value="Year" />
                            <TextInput
                                id="year"
                                v-model="props.dataset.year"
                                type="number"
                                class="tw-block tw-w-full tw-mt-1"
                            />
                            <InputError class="tw-mt-2" :message="form.errors.year" />
                        </div>
                        <div class="tw-col-span-6 sm:tw-col-span-6">
                            <InputLabel for="name" value="Name" />
                            <TextInput
                                id="name"
                                v-model="props.dataset.name"
                                type="text"
                                class="tw-block tw-w-full tw-mt-1"
                                autofocus
                            />
                            <InputError class="tw-mt-2" :message="form.errors.name" />
                        </div>
                    </template>

                    <template #actions>
                        <PrimaryButton :class="{ 'tw-opacity-25': form.processing }" :disabled="form.processing">
                            Save
                        </PrimaryButton>
                    </template>
                </FormSection>
                
                <SectionBorder />
                
                <FormSection @submitted="updateLabels">
                    <template #title>
                        <span class="tw-font-bold">Labels</span>
                    </template>

                    <template #description>
                        This must be a single JSON dictionary object. Each label should have a key (alpha-numerical; no space or signs) which the system will use as a reference the corresponsing label in annotation. You can edit the labels anytime but do not edit any key after the dataset has been annotated.
                    </template>

                    <template #form>
                        <div class="tw-col-span-6 sm:tw-col-span-6">
                            <textarea id="labels" rows="10" class="tw-font-mono tw-block tw-p-3 tw-w-full tw-text-gray-900 tw-rounded-lg tw-border tw-border-gray-300 focus:tw-ring-blue-500 focus:tw-border-blue-500" placeholder='{"key" : "label"}' v-model="form.labels"></textarea>
                            <InputError :message="form.errors.labels" class="tw-mt-2" />
                        </div>
                    </template>

                    <template #actions>
                        <PrimaryButton :class="{ 'tw-opacity-25': form.processing }" :disabled="form.processing">
                            Save
                        </PrimaryButton>
                    </template>
                </FormSection>
                
            </div>
        </div>
    </AppLayout>
</template>

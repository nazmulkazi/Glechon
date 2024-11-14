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

const formUpload = useForm({
    file: null,
});

let formUploadSuccessMessage = null;
const submitFormUpload = () => {
    formUploadSuccessMessage = null;
    formUpload.post(route('dataset.store'), {
        errorBag: 'formUpload',
        preserveScroll: true,
        onSuccess: () => {
            formUpload.reset();
            formUploadSuccessMessage = "Dataset has been imported successfully.";
        },
    })
}

const formDownload = useForm({
    link: null,
});
const submitFormDownload = () => {
    formUpload.post(route('dataset.store'), {
        errorBag: 'formDownload',
        preserveScroll: true,
    })
}

const createTeam = () => {
    form.post(route('teams.store'), {
        errorBag: 'createTeam',
        preserveScroll: true,
    });
};


/* end: import form */
</script>

<template>
    <AppLayout title="Import Dataset">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">
                Import Dataset
            </h2>
        </template>

        <div>
            <div class="tw-max-w-7xl tw-mx-auto tw-py-10 sm:tw-px-6 lg:tw-px-8">
                                
                <FormSection @submitted="submitFormUpload">
                    <template #title>
                        From Local Files
                    </template>

                    <template #description>
                        Use this option to import a backed up dataset from your local device. Only JSON files are acceptable and the data must be in format defined by this app. Import one dataset at a time.
                    </template>

                    <template #form>
                        <div class="tw-col-span-6 sm:tw-col-span-6">
                            <input type="file" @input="formUpload.file = $event.target.files[0]; formUploadSuccessMessage = null;" class="tw-block tw-w-full tw-text-sm text-slate-500 file:tw-mr-4 file:tw-py-2 file:tw-px-4 file:tw-rounded-full file:tw-border-0 file:tw-text-sm file:tw-font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100" accept="application/json"/>
                            <progress v-if="formUpload.progress" :value="formUpload.progress.percentage" max="100" class="tw-mt-2">
                                {{ formUpload.progress.percentage }}%
                            </progress>
                            <InputError :message="formUpload.errors.file" class="tw-mt-2" />
                            <InputSuccess :message="formUploadSuccessMessage" class="tw-mt-2" />
                        </div>
                    </template>

                    <template #actions>
                        <PrimaryButton :class="{ 'tw-opacity-25': formUpload.processing }" :disabled="formUpload.processing">
                            Import
                        </PrimaryButton>
                    </template>
                </FormSection>
                
                <SectionBorder />
                
                <FormSection @submitted="createTeam">
                    <template #title>
                        From Web Link
                    </template>

                    <template #description>
                        Use this option to import a dataset from another server by URL. Only JSON files are acceptable and the data must be in format defined by this app. The file must not be in any compress or archived format.
                    </template>

                    <template #form>
                        <div class="tw-col-span-6 sm:tw-col-span-6">
                            <InputLabel for="name" value="URL" />
                            <TextInput
                                id="name"
                                v-model="formDownload.link"
                                type="text"
                                class="tw-block tw-w-full tw-mt-1"
                                autofocus
                            />
                            <InputError :message="formDownload.errors.name" class="tw-mt-2" />
                        </div>
                    </template>

                    <template #actions>
                        <PrimaryButton :class="{ 'tw-opacity-25': formDownload.processing }" :disabled="formDownload.processing">
                            Import
                        </PrimaryButton>
                    </template>
                </FormSection>
                
                
                
                
            </div>
        </div>
    </AppLayout>
</template>

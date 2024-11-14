<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

/* bgn: import form */
import { useForm } from '@inertiajs/inertia-vue3';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    name: '',
});

const createTeam = () => {
    form.post(route('teams.store'), {
        errorBag: 'createTeam',
        preserveScroll: true,
    });
};


/* end: import form */
</script>

<template>
    <AppLayout title="Create Dataset">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Import Dataset
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                                
                <FormSection @submitted="createTeam">
                    <template #title>
                        Upload Dataset File
                    </template>

                    <template #description>
                        The dataset file must be formatted as JSON.
                    </template>

                    <template #form>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="name" value="Semester" />
                            <TextInput
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="block w-full mt-1"
                                autofocus
                            />
                            <InputError :message="form.errors.name" class="mt-2" />
                        </div>
                    </template>

                    <template #actions>
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Import
                        </PrimaryButton>
                    </template>
                </FormSection>
                

                
                
                
                
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
defineProps({
    options: Object,
    selected: String
});
</script>

<template>
    <select class="tw-form-select tw-appearance-none tw-block tw-w-full tw-px-3 tw-py-1.5 tw-text-base tw-font-normal tw-text-gray-700 tw-bg-white tw-bg-clip-padding tw-bg-no-repeat tw-border tw-border-solid tw-border-gray-300 tw-rounded tw-transition tw-ease-in-out tw-m-0 focus:tw-text-gray-700 focus:tw-bg-white focus:tw-border-blue-600 focus:tw-outline-none" aria-label="">
        <option v-for="(text, value) in options" :value="value" :selected="value == selected">
            {{ text }}
        </option>
    </select>
</template>
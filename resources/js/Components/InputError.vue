<script setup>
defineProps({
    message: String,
});
</script>

<template>
    <div v-show="message">
        <p class="tw-my-0 tw-text-sm tw-text-red-600">
            {{ message }}
        </p>
    </div>
</template>

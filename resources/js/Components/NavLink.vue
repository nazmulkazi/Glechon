<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return 'tw-inline-flex tw-items-center tw-px-1 tw-pt-1 tw-border-b-2 tw-text-sm tw-font-medium tw-leading-5 tw-transition text-decoration-none '
        + (props.active 
        ? 'tw-border-indigo-400  tw-text-gray-900 focus:tw-outline-none focus:tw-border-indigo-700'
        : 'tw-border-transparent tw-text-gray-500 hover:tw-text-gray-700 hover:tw-border-gray-300 focus:tw-outline-none focus:tw-text-gray-700 focus:tw-border-gray-300');
});
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>

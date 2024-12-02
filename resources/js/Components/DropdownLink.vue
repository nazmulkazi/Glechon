<script setup>
import { Link } from '@inertiajs/inertia-vue3';

defineProps({
    href: String,
    as: String,
    download: Boolean, // If true, adds "download" attribute to <a> tags. Ignored if <button>.
});
</script>

<template>
    <div>
        <button v-if="as == 'button'" type="submit" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-text-sm tw-leading-5 tw-text-gray-700 tw-text-left hover:tw-bg-gray-100 focus:tw-outline-none focus:tw-bg-gray-100 tw-transition">
            <slot />
        </button>

        <a v-else-if="as == 'a'" :href="href" :download="download ? '' : null" class="as-a tw-block tw-px-4 tw-py-2 tw-text-sm tw-leading-5 tw-text-gray-700 hover:tw-bg-gray-100 focus:tw-outline-none focus:tw-bg-gray-100 tw-transition text-decoration-none">
            <slot />
        </a>

        <Link v-else :href="href" class="tw-block tw-px-4 tw-py-2 tw-text-sm tw-leading-5 tw-text-gray-700 hover:tw-bg-gray-100 focus:tw-outline-none focus:tw-bg-gray-100 tw-transition text-decoration-none">
            <slot />
        </Link>
    </div>
</template>

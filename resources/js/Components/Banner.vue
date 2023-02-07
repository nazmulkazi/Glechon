<script setup>
import { computed, ref, watch } from 'vue';
import { usePage } from '@inertiajs/inertia-vue3';

const show = ref(true);
const style = computed(() => usePage().props.value.jetstream.flash?.bannerStyle || 'success');
const message = computed(() => usePage().props.value.jetstream.flash?.banner || '');

watch(message, async () => {
  show.value = true;
});
</script>

<template>
    <div>
        <div v-if="show && message" :class="{ 'tw-bg-indigo-500': style == 'success', 'tw-bg-red-700': style == 'danger' }">
            <div class="tw-max-w-screen-xl tw-mx-auto tw-py-2 tw-px-3 sm:tw-px-6 lg:tw-px-8">
                <div class="tw-flex tw-items-center tw-justify-between tw-flex-wrap">
                    <div class="tw-w-0 tw-flex-1 tw-flex tw-items-center tw-min-w-0">
                        <span class="tw-flex tw-p-2 tw-rounded-lg" :class="{ 'tw-bg-indigo-600': style == 'success', 'tw-bg-red-600': style == 'danger' }">
                            <svg
                                v-if="style == 'success'"
                                class="tw-h-5 tw-w-5 tw-text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>

                            <svg
                                v-if="style == 'danger'"
                                class="tw-h-5 tw-w-5 tw-text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                />
                            </svg>
                        </span>

                        <p class="tw-ml-3 tw-font-medium tw-text-sm tw-text-white tw-truncate">
                            {{ message }}
                        </p>
                    </div>

                    <div class="shrink-0 sm:tw-ml-3">
                        <button
                            type="button"
                            class="tw--mr-1 tw-flex tw-p-2 tw-rounded-md focus:tw-outline-none sm:tw--mr-2 tw-transition"
                            :class="{ 'hover:tw-bg-indigo-600 focus:tw-bg-indigo-600': style == 'success', 'hover:tw-bg-red-600 focus:tw-bg-red-600': style == 'danger' }"
                            aria-label="Dismiss"
                            @click.prevent="show = false"
                        >
                            <svg
                                class="tw-h-5 tw-w-5 tw-text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

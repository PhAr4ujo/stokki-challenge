<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        default: '',
    },
    backHref: {
        type: String,
        default: '',
    },
    backLabel: {
        type: String,
        default: 'Voltar',
    },
});
</script>

<template>
    <Head :title="title" />

    <AuthenticatedLayout>
        <template #header>
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between"
            >
                <div class="min-w-0 flex-1">
                    <Link
                        v-if="backHref"
                        :href="backHref"
                        class="mb-3 inline-flex items-center gap-1.5 text-sm font-medium text-gray-600 transition hover:text-gray-900"
                    >
                        <svg
                            class="h-4 w-4"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"
                            />
                        </svg>
                        {{ backLabel }}
                    </Link>
                    <h2
                        class="text-xl font-semibold leading-tight text-gray-800"
                    >
                        {{ title }}
                    </h2>
                    <p
                        v-if="description"
                        class="mt-1 text-sm text-gray-600"
                    >
                        {{ description }}
                    </p>
                </div>
                <div
                    v-if="$slots.actions"
                    class="flex shrink-0 flex-wrap items-center gap-2"
                >
                    <slot name="actions" />
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900 md:p-8">
                        <slot />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

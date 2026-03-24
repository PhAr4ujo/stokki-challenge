<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    method: {
        type: String,
        default: null,
    },
    variant: {
        type: String,
        default: 'view',
    },
    confirmMessage: {
        type: String,
        default: null,
    },
});

const baseClass =
    'inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border transition focus:outline-none focus:ring-2 focus:ring-offset-1';

const variantClass = {
    view: 'border-gray-200 bg-white text-gray-500 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 focus:ring-blue-500',
    edit: 'border-gray-200 bg-white text-gray-500 hover:border-amber-200 hover:bg-amber-50 hover:text-amber-800 focus:ring-amber-500',
    destroy:
        'border-gray-200 bg-white text-gray-500 hover:border-red-200 hover:bg-red-50 hover:text-red-700 focus:ring-red-500',
};

function onClick(e) {
    if (props.confirmMessage && !confirm(props.confirmMessage)) {
        e.preventDefault();
    }
}
</script>

<template>
    <Link
        v-if="method"
        :href="href"
        :method="method"
        as="button"
        type="button"
        :title="title"
        :class="[baseClass, variantClass[variant] ?? variantClass.view]"
        @click="onClick"
    >
        <span class="sr-only">{{ title }}</span>
        <slot>
            <svg
                v-if="variant === 'view'"
                class="h-4 w-4"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                />
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                />
            </svg>
            <svg
                v-else-if="variant === 'edit'"
                class="h-4 w-4"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"
                />
            </svg>
            <svg
                v-else
                class="h-4 w-4"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M14.74 9l-.346 9m-4.008 0L9.502 9m9.122-3.25H3.378a1.125 1.125 0 00-1.12 1.007l-.77 7.5a1.125 1.125 0 001.12 1.007h13.19a1.125 1.125 0 001.12-1.007l-.77-7.5a1.125 1.125 0 00-1.12-1.007zM9.503 4.75V3.375c0-.621.504-1.125 1.125-1.125h2.872c.621 0 1.125.504 1.125 1.125V4.75"
                />
            </svg>
        </slot>
    </Link>

    <Link
        v-else
        :href="href"
        :title="title"
        :class="[baseClass, variantClass[variant] ?? variantClass.view]"
    >
        <span class="sr-only">{{ title }}</span>
        <slot>
            <svg
                v-if="variant === 'view'"
                class="h-4 w-4"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                />
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                />
            </svg>
            <svg
                v-else-if="variant === 'edit'"
                class="h-4 w-4"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"
                />
            </svg>
        </slot>
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    variant: {
        type: String,
        default: 'primary',
    },
});

const linkClass = computed(() => {
    const base =
        'inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold shadow-sm transition focus:outline-none focus:ring-2 focus:ring-offset-2';

    if (props.variant === 'secondary') {
        return `${base} border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:ring-indigo-500`;
    }

    return `${base} border border-transparent bg-gray-800 text-white hover:bg-gray-700 focus:ring-indigo-500 active:bg-gray-900`;
});
</script>

<template>
    <Link
        :href="href"
        :class="linkClass"
    >
        <slot name="leading" />
        <span><slot /></span>
    </Link>
</template>

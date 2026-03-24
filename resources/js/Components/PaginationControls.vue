<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    links: {
        type: Array,
        default: () => [],
    },
    from: {
        type: Number,
        default: null,
    },
    to: {
        type: Number,
        default: null,
    },
    total: {
        type: Number,
        default: 0,
    },
    showNav: {
        type: Boolean,
        default: true,
    },
});

const summary = computed(() => {
    if (props.total === 0) {
        return null;
    }
    if (props.from != null && props.to != null) {
        return `Mostrando ${props.from}–${props.to} de ${props.total}`;
    }
    return `Total: ${props.total}`;
});
</script>

<template>
    <div
        v-if="summary || (showNav && links.length > 0)"
        class="flex flex-col gap-3 border-t border-gray-200 pt-4 sm:flex-row sm:items-center sm:justify-between"
    >
        <p
            v-if="summary"
            class="text-sm text-gray-600"
        >
            {{ summary }}
        </p>
        <nav
            v-if="showNav && links.length > 0"
            class="flex flex-wrap items-center justify-end gap-1"
            aria-label="Paginação"
        >
            <template
                v-for="(link, i) in links"
                :key="i"
            >
                <Link
                    v-if="link.url"
                    :href="link.url"
                    :class="[
                        'inline-flex min-h-9 min-w-9 items-center justify-center rounded-lg border px-2.5 py-1 text-sm transition',
                        link.active
                            ? 'border-indigo-600 bg-indigo-600 text-white'
                            : 'border-gray-200 bg-white text-gray-700 hover:bg-gray-50',
                    ]"
                >
                    <span v-html="link.label" />
                </Link>
                <span
                    v-else
                    :class="[
                        'inline-flex min-h-9 min-w-9 cursor-not-allowed items-center justify-center rounded-lg border border-gray-100 bg-gray-50 px-2.5 py-1 text-sm text-gray-400',
                        link.active ? 'border-indigo-200 bg-indigo-50 text-indigo-800' : '',
                    ]"
                    v-html="link.label"
                />
            </template>
        </nav>
    </div>
</template>

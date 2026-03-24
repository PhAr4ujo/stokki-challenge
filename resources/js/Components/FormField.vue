<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';

defineProps({
    label: {
        type: String,
        required: true,
    },
    labelFor: {
        type: String,
        required: true,
    },
    error: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div>
        <InputLabel
            :for="labelFor"
            :value="label"
        />
        <div class="mt-1">
            <slot />
        </div>
        <InputError
            class="mt-2"
            :message="error"
        />
    </div>
</template>

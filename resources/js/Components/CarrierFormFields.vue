<script setup>
import FormField from '@/Components/FormField.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    form: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="space-y-6">
        <FormField
            label="Nome"
            label-for="carrier-name"
            :error="form.errors.name"
        >
            <TextInput
                id="carrier-name"
                v-model="form.name"
                type="text"
                class="block w-full"
                required
                autocomplete="organization"
            />
        </FormField>

        <div class="grid gap-6 sm:grid-cols-2">
            <FormField
                label="Cidade"
                label-for="carrier-city"
                :error="form.errors.city"
            >
                <TextInput
                    id="carrier-city"
                    v-model="form.city"
                    type="text"
                    class="block w-full"
                    required
                    autocomplete="address-level2"
                />
            </FormField>

            <FormField
                label="Bairro"
                label-for="carrier-neighborhood"
                :error="form.errors.neighborhood"
            >
                <TextInput
                    id="carrier-neighborhood"
                    v-model="form.neighborhood"
                    type="text"
                    class="block w-full"
                    required
                    autocomplete="off"
                />
            </FormField>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <FormField
                label="Número"
                label-for="carrier-residence-number"
                :error="form.errors.residence_number"
            >
                <TextInput
                    id="carrier-residence-number"
                    v-model="form.residence_number"
                    type="number"
                    class="block w-full"
                    min="0"
                    step="1"
                    required
                />
            </FormField>

            <FormField
                label="Complemento"
                label-for="carrier-complement"
                :error="form.errors.complement"
            >
                <TextInput
                    id="carrier-complement"
                    v-model="form.complement"
                    type="text"
                    class="block w-full"
                    required
                    autocomplete="off"
                />
            </FormField>
        </div>
    </div>
</template>

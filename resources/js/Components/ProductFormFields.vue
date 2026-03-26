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
            label-for="product-name"
            :error="form.errors.name"
        >
            <TextInput
                id="product-name"
                v-model="form.name"
                type="text"
                class="block w-full"
                required
                autocomplete="off"
            />
        </FormField>

        <FormField
            label="Descrição"
            label-for="product-description"
            :error="form.errors.description"
        >
            <textarea
                id="product-description"
                v-model="form.description"
                rows="3"
                required
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="Digite a descrição do produto"
            />
        </FormField>

        <FormField
            label="Cliente"
            label-for="product-customer"
            :error="form.errors.customer_name"
        >
            <TextInput
                id="product-customer"
                v-model="form.customer_name"
                type="text"
                class="block w-full"
                required
                autocomplete="off"
                placeholder="Nome do cliente"
            />
        </FormField>

        <div class="grid gap-6 sm:grid-cols-2">
            <FormField
                label="Preço (R$)"
                label-for="product-price"
                :error="form.errors.price"
            >
                <TextInput
                    id="product-price"
                    v-model="form.price"
                    type="number"
                    class="block w-full"
                    min="0"
                    step="0.01"
                    required
                    placeholder="Informe o preço"
                />
            </FormField>

            <FormField
                label="Quantidade"
                label-for="product-amount"
                :error="form.errors.amount"
            >
                <TextInput
                    id="product-amount"
                    v-model="form.amount"
                    type="number"
                    class="block w-full"
                    min="1"
                    step="1"
                    required
                    placeholder="Informe a quantidade"
                />
            </FormField>
        </div>
    </div>
</template>

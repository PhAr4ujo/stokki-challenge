<script setup>
import ButtonLink from '@/Components/ButtonLink.vue';
import FormPage from '@/Components/FormPage.vue';
import ProductFormFields from '@/Components/ProductFormFields.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    description: '',
    customer_name: '',
    price: '',
    amount: '',
});

function submit() {
    form.post(route('products.store'));
}
</script>

<template>
    <FormPage
        title="Novo produto"
        description="Preencha os dados para cadastrar um produto."
        :back-href="route('products.index')"
        back-label="Voltar à lista"
    >
        <form
            class="space-y-8"
            @submit.prevent="submit"
        >
            <ProductFormFields :form="form" />

            <div
                class="flex flex-col gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:flex-wrap sm:items-center sm:gap-4"
            >
                <PrimaryButton :disabled="form.processing">
                    Salvar produto
                </PrimaryButton>
                <ButtonLink
                    :href="route('products.index')"
                    variant="secondary"
                >
                    Cancelar
                </ButtonLink>
            </div>
        </form>
    </FormPage>
</template>

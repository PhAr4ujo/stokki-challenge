<script setup>
import ButtonLink from '@/Components/ButtonLink.vue';
import FormPage from '@/Components/FormPage.vue';
import ProductFormFields from '@/Components/ProductFormFields.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    name: props.product.name,
    description: props.product.description,
    customer_name: props.product.customer_name,
    price: String(props.product.price),
    amount: String(props.product.amount),
});

function submit() {
    form.put(route('products.update', props.product.id));
}
</script>

<template>
    <FormPage
        title="Editar produto"
        description="Atualize os dados do produto."
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
                    Atualizar produto
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

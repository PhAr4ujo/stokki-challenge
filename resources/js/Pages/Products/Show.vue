<script setup>
import ButtonLink from '@/Components/ButtonLink.vue';
import FormPage from '@/Components/FormPage.vue';

defineProps({
    product: {
        type: Object,
        required: true,
    },
});

function formatMoney(value) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(Number(value));
}

function formatDateTime(value) {
    if (!value) {
        return '—';
    }
    return new Intl.DateTimeFormat('pt-BR', {
        dateStyle: 'short',
        timeStyle: 'short',
    }).format(new Date(value));
}
</script>

<template>
    <FormPage
        :title="product.name"
        description="Detalhes do produto"
        :back-href="route('products.index')"
        back-label="Voltar à lista"
    >
        <template #actions>
            <ButtonLink :href="route('products.edit', product.id)">
                <template #leading>
                    <svg
                        class="h-5 w-5"
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
                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"
                        />
                    </svg>
                </template>
                Editar
            </ButtonLink>
        </template>

        <dl class="divide-y divide-gray-100">
            <div class="grid gap-1 py-4 first:pt-0 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">Descrição</dt>
                <dd
                    class="text-sm text-gray-900 sm:col-span-2 sm:mt-0"
                >
                    {{ product.description }}
                </dd>
            </div>
            <div class="grid gap-1 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">Cliente</dt>
                <dd
                    class="text-sm text-gray-900 sm:col-span-2 sm:mt-0"
                >
                    {{ product.customer_name }}
                </dd>
            </div>
            <div class="grid gap-1 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">Preço</dt>
                <dd
                    class="text-sm text-gray-900 sm:col-span-2 sm:mt-0"
                >
                    {{ formatMoney(product.price) }}
                </dd>
            </div>
            <div class="grid gap-1 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">Quantidade</dt>
                <dd
                    class="text-sm text-gray-900 sm:col-span-2 sm:mt-0"
                >
                    {{ product.amount }}
                </dd>
            </div>
            <div class="grid gap-1 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">Total</dt>
                <dd
                    class="text-sm font-semibold text-gray-900 sm:col-span-2 sm:mt-0"
                >
                    {{ formatMoney(product.total) }}
                </dd>
            </div>
            <div class="grid gap-1 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">
                    Cadastrado em
                </dt>
                <dd
                    class="text-sm text-gray-900 sm:col-span-2 sm:mt-0"
                >
                    {{ formatDateTime(product.created_at) }}
                </dd>
            </div>
            <div class="grid gap-1 py-4 last:pb-0 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">
                    Atualizado em
                </dt>
                <dd
                    class="text-sm text-gray-900 sm:col-span-2 sm:mt-0"
                >
                    {{ formatDateTime(product.updated_at) }}
                </dd>
            </div>
        </dl>
    </FormPage>
</template>

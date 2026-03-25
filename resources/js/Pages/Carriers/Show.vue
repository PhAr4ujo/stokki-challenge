<script setup>
import ButtonLink from '@/Components/ButtonLink.vue';
import FormPage from '@/Components/FormPage.vue';

defineProps({
    carrier: {
        type: Object,
        required: true,
    },
});

function formatDateTime(value) {
    if (!value) {
        return '—';
    }
    return new Intl.DateTimeFormat('pt-BR', {
        dateStyle: 'short',
        timeStyle: 'short',
    }).format(new Date(value));
}

function formatCnpj(value) {
    const digits = String(value ?? '').replace(/\D+/g, '').slice(0, 14);
    const p1 = digits.slice(0, 2);
    const p2 = digits.slice(2, 5);
    const p3 = digits.slice(5, 8);
    const p4 = digits.slice(8, 12);
    const p5 = digits.slice(12, 14);

    let out = p1;
    if (p2) out += `.${p2}`;
    if (p3) out += `.${p3}`;
    if (p4) out += `/${p4}`;
    if (p5) out += `-${p5}`;
    return out;
}
</script>

<template>
    <FormPage
        :title="carrier.name"
        description="Detalhes da transportadora"
        :back-href="route('carriers.index')"
        back-label="Voltar à lista"
    >
        <template #actions>
            <ButtonLink :href="route('carriers.edit', carrier.id)">
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
                <dt class="text-sm font-medium text-gray-500">CNPJ</dt>
                <dd class="text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                    {{ formatCnpj(carrier.cnpj) }}
                </dd>
            </div>
            <div class="grid gap-1 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">Rua</dt>
                <dd class="text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                    {{ carrier.street }}
                </dd>
            </div>
            <div class="grid gap-1 py-4 first:pt-0 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">Cidade</dt>
                <dd class="text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                    {{ carrier.city }}
                </dd>
            </div>
            <div class="grid gap-1 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                <dd class="text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                    {{ carrier.state }}
                </dd>
            </div>
            <div class="grid gap-1 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">Bairro</dt>
                <dd class="text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                    {{ carrier.neighborhood }}
                </dd>
            </div>
            <div class="grid gap-1 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">Número</dt>
                <dd
                    class="text-sm text-gray-900 sm:col-span-2 sm:mt-0"
                >
                    {{ carrier.residence_number }}
                </dd>
            </div>
            <div class="grid gap-1 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">Complemento</dt>
                <dd
                    class="text-sm text-gray-900 sm:col-span-2 sm:mt-0"
                >
                    {{ carrier.complement }}
                </dd>
            </div>
            <div class="grid gap-1 py-4 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">
                    Cadastrado em
                </dt>
                <dd
                    class="text-sm text-gray-900 sm:col-span-2 sm:mt-0"
                >
                    {{ formatDateTime(carrier.created_at) }}
                </dd>
            </div>
            <div class="grid gap-1 py-4 last:pb-0 sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">
                    Atualizado em
                </dt>
                <dd
                    class="text-sm text-gray-900 sm:col-span-2 sm:mt-0"
                >
                    {{ formatDateTime(carrier.updated_at) }}
                </dd>
            </div>
        </dl>
    </FormPage>
</template>

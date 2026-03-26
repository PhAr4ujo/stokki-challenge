<script setup>
import ButtonLink from '@/Components/ButtonLink.vue';
import IconActionLink from '@/Components/IconActionLink.vue';
import PageIndex from '@/Components/PageIndex.vue';
import PaginationControls from '@/Components/PaginationControls.vue';
import { router } from '@inertiajs/vue3';
import { computed, onUnmounted, ref, watch } from 'vue';

const props = defineProps({
    products: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

const search = ref(props.filters.search ?? '');
const customer = ref(props.filters.customer ?? '');
const perPage = ref(String(props.filters.per_page ?? 15));

watch(
    () => props.filters,
    (f) => {
        search.value = f.search ?? '';
        customer.value = f.customer ?? '';
        perPage.value = String(f.per_page ?? 15);
    },
    { deep: true },
);

let debounceTimer = null;

function runFilter() {
    router.get(
        route('products.index'),
        {
            search: search.value.trim() || undefined,
            customer: customer.value.trim() || undefined,
            per_page: perPage.value,
        },
        {
            preserveState: true,
            replace: true,
            preserveScroll: true,
        },
    );
}

function scheduleFilter() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(runFilter, 400);
}

function onPerPageChange() {
    clearTimeout(debounceTimer);
    runFilter();
}

function onSearchInput(e) {
    search.value = e.target.value;
    scheduleFilter();
}

function onCustomerInput(e) {
    customer.value = e.target.value;
    scheduleFilter();
}

const hasActiveFilters = computed(
    () => !!(search.value.trim() || customer.value.trim()),
);

const clearFiltersUrl = computed(() =>
    route('products.index', { per_page: perPage.value }),
);

onUnmounted(() => clearTimeout(debounceTimer));

function formatMoney(value) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(Number(value));
}

// Add export functionality
function exportCsv() {
    // We just navigate to the backend export route (GET), triggers browser download.
    window.open(route('products.export'), '_blank');
}
</script>

<template>
    <PageIndex title="Produtos">
        <template #actions>
            <ButtonLink :href="route('products.create')">
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
                            d="M12 4.5v15m7.5-7.5h-15"
                        />
                    </svg>
                </template>
                Novo produto
            </ButtonLink>
            <ButtonLink
                type="button"
                variant="secondary"
                class="ml-2"
                @click="exportCsv"
                :href="undefined"
            >
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
                            d="M7 16v1a2 2 0 002 2h6a2 2 0 002-2v-1M12 12v6m0 0l-3-3m3 3l3-3M5 12V7a2 2 0 012-2h10a2 2 0 012 2v5"
                        />
                    </svg>
                </template>
                Exportar dados
            </ButtonLink>
        </template>

        <div class="-mx-6 -mt-6 border-b border-gray-100 bg-gray-50 px-6 py-4">
            <div
                class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between"
            >
                <div
                    class="grid flex-1 gap-4 sm:grid-cols-2 lg:max-w-3xl lg:grid-cols-2"
                >
                    <div>
                        <label
                            for="product-customer"
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Cliente
                        </label>
                        <input
                            id="product-customer"
                            :value="customer"
                            type="text"
                            autocomplete="off"
                            placeholder="Filtrar por cliente"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            @input="onCustomerInput"
                        />
                    </div>
                </div>
                <div class="flex flex-wrap items-end gap-3 sm:justify-end">
                    <div class="min-w-[8rem]">
                        <label
                            for="product-per-page"
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Por página
                        </label>
                        <select
                            id="product-per-page"
                            v-model="perPage"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            @change="onPerPageChange"
                        >
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                    <ButtonLink
                        v-if="hasActiveFilters"
                        :href="clearFiltersUrl"
                        variant="secondary"
                    >
                        Limpar filtros
                    </ButtonLink>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <p
                v-if="products.data.length === 0 && hasActiveFilters"
                class="text-sm text-gray-600"
            >
                Nenhum produto encontrado com os filtros atuais.
            </p>
            <p
                v-else-if="products.data.length === 0"
                class="text-sm text-gray-600"
            >
                Nenhum produto cadastrado ainda.
            </p>

            <div
                v-else
                class="overflow-x-auto rounded-lg border border-gray-200"
            >
                <table
                    class="min-w-full divide-y divide-gray-200 text-left text-sm"
                >
                    <thead
                        class="bg-gray-50 text-xs font-medium uppercase tracking-wide text-gray-500"
                    >
                        <tr>
                            <th class="px-4 py-3">Nome</th>
                            <th class="px-4 py-3">Descrição</th>
                            <th class="px-4 py-3">Cliente</th>
                            <th class="px-4 py-3">Preço</th>
                            <th class="px-4 py-3">Quantidade</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <tr
                            v-for="product in products.data"
                            :key="product.id"
                            class="hover:bg-gray-50"
                        >
                            <td
                                class="whitespace-nowrap px-4 py-3 font-medium text-gray-900"
                            >
                                {{ product.name }}
                            </td>
                            <td
                                class="whitespace-nowrap px-4 py-3 font-medium text-gray-900"
                            >
                                {{ product.description }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ product.customer_name }}
                            </td>
                            <td
                                class="whitespace-nowrap px-4 py-3 text-gray-700"
                            >
                                {{ formatMoney(product.price) }}
                            </td>
                            <td
                                class="whitespace-nowrap px-4 py-3 text-gray-700"
                            >
                                {{ product.amount }}
                            </td>
                            <td
                                class="whitespace-nowrap px-4 py-3 text-gray-700"
                            >
                                {{ formatMoney(product.total) }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-right">
                                <div
                                    class="flex items-center justify-end gap-1.5"
                                >
                                    <IconActionLink
                                        :href="
                                            route('products.show', product.id)
                                        "
                                        title="Visualizar"
                                        variant="view"
                                    />
                                    <IconActionLink
                                        :href="
                                            route('products.edit', product.id)
                                        "
                                        title="Editar"
                                        variant="edit"
                                    />
                                    <IconActionLink
                                        :href="
                                            route(
                                                'products.destroy',
                                                product.id,
                                            )
                                        "
                                        title="Excluir"
                                        variant="destroy"
                                        method="delete"
                                        confirm-message="Tem certeza que deseja excluir este produto?"
                                    />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <PaginationControls
                v-if="products.total > 0"
                class="mt-6"
                :links="products.links"
                :from="products.from"
                :to="products.to"
                :total="products.total"
                :show-nav="products.last_page > 1"
            />
        </div>
    </PageIndex>
</template>

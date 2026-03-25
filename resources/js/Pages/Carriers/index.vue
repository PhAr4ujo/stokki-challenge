<script setup>
import ButtonLink from '@/Components/ButtonLink.vue';
import IconActionLink from '@/Components/IconActionLink.vue';
import PageIndex from '@/Components/PageIndex.vue';
import PaginationControls from '@/Components/PaginationControls.vue';
import { router } from '@inertiajs/vue3';
import { computed, onUnmounted, ref, watch } from 'vue';

const props = defineProps({
    carriers: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

const search = ref(props.filters.name ?? '');
const perPage = ref(String(props.filters.per_page ?? 15));

watch(
    () => props.filters,
    (f) => {
        search.value = f.name ?? '';
        perPage.value = String(f.per_page ?? 15);
    },
    { deep: true },
);

let debounceTimer = null;

function runFilter() {
    router.get(
        route('carriers.index'),
        {
            name: search.value.trim() || undefined,
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

const hasActiveFilters = computed(() => !!search.value.trim());

const clearFiltersUrl = computed(() =>
    route('carriers.index', { per_page: perPage.value }),
);

onUnmounted(() => clearTimeout(debounceTimer));
</script>

<template>
    <PageIndex title="Transportadoras">
        <template #actions>
            <ButtonLink :href="route('carriers.create')">
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
                Nova transportadora
            </ButtonLink>
        </template>

        <div class="-mx-6 -mt-6 border-b border-gray-100 bg-gray-50 px-6 py-4">
            <div
                class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between"
            >
                <div class="flex-1 lg:max-w-sm">
                    <label
                        for="carrier-search"
                        class="mb-1 block text-sm font-medium text-gray-700"
                    >
                        Busca
                    </label>
                    <input
                        id="carrier-search"
                        :value="search"
                        type="search"
                        autocomplete="off"
                        placeholder="Nome"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        @input="onSearchInput"
                    />
                </div>

                <div class="flex flex-wrap items-end gap-3 sm:justify-end">
                    <div class="min-w-[8rem]">
                        <label
                            for="carrier-per-page"
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Por página
                        </label>
                        <select
                            id="carrier-per-page"
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
                v-if="carriers.data.length === 0 && hasActiveFilters"
                class="text-sm text-gray-600"
            >
                Nenhuma transportadora encontrada com os filtros atuais.
            </p>
            <p
                v-else-if="carriers.data.length === 0"
                class="text-sm text-gray-600"
            >
                Nenhuma transportadora cadastrada ainda.
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
                            <th class="px-4 py-3">Cidade</th>
                            <th class="px-4 py-3">Bairro</th>
                            <th class="px-4 py-3">Nº</th>
                            <th class="px-4 py-3">Complemento</th>
                            <th class="px-4 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <tr
                            v-for="carrier in carriers.data"
                            :key="carrier.id"
                            class="hover:bg-gray-50"
                        >
                            <td
                                class="whitespace-nowrap px-4 py-3 font-medium text-gray-900"
                            >
                                {{ carrier.name }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ carrier.city }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ carrier.neighborhood }}
                            </td>
                            <td
                                class="whitespace-nowrap px-4 py-3 text-gray-700"
                            >
                                {{ carrier.residence_number }}
                            </td>
                            <td class="max-w-xs truncate px-4 py-3 text-gray-700">
                                {{ carrier.complement }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-right">
                                <div
                                    class="flex items-center justify-end gap-1.5"
                                >
                                    <IconActionLink
                                        :href="route('carriers.show', carrier.id)"
                                        title="Visualizar"
                                        variant="view"
                                    />
                                    <IconActionLink
                                        :href="route('carriers.edit', carrier.id)"
                                        title="Editar"
                                        variant="edit"
                                    />
                                    <IconActionLink
                                        :href="route('carriers.destroy', carrier.id)"
                                        title="Excluir"
                                        variant="destroy"
                                        method="delete"
                                        confirm-message="Tem certeza que deseja excluir esta transportadora?"
                                    />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <PaginationControls
                v-if="carriers.total > 0"
                class="mt-6"
                :links="carriers.links"
                :from="carriers.from"
                :to="carriers.to"
                :total="carriers.total"
                :show-nav="carriers.last_page > 1"
            />
        </div>
    </PageIndex>
</template>
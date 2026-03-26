<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

import Card from 'primevue/card';
import Chart from 'primevue/chart';

const page = usePage();
const dashboardData = computed(() => page.props.data || {});

const orderCount = computed(() => Number(dashboardData.value.order_count ?? 0));
const totalSold = computed(() => Number(dashboardData.value.total_sold ?? 0));
const averageOrderValue = computed(() => Number(dashboardData.value.average_order_value ?? 0));
const uniqueCustomers = computed(() => Number(dashboardData.value.unique_customers ?? 0));

function getProductAttributesSafe(productObj) {
    if (!productObj) return {};
    return productObj.attributes ?? productObj;
}

const topProducts = computed(() => Array.isArray(dashboardData.value.top_products) ? dashboardData.value.top_products : []);

const mostPopularProductRaw = computed(() => dashboardData.value.most_popular_product ?? {});
const mostPopularProduct = computed(() => getProductAttributesSafe(mostPopularProductRaw.value));

const totalQuantitySold = computed(() => Number(dashboardData.value.total_quantity_sold ?? 0));
const averageItemPrice = computed(() => Number(dashboardData.value.average_item_price ?? 0));
const topCustomers = computed(() => Array.isArray(dashboardData.value.top_customers) ? dashboardData.value.top_customers : []);
const monthlySales = computed(() => Array.isArray(dashboardData.value.monthly_sales) ? dashboardData.value.monthly_sales : []);
const amountOfCarriers = computed(() => Number(dashboardData.value.amount_of_carriers ?? 0));

const dashboardColors = {
    blueDark: '#343a40',
    blue: '#3858ea',
    blueLight: '#cad8fa',
    accent: '#28a745',
    accentSubtle: '#d7ffeb',
    cardBg: '#fff',
    border: '#f2f4f8',
    textDark: '#212529',
    textMuted: '#6c757d',
    warning: '#ffc107',
    warningLight: '#fff3cd',
    info: '#17a2b8',
    infoLight: '#E4F8FB',
    orange: '#ffa600',
    purple: '#6f42c1',
};

const kpiCards = [
    {
        icon: 
            `<svg width="28" height="28" fill="none" stroke="#3858ea" stroke-width="2"><rect x="4" y="4" width="20" height="16" rx="3" /><path d="M10 10h8"/><path d="M10 14h5"/></svg>`,
        title: "Pedidos",
        value: orderCount,
        note: "Total de pedidos",
        class: "p-6",
        color: "kpi-blue"
    },
    {
        icon: 
            `<svg width="28" height="28" fill="none" stroke="#28a745" stroke-width="2"><rect x="5" y="5" width="18" height="18" rx="5" /><path d="M8 15l4 4 8-8" stroke-linecap="round" stroke-linejoin="round" /></svg>`,
        title: "Vendas (R$)",
        value: computed(() => `R$ ${totalSold.value.toFixed(2)}`),
        note: "Total vendido",
        class: "p-6",
        color: "kpi-green"
    },
    {
        icon:
            `<svg width="28" height="28" fill="none" stroke="#3858ea" stroke-width="2"><rect x="8" y="8" width="12" height="12" rx="4"/><path d="M14 11v6"/></svg>`,
        title: "Transportadoras",
        value: amountOfCarriers,
        note: "Transportadoras ativas",
        class: "p-6",
        color: "kpi-indigo"
    },
    {
        icon: 
            `<svg width="28" height="28" fill="none" stroke="#17a2b8" stroke-width="2"><rect x="4" y="8" width="20" height="8" rx="4"/><path d="M8 12h12"/></svg>`,
        title: "Média por Pedido",
        value: computed(() => `R$ ${averageOrderValue.value.toFixed(2)}`),
        note: "Valor médio/pedido",
        class: "p-6 !bg-infoLight",
        color: "text-info"
    },
    {
        icon: 
            `<svg width="28" height="28" fill="none" stroke="#212529" stroke-width="2"><circle cx="14" cy="14" r="10"/><path d="M10 16l4-6 4 6"/></svg>`,
        title: "Média por Item",
        value: computed(() => `R$ ${averageItemPrice.value.toFixed(2)}`),
        note: "Preço médio/item",
        class: "p-6 !bg-gray-100",
        color: "text-gray-900"
    }
];

const mostPopularProductName = computed(() => mostPopularProduct.value?.name ?? '-');
const mostPopularProductQty = computed(() => Number(mostPopularProduct.value?.total_quantity ?? 0));

const top5ProductLabels = computed(() =>
    topProducts.value.slice(0, 5).map(p => p.name)
);
const top5ProductData = computed(() =>
    topProducts.value.slice(0, 5).map(p => Number(p.total_sold ?? 0))
);
const productShareChartData = ref({
    labels: top5ProductLabels.value,
    datasets: [
        {
            data: top5ProductData.value,
            backgroundColor: [
                dashboardColors.accent,
                dashboardColors.blue,
                dashboardColors.orange,
                dashboardColors.purple,
                dashboardColors.blueLight
            ],
            borderWidth: 2,
            borderColor: dashboardColors.cardBg,
            hoverOffset: 8
        }
    ]
});

watch([top5ProductLabels, top5ProductData], ([labels, data]) => {
    productShareChartData.value.labels = labels;
    productShareChartData.value.datasets[0].data = data;
}, { immediate: true });

const salesLeaderboardBarData = ref({
    labels: topCustomers.value.map(c => c.customer_name),
    datasets: [
        {
            label: 'Top 5 Clientes (R$)',
            data: topCustomers.value.map(c => Number(c.total_spent)),
            backgroundColor: [
                dashboardColors.blue, dashboardColors.purple, dashboardColors.info, dashboardColors.accent, dashboardColors.orange
            ],
            borderRadius: 8,
            maxBarThickness: 30,
        }
    ]
});

watch(topCustomers, (customers) => {
    salesLeaderboardBarData.value.labels = customers.map(c => c.customer_name);
    salesLeaderboardBarData.value.datasets[0].data = customers.map(c => Number(c.total_spent));
});

const ordersCarriersBarData = ref({
    labels: ['Pedidos', 'Transportadoras'],
    datasets: [
        {
            label: 'Totais',
            backgroundColor: [dashboardColors.blue, dashboardColors.info],
            borderRadius: 8,
            categoryPercentage: 0.5,
            barPercentage: 0.7,
            data: [orderCount.value, amountOfCarriers.value],
            maxBarThickness: 30,
        }
    ]
});
watch([orderCount, amountOfCarriers], () => {
    ordersCarriersBarData.value.datasets[0].data = [orderCount.value, amountOfCarriers.value];
});

const areaChartData = ref({
    labels: monthlySales.value.map(m => m.month),
    datasets: [
        {
            label: 'Total Vendido',
            data: monthlySales.value.map(m => Number(m.total_sold)),
            fill: true,
            borderColor: dashboardColors.accent,
            backgroundColor: 'rgba(40, 167, 69, 0.08)'
        }
    ]
});
watch(monthlySales, (sales) => {
    areaChartData.value.labels = sales.map(m => m.month);
    areaChartData.value.datasets[0].data = sales.map(m => Number(m.total_sold));
});

const sharedChartOptions = {
    plugins: {
        legend: {
            display: true,
            position: 'bottom',
            labels: {
                color: dashboardColors.textMuted,
                font: { family: 'Inter, sans-serif', size: 14 }
            }
        },
        tooltip: {
            mode: 'index',
            intersect: false,
            backgroundColor: dashboardColors.blue,
            titleColor: '#fff',
            bodyColor: '#fff',
            borderColor: dashboardColors.cardBg
        }
    },
    responsive: true,
};

const areaChartOptions = {
    ...sharedChartOptions,
    elements: {
        line: { tension: 0.3 }
    },
    scales: {
        x: {
            ticks: {
                color: dashboardColors.textMuted
            },
            grid: {
                color: dashboardColors.border
            }
        },
        y: {
            beginAtZero: true,
            ticks: {
                color: dashboardColors.textMuted,
                callback: (value) => `R$ ${value}`,
            },
            grid: {
                color: dashboardColors.border
            }
        }
    }
};

const leaderboardBarChartOptions = {
    ...sharedChartOptions,
    plugins: {
        ...sharedChartOptions.plugins,
        legend: { display: false }
    },
    scales: {
        x: {
            ticks: {
                color: dashboardColors.textMuted
            },
            grid: {
                display: false
            }
        },
        y: {
            beginAtZero: true,
            ticks: {
                color: dashboardColors.textMuted,
                callback: (value) => `R$ ${value}`,
                precision: 2
            },
            grid: {
                color: dashboardColors.border
            }
        }
    }
};

const miniCard = (icon, label, value, extra = null) => ({
    icon, label, value, extra
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center space-x-2">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 15l3-3 5 5" />
                </svg>
                <h2 class="text-2xl font-semibold leading-tight text-gray-800 tracking-tight">Painel de Negócios</h2>
            </div>
        </template>

        <div class="bg-gray-50 min-h-screen py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <Card v-for="(kpi, i) in kpiCards" :key="i" class="kpi-card border-none" :class="kpi.class">
                        <template #title>
                            <div class="flex items-center gap-2 text-sm font-semibold uppercase tracking-tight" v-html="kpi.icon"></div>
                            <div :class="`mt-1 ${kpi.color} text-xs`">{{ kpi.title }}</div>
                        </template>
                        <template #content>
                            <div class="text-3xl font-bold mb-2" :class="kpi.color">
                                <span v-if="typeof kpi.value === 'function' || typeof kpi.value === 'object' && kpi.value.value !== undefined">
                                    {{ typeof kpi.value === 'function' ? kpi.value() : kpi.value.value }}
                                </span>
                                <span v-else>
                                    {{ kpi.value }}
                                </span>
                            </div>
                            <div class="font-medium text-gray-500 text-xs">{{ kpi.note }}</div>
                        </template>
                    </Card>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <Card
                        v-if="topProducts.length > 0"
                        class="kpi-card border-none !bg-green-100"
                        :key="topProducts[0].name"
                    >
                        <template #title>
                            <div class="flex p-6 items-center gap-2 text-base font-semibold uppercase tracking-tight text-green-600">
                                <svg class="inline-block" width="28" height="28" viewBox="0 0 28 28" fill="none">
                                    <circle cx="14" cy="14" r="11" stroke="#28a745" stroke-width="2" fill="none" />
                                    <path d="M10 16l4-6 4 6" stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                                </svg>
                                Produto #1
                            </div>
                        </template>
                        <template #content>
                            <div class="font-bold p-3 text-lg text-green-700">
                                {{ topProducts[0].name }}
                            </div>
                            <div class="text-sm text-green-800 p-3">
                                Vendido: R$ {{ Number(topProducts[0].total_sold).toFixed(2) }} / Qtd: {{ Number(topProducts[0].quantity_sold) }}
                            </div>
                        </template>
                    </Card>
                    <Card
                        v-else
                        class="kpi-card border-none !bg-green-100"
                    >
                        <template #content>
                            <div class="font-bold text-lg text-green-700 p-3">
                                -
                            </div>
                            <div class="text-sm text-green-800 p-3">
                                Vendido: R$ 0.00 / Qtd: 0
                            </div>
                        </template>
                    </Card>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded shadow p-0 flex flex-col min-h-[340px]">
                        <h3 class="font-semibold mb-2 text-lg text-gray-700 p-6 pb-2">Top 5 Produtos Vendidos</h3>
                        <div class="flex-1 flex justify-center items-center p-4">
                            <div class="flex items-center justify-center w-full h-full" style="min-height:220px;">
                                <Chart 
                                    type="pie" 
                                    :data="productShareChartData" 
                                    :options="sharedChartOptions" 
                                    style="width:100%;min-height:220px"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded shadow p-0 flex flex-col min-h-[340px] h-full" style="height:100%;">
                        <h3 class="font-semibold mb-2 text-lg text-gray-700 p-6 pb-2">Pedidos vs. Transportadoras</h3>
                        <div class="flex-1 flex items-center justify-center p-4" style="height:100%;">
                            <Chart 
                                type="bar" 
                                :data="ordersCarriersBarData" 
                                :options="leaderboardBarChartOptions"
                                style="width:100%;min-height:220px;flex:1;"
                            />
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded shadow p-6">
                        <h3 class="font-semibold mb-2 text-lg text-gray-700">Top 5 Clientes</h3>
                        <Chart type="bar" :data="salesLeaderboardBarData" :options="leaderboardBarChartOptions" style="max-width:420px" />
                        <div v-if="topCustomers.length" class="mt-3">
                            <ul>
                                <li v-for="(c, i) in topCustomers" :key="i" class="flex justify-between px-2 py-1 border-b last:border-b-0">
                                    <span class="font-medium">{{ c.customer_name }}</span>
                                    <span class="text-primary font-mono">R$ {{ Number(c.total_spent).toFixed(2) }}</span>
                                </li>
                            </ul>
                        </div>
                        <div v-else class="text-gray-400 mt-2">Ainda não há dados de clientes no ranking.</div>
                    </div>
                    <div class="bg-white rounded shadow p-6 flex flex-col min-h-[300px] h-full justify-center">
                        <h3 class="font-semibold mb-2 text-lg text-gray-700">Vendas Mensais</h3>
                        <div class="flex-1 flex flex-col justify-center items-center w-full h-full min-h-[240px]">
                            <Chart
                                v-if="monthlySales.length"
                                type="line"
                                :data="areaChartData"
                                :options="areaChartOptions"
                                style="width:100%;min-height:200px"
                            />
                            <div v-else class="text-gray-400 mt-5">Sem dados de vendas nos últimos meses.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.bg-info { background-color: #17a2b8 !important; }
.bg-success { background-color: #28a745 !important; }
.bg-primary { background-color: #007bff !important; }
.bg-yellow-50 { background-color: #fffbea !important; }
.bg-green-50  { background-color: #e8faed !important; }
.bg-indigo-100 { background-color: #f3f0fc !important; }
.bg-gray-100  { background-color: #f7faff !important; }
.bg-green-100 { background-color: #d7ffeb !important; }
.bg-blue-50   { background-color: #cad8fa !important; }
.bg-purple-50 { background-color: #ede7fb !important; }
.kpi-card {
    background-color: var(--kpicard-bg, #fff);
    border-radius: 12px;
    box-shadow: 0 2px 8px 0 rgba(60,72,90,.02);
}
</style>
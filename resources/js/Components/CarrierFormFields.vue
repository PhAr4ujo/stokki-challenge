<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import FormField from '@/Components/FormField.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    form: {
        type: Object,
        required: true,
    },
    mode: {
        type: String,
        default: 'create',
    },
});

const isEdit = computed(() => props.mode === 'edit');
const addressLocked = ref(true);

function digitsOnly(value) {
    return String(value ?? '').replace(/\D+/g, '');
}

function formatCnpj(digits) {
    const d = digitsOnly(digits).slice(0, 14);
    const p1 = d.slice(0, 2);
    const p2 = d.slice(2, 5);
    const p3 = d.slice(5, 8);
    const p4 = d.slice(8, 12);
    const p5 = d.slice(12, 14);

    let out = p1;
    if (p2) out += `.${p2}`;
    if (p3) out += `.${p3}`;
    if (p4) out += `/${p4}`;
    if (p5) out += `-${p5}`;
    return out;
}

function formatCep(digits) {
    const d = digitsOnly(digits).slice(0, 8);
    const p1 = d.slice(0, 5);
    const p2 = d.slice(5, 8);
    return p2 ? `${p1}-${p2}` : p1;
}

function onCnpjInput(e) {
    props.form.cnpj = digitsOnly(e.target.value).slice(0, 14);
}

const cepLoading = ref(false);
const cepAddressFetched = ref(false);
let cepDebounceTimer = null;

function onCepInput(e) {
    props.form.cep = digitsOnly(e.target.value).slice(0, 8);
}

function unlockAddress() {
    addressLocked.value = false;
    props.form.cep = '';
    props.form.street = '';
    props.form.city = '';
    props.form.state = '';
    props.form.neighborhood = '';
    cepAddressFetched.value = false;
}

const cnpjMasked = computed(() => formatCnpj(props.form.cnpj));
const cepMasked = computed(() => formatCep(props.form.cep));

async function lookupViaCep() {
    const cep = digitsOnly(props.form.cep).slice(0, 8);
    if (cep.length !== 8) return;

    cepLoading.value = true;
    cepAddressFetched.value = false;

    try {
        const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
        const data = await response.json();

        if (data?.erro) throw new Error('invalid');

        props.form.street       = data.logradouro ?? '';
        props.form.city         = data.localidade ?? '';
        props.form.state        = data.uf ?? '';
        props.form.neighborhood = data.bairro ?? '';
        cepAddressFetched.value = true;

        if (props.form.clearErrors) props.form.clearErrors('cep');
    } catch (e) {
        props.form.street       = '';
        props.form.city         = '';
        props.form.state        = '';
        props.form.neighborhood = '';
        cepAddressFetched.value = false;
        props.form.setError?.('cep', 'CEP inválido');
    } finally {
        cepLoading.value = false;
    }
}

watch(
    () => props.form.cep,
    (value) => {
        if (isEdit.value && addressLocked.value) return;

        clearTimeout(cepDebounceTimer);
        cepDebounceTimer = setTimeout(() => {
            if (digitsOnly(value).length === 8) {
                lookupViaCep();
            } else {
                props.form.street       = '';
                props.form.city         = '';
                props.form.state        = '';
                props.form.neighborhood = '';
                cepAddressFetched.value = false;
            }
        }, 400);
    },
);

onMounted(() => {
    if (isEdit.value && props.form.city && props.form.neighborhood) {
        cepAddressFetched.value = true;
    }
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

        <FormField
            label="CNPJ"
            label-for="carrier-cnpj"
            :error="form.errors.cnpj"
        >
            <input
                id="carrier-cnpj"
                :value="cnpjMasked"
                type="text"
                inputmode="numeric"
                maxlength="18"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required
                autocomplete="off"
                placeholder="00.000.000/0000-00"
                @input="onCnpjInput"
            />
        </FormField>

        <FormField
            v-if="!isEdit || !addressLocked"
            label="CEP"
            label-for="carrier-cep"
            :error="form.errors.cep"
        >
            <input
                id="carrier-cep"
                :value="cepMasked"
                type="text"
                inputmode="numeric"
                maxlength="9"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required
                autocomplete="off"
                placeholder="00000-000"
                @input="onCepInput"
            />
            <p
                v-if="cepLoading"
                class="mt-2 text-sm text-gray-600"
            >
                Carregando endereço...
            </p>
        </FormField>

        <FormField
            label="Rua"
            label-for="carrier-street"
            :error="form.errors.street"
        >
            <TextInput
                id="carrier-street"
                v-model="form.street"
                type="text"
                class="block w-full"
                required
                disabled
                autocomplete="street-address"
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
                    disabled
                    autocomplete="address-level2"
                />
            </FormField>

            <FormField
                label="Estado"
                label-for="carrier-state"
                :error="form.errors.state"
            >
                <TextInput
                    id="carrier-state"
                    v-model="form.state"
                    type="text"
                    class="block w-full"
                    required
                    disabled
                    autocomplete="address-level1"
                />
            </FormField>
        </div>

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
                disabled
                autocomplete="off"
            />
        </FormField>

        <div v-if="isEdit && addressLocked">
            <button
                type="button"
                class="shrink-0 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                @click="unlockAddress"
            >
                Alterar endereço
            </button>
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
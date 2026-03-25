<script setup>
import ButtonLink from '@/Components/ButtonLink.vue';
import CarrierFormFields from '@/Components/CarrierFormFields.vue';
import FormPage from '@/Components/FormPage.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    cnpj: '',
    cep: '',
    street: '',     
    city: '',
    state: '',       
    neighborhood: '',
    residence_number: '',
    complement: '',
});

function submit() {
    form.post(route('carriers.store'));
}
</script>

<template>
    <FormPage
        title="Nova transportadora"
        description="Preencha os dados para cadastrar uma transportadora."
        :back-href="route('carriers.index')"
        back-label="Voltar à lista"
    >
        <form
            class="space-y-8"
            @submit.prevent="submit"
        >
            <CarrierFormFields
                :form="form"
                mode="create"
            />

            <div
                class="flex flex-col gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:flex-wrap sm:items-center sm:gap-4"
            >
                <PrimaryButton :disabled="form.processing">
                    Salvar
                </PrimaryButton>
                <ButtonLink
                    :href="route('carriers.index')"
                    variant="secondary"
                >
                    Cancelar
                </ButtonLink>
            </div>
        </form>
    </FormPage>
</template>

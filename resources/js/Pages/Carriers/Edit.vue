<script setup>
import ButtonLink from '@/Components/ButtonLink.vue';
import CarrierFormFields from '@/Components/CarrierFormFields.vue';
import FormPage from '@/Components/FormPage.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    carrier: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    name: props.carrier.name,
    city: props.carrier.city,
    neighborhood: props.carrier.neighborhood,
    residence_number: String(props.carrier.residence_number),
    complement: props.carrier.complement,
});

function submit() {
    form.put(route('carriers.update', props.carrier.id));
}
</script>

<template>
    <FormPage
        title="Editar transportadora"
        description="Atualize os dados da transportadora."
        :back-href="route('carriers.index')"
        back-label="Voltar à lista"
    >
        <form
            class="space-y-8"
            @submit.prevent="submit"
        >
            <CarrierFormFields :form="form" />

            <div
                class="flex flex-col gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:flex-wrap sm:items-center sm:gap-4"
            >
                <PrimaryButton :disabled="form.processing">
                    Atualizar
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

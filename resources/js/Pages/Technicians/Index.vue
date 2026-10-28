<script setup>
import DefaultLayout from '@/Layouts/Default.vue';
import Table from '@/Components/Table.vue';
import { router } from '@inertiajs/vue3';

defineProps({
    technicians: {
        type: Object,
        required: true,
    },
});

function softDelete(selected) {
    selected.forEach(technician => router.delete(route('technicians.destroy', technician.id)));
}
</script>

<template>
    <DefaultLayout>
        <h1 class="font-medium text-3xl mb-10">Listado de Técnicos</h1>

        <section class="border-t-2 mt-2 pt-6">
                <Table
                    empty-message="No hay técnicos registrados."
                    :headers="['Id', 'Nombre', 'Correo', 'Área de Especialidad']"
                    :elements="technicians.data"
                    :allow-create="true" :allow-edit="true" :allow-soft-delete="true" :allow-delete="false"
                    @create="router.get(route('technicians.create'))"
                    @edit="x => router.get(route('technicians.edit', x))"
                    @soft-delete="softDelete"
                >
                    <template #id="{ element }">{{ element.id }}</template>

                    <template #row="{ element }">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ element.user.name }}
                        </th>

                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ element.user.email }}
                        </th>

                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ element.area_of_expertise }}
                        </th>
                    </template>
                </Table>
        </section>
    </DefaultLayout>
</template>

<script setup>
import DefaultLayout from '@/Layouts/Default.vue';
import Table from '@/Components/Table.vue';
import { router } from '@inertiajs/vue3';

defineProps({
    serviceRecords: {
        type: Object,
        default: () => ({ data: [] }),
    },
});
</script>

<template>
    <DefaultLayout>
        <h1 class="font-medium text-3xl mb-10">Listado de Servicios Realizados</h1>

        <section class="border-t-2 mt-2 pt-6">
            <Table
                empty-message="No hay servicios registrados."
                :headers="['Id', 'Vehículo', 'Técnico', 'Servicio', 'Inicio', 'Fin', 'Costo de Mano de Obra']"
                :elements="serviceRecords.data"
            >
                <template #id="{ element }">{{ element.id }}</template>

                <template #row="{ element }">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ element.appointment?.vehicle?.license_plate }}
                    </th>

                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ element.technician?.user?.name }}
                    </th>

                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ element.service_performed }}
                    </th>

                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ element.start_datetime }}
                    </th>

                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ element.end_datetime }}
                    </th>

                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ element.labor_cost }}
                    </th>
                </template>
            </Table>
        </section>
    </DefaultLayout>
</template>

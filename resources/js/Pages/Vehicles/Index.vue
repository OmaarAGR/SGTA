<script setup>
import DefaultLayout from '@/Layouts/Default.vue';
import Table from '@/Components/Table.vue';
import { router, Link } from '@inertiajs/vue3';

const props = defineProps({
    vehicles: {
        type: Array,
        default: () => [],
    },
});

function translateStatus(status) {
    const translations = {
        'scheduled': 'Programada',
        'completed': 'Completada',
        'cancelled': 'Cancelada',
    };
    return translations[status] || status;
}
</script>

<template>
    <DefaultLayout>
        <h1 class="font-medium text-3xl mb-10">Listado de Vehiculos</h1>

        <section class="border-t-2 mt-2 pt-6">
                <Table
                    empty-message="No hay citas registradas."
                    :headers="['Id', 'Placa', 'Año', 'Marca', 'Modelo']"
                    :elements="vehicles.data"
                    allow-create allow-edit :allow-delete="false"
                    @create="router.get(route('vehicles.create'))"
                    @edit="x => router.get(route('vehicles.edit', x))"
                >
                    <template #id="{ element }">{{ element.id }}</template>

                    <template #row="{ element }">
                        <th scope="row" class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ element.license_plate }}
                        </th>

                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ element.year }}
                        </th>

                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ element.make }}
                        </th>

                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ element.model }}
                        </th>
                    </template>
                </Table>



        <div class="flex flex-col items-center mt-5">
            <!-- Help text -->
            <span class="text-sm text-gray-700 dark:text-gray-400">
                Mostrando <span class="font-semibold text-gray-900 dark:text-white">{{ vehicles.from }}</span> a <span class="font-semibold text-gray-900 dark:text-white">{{ vehicles.to }}</span> de <span class="font-semibold text-gray-900 dark:text-white">{{ vehicles.total }}</span> entradas
            </span>
            <!-- Buttons -->
            <div class="inline-flex mt-2 xs:mt-0">
                <button :disabled="!vehicles.prev_page_url" @click="router.get(vehicles.prev_page_url)" class="flex items-center justify-center px-4 h-10 text-base font-medium text-white bg-gray-800 rounded-s hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                    Anterior
                </button>
                <button :disabled="!vehicles.next_page_url" @click="router.get(vehicles.next_page_url)" class="flex items-center justify-center px-4 h-10 text-base font-medium text-white bg-gray-800 border-0 border-s border-gray-700 rounded-e hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                    Siguiente
                </button>
            </div>
        </div>

        </section>
    </DefaultLayout>
</template>

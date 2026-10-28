<script setup>
import DefaultLayout from '@/Layouts/Default.vue';
import Table from '@/Components/Table.vue';
import { router } from '@inertiajs/vue3';

defineProps({
    clients: {
        type: Object,
        required: true,
    },
});

function softDelete(selected) {
    selected.forEach(client => router.delete(route('clients.destroy', client.id)));
}
</script>

<template>
    <DefaultLayout>
        <h1 class="font-medium text-3xl mb-10">Listado de Clientes</h1>

        <section class="border-t-2 mt-2 pt-6">
                <Table
                    empty-message="No hay clientes registrados."
                    :headers="['Id', 'Nombre', 'Correo', 'Teléfono', 'Dirección', 'Ciudad', 'Departamento/Estado', 'País', 'Acciones']"
                    :elements="clients.data"
                    :allow-create="true" :allow-edit="true" :allow-soft-delete="true" :allow-delete="false"
                    @create="router.get(route('clients.create'))"
                    @edit="x => router.get(route('clients.edit', x))"
                    @soft-delete="softDelete"
                >
                    <template #id="{ element }">{{ element.id }}</template>

                    <template #row="{ element }">
                        <th scope="row" class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ element.user.name }}
                        </th>

                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ element.user.email }}
                        </th>

                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ element.phone }}
                        </th>

                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ element.address }}
                        </th>

                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ element.city }}
                        </th>

                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ element.state }}
                        </th>

                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ element.country }}
                        </th>

                        <td class="px-6 py-4">
                        </td>
                    </template>
                </Table>
        </section>
    </DefaultLayout>
</template>

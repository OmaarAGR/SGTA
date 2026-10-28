<script setup>
import DefaultLayout from '@/Layouts/Default.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    client: { type: Object, required: true },
});
</script>

<template>
    <DefaultLayout>
        <h1 class="font-medium text-3xl mb-10">{{ client.user?.name }}</h1>

        <section class="border-t-2 mt-2 pt-6">
            <dl class="max-w-sm space-y-3 mb-8">
                <div><dt class="text-sm text-gray-500">Correo</dt><dd>{{ client.user?.email }}</dd></div>
                <div><dt class="text-sm text-gray-500">Teléfono</dt><dd>{{ client.phone }}</dd></div>
                <div><dt class="text-sm text-gray-500">Dirección</dt><dd>{{ client.address }}</dd></div>
                <div><dt class="text-sm text-gray-500">Ciudad</dt><dd>{{ client.city }}</dd></div>
                <div><dt class="text-sm text-gray-500">Departamento/Estado</dt><dd>{{ client.state }}</dd></div>
                <div><dt class="text-sm text-gray-500">País</dt><dd>{{ client.country }}</dd></div>
            </dl>

            <div class="flex items-center gap-4">
                <Link :href="route('clients.edit', client)" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700">Editar</Link>
                <Link :href="route('clients.index')" class="text-sm text-gray-600 hover:underline dark:text-gray-400">Volver</Link>
            </div>
        </section>
    </DefaultLayout>
</template>

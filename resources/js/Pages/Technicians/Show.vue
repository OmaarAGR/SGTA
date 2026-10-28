<script setup>
import DefaultLayout from '@/Layouts/Default.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    technician: { type: Object, required: true },
});
</script>

<template>
    <DefaultLayout>
        <h1 class="font-medium text-3xl mb-10">{{ technician.user?.name }}</h1>

        <section class="border-t-2 mt-2 pt-6">
            <dl class="max-w-sm space-y-3 mb-8">
                <div><dt class="text-sm text-gray-500">Correo</dt><dd>{{ technician.user?.email }}</dd></div>
                <div><dt class="text-sm text-gray-500">Área de especialidad</dt><dd>{{ technician.area_of_expertise }}</dd></div>
                <div><dt class="text-sm text-gray-500">Servicios realizados</dt><dd>{{ technician.service_records?.length ?? 0 }}</dd></div>
            </dl>

            <div class="flex items-center gap-4">
                <Link :href="route('technicians.edit', technician)" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700">Editar</Link>
                <Link :href="route('technicians.index')" class="text-sm text-gray-600 hover:underline dark:text-gray-400">Volver</Link>
            </div>
        </section>
    </DefaultLayout>
</template>

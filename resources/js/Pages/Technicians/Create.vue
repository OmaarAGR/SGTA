<script setup>
import DefaultLayout from '@/Layouts/Default.vue';
import FormField from '@/Components/FormField.vue';
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    area_of_expertise: 'Mecánico',
});
</script>

<template>
    <DefaultLayout>
        <h1 class="font-medium text-3xl mb-10">Crear Técnico</h1>

        <section class="border-t-2 mt-2 pt-6">
            <form @submit.prevent="form.post(route('technicians.store'))" class="max-w-sm">
                <FormField id="name" label="Nombre completo" v-model="form.name" :error="form.errors.name" required />
                <FormField id="email" label="Correo electrónico" type="email" v-model="form.email" :error="form.errors.email" required />
                <FormField id="password" label="Contraseña" type="password" v-model="form.password" :error="form.errors.password" required />
                <FormField id="password_confirmation" label="Confirme la contraseña" type="password" v-model="form.password_confirmation" :error="form.errors.password_confirmation" required />
                <FormField id="area_of_expertise" label="Área de especialidad" v-model="form.area_of_expertise" :error="form.errors.area_of_expertise" required />

                <div class="flex items-center gap-4">
                    <button type="submit" :disabled="form.processing" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Crear</button>
                    <Link :href="route('technicians.index')" class="text-sm text-gray-600 hover:underline dark:text-gray-400">Cancelar</Link>
                </div>
            </form>
        </section>
    </DefaultLayout>
</template>

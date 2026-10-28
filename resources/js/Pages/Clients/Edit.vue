<script setup>
import DefaultLayout from '@/Layouts/Default.vue';
import FormField from '@/Components/FormField.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    client: { type: Object, required: true },
});

const form = useForm({
    name: props.client.user?.name ?? '',
    email: props.client.user?.email ?? '',
    phone: props.client.phone ?? '',
    address: props.client.address ?? '',
    city: props.client.city ?? '',
    state: props.client.state ?? '',
    country: props.client.country ?? '',
});
</script>

<template>
    <DefaultLayout>
        <h1 class="font-medium text-3xl mb-10">Editar Cliente</h1>

        <section class="border-t-2 mt-2 pt-6">
            <form @submit.prevent="form.put(route('clients.update', client))" class="max-w-sm">
                <FormField id="name" label="Nombre completo" v-model="form.name" :error="form.errors.name" required />
                <FormField id="email" label="Correo electrónico" type="email" v-model="form.email" :error="form.errors.email" required />
                <FormField id="phone" label="Teléfono" v-model="form.phone" :error="form.errors.phone" />
                <FormField id="address" label="Dirección" v-model="form.address" :error="form.errors.address" />
                <FormField id="city" label="Ciudad" v-model="form.city" :error="form.errors.city" />
                <FormField id="state" label="Departamento/Estado" v-model="form.state" :error="form.errors.state" />
                <FormField id="country" label="País" v-model="form.country" :error="form.errors.country" />

                <div class="flex items-center gap-4">
                    <button type="submit" :disabled="form.processing" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Guardar</button>
                    <Link :href="route('clients.index')" class="text-sm text-gray-600 hover:underline dark:text-gray-400">Cancelar</Link>
                </div>
            </form>
        </section>
    </DefaultLayout>
</template>

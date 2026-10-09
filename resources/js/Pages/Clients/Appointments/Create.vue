<script setup>
import DefaultLayout from '@/Layouts/Default.vue';
import FormError from '@/Components/FormError.vue';
import { computed } from 'vue';
import { usePage, useForm } from '@inertiajs/vue3';
import { FwbSelect } from 'flowbite-vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
const form = useForm({
    vehicle_id: '',
    date: '',
    time: '',
});

const props = defineProps({
    vehicles: {
        type: Array,
        default: () => [],
    },
});

const vehiclesSelect = props.vehicles.map(x => ({ value: x.id, name: `${x.license_plate} ${x.year} ${x.make} ${x.model}` }));
</script>

<template>
    <DefaultLayout>
        <div class="py-8 px-4 mx-auto max-w-screen-lg text-center lg:py-8">
            <h1 class="mb-8 text-3xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl lg:text-5xl dark:text-white">Agendar cita</h1>

            <form @submit.prevent="form.post(route('clients.appointments.store', user.client))" class="max-w-lg mx-auto text-left">
                <FormError :error="form.errors?.vehicle_id" />
                <div class="relative w-full mb-5">
                    <fwb-select
                        v-model="form.vehicle_id"
                        :options="vehiclesSelect"
                        label="Vehículo"
                        placeholder="Seleccione un vehículo"
                        required
                    />
                </div>

                <FormError :error="form.errors?.date" />
                <label for="default-datepicker" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha</label>
                <div class="relative mb-5">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                        </svg>
                    </div>

                    <input datepicker datepicker-autohide datepicker-format="dd/mm/yyyy" id="default-datepicker" type="text" v-model="form.date" @change="form.date = $event.target.value" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="dd/mm/yyyy" required />
                </div>

                <FormError :error="form.errors?.time" />
                <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hora</label>
                <div class="relative mb-5">
                    <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                        </svg>
                    </div>

                    <input type="time" id="time" v-model="form.time" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="09:00" max="16:00" value="00:00" required />
                </div>

                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Guardar cita</button>
            </form>
        </div>
    </DefaultLayout>
</template>

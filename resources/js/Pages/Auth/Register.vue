<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import FormError from '@/Components/FormError.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});
</script>

<template>
    <main class="bg-white border-gray-200 dark:bg-gray-900">
        <div class="flex items-center justify-center h-screen">
            <div class="w-full max-w-md">
                <div class="mb-4 text-center">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Registrarse</h1>
                    <p class="text-gray-500 dark:text-gray-400">Ingrese sus credenciales a continuación</p>
                </div>

                <form @submit.prevent="form.post(route('register'))" class="max-w-sm mx-auto">
                    <div class="mb-5">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre completo</label>
                        <FormError :error="form.errors?.name" />
                        <input v-model="form.name" type="text" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required />
                    </div>
                    <div class="mb-5">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Correo electrónico</label>
                        <FormError :error="form.errors?.email" />
                        <input v-model="form.email" type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" placeholder="name@domain.com" required />
                    </div>
                    <div class="mb-5">
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contraseña</label>
                        <FormError :error="form.errors?.password" />
                        <input v-model="form.password" type="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required />
                    </div>
                    <div class="mb-5">
                        <label for="password-confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirme su contraseña</label>
                        <FormError :error="form.errors?.password_confirmation" />
                        <input v-model="form.password_confirmation" type="password" id="password-confirmation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required />
                    </div>

                    <div class="flex items-start mb-8">
                        <span class="text-sm italic font-medium text-gray-900 dark:text-gray-300">Haciendo click en "Crear cuenta", usted acepta nuestros Términos y Condiciones de Servicio.</span>
                    </div>

                    <Link :href="route('login')" class="my-2.5 float-right text-blue-600 text-sm hover:underline dark:text-blue-500">Ya tengo una cuenta</Link>

                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Crear cuenta</button>
                </form>
            </div>
        </div>
    </main>
</template>

<script setup>
import FormError from '@/Components/FormError.vue';

defineProps({
    id: { type: String, required: true },
    label: { type: String, required: true },
    type: { type: String, default: 'text' },
    error: String,
    required: Boolean,
});

const model = defineModel();
</script>

<template>
    <div class="mb-5">
        <label :for="id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ label }}</label>
        <FormError :error="error" />
        <input v-model="model" :type="type" :id="id" :required="required" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
    </div>
</template>

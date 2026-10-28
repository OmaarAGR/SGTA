<script setup>
import DefaultLayout from '@/Layouts/Default.vue';
import VehicleItem from '@/Components/VehicleItem.vue';

defineProps({
    vehicles: {
        type: Array,
        default: () => [],
    }
});
</script>

<template>
    <DefaultLayout>
        <h1 class="font-bold text-3xl mb-10">Listado de Vehículos</h1>

        <VehicleItem v-for="vehicle in vehicles" :key="vehicle.id" :vehicle="vehicle"></VehicleItem>
    </DefaultLayout>
</template>

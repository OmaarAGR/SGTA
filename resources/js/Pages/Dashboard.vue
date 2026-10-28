<script setup>
import DefaultLayout from '@/Layouts/Default.vue';
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <DefaultLayout>
        <div v-if="user.role === 'client'">
            <div v-if="user.client.appointments_count > 0">
                <h1 class="font-bold text-3xl mb-5">
                    Tus citas pendientes
                </h1>
            </div>
        </div>
    </DefaultLayout>
</template>

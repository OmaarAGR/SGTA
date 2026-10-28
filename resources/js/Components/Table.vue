<script setup>
import { ref, computed, watch } from 'vue'

defineEmits(['search', 'create', 'softDelete', 'delete', 'edit'])

const props = defineProps({
    headers: { type: Array, required: true },
    elements: { type: Array, required: true },
    emptyMessage: { type: String, required: true },
    enableSearch: Boolean,
    allowCreate: Boolean,
    allowEdit: Boolean,
    allowSoftDelete: Boolean,
    softDeleteText: { type: String, default: 'Activar/Desactivar Selección'},
    allowDelete: Boolean,
    deleteText: { type: String, default: 'Eliminar Selección' },
})

const searchQuery = defineModel('searchQuery')

const allSelected = ref(false)
function allSelectedUpdate(evt) {
    allSelected.value = evt.target.checked
    props.elements.forEach(x => x.selected = allSelected.value)
}

const selectedElements = computed(() => props.elements.filter(x => x.selected))
watch(selectedElements, selectedElements =>
    allSelected.value = selectedElements.length === props.elements.length
)
</script>

<template>
    <div class="relative overflow-x-auto min-h-[150px]">
        <div class="flex items-center justify-between flex-column flex-wrap md:flex-row space-y-4 md:space-y-0 pb-4">
            <div v-if="allowCreate || allowSoftDelete || allowDelete">
                <button id="dropdownActionButton" data-dropdown-toggle="dropdownAction" class="inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600" type="button">
                    <span class="sr-only">Botón de acciones</span>
                    Acciones
                    <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>

                <div id="dropdownAction" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                    <div v-if="allowCreate" class="py-1">
                        <a @click="$emit('create')" href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Crear</a>
                    </div>
                    <div v-if="elements.length">
                        <div v-if="allowSoftDelete" class="py-1">
                            <a @click="$emit('softDelete', selectedElements)" href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">{{ softDeleteText }}</a>
                        </div>
                        <div v-if="allowDelete" class="py-1">
                            <a @click="$emit('delete', selectedElements)" href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">{{ deleteText }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="enableSearch" :class="{ 'mx-auto': !allowCreate && !allowSoftDelete && !allowDelete }">
                <label for="table-search" class="sr-only">Buscar</label>
                <div class="relative">
                    <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input type="text" id="table-search-users" class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :class="{ 'w-screen max-w-screen-sm': !allowCreate && !allowSoftDelete && !allowDelete }" placeholder="Buscar" v-model="searchQuery">
                </div>
            </div>
        </div>
        <div v-if="elements.length > 0">
            <table class="w-full px-6 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th v-if="allowSoftDelete || allowDelete" scope="col" class="p-4">
                            <div class="flex items-center">
                                <input id="checkbox-all-search" type="checkbox" :checked="allSelected" @input="allSelectedUpdate" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checkbox-all-search" class="sr-only">checkbox</label>
                            </div>
                        </th>
                        <th v-for="header in headers" scope="col" :class="{ 'px-2': header == 'Id', 'px-6': header != 'Id' }" class="py-3">{{ header }}</th>
                        <th v-if="allowEdit" scope="col" class="px-6 py-3">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="element in elements" :key="element.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td v-if="allowSoftDelete || allowDelete" class="w-4 p-4">
                            <div class="flex items-center">
                                <input id="checkbox-table-search-1" type="checkbox" v-model="element.selected" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                            </div>
                        </td>

                        <th scope="row" class="px-2 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <slot name="id" :element="element" />
                        </th>

                        <slot name="row" :element="element" />

                        <td v-if="allowEdit" class="px-6 py-4">
                            <a @click="$emit('edit', element.id)" type="button" class="font-medium text-blue-600 dark:text-blue-500 cursor-pointer hover:underline">Editar</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-else>
            <p class="text-lg text-center">{{ emptyMessage }}</p>
        </div>
    </div>
</template>

<template>
<AuthenticatedLayout>
    <template #header>
        <div class="flex items-center space-x-4">
            <Link :href="route('user.requests.index')" class="text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </Link>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Request</h2>
        </div>
    </template>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <!-- Tipo richiesta -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo richiesta</label>
                        <select v-model="requestType" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleziona tipo</option>
                            <option value="existing">Oggetti esistenti</option>
                            <option value="to_order">Oggetti da ordinare</option>
                        </select>
                    </div>

                    <!-- Form condizionali -->
                    <ExistingItemsForm 
                        v-if="requestType === 'existing'" 
                        :items="items" 
                        v-model="form.items" 
                    />
                    <NewItemsForm 
                        v-if="requestType === 'to_order'" 
                        v-model="form.items" 
                    />

                    <!-- Note / Descrizione -->
                    <div class="mt-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Note</label>
                        <textarea id="notes" 
                                  v-model="form.notes" 
                                  rows="4"
                                  placeholder="Inserisci eventuali note..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none">
                        </textarea>
                    </div>

                    <!-- Azioni form -->
                    <div class="flex justify-end space-x-4 mt-6">
                        <Link :href="route('user.requests.index')" class="px-4 py-2 bg-gray-100 rounded-md">Cancel</Link>
                        <button type="button" @click="submit" :disabled="processing" class="px-4 py-2 bg-blue-600 text-white rounded-md">
                            {{ processing ? 'Salvataggio...' : 'Crea Richiesta' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</AuthenticatedLayout>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ExistingItemsForm from './ExistingItemsForm.vue';
import NewItemsForm from './NewItemsForm.vue';

const props = defineProps({
    items: Array
});

const processing = ref(false);
const requestType = ref('');

const form = reactive({
    items: [],
    notes: ''
});

const submit = () => {
    processing.value = true;
    router.post(route('user.requests.store'), form, {
        onFinish: () => { processing.value = false; }
    });
};
</script>

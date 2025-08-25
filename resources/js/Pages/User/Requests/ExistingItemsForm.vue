<template>
    <div>
        <div v-for="(item, index) in modelValue" :key="index" class="mb-4 border p-3 rounded-md space-y-2">
            <select v-model="item.id" @change="checkIfItemSelected" 
                    :class="['w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500']"
                    :required="required">
                <option value="">{{ required ? 'Seleziona oggetto *' : 'Seleziona oggetto' }}</option>
                <option v-for="o in items" :key="o.id" :value="o.id">{{ o.name }}</option>
            </select>

            <input v-if="item.isNewItem" type="text" v-model="item.name" 
                   :placeholder="required ? 'Nome nuovo oggetto *' : 'Nome nuovo oggetto'"
                   class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500"
                   :required="required && item.isNewItem" />

            <input type="number" v-model="item.quantity" min="1" 
                   :placeholder="required ? 'Quantità *' : 'Quantità'"
                   class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" 
                   :required="required" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4"> 
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Inizio{{ required ? ' *' : '' }}
                    </label>
                    <input type="date" v-model="item.needed_from" 
                           class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500"
                           :required="required">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Fine{{ required ? ' *' : '' }}
                    </label>
                    <input type="date" v-model="item.needed_to" 
                           class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500"
                           :required="required">
                </div>
            </div>

            <button v-if="modelValue.length > 1" type="button" @click="removeItem(index)" class="text-red-500 text-sm">Rimuovi</button>
        </div>

        <button type="button" @click="addItem" class="mt-2 px-3 py-1 bg-blue-600 text-white rounded-md">Aggiungi oggetto</button>
    </div>
</template>

<script setup>
import { watch } from 'vue';

const props = defineProps({
    items: Array,
    modelValue: Array,
    required: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:modelValue', 'selectedItem']);

const addItem = () => {
    props.modelValue.push({ id: '', name: '', quantity: 1, needed_from: '', needed_to: '', isNewItem: false });
    emit('update:modelValue', props.modelValue);
};

const removeItem = (index) => {
    props.modelValue.splice(index, 1);
    emit('update:modelValue', props.modelValue);
    // Ricontrolla dopo la rimozione
    checkIfItemSelected();
};

// Funzione che controlla se almeno un item è selezionato
const checkIfItemSelected = () => {
    const hasSelectedItems = props.modelValue.some(item => item.id && item.id !== '');
    emit('selectedItem', hasSelectedItems);
};

// Inizializzazione
props.modelValue.forEach(item => {
    if (item.isNewItem === undefined) item.isNewItem = false;
    watch(() => item.id, (newVal) => { 
        item.isNewItem = (newVal === 'new');
        checkIfItemSelected(); // Controlla anche qui quando cambia l'ID
    });
});
</script>
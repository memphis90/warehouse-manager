<template>
    <div>
        <div v-for="(item, index) in modelValue" :key="index" class="mb-4 border p-3 rounded-md space-y-2">
            <select v-model="item.id" @change="item.isNewItem = (item.id === 'new')" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                <option value="">Seleziona oggetto</option>
                <option v-for="o in items" :key="o.id" :value="o.id">{{ o.name }}</option>
            </select>

            <input v-if="item.isNewItem" type="text" v-model="item.name" placeholder="Nome nuovo oggetto"
                   class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" />

            <input type="number" v-model="item.quantity" min="1" placeholder="Quantità"
                   class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4"> 
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Inizio</label>
                    <input type="date" v-model="item.needed_from" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fine</label>
                    <input type="date" v-model="item.needed_to" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <button v-if="modelValue.length > 1" type="button" @click="removeItem(index)" class="text-red-500 text-sm">Rimuovi</button>
        </div>

        <button type="button" @click="addItem" class="mt-2 px-3 py-1 bg-blue-600 text-white rounded-md">Aggiungi oggetto</button>
    </div>
</template>

<script setup>
import { reactive, watch } from 'vue';
const props = defineProps({
    items: Array,
    modelValue: Array
});
const emit = defineEmits(['update:modelValue']);

const addItem = () => {
    props.modelValue.push({ id: '', name: '', quantity: 1, needed_from: '', needed_to: '', isNewItem: false });
    emit('update:modelValue', props.modelValue);
};

const removeItem = (index) => {
    props.modelValue.splice(index, 1);
    emit('update:modelValue', props.modelValue);
};

// watch for changes
props.modelValue.forEach(item => {
    if (item.isNewItem === undefined) item.isNewItem = false;
    watch(() => item.id, (newVal) => { item.isNewItem = (newVal === 'new'); });
});
</script>

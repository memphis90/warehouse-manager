<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center space-x-4">
                <Link :href="route('admin.items.show', item.id)" 
                      class="text-gray-600 hover:text-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </Link>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Item: {{ item.name }}
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        
                        <!-- Success Message -->
                        <div v-if="$page.props.flash?.success" 
                             class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ $page.props.flash.success }}
                        </div>

                        <!-- Error Messages -->
                        <div v-if="Object.keys($page.props.errors).length > 0" 
                             class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li v-for="(error, field) in $page.props.errors" :key="field">
                                                {{ error }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form @submit.prevent="submit">
                            <div class="space-y-6">
                                
                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Item Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="name" 
                                           v-model="form.name"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500': $page.props.errors.name }"
                                           placeholder="Enter item name">
                                    <p v-if="$page.props.errors.name" class="mt-1 text-sm text-red-600">
                                        {{ $page.props.errors.name }}
                                    </p>
                                </div>

                                <!-- Description -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Description
                                    </label>
                                    <textarea id="description" 
                                              v-model="form.description"
                                              rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                              :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500': $page.props.errors.description }"
                                              placeholder="Enter item description (optional)">
                                    </textarea>
                                    <p v-if="$page.props.errors.description" class="mt-1 text-sm text-red-600">
                                        {{ $page.props.errors.description }}
                                    </p>
                                </div>

                                <!-- Category -->
                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Category <span class="text-red-500">*</span>
                                    </label>
                                    <select id="category_id" 
                                            v-model="form.category_id"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500': $page.props.errors.category_id }">
                                        <option value="">Select a category</option>
                                        <option v-for="category in categories" :key="category.id" :value="category.id">
                                            {{ category.name }}
                                        </option>
                                    </select>
                                    <p v-if="$page.props.errors.category_id" class="mt-1 text-sm text-red-600">
                                        {{ $page.props.errors.category_id }}
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Current category: <span class="font-medium">{{ item.category?.name || 'No Category' }}</span>
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Quantity -->
                                    <div>
                                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                            Quantity <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" 
                                               id="quantity" 
                                               v-model="form.quantity"
                                               min="0"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500': $page.props.errors.quantity }"
                                               placeholder="0">
                                        <p v-if="$page.props.errors.quantity" class="mt-1 text-sm text-red-600">
                                            {{ $page.props.errors.quantity }}
                                        </p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Current: {{ item.quantity }} units
                                        </p>
                                    </div>

                                    <!-- Status -->
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                            Status <span class="text-red-500">*</span>
                                        </label>
                                        <select id="status" 
                                                v-model="form.status"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500': $page.props.errors.status }">
                                            <option value="">Select status</option>
                                            <option value="available">Available</option>
                                            <option value="in_use">In Use</option>
                                            <option value="maintenance">Maintenance</option>
                                            <option value="retired">Retired</option>
                                        </select>
                                        <p v-if="$page.props.errors.status" class="mt-1 text-sm text-red-600">
                                            {{ $page.props.errors.status }}
                                        </p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Current: <span class="font-medium">{{ formatStatusDisplay(item.status) }}</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Serial Number -->
                                <div>
                                    <label for="serial_number" class="block text-sm font-medium text-gray-700 mb-2">
                                        Serial Number
                                    </label>
                                    <input type="text" 
                                           id="serial_number" 
                                           v-model="form.serial_number"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500': $page.props.errors.serial_number }"
                                           placeholder="Enter serial number (optional)">
                                    <p v-if="$page.props.errors.serial_number" class="mt-1 text-sm text-red-600">
                                        {{ $page.props.errors.serial_number }}
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500">
                                        For tracking individual items (optional)
                                    </p>
                                </div>

                                <!-- Warning if item has active requests -->
                                <div v-if="item.request_items && item.request_items.length > 0" 
                                     class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">
                                                <strong>Warning:</strong> This item has {{ item.request_items.length }} active request(s). 
                                                Changes to quantity or status may affect pending requests.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Form Actions -->
                            <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                                <div class="flex space-x-3">
                                    <Link :href="route('admin.items.show', item.id)" 
                                          class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                        Cancel
                                    </Link>
                                    <Link :href="route('admin.items.index')" 
                                          class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900">
                                        Back to List
                                    </Link>
                                </div>
                                
                                <button type="submit" 
                                        :disabled="processing"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                        :class="{ 'opacity-50 cursor-not-allowed': processing }">
                                    
                                    <svg v-if="processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    
                                    {{ processing ? 'Updating...' : 'Update Item' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    item: {
        type: Object,
        required: true
    },
    categories: {
        type: Array,
        required: true
    }
});

const processing = ref(false);

const form = reactive({
    name: '',
    description: '',
    category_id: '',
    quantity: 0,
    status: '',
    serial_number: ''
});

const formatStatusDisplay = (status) => {
    const statusMap = {
        'available': 'Available',
        'in_use': 'In Use',
        'maintenance': 'Maintenance',
        'retired': 'Retired'
    };
    return statusMap[status] || status;
};

const submit = () => {
    processing.value = true;
    
    router.put(route('admin.items.update', props.item.id), form, {
        onFinish: () => {
            processing.value = false;
        }
    });
};

// Initialize form with item data
onMounted(() => {
    form.name = props.item.name;
    form.description = props.item.description || '';
    form.category_id = props.item.category_id;
    form.quantity = props.item.quantity;
    form.status = props.item.status;
    form.serial_number = props.item.serial_number || '';
});
</script>
<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <Link :href="route('admin.items.index')" 
                          class="text-gray-600 hover:text-gray-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </Link>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Item Details
                    </h2>
                </div>
                <div class="flex space-x-3">
                    <Link :href="route('admin.items.edit', item.id)" 
                          class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Item
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8">
                        
                        <!-- Item Header -->
                        <div class="mb-8">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                                        {{ item.name }}
                                    </h1>
                                    <p v-if="item.description" class="text-lg text-gray-600 mb-4">
                                        {{ item.description }}
                                    </p>
                                    <div class="flex items-center space-x-4">
                                        <span :class="{
                                            'bg-green-100 text-green-800': item.status === 'available',
                                            'bg-yellow-100 text-yellow-800': item.status === 'in_use',
                                            'bg-red-100 text-red-800': item.status === 'maintenance',
                                            'bg-gray-100 text-gray-800': item.status === 'retired'
                                        }" class="inline-flex px-3 py-1 text-sm font-semibold rounded-full">
                                            {{ formatted_status }}
                                        </span>
                                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ item.category?.name || 'No Category' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-3xl font-bold text-blue-600">
                                        {{ item.quantity }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Total Stock
                                    </div>
                                    <div v-if="item.available_quantity !== undefined" class="mt-1">
                                        <div class="text-lg font-semibold text-green-600">
                                            {{ item.available_quantity }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Available
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Item Information Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-900 mb-4">Item Information</h3>
                                <dl class="space-y-3">
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">Item ID:</dt>
                                        <dd class="text-sm font-medium text-gray-900">#{{ item.id }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">Category:</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ item.category?.name || 'No Category' }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">Serial Number:</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ item.serial_number || 'Not specified' }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">Status:</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ formatted_status }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-900 mb-4">Stock & Availability</h3>
                                <dl class="space-y-3">
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">Total Quantity:</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ item.quantity }} units</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">Available:</dt>
                                        <dd class="text-sm font-medium text-green-600">{{ item.available_quantity || item.quantity }} units</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">In Use:</dt>
                                        <dd class="text-sm font-medium text-yellow-600">{{ (item.quantity - (item.available_quantity || item.quantity)) }} units</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">Utilization:</dt>
                                        <dd class="text-sm font-medium text-gray-900">
                                            {{ item.quantity > 0 ? Math.round(((item.quantity - (item.available_quantity || item.quantity)) / item.quantity) * 100) : 0 }}%
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Recent Requests Section -->
                        <div class="border-t border-gray-200 pt-6 mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Requests</h3>
                            <div v-if="item.request_items && item.request_items.length > 0">
                                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="requestItem in item.request_items.slice(0, 5)" :key="requestItem.id">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ requestItem.request?.user?.name || 'Unknown User' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ requestItem.quantity }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ requestItem.needed_from }} - {{ requestItem.needed_to }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        {{ requestItem.request?.status?.name || 'Unknown' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ requestItem.request?.requested_at }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div v-if="item.request_items.length > 5" class="mt-3 text-center">
                                    <span class="text-sm text-gray-500">Showing 5 of {{ item.request_items.length }} requests</span>
                                </div>
                            </div>
                            <div v-else class="text-center py-8 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                <p>No requests for this item yet</p>
                                <p class="text-sm">Request history will appear here once users start requesting this item.</p>
                            </div>
                        </div>

                        <!-- Activity Timestamps -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Activity</h3>
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ formatted_created_at }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Modified</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ formatted_updated_at }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    item: {
        type: Object,
        required: true
    },
    formatted_status: String,
    formatted_created_at: String,
    formatted_updated_at: String,
    utilization_percentage: Number,
});

</script>
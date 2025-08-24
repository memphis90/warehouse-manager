<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Requests Management
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        
                        <!-- Success Message -->
                        <div v-if="$page.props.flash?.success" 
                             class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ $page.props.flash.success }}
                        </div>

                        <!-- Requests Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Items & Dates
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Type
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Overall Period
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Requested Date
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="request in requestsData" :key="request?.id || Math.random()" class="hover:bg-gray-50">                                      
                                        <td class="px-6 py-4">
                                            <div v-if="request.request_items && request.request_items.length > 0">
                                                <div v-for="(requestItem, index) in request.request_items" :key="requestItem.id || index" 
                                                     class="mb-2 last:mb-0 p-2 bg-gray-50 rounded">
                                                    <div class="flex justify-between items-start">
                                                        <div class="flex-1">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ requestItem.item?.name || 'Unknown Item' }}
                                                            </div>
                                                            <div class="text-xs text-gray-600 mt-1">
                                                                Qty: <span class="font-medium">{{ requestItem.quantity || 0 }}</span>
                                                                <span v-if="requestItem.item?.category?.name" class="ml-2">
                                                                    • {{ requestItem.item.category.name }}
                                                                </span>
                                                            </div>
                                                            <div v-if="requestItem.needed_from && requestItem.needed_to" 
                                                                 class="text-xs text-blue-600 mt-1 font-medium">
                                                                📅 {{ formatDate(requestItem.needed_from) }} → {{ formatDate(requestItem.needed_to) }}
                                                            </div>
                                                            <div v-if="requestItem.item && requestItem.item.available_quantity !== undefined" 
                                                                 class="text-xs mt-1">
                                                                <span :class="requestItem.quantity <= requestItem.item.available_quantity ? 'text-green-600' : 'text-red-600'">
                                                                    Available: {{ requestItem.item.available_quantity }}/{{ requestItem.item.quantity }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-else class="text-sm text-gray-500 italic">
                                                No items specified
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span v-if="request.request_type?.name"
                                                  :class="getRequestTypeClass(request.request_type.name)"
                                                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                                {{ request.request_type.name }}
                                            </span>
                                            <span v-else class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                N/A
                                            </span>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div v-if="getRequestDateRange(request).hasValidRange" class="space-y-1">
                                                <div class="text-xs font-medium text-gray-800">
                                                    📊 Request Period:
                                                </div>
                                                <div class="text-xs text-gray-600">
                                                    {{ formatDate(getRequestDateRange(request).minDate) }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    to {{ formatDate(getRequestDateRange(request).maxDate) }}
                                                </div>
                                                <div v-if="getRequestDateRange(request).itemsCount > 1" 
                                                     class="text-xs text-blue-500">
                                                    ({{ getRequestDateRange(request).itemsCount }} items)
                                                </div>
                                            </div>
                                            <div v-else class="text-gray-400 text-xs">
                                                No valid dates
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span v-if="request.status?.name"
                                                  :class="getStatusClass(request.status.name)"
                                                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                                {{ request.status.name }}
                                            </span>
                                            <span v-else class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Unknown Status
                                            </span>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ formatDate(request.requested_at || request.created_at) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Empty State -->
                        <div v-if="!requests || requests.length === 0" class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No requests found</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                No requests have been submitted yet.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    requests: {
        type: [Array, Object],
        default: () => []
    }
});

// Gestione della struttura dati - potrebbe essere un array o un oggetto con data
const requestsData = computed(() => {
    if (!props.requests) return []
    if (Array.isArray(props.requests)) return props.requests
    if (props.requests.data && Array.isArray(props.requests.data)) return props.requests.data
    return []
})

const getStatusClass = (status) => {
    if (!status) return 'bg-gray-100 text-gray-800'
    
    const statusLower = status.toLowerCase()
    if (statusLower.includes('pending') || statusLower.includes('attesa')) {
        return 'bg-yellow-100 text-yellow-800'
    } else if (statusLower.includes('approved') || statusLower.includes('approvato')) {
        return 'bg-green-100 text-green-800'
    } else if (statusLower.includes('rejected') || statusLower.includes('rifiutato')) {
        return 'bg-red-100 text-red-800'
    }
    return 'bg-gray-100 text-gray-800'
}

const getRequestTypeClass = (type) => {
    if (!type) return 'bg-gray-100 text-gray-800'
    
    const typeLower = type.toLowerCase()
    if (typeLower.includes('existing') || typeLower.includes('esistente')) {
        return 'bg-blue-100 text-blue-800'
    }
    return 'bg-purple-100 text-purple-800'
}

const formatDate = (date) => {
    if (!date) return 'N/A'
    return new Date(date).toLocaleDateString('it-IT', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    })
}

const getRequestDateRange = (request) => {
    if (!request.request_items || request.request_items.length === 0) {
        return { hasValidRange: false, minDate: null, maxDate: null, itemsCount: 0 }
    }

    const dates = []
    request.request_items.forEach(item => {
        if (item.needed_from) dates.push(new Date(item.needed_from))
        if (item.needed_to) dates.push(new Date(item.needed_to))
    })

    if (dates.length === 0) {
        return { hasValidRange: false, minDate: null, maxDate: null, itemsCount: request.request_items.length }
    }

    return {
        hasValidRange: true,
        minDate: new Date(Math.min(...dates)),
        maxDate: new Date(Math.max(...dates)),
        itemsCount: request.request_items.length
    }
}
</script>
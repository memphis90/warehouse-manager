<!-- resources/js/Pages/Admin/Requests/Index.vue -->
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

                        <!-- Filters -->
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Filter by Status
                                    </label>
                                    <select v-model="filters.status" 
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">All Statuses</option>
                                        <option v-for="status in requestStatuses" :key="status.id" :value="status.name">
                                            {{ status.name }}
                                        </option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Filter by Type
                                    </label>
                                    <select v-model="filters.type" 
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">All Types</option>
                                        <option v-for="type in requestTypes" :key="type.id" :value="type.name">
                                            {{ type.name }}
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Search User
                                    </label>
                                    <input v-model="filters.user"
                                           type="text" 
                                           placeholder="Search by name or email..."
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- Debug Info (rimuovere in produzione) -->
                        <div v-if="false" class="mb-4 p-4 bg-gray-100 text-xs">
                            <p><strong>Debug Info:</strong></p>
                            <p>Requests type: {{ typeof requests }}</p>
                            <p>Has data property: {{ !!requests.data }}</p>
                            <p>Data is array: {{ Array.isArray(requests.data || requests) }}</p>
                            <p>Data length: {{ (requests.data || requests || []).length }}</p>
                            <div v-if="(requests.data || requests || []).length > 0">
                                <p><strong>First request structure:</strong></p>
                                <pre class="text-xs">{{ JSON.stringify((requests.data || requests)[0], null, 2).substring(0, 800) }}...</pre>
                            </div>
                        </div>

                        <!-- Requests Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            User
                                        </th>
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
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="request in filteredRequests" :key="request.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div v-if="request.user">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ request.user.name || 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ request.user.email || 'N/A' }}
                                                </div>
                                            </div>
                                            <div v-else class="text-sm text-gray-500 italic">
                                                User not available
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-4">
                                            <div v-if="request.request_items && request.request_items.length > 0">
                                                <!-- Mostra tutti gli items con le loro date specifiche -->
                                                <div v-for="(requestItem, index) in request.request_items" :key="requestItem.id || index" 
                                                     class="mb-2 last:mb-0 p-2 bg-gray-50 rounded">
                                                    <div class="flex justify-between items-start">
                                                        <div class="flex-1">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ requestItem.name || 'Unknown Item' }}
                                                            </div>
                                                            <div class="text-xs text-gray-600 mt-1">
                                                                Qty: <span class="font-medium">{{ requestItem.quantity || 0 }}</span>
                                                                <span v-if="requestItem.item?.category" class="ml-2">
                                                                    • {{ requestItem.item.category.name }}
                                                                </span>
                                                            </div>
                                                            <!-- Date specifiche per item -->
                                                            <div v-if="requestItem.needed_from && requestItem.needed_to" 
                                                                 class="text-xs text-blue-600 mt-1 font-medium">
                                                                📅 {{ formatDate(requestItem.needed_from) }} → {{ formatDate(requestItem.needed_to) }}
                                                            </div>
                                                            <!-- Indicatore disponibilità (se hai l'attributo) -->
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
                                            <span v-if="request.request_type"
                                                  :class="getRequestTypeClass(request.request_type.name)"
                                                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                                {{ request.request_type.name }}
                                            </span>
                                            <span v-else class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                N/A
                                            </span>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <!-- Riassunto periodo generale della richiesta -->
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
                                            <span v-if="request.status"
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
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-3">
                                                <button v-if="isPending(request.status?.name)"
                                                        @click="updateStatus(request, 'Approved')" 
                                                        class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50"
                                                        title="Approve Request">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                                
                                                <button v-if="isPending(request.status?.name)"
                                                        @click="updateStatus(request, 'Rejected')" 
                                                        class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50"
                                                        title="Reject Request">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>

                                                <span v-if="!isPending(request.status?.name)" 
                                                      class="text-gray-400 text-xs">
                                                    {{ request.status?.name || 'No actions' }}
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div v-if="requests.links" class="mt-6">
                            <nav class="flex items-center justify-between">
                                <div class="flex-1 flex justify-between sm:hidden">
                                    <Link v-if="requests.prev_page_url" 
                                          :href="requests.prev_page_url" 
                                          class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        Previous
                                    </Link>
                                    <Link v-if="requests.next_page_url" 
                                          :href="requests.next_page_url"
                                          class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        Next
                                    </Link>
                                </div>
                                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                    <div>
                                        <p class="text-sm text-gray-700">
                                            Showing {{ requests.from || 0 }} to {{ requests.to || 0 }} of {{ requests.total || 0 }} results
                                        </p>
                                    </div>
                                    <div>
                                        <span class="relative z-0 inline-flex shadow-sm rounded-md">
                                            <template v-for="(link, index) in requests.links" :key="index">
                                                <Link v-if="link.url && !link.active" 
                                                      :href="link.url"
                                                      class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                                      v-html="link.label">
                                                </Link>
                                                <span v-else-if="link.active" 
                                                      class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600"
                                                      v-html="link.label">
                                                </span>
                                                <span v-else 
                                                      class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-300"
                                                      v-html="link.label">
                                                </span>
                                            </template>
                                        </span>
                                    </div>
                                </div>
                            </nav>
                        </div>

                        <!-- Empty State -->
                        <div v-if="filteredRequests.length === 0" class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No requests found</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ filteredRequests.length === 0 && (filters.status || filters.type || filters.user) 
                                    ? 'No requests match the current filters.' 
                                    : 'No requests have been submitted yet.' }}
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
import Swal from 'sweetalert2'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
    requests: {
        type: Object,
        required: true
    },
    requestStatuses: {
        type: Array,
        default: () => []
    },
    requestTypes: {
        type: Array,
        default: () => []
    }
})

const filters = ref({
    status: '',
    type: '',
    user: ''
})

const filteredRequests = computed(() => {
    try {
        const data = props.requests?.data || props.requests
        
        if (!data || !Array.isArray(data)) {
            console.warn('Requests data is not a valid array:', data)
            return []
        }
        
        return data.filter(request => {
            // Extra safety check
            if (!request || typeof request !== 'object') {
                return false
            }
            
            // Safe status check - multiple fallbacks
            let matchesStatus = true
            if (filters.value.status) {
                matchesStatus = !!(
                    request.status && 
                    typeof request.status === 'object' && 
                    request.status.name && 
                    request.status.name === filters.value.status
                )
            }
            
            // Safe type check - multiple fallbacks
            let matchesType = true
            if (filters.value.type) {
                matchesType = !!(
                    request.request_type && 
                    typeof request.request_type === 'object' && 
                    request.request_type.name && 
                    request.request_type.name === filters.value.type
                )
            }
            
            // Safe user check - multiple fallbacks
            let matchesUser = true
            if (filters.value.user) {
                const searchTerm = filters.value.user.toLowerCase()
                matchesUser = !!(
                    request.user && 
                    typeof request.user === 'object' && (
                        (request.user.name && request.user.name.toLowerCase().includes(searchTerm)) ||
                        (request.user.email && request.user.email.toLowerCase().includes(searchTerm))
                    )
                )
            }
            
            return matchesStatus && matchesType && matchesUser
        })
    } catch (error) {
        console.error('Error in filteredRequests:', error)
        return []
    }
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

const isPending = (status) => {
    if (!status) return false
    
    const statusLower = status.toLowerCase()
    return statusLower.includes('pending') || statusLower.includes('attesa')
}

const updateStatus = (request, newStatusName) => {
    if (!request || !request.id) {
        Swal.fire('Error!', 'Invalid request.', 'error')
        return
    }

    const newStatus = props.requestStatuses.find(s => 
        s.name.toLowerCase() === newStatusName.toLowerCase()
    )
    
    if (!newStatus) {
        Swal.fire('Error!', 'Status not found.', 'error')
        return
    }

    const actionText = newStatusName.toLowerCase() === 'approved' ? 'approve' : 'reject'
    
    Swal.fire({
        title: `${newStatusName} Request`,
        text: `Are you sure you want to ${actionText} this request?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: newStatusName.toLowerCase() === 'approved' ? '#10b981' : '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: newStatusName,
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            router.put(route('admin.requests.update', request.id), { 
                status_id: newStatus.id 
            }, {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire('Updated!', `Request has been ${actionText}ed successfully.`, 'success')
                },
                onError: () => {
                    Swal.fire('Error!', `Failed to ${actionText} request.`, 'error')
                }
            })
        }
    })
}

const getRequestDateRange = (request) => {
    if (!request.request_items || !Array.isArray(request.request_items)) {
        return { hasValidRange: false }
    }

    const validDates = request.request_items
        .filter(item => item.needed_from && item.needed_to)
        .map(item => ({
            from: new Date(item.needed_from),
            to: new Date(item.needed_to)
        }))

    if (validDates.length === 0) {
        return { hasValidRange: false }
    }

    const allFromDates = validDates.map(d => d.from)
    const allToDates = validDates.map(d => d.to)

    const minDate = new Date(Math.min(...allFromDates))
    const maxDate = new Date(Math.max(...allToDates))

    return {
        hasValidRange: true,
        minDate,
        maxDate,
        itemsCount: request.request_items.length
    }
}

const formatDate = (date) => {
    if (!date) return 'N/A'
    
    try {
        return new Date(date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        })
    } catch (e) {
        return 'Invalid Date'
    }
}
</script>
<!-- resources/js/Pages/Admin/Requests/Index.vue -->
<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Requests
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

                        <!-- Requests Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            User
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Items
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Type
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Period
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
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ request.user.name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ request.user.email }}
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-4">
                                            <div v-if="request.request_items && request.request_items.length > 0">
                                                <div v-for="(requestItem, index) in request.request_items.slice(0, 2)" :key="requestItem.id" class="mb-1">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ requestItem.item.name }}
                                                        <span class="text-xs text-gray-500">(qty: {{ requestItem.quantity }})</span>
                                                    </div>
                                                </div>
                                                <div v-if="request.request_items.length > 2" class="text-xs text-gray-500">
                                                    +{{ request.request_items.length - 2 }} more items
                                                </div>
                                            </div>
                                            <div v-else class="text-sm text-gray-500 italic">
                                                No items specified
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="getRequestTypeClass(request.request_type.name)"
                                                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                                {{ request.request_type.name }}
                                            </span>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div v-if="request.request_items && request.request_items.length > 0 && request.request_items[0].needed_from">
                                                <div class="text-xs">
                                                    {{ formatDate(request.request_items[0].needed_from) }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    to {{ formatDate(request.request_items[0].needed_to) }}
                                                </div>
                                            </div>
                                            <div v-else class="text-gray-400">
                                                N/A
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="getStatusClass(request.status.name)"
                                                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                                {{ request.status.name }}
                                            </span>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ formatDate(request.requested_at) }}
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-3">
                                                <Link :href="route('admin.requests.show', request.id)" 
                                                      class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50"
                                                      title="View Request">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </Link>
                                                
                                                <button v-if="isPending(request.status.name)"
                                                        @click="updateStatus(request, 'Approved')" 
                                                        class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50"
                                                        title="Approve Request">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                                
                                                <button v-if="isPending(request.status.name)"
                                                        @click="updateStatus(request, 'Rejected')" 
                                                        class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50"
                                                        title="Reject Request">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
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
                                            Showing {{ requests.from }} to {{ requests.to }} of {{ requests.total }} results
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
                                No requests match the current filters.
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
    const data = props.requests.data || props.requests
    return data.filter(request => {
        const matchesStatus = !filters.value.status || request.status.name === filters.value.status
        const matchesType = !filters.value.type || request.request_type.name === filters.value.type
        const matchesUser = !filters.value.user || 
          request.user.name.toLowerCase().includes(filters.value.user.toLowerCase()) ||
          request.user.email.toLowerCase().includes(filters.value.user.toLowerCase())
        
        return matchesStatus && matchesType && matchesUser
    })
})

const getStatusClass = (status) => {
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
    const typeLower = type.toLowerCase()
    if (typeLower.includes('existing') || typeLower.includes('esistente')) {
        return 'bg-blue-100 text-blue-800'
    }
    return 'bg-purple-100 text-purple-800'
}

const isPending = (status) => {
    const statusLower = status.toLowerCase()
    return statusLower.includes('pending') || statusLower.includes('attesa')
}

const updateStatus = (request, newStatusName) => {
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

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}
</script>
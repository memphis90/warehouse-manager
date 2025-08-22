<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <Link :href="route('admin.users.index')" 
                          class="text-gray-600 hover:text-gray-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </Link>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        User Details
                    </h2>
                </div>
                <div class="flex space-x-3">
                    <Link :href="route('admin.users.edit', user.id)" 
                          class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit User
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8">
                        
                        <!-- User Profile Section -->
                        <div class="flex items-start space-x-6 mb-8">
                            <div class="flex-shrink-0">
                                <div class="h-24 w-24 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-2xl font-bold text-gray-700">
                                        {{ user.name.charAt(0).toUpperCase() }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                                    {{ user.name }}
                                </h1>
                                <div class="flex items-center space-x-4 text-sm text-gray-600 mb-4">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ user.email }}
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a2 2 0 012 2v1a2 2 0 01-2 2H6a2 2 0 01-2-2V9a2 2 0 012-2h2z"></path>
                                        </svg>
                                        Member since {{ formatDate(user.created_at) }}
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span v-for="role in user.roles" :key="role.id"
                                          :class="{
                                              'bg-red-100 text-red-800': role.name === 'admin',
                                              'bg-blue-100 text-blue-800': role.name === 'user'
                                          }"
                                          class="inline-flex px-3 py-1 text-sm font-semibold rounded-full">
                                        {{ role.name.charAt(0).toUpperCase() + role.name.slice(1) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- User Information Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-900 mb-2">Account Information</h3>
                                <dl class="space-y-2">
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">User ID:</dt>
                                        <dd class="text-sm font-medium text-gray-900">#{{ user.id }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">Status:</dt>
                                        <dd class="text-sm font-medium">
                                            <span :class="{
                                                'text-green-600': user.email_verified_at,
                                                'text-yellow-600': !user.email_verified_at
                                            }">
                                                {{ user.email_verified_at ? 'Verified' : 'Unverified' }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">Email Verified:</dt>
                                        <dd class="text-sm font-medium text-gray-900">
                                            {{ user.email_verified_at ? formatDate(user.email_verified_at) : 'Not verified' }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-900 mb-2">Activity</h3>
                                <dl class="space-y-2">
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">Created:</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ formatDate(user.created_at) }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">Last Updated:</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ formatDate(user.updated_at) }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">Total Requests:</dt>
                                        <dd class="text-sm font-medium text-gray-900">0</dd> <!-- TODO: Add when requests are implemented -->
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Roles & Permissions Section -->
                        <div class="border-t border-gray-200 pt-6 mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Roles & Permissions</h3>
                            <div class="space-y-4">
                                <div v-for="role in user.roles" :key="role.id" class="bg-gray-50 p-4 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-medium text-gray-900">{{ role.name.charAt(0).toUpperCase() + role.name.slice(1) }} Role</h4>
                                        <span :class="{
                                            'bg-red-100 text-red-800': role.name === 'admin',
                                            'bg-blue-100 text-blue-800': role.name === 'user'
                                        }" class="px-2 py-1 text-xs font-semibold rounded">
                                            {{ role.name }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        <span v-if="role.name === 'admin'">
                                            Can manage users, items, and approve/reject requests. Has full access to the admin panel.
                                        </span>
                                        <span v-else-if="role.name === 'user'">
                                            Can create requests for items and view their own request history.
                                        </span>
                                        <span v-else>
                                            Custom role with specific permissions.
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity Section (Placeholder) -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h3>
                            <div class="text-center py-8 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                <p>No recent activity to display</p>
                                <p class="text-sm">User activity will appear here once the request system is implemented.</p>
                            </div>
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
    user: {
        type: Object,
        required: true
    }
});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};
</script>
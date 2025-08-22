<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center space-x-4">
                <Link :href="route('admin.users.show', user.id)" 
                      class="text-gray-600 hover:text-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </Link>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit User: {{ user.name }}
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
                                        Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="name" 
                                           v-model="form.name"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500': $page.props.errors.name }"
                                           placeholder="Enter user's full name">
                                    <p v-if="$page.props.errors.name" class="mt-1 text-sm text-red-600">
                                        {{ $page.props.errors.name }}
                                    </p>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" 
                                           id="email" 
                                           v-model="form.email"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500': $page.props.errors.email }"
                                           placeholder="user@example.com">
                                    <p v-if="$page.props.errors.email" class="mt-1 text-sm text-red-600">
                                        {{ $page.props.errors.email }}
                                    </p>
                                </div>

                                <!-- Password -->
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                        New Password
                                        <span class="text-gray-500 font-normal">(leave empty to keep current password)</span>
                                    </label>
                                    <input type="password" 
                                           id="password" 
                                           v-model="form.password"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500': $page.props.errors.password }"
                                           placeholder="Enter new password (optional)">
                                    <p v-if="$page.props.errors.password" class="mt-1 text-sm text-red-600">
                                        {{ $page.props.errors.password }}
                                    </p>
                                </div>

                                <!-- Password Confirmation -->
                                <div v-if="form.password">
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                        Confirm New Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" 
                                           id="password_confirmation" 
                                           v-model="form.password_confirmation"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500': $page.props.errors.password_confirmation }"
                                           placeholder="Confirm new password">
                                    <p v-if="$page.props.errors.password_confirmation" class="mt-1 text-sm text-red-600">
                                        {{ $page.props.errors.password_confirmation }}
                                    </p>
                                </div>

                                <!-- Role -->
                                <div>
                                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                        Role <span class="text-red-500">*</span>
                                    </label>
                                    <select id="role" 
                                            v-model="form.role"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500': $page.props.errors.role }">
                                        <option value="">Select a role</option>
                                        <option v-for="role in roles" :key="role.id" :value="role.name">
                                            {{ role.name.charAt(0).toUpperCase() + role.name.slice(1) }}
                                        </option>
                                    </select>
                                    <p v-if="$page.props.errors.role" class="mt-1 text-sm text-red-600">
                                        {{ $page.props.errors.role }}
                                    </p>
                                </div>

                                <!-- Warning for Self Edit -->
                                <div v-if="user.id === $page.props.auth.user.id" 
                                     class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">
                                                <strong>Warning:</strong> You are editing your own account. 
                                                Be careful when changing your role or password as it may affect your access.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Form Actions -->
                            <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                                <div class="flex space-x-3">
                                    <Link :href="route('admin.users.show', user.id)" 
                                          class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                        Cancel
                                    </Link>
                                    <Link :href="route('admin.users.index')" 
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
                                    
                                    {{ processing ? 'Updating...' : 'Update User' }}
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
import { reactive, ref, computed, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    roles: {
        type: Array,
        required: true
    }
});

const processing = ref(false);

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: ''
});

const submit = () => {
    processing.value = true;
    
    router.put(route('admin.users.update', props.user.id), form, {
        onFinish: () => {
            processing.value = false;
        },
        onError: () => {
            // Reset password fields on error
            form.password = '';
            form.password_confirmation = '';
        },
        onSuccess: () => {
            // Keep password fields empty after successful update
            form.password = '';
            form.password_confirmation = '';
        }
    });
};

// Initialize form with user data
onMounted(() => {
    form.name = props.user.name;
    form.email = props.user.email;
    form.role = props.user.roles.length > 0 ? props.user.roles[0].name : '';
});
</script>
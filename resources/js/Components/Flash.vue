<!-- Flash.vue -->
<template>
    <div v-if="flash" class="mt-2">
        <!-- Success Message -->
        <div
            v-if="flash.success"
            class="p-4 bg-green-100 border-l-4 border-green-500 text-green-700"
            role="alert"
        >
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm">{{ flash.success }}</p>
                </div>
            </div>
        </div>

        <!-- Error Message -->
        <div
            v-if="flash.error"
            class="p-4 bg-red-100 border-l-4 border-red-500 text-red-700"
            role="alert"
        >
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm">{{ flash.error }}</p>
                </div>
            </div>
        </div>

        <!-- Notify Message -->
        <div
            v-if="flash.notify"
            class="p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700"
            role="alert"
        >
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm">{{ flash.notify }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    flash: {
        type: Object,
        default: () => ({})
    }
})
</script>

<script setup>
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

defineProps({
    posts: {
        type: Array,
        required: true
    },
    isAdmin: {
        type: Boolean,
        required: true
    },
    canManage: {
        type: Boolean,
        required: true
    }
});
const emit = defineEmits(['deletePost', 'editPost']);
</script>

<template>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Desktop View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-white">
                <thead class="bg-gray-50">
                <tr>
                    <th class="th-style">Image</th>
                    <th class="th-style">Title</th>
                    <th class="th-style">Description</th>
                    <th v-if="isAdmin" class="th-style">Author</th>
                    <th class="th-style">Actions</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="post in posts" :key="post.id" class="hover:bg-gray-50">
                    <td class="td-style">
                        <img
                            v-if="post.image"
                            :src="post.image"
                            :alt="post.title"
                            class="h-12 w-12 object-cover rounded-lg"
                        />
                        <div
                            v-else
                            class="h-12 w-12 image-placeholder"
                        />
                    </td>
                    <td class="td-style">
                        <div class="text-sm font-medium text-gray-900">{{ post.title }}</div>
                    </td>
                    <td class="td-style">
                        <div class="text-sm text-gray-500">
                            {{ post.description.length > 100
                            ? post.description.substring(0, 100) + '...'
                            : post.description }}
                        </div>
                    </td>
                    <td v-if="isAdmin" class="td-style">
                        <div class="text-sm text-gray-900">{{ post.user.name }}</div>
                    </td>
                    <td class="td-style">
                        <div class="flex items-center gap-2">
                            <SecondaryButton
                                v-if="canManage"
                                @click="emit('editPost', post.id)">
                                Edit
                            </SecondaryButton>
                            <DangerButton
                                v-if="canManage"
                                @click="emit('deletePost', post.id)">
                                Delete
                            </DangerButton>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Mobile View -->
        <div class="md:hidden">
            <div v-for="post in posts" :key="post.id"
                 class="p-4 border-b border-gray-200 hover:bg-gray-50">
                <div class="flex items-start space-x-4">
                    <img
                        v-if="post.image"
                        :src="post.image"
                        :alt="post.title"
                        class="h-16 w-16 object-cover rounded-lg flex-shrink-0"
                    />
                    <div
                        v-else
                        class="h-16 w-16 image-placeholder"
                    />
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-gray-900 mb-1">
                            {{ post.title }}
                        </div>
                        <div class="text-sm text-gray-500 mb-2">
                            {{ post.description.length > 100
                            ? post.description.substring(0, 100) + '...'
                            : post.description }}
                        </div>
                        <div v-if="isAdmin" class="text-sm text-gray-900 mb-3">
                            Author: {{ post.user.name }}
                        </div>
                        <div class="flex items-center gap-2">
                            <SecondaryButton
                                v-if="canManage"
                                @click="emit('editPost', post.id)"
                                class="text-xs px-3 py-1">
                                Edit
                            </SecondaryButton>
                            <DangerButton
                                v-if="canManage"
                                @click="emit('deletePost', post.id)"
                                class="text-xs px-3 py-1">
                                Delete
                            </DangerButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.th-style {
    @apply px-6 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider;
}
.td-style {
    @apply px-6 py-3;
}
.image-placeholder {
    @apply bg-gray-100 rounded-lg;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239CA3AF'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'/%3E%3C/svg%3E");
    background-position: center;
    background-repeat: no-repeat;
    background-size: 50%;
}
</style>

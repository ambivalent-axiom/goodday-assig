<script setup>
import {router, usePage} from "@inertiajs/vue3";
import Pagination from '@/Components/Pagination.vue';
import PostsTable from "@/Components/PostsTable.vue";
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {capitalize, ref} from 'vue';

const props = defineProps({
    posts: {
        type: Object,
        required: true
    },
    type: {
        type: String,
        required: true
    }
});

const showDeleteModal = ref(false);
const postIdToDelete = ref(null);
const deletePost = (id) => {
    postIdToDelete.value = id;
    showDeleteModal.value = true;
};
const editPost = (id) => {
    router.get(route(`${props.type}.edit`, { post: id }));
}
const confirmDelete = () => {
    router.delete(route(`${props.type}.destroy`, postIdToDelete.value));
    showDeleteModal.value = false;
};
const createPost = () => {
    router.get(route(`${props.type}.create`));
}
const hasPermission = (permission) => {
    const user = usePage().props.auth.user
    if (!user || !user.permissions) return false
    return user.permissions.includes(permission)
}
const isAdmin = () => {
    const user = usePage().props.auth.user
    if (!user) return false
    return user.roles.includes('admin')
}
</script>

<template>
    <div>
        <div class="pl-4 bg-white">
            <div class="flex items-center justify-between">
                <h1 class="mt-8 text-2xl font-medium text-gray-900">
                    {{ capitalize(type) }} Posts List
                </h1>
                <PrimaryButton
                    v-if="hasPermission('edit ' + props.type)"
                    class="mr-5"
                    @click="createPost"
                >Create {{ type }} post</PrimaryButton>
            </div>
            <div class="flex bg-white px-4 py-3 border-t border-gray-200 sm:px-6 justify-center">
                <pagination :links="posts.links" :desktopCount="10" :mobile-count="2"  />
            </div>
        </div>

        <PostsTable
            :posts="posts.data"
            :is-admin="isAdmin()"
            :canManage="hasPermission('edit ' + props.type)"
            @delete-post="deletePost"
            @edit-post="editPost"
        />

        <div class="flex bg-white px-4 py-3 border-t border-gray-200 sm:px-6 justify-center">
            <pagination :links="posts.links" :desktopCount="10" :mobile-count="2" />
        </div>

        <Modal :show="showDeleteModal" @close="showDeleteModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">Delete Blog Post</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Are you sure you want to delete this post? This action cannot be undone.
                </p>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="showDeleteModal = false">Cancel</SecondaryButton>
                    <DangerButton class="ml-3" @click="confirmDelete">Delete</DangerButton>
                </div>
            </div>
        </Modal>
    </div>
</template>

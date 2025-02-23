<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Flash from "@/Components/Flash.vue";
import PostList from './Posts/list.vue';
import PostEdit from './Posts/edit.vue';
import Permissions from './Permissions/edit.vue';
import {capitalize, computed} from 'vue';
import { usePage } from '@inertiajs/vue3';
defineProps({
    posts: {type: Object, required: false},
    post: {type: Object, required: false},
    template: {type: String, required: true,},
    mode: {type: String, required: false,},
    type: {type: String, required: true,},
    permissions: {type: Array, required: false},
    roles: {type: Array, required: false},
})
const flash = computed(() => usePage().props.flash || {});
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ capitalize(type) }}
            </h2>
            <Flash :flash="flash" />
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <PostList
                        v-if="template === 'list'"
                        :posts="posts"
                        :type="type"
                    />
                    <PostEdit
                        v-else-if="template === 'edit'"
                        :post="post"
                        :mode="mode"
                        :type="type"
                    />
                    <Permissions
                        v-else-if="template === 'permissions'"
                        :permissions="permissions"
                        :roles="roles"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

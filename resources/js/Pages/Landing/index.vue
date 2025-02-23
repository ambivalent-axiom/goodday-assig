<script setup>
import { ref, onMounted, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import Pagination from '../../Components/Pagination.vue';
import NavLink from "@/Components/NavLink.vue";
import DialogModal from '@/Components/DialogModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PostList from './list.vue';
import FeaturedPosts from './featured.vue';

const props = defineProps({
    posts: {type: Object, required: true,},
    type: {type: String, required: true}
})

const featuredPosts = computed(() => props.posts.data.slice(0, 3));
const remainingPosts = computed(() => props.posts.data.slice(3));

const showingPostModal = ref(false);
const selectedPost = ref(null);

const openPostModal = (post) => {
    selectedPost.value = post;
    showingPostModal.value = true;
};

const closePostModal = () => {
    showingPostModal.value = false;
    selectedPost.value = null;
};
</script>

<template>
    <Head title="Blog Posts" />
    <div class="bg-white py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:mx-0">
                <div class="flex items-center gap-20">
                    <h2 class="text-4xl font-semibold tracking-tight text-pretty text-gray-900 sm:text-5xl">From the {{ type }}</h2>
                    <div class="flex rounded-lg shadow-sm">
                        <NavLink
                            class="px-4 py-1 text-sm font-medium rounded-l-lg"
                            :href="route('landing', 'blog')"
                            :class="type === 'blog' ? 'bg-gray-900 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                        >
                            Blog
                        </NavLink>
                        <NavLink
                            class="px-4 py-1 text-sm font-medium rounded-r-lg"
                            :href="route('landing', 'news')"
                            :class="type === 'news' ? 'bg-gray-900 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                        >
                            News
                        </NavLink>
                    </div>
                </div>
                <p class="mt-2 text-lg/8 text-gray-600">Learn how to grow your business with our expert advice.</p>
            </div>

            <FeaturedPosts
                :posts="featuredPosts"
                @open-post="openPostModal"
            />

            <PostList
                :posts="posts"
                @open-post="openPostModal"
            />

            <div class="mt-8">
                <Pagination :links="posts.links" :desktopCount="10" :mobileCount="2" />
            </div>
        </div>
    </div>

    <!-- Single Post Modal -->
    <DialogModal :show="showingPostModal" @close="closePostModal">
        <template #title>
            <div class="flex items-center gap-4">
                <img v-if="selectedPost?.user?.avatar"
                     :src="selectedPost.user.avatar"
                     :alt="selectedPost.user.name"
                     class="size-10 rounded-full bg-gray-50">
                <div v-else class="size-10 rounded-full bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-500 text-xl">{{ selectedPost?.user?.name?.charAt(0) || '?' }}</span>
                </div>
                <div>
                    <h3 class="text-lg font-medium">{{ selectedPost?.title }}</h3>
                    <p class="text-sm text-gray-500">
                        By {{ selectedPost?.user?.name }} •
                        {{ selectedPost?.created_at ? new Date(selectedPost.created_at).toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    }) : '' }}
                    </p>
                </div>
            </div>
        </template>

        <template #content>
            <div class="space-y-6">
                <img v-if="selectedPost?.image"
                     :src="selectedPost.image"
                     :alt="selectedPost.title"
                     class="w-full rounded-lg object-cover max-h-96">

                <div class="prose max-w-none">
                    <p class="text-gray-600">{{ selectedPost?.description }}</p>
                    <div v-html="selectedPost?.text" class="mt-4"></div>
                </div>
            </div>
        </template>

        <template #footer>
            <SecondaryButton @click="closePostModal">
                Close
            </SecondaryButton>
        </template>
    </DialogModal>
</template>

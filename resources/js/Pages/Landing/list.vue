<script setup>
defineProps({
    posts: {
        type: Object,
        required: true,
    }
});

defineEmits(['open-post']);

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    });
};
</script>

<template>
    <div class="mt-16 border-t border-gray-200 pt-16">
        <div class="space-y-8">
            <article
                v-for="post in posts.data"
                :key="post.id"
                class="flex gap-6 p-6 border border-gray-200 rounded-lg"
            >
                <!-- Image container on the left -->
                <div class="flex-shrink-0 w-48">
                    <img
                        v-if="post.image"
                        :src="post.image"
                        :alt="post.title"
                        class="rounded-lg object-cover w-full aspect-[4/3]"
                    >
                    <div
                        v-else
                        class="rounded-lg bg-gray-200 w-full aspect-[4/3] flex items-center justify-center"
                    >
                        <span class="text-gray-400">No image</span>
                    </div>
                </div>

                <!-- Content container -->
                <div class="flex flex-col flex-grow gap-4">
                    <div class="flex items-center gap-x-4 text-xs">
                        <time :datetime="post.created_at" class="text-gray-500">
                            {{ formatDate(post.created_at) }}
                        </time>
                    </div>

                    <div class="group">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-gray-600">
                            <a
                                @click.prevent="$emit('open-post', post)"
                                href="#"
                                class="hover:text-gray-600 cursor-pointer"
                            >{{ post.title }}</a>
                        </h3>
                        <p class="mt-2 text-sm text-gray-600 line-clamp-2">{{ post.description }}</p>
                    </div>

                    <div class="flex items-center gap-x-4 mt-auto">
                        <div class="text-sm">
                            <p class="font-semibold text-gray-900">{{ post.user?.name }}</p>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
</template>

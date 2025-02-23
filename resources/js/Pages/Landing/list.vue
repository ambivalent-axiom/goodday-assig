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
        <div class="space-y-4">
            <article
                v-for="post in posts.data"
                :key="post.id"
                class="flex items-start gap-4 p-4 border border-gray-200 rounded-lg"
            >
                <!-- Small Image Container -->
                <div class="w-24 flex-shrink-0">
                    <img
                        v-if="post.image"
                        :src="post.image"
                        :alt="post.title"
                        class="rounded-md object-cover w-full aspect-square"
                    >
                    <div
                        v-else
                        class="rounded-md bg-gray-200 w-full aspect-square flex items-center justify-center text-sm text-gray-500"
                    >
                        No image
                    </div>
                </div>

                <!-- Content container -->
                <div class="flex flex-col flex-grow">
                    <div class="text-xs text-gray-500">
                        <time :datetime="post.created_at">{{ formatDate(post.created_at) }}</time>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900">
                        <a
                            @click.prevent="$emit('open-post', post)"
                            href="#"
                            class="hover:text-gray-600 cursor-pointer"
                        >{{ post.title }}</a>
                    </h3>

                    <p class="mt-1 text-sm text-gray-600 line-clamp-2">
                        {{ post.description }}
                    </p>

                    <div class="mt-2 text-sm font-semibold text-gray-900">
                        {{ post.user?.name }}
                    </div>
                </div>
            </article>
        </div>
    </div>
</template>

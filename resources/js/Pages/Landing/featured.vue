<script setup>
defineProps({
    posts: {
        type: Array,
        required: true,
        default: () => []
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

const getUserInitial = (user) => {
    return user?.name?.charAt(0) || '?';
};
</script>

<template>
    <div class="mx-auto mt-10 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-200 pt-10 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-3">
        <article
            v-for="post in posts"
            :key="post.id"
            class="flex max-w-xl flex-col items-start justify-between"
        >
            <div class="relative w-full aspect-[16/9] mb-4">
                <img
                    v-if="post.image"
                    :src="post.image"
                    :alt="post.title"
                    class="rounded-lg object-cover w-full h-full"
                >
            </div>

            <div class="flex items-center gap-x-4 text-xs">
                <time :datetime="post.created_at" class="text-gray-500">
                    {{ formatDate(post.created_at) }}
                </time>
            </div>

            <div class="group relative">
                <h3 class="mt-3 text-lg/6 font-semibold text-gray-900 group-hover:text-gray-600">
                    <a
                        @click.prevent="$emit('open-post', post)"
                        href="#"
                        class="cursor-pointer"
                    >
                        <span class="absolute inset-0"></span>
                        {{ post.title }}
                    </a>
                </h3>
                <p class="mt-5 line-clamp-3 text-sm/6 text-gray-600">
                    {{ post.description }}
                </p>
            </div>

            <div class="relative mt-8 flex items-center gap-x-4">
                <img
                    v-if="post.user?.avatar"
                    :src="post.user.avatar"
                    :alt="post.user.name"
                    class="size-10 rounded-full bg-gray-50"
                >
                <div
                    v-else
                    class="size-10 rounded-full bg-gray-200 flex items-center justify-center"
                >
                    <span class="text-gray-500 text-xl">{{ getUserInitial(post.user) }}</span>
                </div>

                <div class="text-sm/6">
                    <p class="font-semibold text-gray-900">
                        <a href="#">
                            <span class="absolute inset-0"></span>
                            {{ post.user?.name }}
                        </a>
                    </p>
                </div>
            </div>
        </article>
    </div>
</template>

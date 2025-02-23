<script setup>
import { Link } from '@inertiajs/vue3'
import { computed, ref, onMounted, onUnmounted } from 'vue'

const props = defineProps({
    links: {
        type: Array,
        required: true,
    },
    mobileCount: {
        type: Number,
        default: 2,
        validator: (value) => value > 0
    },
    desktopCount: {
        type: Number,
        default: 5,
        validator: (value) => value > 0
    }
})

const isMobile = ref(false)

const updateViewportSize = () => {
    isMobile.value = window.innerWidth < 640 // sm breakpoint in Tailwind
}

onMounted(() => {
    updateViewportSize()
    window.addEventListener('resize', updateViewportSize)
})

onUnmounted(() => {
    window.removeEventListener('resize', updateViewportSize)
})

const visibleLinks = computed(() => {
    if (props.links.length <= 3) return []

    const currentPageIndex = props.links.findIndex(link => link.active)
    const firstLink = props.links[0]
    const lastLink = props.links[props.links.length - 1]
    let middleLinks = []

    // Get the actual page numbers, excluding "Previous" and "Next"
    const pageLinks = props.links.slice(1, -1)

    if (currentPageIndex !== -1) {
        const visibleCount = isMobile.value ? props.mobileCount : props.desktopCount
        const sideLinks = Math.floor((visibleCount - 1) / 2)
        const start = Math.max(1, currentPageIndex - sideLinks)
        const end = Math.min(pageLinks.length, start + visibleCount - 1)

        // Add ellipsis before
        if (start > 1) {
            middleLinks.push({
                url: null,
                label: '...',
                active: false
            })
        }

        // Add the visible page links
        middleLinks = [
            ...middleLinks,
            ...pageLinks.slice(start - 1, end)
        ]

        // Add ellipsis after
        if (end < pageLinks.length) {
            middleLinks.push({
                url: null,
                label: '...',
                active: false
            })
        }
    }

    return [firstLink, ...middleLinks, lastLink]
})
</script>

<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, key) in visibleLinks" :key="key">
                <div v-if="link.url === null"
                     class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                     v-html="link.label"
                />
                <Link v-else
                      :href="link.url"
                      class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-gray-100 focus:border-indigo-500 focus:text-indigo-500"
                      :class="{ 'bg-gray-100': link.active }"
                      v-html="link.label"
                />
            </template>
        </div>
    </div>
</template>

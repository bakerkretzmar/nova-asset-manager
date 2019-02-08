<template>
    <div class="relative h-48 w-48 overflow-hidden rounded-lg mb-4 mr-4 shadow-md">

        <div class="absolute pin opacity z-20">
            <button
                @click.prevent="$emit('remove')"
                class="flex items-center absolute pin-t pin-r text-danger m-3 cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="h-10 w-10 -m-2 svg-shadow fill-current"><path d="M16.24 14.83a1 1 0 0 1-1.41 1.41L12 13.41l-2.83 2.83a1 1 0 0 1-1.41-1.41L10.59 12 7.76 9.17a1 1 0 0 1 1.41-1.41L12 10.59l2.83-2.83a1 1 0 0 1 1.41 1.41L13.41 12l2.83 2.83z"/></svg>
            </button>

            <button
                v-if="index != 0"
                @click.prevent="$emit('move', -1)"
                class="flex items-center absolute pin-b pin-l text-white m-3 cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="h-6 w-6 -m-1 svg-shadow fill-current"><path d="M5.41 11H21a1 1 0 0 1 0 2H5.41l5.3 5.3a1 1 0 0 1-1.42 1.4l-7-7a1 1 0 0 1 0-1.4l7-7a1 1 0 0 1 1.42 1.4L5.4 11z"/></svg>
            </button>

            <button
                v-if="index != length-1"
                @click.prevent="$emit('move', 1)"
                class="flex items-center absolute pin-b pin-r text-white m-3 cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="h-6 w-6 -m-1 svg-shadow fill-current"><path d="M18.59 13H3a1 1 0 0 1 0-2h15.59l-5.3-5.3a1 1 0 1 1 1.42-1.4l7 7a1 1 0 0 1 0 1.4l-7 7a1 1 0 0 1-1.42-1.4l5.3-5.3z"/></svg>
            </button>
        </div>

        <Preview :file="file" />

    </div>
</template>

<script>
import Preview from './FilePreview'

export default {
    props: {
        file: Object,
        index: Number,
        length: Number,
    },

    components: { Preview },
}
</script>

<style scoped>
.opacity {
    opacity: 0;
    transition: all .1s;
}
.opacity:hover {
    opacity: 1;
    background: linear-gradient(rgba(80,80,80,.1), rgba(0,0,0,.1));
}
</style>

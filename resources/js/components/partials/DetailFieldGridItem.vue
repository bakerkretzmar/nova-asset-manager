<template>
    <div
        class="relative h-48 w-48 overflow-hidden rounded-lg mb-4 mr-4 shadow-md"
    >

        <div class="flex items-start absolute pin opacity z-20">
            <!-- <button
                @click.prevent="$emit('remove')"
                type="button"
                class="flex items-center text-danger m-3 ml-auto cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="h-10 w-10 -m-2 fill-current"><title>Close</title><path d="M16.24 14.83a1 1 0 0 1-1.41 1.41L12 13.41l-2.83 2.83a1 1 0 0 1-1.41-1.41L10.59 12 7.76 9.17a1 1 0 0 1 1.41-1.41L12 10.59l2.83-2.83a1 1 0 0 1 1.41 1.41L13.41 12l2.83 2.83z"/></svg>
            </button> -->
        </div>

        <Preview :file="file" />

    </div>
</template>

<script>
import Preview from './FilePreview'

export default {
    props: {
        file: Object,
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

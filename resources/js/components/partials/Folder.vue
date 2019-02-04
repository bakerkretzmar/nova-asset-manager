<template>
    <div
        v-if="view == 'grid'"
        class="w-1/5 p-1 mb-2"
    >
        <div @click="navigate" class="flex flex-col h-40 rounded-lg border-2 border-50 text-70 hover:text-primary shadow cursor-pointer overflow-hidden">
            <svg class="w-1/2 m-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M464 128H272l-54.63-54.63c-6-6-14.14-9.37-22.63-9.37H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V176c0-26.51-21.49-48-48-48zm0 272H48V112h140.12l54.63 54.63c6 6 14.14 9.37 22.63 9.37H464v224z"/></svg>

            <div class="w-full text-center text-black text-sm p-1 bg-50 truncate" :title="file.name">
                {{ file.name }}
            </div>
        </div>
    </div>

    <div v-else class="w-full p-1 mb-2">
        <div @click="navigate" class="flex h-12 border-2 border-50 rounded text-70 hover:text-primary shadow cursor-pointer overflow-hidden">

            <svg class="h-4/5 w-12 ml-2 mr-6 my-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M464 128H272l-54.63-54.63c-6-6-14.14-9.37-22.63-9.37H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V176c0-26.51-21.49-48-48-48zm0 272H48V112h140.12l54.63 54.63c6 6 14.14 9.37 22.63 9.37H464v224z"/></svg>

            <div class="text-black text-sm w-1/4 p-1 my-auto truncate" :title="file.name">
                {{ file.name }}
            </div>

        </div>
    </div>
</template>

<script>
export default {
    props: {
        file: Object,
        view: {
            type: String,
            default: 'grid',
        },
    },

    methods: {
        navigate() {
            this.$emit('navigate', this.file.path)
        },
    },
};
</script>

<style scoped>
.w-1\/6 {
    width: 16.66667%;
}
.h-40 {
    height: 10rem;
}
.h-4\/5 {
    height: 80%;
}
.hover\:text-primary {
    transition: color .1s;
}
</style>

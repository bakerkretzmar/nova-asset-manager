<template>
    <div class="w-1/5 p-1 mb-2" @click="handleClick">
        <div class="flex flex-col h-40 rounded-lg border-2 border-50 text-70 hover:text-primary shadow cursor-pointer overflow-hidden">

            <file-icon
                v-if="file.mime == 'folder'"
                :type="'folder'"
                class="w-1/2 m-auto"
            />

            <div
                v-else-if="file.mime == 'image'"
                class="flex items-center h-34 overflow-hidden"
            >
                <Preview :file="file" />
            </div>

            <file-icon
                v-else
                :type="file.mime"
                class="block w-1/3 m-auto"
            />

            <div class="w-full text-center text-black text-sm p-1 h-6 bg-50 truncate" :title="file.basename">
                {{ file.basename }}
            </div>

        </div>
    </div>
</template>

<script>
import Preview from './FilePreview'

export default {
    props: {
        file: Object
    },

    components: { Preview },

    methods: {
        handleClick() {
            let event = this.file.mime == 'folder' ? 'navigate' : 'preview'
            this.$emit(event, this.file)
        },
    },
}
</script>

<style scoped>
.h-34 {
    height: 8.5rem;
}
.h-40 {
    height: 10rem;
}
.hover\:text-primary {
    transition: color .1s;
}
</style>

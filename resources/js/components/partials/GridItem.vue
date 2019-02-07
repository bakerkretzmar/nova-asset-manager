<template>
    <div class="griditem" @click="handleClick">
        <div class="flex flex-col h-40 rounded-lg border-2 border-50 text-70 hover:text-primary shadow cursor-pointer overflow-hidden">

            <file-icon
                v-if="file.mime == 'folder'"
                :type="'folder'"
                class="h-4/5 m-auto"
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
                class="h-3/5 m-auto"
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
.griditem {
    width: 20%;
    padding-right: .25rem;
    padding-bottom: .5rem;
    padding-left: .25rem;
}
@media only screen and (min-width: 1600px) {
    .griditem {
        width: 16.6667%;
        padding-right: .5rem;
        padding-bottom: 1rem;
        padding-left: .5rem;
    }
}
.h-4\/5 {
    height: 80%;
}
.h-3\/5 {
    height: 60%;
}
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

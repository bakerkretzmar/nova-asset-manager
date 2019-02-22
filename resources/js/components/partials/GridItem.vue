<template>
    <div class="griditem" @click="handleClick">
        <div class="flex flex-col h-40 rounded-lg border-2 border-50 text-70 hover:text-primary shadow cursor-pointer overflow-hidden relative">

            <div v-if="context == 'field'" class="absolute pin opacity z-20 flex items-center justify-center text-lg text-white font-bold">Click to add</div>

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
        file: Object,
        context: String,
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
    padding-right: .375rem;
    padding-bottom: .75rem;
    padding-left: .375rem;
}
@media only screen and (min-width: 1600px) {
    .griditem {
        width: 16.6667%;
        padding-right: .75rem;
        padding-bottom: 1.5rem;
        padding-left: .75rem;
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

.opacity {
    opacity: 0;
    transition: all .1s;
}
.opacity:hover {
    opacity: 1;
    background: linear-gradient(rgba(80,80,80,.5), rgba(0,0,0,.5));
}
</style>

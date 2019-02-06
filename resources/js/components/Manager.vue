<template>
    <transition name="fade">
        <div class="relative min-h-loader">
            <div v-if="loading" class="flex items-center justify-center absolute pin z-50 h-loader bg-white"><loader class="text-60" /></div>

            <Uploader :path="path" @reload="reload" />

            <div class="flex flex-wrap -mx-1">

                <!-- <template v-if="files.error">
                    <p class="text-center text-danger w-full my-8">Sorry, you don’t have permission to view this folder.</p>
                </template> -->

                <div v-if="!loading && files.length == 0" class="mx-auto">
                    <p class="text-center text-80 italic mt-8 mb-4">Nothing to see here!</p>
                    <button
                        class="btn btn-default btn-danger"
                        @click="deleteFolder"
                    >
                        Delete folder
                    </button>
                </div>

                <template v-for="file in files">

                    <Folder
                        v-if="file.type == 'folder'"
                        :key="file.id"
                        :file="file"
                        :view="view"
                        @navigate="navigateToPath"
                    />

                    <File
                        v-else
                        :key="file.id"
                        :file="file"
                        :view="view"
                        @preview="preview"
                    />

                </template>

            </div>

        </div>
    </transition>
</template>

<script>
import File from './partials/File'
import Folder from './partials/Folder'
import Uploader from './Uploader'

export default {
    props: {
        files: Array,
        loading: Boolean,
        path: String,
        view: String,
    },

    components: {
        File,
        Folder,
        Uploader,
    },

    methods: {
        navigateToPath(path) {
            this.$emit('navigate', path)
        },

        deleteFolder() {
            Nova.request()
                .post('/nova-vendor/nova-asset-manager/folders/delete', { path: this.path })
                .then(response => {
                    if (response.data == true) {
                        this.$toasted.show('Folder deleted!', { type: 'success' })
                        this.$emit('navigate', '/')
                    } else {
                        console.error(response)
                        this.$toasted.show('Error deleting folder. Please check permissions.', { type: 'error' })
                    }
                })
                .catch(error => {
                    console.error(error)
                })
        },

        preview(file) {
            this.$emit('preview', file)
        },

        reload() {
            this.$emit('reload')
        },
    },
}
</script>

<style scoped>
.h-loader {
    height: 12rem;
}
.min-h-loader {
    min-height: 12rem;
}
</style>

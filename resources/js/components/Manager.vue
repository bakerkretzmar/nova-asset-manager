<template>
    <div ref="manager">
        <div class="flex items-center w-full mt-4 mb-3">
            <div
                class="text-primary dim cursor-pointer"
                @click="navigateToPath('/')"
            >
                Home
            </div>

            <template v-for="(folder, index) in path">
                <div class="text-60 mx-2">/</div>

                <template v-if="Object.keys(path).length -1 == index">
                    <div class="text-black">{{ folder.name }}</div>
                </template>

                <template v-else>
                    <div
                        class="text-primary dim cursor-pointer"
                        @click="navigateToPath(folder.path)"
                    >
                        {{ folder.name }}
                    </div>
                </template>
            </template>
        </div>

        <transition name="fade">
            <div class="relative min-h-loader">
                <div v-if="loading" class="flex items-center justify-center absolute pin z-50 h-loader bg-white"><loader class="text-60" /></div>

                <Uploader
                    :path="fullPath"
                    @reload="reload"
                />

                <div class="flex flex-wrap -mx-1">

                    <template v-if="files.error">
                        <p class="text-center text-danger w-full my-8">Sorry, you don’t have permission to view this folder.</p>
                    </template>

                    <template v-if="!loading && files.length == 0">
                        <div class="mx-auto">
                            <p class="text-center text-black my-6">This folder is empty.</p>
                            <button
                                @click="deleteFolder"
                                class="btn btn-default btn-danger"
                            >
                                Delete folder
                            </button>
                        </div>
                    </template>

                    <template v-if="!files.error" v-for="(file, index) in files">

                        <Folder
                            :key="file.id"
                            v-if="file.type == 'dir'"
                            :file="file"
                            :view="view"
                            @navigate="navigateToPath"
                        />

                        <File
                            :key="file.id"
                            v-if="file.type == 'file'"
                            :file="file"
                            :view="view"
                            @preview="preview"
                        />

                    </template>

                </div>

            </div>

        </transition>
    </div>
</template>

<script>
import File from './partials/File'
import Folder from './partials/Folder'
import Uploader from './Uploader'

export default {
    props: {
        files: Array,
        path: Array,
        fullPath: String,
        loading: Boolean,
        view: String,
    },

    components: {
        File,
        Folder,
        Uploader,
    },

    methods: {
        navigateToPath(path) {
            this.$emit('navigateToPath', path)
        },

        deleteFolder() {
            Nova.request()
                .post('/nova-vendor/nova-asset-manager/folders/delete', { path: this.fullPath })
                .then(response => {
                    if (response.data == true) {
                        this.$toasted.show('Folder deleted!', { type: 'success' })
                        this.$emit('navigateToPath', '/')
                    } else {
                        this.$toasted.show('Error deleting folder. Please check permissions.', { type: 'error' })
                    }
                })
                .catch(error => {
                    console.log(error)
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

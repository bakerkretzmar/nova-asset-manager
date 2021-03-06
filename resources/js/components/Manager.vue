<template>
    <transition name="fade">
        <div class="relative min-h-loader">
            <div v-if="loading" class="flex items-center justify-center absolute pin z-50 h-loader bg-white"><loader class="text-60" /></div>

            <Uploader :path="path" :field="field" :context="context" @reload="reload" />

            <div class="flex flex-wrap -mx-1">

                <!-- <template v-if="files.error">
                    <p class="text-center text-danger w-full my-8">Sorry, you don’t have permission to view this folder.</p>
                </template> -->

                <div v-if="!loading && files.length == 0" class="mx-auto">
                    <p class="text-center text-80 italic mt-8 mb-4">Nothing to see here!</p>
                    <button
                        v-if="context == 'tool'"
                        class="btn btn-default btn-danger"
                        @click="deleteFolder"
                    >
                        Delete folder
                    </button>
                </div>

                <template v-for="file in files">

                    <GridItem
                        v-if="view == 'grid'"
                        :context="context"
                        :key="file.basename"
                        :file="file"
                        @navigate="navigateToPath"
                        @preview="preview"
                    />

                    <ListItem
                        v-else-if="view == 'list'"
                        :context="context"
                        :key="file.basename"
                        :file="file"
                        @navigate="navigateToPath"
                        @preview="preview"
                    />

                </template>

            </div>

        </div>
    </transition>
</template>

<script>
import GridItem from './partials/GridItem'
import ListItem from './partials/ListItem'
import Uploader from './Uploader'

export default {
    props: {
        files: Array,
        loading: Boolean,
        path: String,
        view: String,
        context: String,
        field: {
            tyle: Object,
            required: false,
        },
    },

    components: {
        GridItem,
        ListItem,
        Uploader,
    },

    methods: {
        navigateToPath(file) {
            this.$emit('navigate', file.path)
        },

        deleteFolder() {
            Nova.request()
                .post('/nova-vendor/nova-asset-manager/folders/delete', {
                    disk: this.field && this.field.disk ? this.field.disk : '',
                    path: this.path
                })
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

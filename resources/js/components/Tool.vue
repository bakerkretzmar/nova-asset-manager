<template>
    <div>
        <heading class="mb-6">Asset Manager</heading>

        <card class="relative mb-6 py-4 px-6">

            <div class="flex items-center pb-4">

                <!-- <progress-button
                    class="mr-4"
                    :disabled="loading"
                    :processing="loading"
                    @click.native="reload"
                >
                    <svg class="flex items-center pb-px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="20" height="20"><path fill="currentColor" d="M4,16.7V19c0,0.6-0.4,1-1,1s-1-0.4-1-1v-5c0-0.6,0.4-1,1-1h5c0.6,0,1,0.4,1,1c0,0.6-0.4,1-1,1H5.1c2.8,2.7,7.2,2.7,9.9-0.1 c1.3-1.3,2-3.1,2-4.9c0-0.6,0.4-1,1-1s1,0.4,1,1c0,5-4,9-9,9C7.8,19,5.6,18.2,4,16.7z M16,3.3V1c0-0.6,0.4-1,1-1s1,0.4,1,1v5 c0,0.6-0.4,1-1,1h-5c-0.6,0-1-0.4-1-1s0.4-1,1-1h2.9C12.1,2.3,7.7,2.3,5,5.1C3.7,6.4,3,8.2,3,10c0,0.6-0.4,1-1,1s-1-0.4-1-1 c0-5,4-9,9-9C12.2,1,14.4,1.8,16,3.3z"/></svg>
                </progress-button> -->

                <button
                    v-if="!inBaseFolder"
                    class="btn btn-default btn-primary flex items-center mr-4 px-3"
                    @click="back"
                >
                    <svg class="svg-shadow pb-px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="currentColor" d="M5.41 11H21a1 1 0 0 1 0 2H5.41l5.3 5.3a1 1 0 0 1-1.42 1.4l-7-7a1 1 0 0 1 0-1.4l7-7a1 1 0 0 1 1.42 1.4L5.4 11z"/></svg>
                </button>

                <button
                    class="btn btn-default btn-primary whitespace-no-wrap mr-4"
                    @click="openCreateFolderModal"
                >
                    <span>Create folder</span>
                </button>

                <div class="relative w-full max-w-xs mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="search" role="presentation" class="fill-current absolute search-icon-center ml-3 text-70"><path fill-rule="nonzero" d="M14.32 12.906l5.387 5.387a1 1 0 0 1-1.414 1.414l-5.387-5.387a8 8 0 1 1 1.414-1.414zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"></path></svg>
                    <input
                        type="search"
                        placeholder="Search"
                        class="pl-search form-control form-input form-input-bordered w-full"
                        v-model="query"
                        @input="search"
                    />
                </div>

                <Breadcrumbs :path="path" @navigate="navigateToPath" />

                <div class="flex items-center ml-auto">
                    <button
                        class="btn btn-link inline-flex items-center mr-4"
                        @click="setListView"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="currentColor" d="M7.5,5H20c0.6,0,1,0.4,1,1s-0.4,1-1,1H7.5c-0.6,0-1-0.4-1-1S7,5,7.5,5z M4,5L4,5c0.6,0,1,0.4,1,1S4.6,7,4,7h0 C3.5,7,3,6.6,3,6S3.5,5,4,5z M4,11L4,11c0.6,0,1,0.4,1,1c0,0.6-0.4,1-1,1h0c-0.6,0-1-0.4-1-1C3,11.4,3.5,11,4,11z M4,17L4,17 c0.6,0,1,0.4,1,1s-0.4,1-1,1h0c-0.6,0-1-0.4-1-1S3.5,17,4,17z M7.5,11H20c0.6,0,1,0.4,1,1s-0.4,1-1,1H7.5c-0.6,0-1-0.4-1-1 S7,11,7.5,11z M7.5,17H20c0.6,0,1,0.4,1,1s-0.4,1-1,1H7.5c-0.6,0-1-0.4-1-1S7,17,7.5,17z"/></svg>
                    </button>
                    <button
                        class="btn btn-link inline-flex items-center"
                        @click="setGridView"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="currentColor" d="M5 3h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2zm0 2v4h4V5H5zm10-2h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2zm0 2v4h4V5h-4zM5 13h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4c0-1.1.9-2 2-2zm0 2v4h4v-4H5zm10-2h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-4c0-1.1.9-2 2-2zm0 2v4h4v-4h-4z"/></svg>
                    </button>
                </div>

            </div>

            <Manager
                :path="path"
                :files="files"
                :view="view"
                :loading="loading"
                @reload="loadPath"
                @navigate="navigateToPath"
                @preview="openPreviewFileModal"
            />

        </card>

        <portal to="modals">
            <CreateFolderModal
                v-if="isCreateFolderModalOpen"
                :path="path"
                @reload="loadPath"
                @close="closeCreateFolderModal"
            />

            <PreviewFileModal
                v-if="isPreviewFileModalOpen"
                :file="previewFile"
                @reload="loadPath"
                @close="closePreviewFileModal"
            />
        </portal>

    </div>
</template>

<script>
import Breadcrumbs from './partials/Breadcrumbs'
import CreateFolderModal from './partials/CreateFolderModal'
import Manager from './Manager'
import PreviewFileModal from './partials/PreviewFileModal'

export default {
    components: {
        Breadcrumbs,
        CreateFolderModal,
        Manager,
        PreviewFileModal,
    },

    data: () => ({
        files: [],
        isCreateFolderModalOpen: false,
        isPreviewFileModalOpen: false,
        loading: true,
        path: '/',
        previewFile: {},
        query: '',
        view: 'grid',
    }),

    created() {
        let urlParameters = new URLSearchParams(window.location.search)
        if (urlParameters.has('path')) {
            this.path = urlParameters.get('path')
        }
        if (urlParameters.has('view')) {
            this.view = urlParameters.get('view')
        }
        this.updateURL()
        this.loadPath()
    },

    methods: {
        openCreateFolderModal() {
            this.isCreateFolderModalOpen = true
        },
        closeCreateFolderModal() {
            this.isCreateFolderModalOpen = false
        },

        openPreviewFileModal(file) {
            this.previewFile = file
            this.isPreviewFileModalOpen = true
        },
        closePreviewFileModal() {
            this.isPreviewFileModalOpen = false
            this.previewFile = {}
        },

        setListView() {
            this.view = 'list'
            this.updateURL()
        },
        setGridView() {
            this.view = 'grid'
            this.updateURL()
        },

        navigateToPath(path) {
            this.path = path
            this.updateURL()
            this.reload()
        },

        updateURL() {
            history.pushState(null, null, '?view=' + this.view + '&path=' + this.path)
        },

        back() {
            if (this.path.lastIndexOf('/') == -1) {
                return this.navigateToPath('/')
            }

            if (this.path.endsWith('/')) {
                this.path = this.path.substring(this.path.length - 1)
            }

            this.navigateToPath(this.path.substring(0, this.path.lastIndexOf('/')))
        },

        reload() {
            this.files = []
            this.loading = true
            this.loadPath()
        },

        loadPath() {
            Nova.request()
                .get('/nova-vendor/nova-asset-manager/folders/info', {
                    params: {
                        path: this.path,
                    },
                })
                .then(response => {
                    // console.log(response.data)
                    this.files = response.data.files
                    this.loading = false
                })
                .catch(error => {
                    console.error(error)
                    this.loading = false
                })
        },

        search: _.debounce(function(e) {
            // Make this a watch on the 'query' property and just debounce that...
            this.query = e.target.value
        }, 300),
    },

    computed: {
        inBaseFolder() {
            return this.path == '/' || ''
        },
    },
}
</script>

<style scoped>
.rotate >>> svg {
    animation: spin 2s infinite linear;
}
@keyframes spin {
    0% {
        -webkit-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(359deg);
        transform: rotate(359deg);
    }
}
.svg-shadow {
    filter: drop-shadow(0 1px 2px rgba(0,0,0,.2));
}
</style>

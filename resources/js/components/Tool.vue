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

                <BackButton v-if="!inBaseFolder" @back="back" />

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
                        v-model.trim="query"
                    />
                </div>

                <Breadcrumbs :path="path" @navigate="navigateToPath" />

                <ViewButtons :view="view" @set="setView" />

            </div>

            <Manager
                :path="path"
                :files="visibleFiles"
                :view="view"
                context="tool"
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
import BackButton from './partials/BackButton'
import Breadcrumbs from './partials/Breadcrumbs'
import CreateFolderModal from './partials/CreateFolderModal'
import Manager from './Manager'
import PreviewFileModal from './partials/PreviewFileModal'
import ViewButtons from './partials/ViewButtons'

export default {
    components: {
        BackButton,
        Breadcrumbs,
        CreateFolderModal,
        Manager,
        PreviewFileModal,
        ViewButtons,
    },

    data: () => ({
        files: [],
        isCreateFolderModalOpen: false,
        isPreviewFileModalOpen: false,
        loading: true,
        path: '/',
        previewFile: {},
        query: '',
        searching: false,
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

        setView(view) {
            this.view = view
            this.updateURL()
        },

        navigateToPath(path) {
            this.query = ''
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
    },

    computed: {
        inBaseFolder() {
            return this.path == '/' || ''
        },

        visibleFiles() {
            if (!this.query) return this.files
            return this.files.filter(file => {
                return file.basename.toLowerCase().includes(this.query.toLowerCase())
            })
        },
    },
}
</script>

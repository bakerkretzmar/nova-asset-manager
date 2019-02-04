<template>
    <div>
        <heading class="mb-6">Asset Manager</heading>

        <card class="relative mb-6 py-4 px-6">

            <div class="flex items-center justify-between border-b border-40 pb-4">

                <div class="flex w-2/3">

                    <button
                        :class="{ 'rotate' : loadingfiles }"
                        class="btn btn-default btn-primary inline-flex items-center text-white mr-4"
                        @click="reload"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="20" height="20"><path fill="currentColor" d="M4,16.7V19c0,0.6-0.4,1-1,1s-1-0.4-1-1v-5c0-0.6,0.4-1,1-1h5c0.6,0,1,0.4,1,1c0,0.6-0.4,1-1,1H5.1c2.8,2.7,7.2,2.7,9.9-0.1 c1.3-1.3,2-3.1,2-4.9c0-0.6,0.4-1,1-1s1,0.4,1,1c0,5-4,9-9,9C7.8,19,5.6,18.2,4,16.7z M16,3.3V1c0-0.6,0.4-1,1-1s1,0.4,1,1v5 c0,0.6-0.4,1-1,1h-5c-0.6,0-1-0.4-1-1s0.4-1,1-1h2.9C12.1,2.3,7.7,2.3,5,5.1C3.7,6.4,3,8.2,3,10c0,0.6-0.4,1-1,1s-1-0.4-1-1 c0-5,4-9,9-9C12.2,1,14.4,1.8,16,3.3z"/></svg>
                    </button>

                    <button
                        class="btn btn-default btn-primary mr-4"
                        @click="openCreateFolderModal"
                    >
                        <span>Create folder</span>
                    </button>

                    <div class="relative w-full max-w-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="search" role="presentation" class="fill-current absolute search-icon-center ml-3 text-70"><path fill-rule="nonzero" d="M14.32 12.906l5.387 5.387a1 1 0 0 1-1.414 1.414l-5.387-5.387a8 8 0 1 1 1.414-1.414zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"></path></svg>
                        <input
                            v-on:input="searchItems"
                            v-model="search"
                            dusk="asset-manager-search"
                            type="search"
                            placeholder="Search"
                            class="pl-search form-control form-input form-input-bordered w-full"
                        />
                    </div>

                </div>

                <div class="flex items-center">

                    <button
                        class="btn btn-link inline-flex items-center mr-4"
                        @click="setListView"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="currentColor" d="M7.5,5H20c0.6,0,1,0.4,1,1s-0.4,1-1,1H7.5c-0.6,0-1-0.4-1-1S7,5,7.5,5z M4,5L4,5c0.6,0,1,0.4,1,1S4.6,7,4,7h0 C3.5,7,3,6.6,3,6S3.5,5,4,5z M4,11L4,11c0.6,0,1,0.4,1,1c0,0.6-0.4,1-1,1h0c-0.6,0-1-0.4-1-1C3,11.4,3.5,11,4,11z M4,17L4,17 c0.6,0,1,0.4,1,1s-0.4,1-1,1h0c-0.6,0-1-0.4-1-1S3.5,17,4,17z M7.5,11H20c0.6,0,1,0.4,1,1s-0.4,1-1,1H7.5c-0.6,0-1-0.4-1-1 S7,11,7.5,11z M7.5,17H20c0.6,0,1,0.4,1,1s-0.4,1-1,1H7.5c-0.6,0-1-0.4-1-1S7,17,7.5,17z"/></svg>
                    </button>

                    <button
                        @click="setGridView"
                        class="btn btn-link inline-flex items-center"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="currentColor" d="M5 3h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2zm0 2v4h4V5H5zm10-2h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2zm0 2v4h4V5h-4zM5 13h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4c0-1.1.9-2 2-2zm0 2v4h4v-4H5zm10-2h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-4c0-1.1.9-2 2-2zm0 2v4h4v-4h-4z"/></svg>
                    </button>

                </div>

            </div>

            <Manager
                ref="manager"
                :files="files"
                :path="path"
                :fullPath="currentPath"
                :view="view"
                :loading="loadingfiles"
                @navigateToPath="navigateToPath"
                @reload="reload"
                @preview="openFileDetailModal"
            />

        </card>

        <CreateFolderModal
            v-if="isCreateFolderModalOpen"
            :fullPath="currentPath"
            @close="closeCreateFolderModal"
            @reload="reload"
        />

        <FileDetailModal
            v-if="isFileDetailModalOpen"
            :file="fileDetail"
            @close="closeFileDetailModal"
            @reload="reload"
        />

    </div>
</template>

<script>
import CreateFolderModal from './partials/CreateFolderModal'
import FileDetailModal from './partials/FileDetailModal'
import Manager from './Manager'

import URI from 'urijs';
import api from '../api'

export default {
    components: {
        CreateFolderModal,
        FileDetailModal,
        Manager,
    },

    data: () => ({
        isCreateFolderModalOpen: false,
        isFileDetailModalOpen: false,
        fileDetail: {},
        view: 'grid',

        loaded: false,
        loadingfiles: false,
        currentPath: '/',
        files: [],
        path: [],
        noFiles: false,
        search: '',
    }),

    async created() {
        let currentUrl = new URI();

        if (currentUrl.hasQuery('path')) {
            let params = currentUrl.query(true);
            this.currentPath = params.path;
        }

        await this.getData(this.currentPath);

        this.loaded = true;
    },

    methods: {
        openCreateFolderModal() {
            this.isCreateFolderModalOpen = true
        },
        closeCreateFolderModal() {
            this.isCreateFolderModalOpen = false
        },

        openFileDetailModal(file) {
            this.isFileDetailModalOpen = true
            this.fileDetail = file
        },
        closeFileDetailModal() {
            this.isFileDetailModalOpen = false
            this.fileDetail = {}
        },

        setListView() {
            this.view = 'list'
            localStorage.setItem('cd_assetmanager_view_layout', 'list')
        },
        setGridView() {
            this.view = 'grid'
            localStorage.setItem('cd_assetmanager_view_layout', 'grid')
        },

        navigateToPath(path) {
            this.getData(path)
            this.currentPath = path
            history.pushState(null, null, '?path=' + path)
        },






        getData(pathToList) {
            this.files = [];
            this.path = [];
            this.noFiles = false;
            this.loadingfiles = true;
            return api.getData(pathToList).then(result => {
                if (_.size(result.files) == 0) {
                    this.noFiles = true;
                }
                this.files = result.files;
                this.path = result.path;
                this.loadingfiles = false;
            });
        },




        reload() {
            this.getData(this.currentPath);
        },



        searchItems: _.debounce(function(e) {
            this.search = e.target.value;
        }, 300),
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
</style>

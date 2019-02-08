<template>
    <default-field :field="field" :full-width-content="true">
        <template slot="field">

            <div class="flex flex-wrap">

                <FormGridItem
                    v-for="(file, index) in value"
                    :key="file.id"
                    :file="file"
                    :index="index"
                    :length="value.length"
                    @remove="remove(file.id)"
                    @move="reorder($event, index)"
                />

            </div>

            <button
                class="btn btn-default btn-primary my-2"
                :class="{ 'cursor-not-allowed opacity-50': hasMaximum }"
                @click.prevent="handleOpen"
            >
                Add {{ field.name.toLowerCase() }}
            </button>

            <portal to="modals">
                <transition name="fade">
                    <modal v-if="isManagerOpen" @modal-close="handleClose">

                        <card class="manager-modal relative py-4 px-6 overflow-y-auto">

                            <div class="flex items-center pb-4">

                                <BackButton v-if="!inBaseFolder" @back="back" />

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

                                <button
                                    @click.prevent="handleClose"
                                    type="button"
                                    class="flex items-center text-60 hover:text-80 ml-4"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="h-10 w-10 -m-2 pt-px fill-current"><title>Close</title><path d="M16.24 14.83a1 1 0 0 1-1.41 1.41L12 13.41l-2.83 2.83a1 1 0 0 1-1.41-1.41L10.59 12 7.76 9.17a1 1 0 0 1 1.41-1.41L12 10.59l2.83-2.83a1 1 0 0 1 1.41 1.41L13.41 12l2.83 2.83z"/></svg>
                                </button>

                            </div>

                            <Manager
                                :path="path"
                                :files="visibleFiles"
                                :view="view"
                                context="field"
                                :loading="loading"
                                @reload="loadPath"
                                @navigate="navigateToPath"
                                @preview="add"
                            />

                        </card>

                    </modal>
                </transition>
            </portal>

        </template>
    </default-field>
</template>

<script>
import BackButton from './partials/BackButton'
import Breadcrumbs from './partials/Breadcrumbs'
import FormGridItem from './partials/FormFieldGridItem'
import Manager from './Manager'
import ViewButtons from './partials/ViewButtons'

import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
    props: ['resourceName', 'resourceId', 'field'],

    mixins: [FormField, HandlesValidationErrors],

    components: {
        BackButton,
        Breadcrumbs,
        FormGridItem,
        Manager,
        ViewButtons,
    },

    data: () => ({
        value: [],
        files: [],
        isManagerOpen: false,
        loading: true,
        path: '/',
        query: '',
        view: 'grid',
    }),

    methods: {
        setView(view) {
            this.view = view
        },

        navigateToPath(path) {
            this.path = path
            this.reload()
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
                        mimes: this.field.filetypes || ['folder', 'image'],
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

        handleOpen() {
            if (this.hasMaximum) return
            this.isManagerOpen = true
            this.loadPath()
        },

        handleClose() {
            this.isManagerOpen = false
        },

        add(file) {
            if (this.hasMaximum) return
            this.handleClose()
            this.value.push(file)
        },

        remove(id) {
            this.value = this.value.filter(item => {
                return item.id != id
            })
        },

        reorder(distance, from) {
            let to = from + distance
            this.value.splice(to, 0, this.value.splice(from, 1)[0])
        },

        setInitialValue() {
            this.value = this.field.value || []
            this.path = this.field.folder || '/'
        },

        fill(formData) {
            formData.append(this.field.attribute, JSON.stringify(this.value))
        },
    },

    computed: {
        inBaseFolder() {
            return this.path == '/' || ''
        },

        hasMaximum() {
            return this.value.length >= this.field.maximum
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

<style scoped>
.manager-modal {
    width: 80vw;
    max-height: calc(100vh - 200px);
}
</style>

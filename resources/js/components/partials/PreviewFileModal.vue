<template>
    <transition name="fade">

        <modal @modal-close="handleClose">

            <div class="bg-white rounded-lg shadow-lg overflow-hidden file-detail-modal">

                <div class="p-6">

                    <div class="flex justify-between mb-6">
                        <h4 class="font-bold text-80 text-lg truncate">{{ file.basename }}</h4>
                        <button
                            @click="handleClose"
                            type="button"
                            class="flex items-center text-60 hover:text-80"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="h-8 w-8 -m-2 pt-px fill-current"><title>Close</title><path d="M16.24 14.83a1 1 0 0 1-1.41 1.41L12 13.41l-2.83 2.83a1 1 0 0 1-1.41-1.41L10.59 12 7.76 9.17a1 1 0 0 1 1.41-1.41L12 10.59l2.83-2.83a1 1 0 0 1 1.41 1.41L13.41 12l2.83 2.83z"/></svg>
                        </button>
                    </div>

                    <div class="flex">

                        <div class="flex items-center justify-center w-3/5 mr-6">
                            <template v-if="file.mime == 'image'">
                                <Preview :file="file" />
                            </template>

                            <template v-else-if="file.mime == 'audio'">
                                <audio
                                    controls
                                    class="w-full"
                                    :src="file.url"
                                >
                                    Your browser does not support the <code>audio</code> element.
                                </audio>
                            </template>

                            <template v-else-if="file.mime == 'video'">
                                <video controls crossorigin playsinline>
                                    <source :src="file.url" :type="file.mime" />
                                </video>
                            </template>

                            <!-- <template v-else-if="file.mime == 'pdf'">
                                <iframe :src="file.url" width="400px" height="400px"></iframe>
                                <object :data="file.url" type="application/pdf" width="100%" height="100%">
                                    <iframe :src="file.url" width="100%" height="100%" style="border: none;">
                                        <object class="no-preview" v-html="file.image">

                                        </object>
                                    </iframe>
                                </object>
                            </template> -->

                            <template v-else>
                                <file-icon
                                    :type="file.mime"
                                    class="block w-1/4 m-auto"
                                />
                            </template>
                        </div>

                        <div class="flex flex-col w-2/5">
                            <div class="flex flex-col mb-6">
                                <p class="text-80 font-bold mb-2">Name</p>
                                <div class="bg-40 rounded px-3 py-2 break-words select-text">{{ file.basename }}</div>
                            </div>

                            <!-- <div v-if="file.mime" class="flex flex-col mb-6">
                                <p class="text-80 font-bold mb-2">Type</p>
                                <p class="bg-40 rounded px-3 py-2 break-words select-text">
                                    {{ file.mime }}
                                </p>
                            </div> -->

                            <div v-if="file.timestamp" class="flex flex-col mb-6">
                                <p class="text-80 font-bold mb-2">Last modified</p>
                                <div class="bg-40 rounded px-3 py-2 break-words select-text">{{ formatDate(file.timestamp) }}</div>
                            </div>

                            <div v-if="file.size_string" class="flex flex-col mb-6">
                                <p class="text-80 font-bold mb-2">Size</p>
                                <div class="bg-40 rounded px-3 py-2 break-words select-text">{{ file.size_string }}</div>
                            </div>

                            <div v-if="file.dimensions" class="flex flex-col mb-6">
                                <p class="text-80 font-bold mb-2">Dimensions</p>
                                <div class="bg-40 rounded px-3 py-2 break-words select-text">{{ file.dimensions }}</div>
                            </div>

                            <div v-if="file.url" class="flex flex-col mb-6">
                                <p class="text-80 font-bold mb-2">
                                    URL
                                    <span
                                        ref="copy"
                                        class="font-normal ml-1"
                                        :class="justCopied ? 'text-80' : 'text-60'"
                                    >
                                        {{ justCopied ? 'Copied!' : 'Click to copy' }}
                                    </span>
                                </p>
                                <p
                                    v-copy="file.url"
                                    v-copy:callback="copyCallback"
                                    class="bg-40 rounded px-3 py-2 break-words cursor-pointer"
                                >
                                    {{ file.url }}
                                </p>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="bg-30 px-6 py-3 flex">
                    <div class="ml-auto">
                        <button
                            type="button"
                            ref="deleteButton"
                            class="btn text-80 hover:text-danger font-normal h-9 px-1 mr-3 btn-link"
                            @click.prevent="handleDelete"
                        >
                            Delete
                        </button>
                        <progress-button
                            class="btn btn-default btn-primary"
                        >
                            <!-- @click.native="handleCreate"
                            :disabled="isSaving || ! folderName.length"
                            :processing="isSaving" -->
                            Edit
                        </progress-button>
                    </div>
                </div>

            </div>

        </modal>

    </transition>
</template>

<script>
import Preview from './FilePreview'

import { copy } from 'v-copy'

export default {
    props: {
        file: Object,
    },

    components: { Preview },

    directives: { copy },

    data: () => ({
        loading: true,
        justCopied: false,
    }),

    methods: {
        formatDate(timestamp) {
            return moment.unix(timestamp).format('ddd MMM D YYYY, h:mm A')
        },

        handleClose() {
            this.$emit('close')
        },

        copyCallback: _.throttle(function() {
            console.log('copied')
            this.justCopied = true
            setTimeout(() => { this.justCopied = false }, 2000)
        }, 2000, { trailing: false, }),

        handleDelete() {
            Nova.request()
                .post('/nova-vendor/nova-asset-manager/files/delete', {
                    path: this.file.path,
                })
                .then(response => {
                    if (response.data == 'sucess') {
                        this.handleClose()
                        this.$toasted.show('File deleted!', { type: 'success' })
                        this.$emit('reload')
                    } else {
                        this.handleClose()
                        this.$toasted.show('Error deleting the file. Please make sure it exists and you have the right permissions.', { type: 'error' })
                    }
                })
                .catch(error => {
                    console.error(error)
                    //
                })
        },
    },
}
</script>

<style scoped>
.file-detail-modal {
    width: 1200px;
    max-width: 80vw;
}
.clipboard-icon {
    height: 20px;
    width: 20px;
}
</style>

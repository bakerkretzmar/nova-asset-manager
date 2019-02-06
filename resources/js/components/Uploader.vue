<template>
    <div ref="uploader" class="uploader flex flex-col justify-center mb-3 py-2 px-3 rounded-lg" :class="{ 'active' : dragActive }">
        <div
            v-if="!files.length"
            class="mx-auto text-center text-primary font-bold italic"
            :class="{ 'pointer-events-none' : dragActive }"
        >
            <label class="cursor-pointer">
                {{ dragActive ? 'Drop to upload!' : 'Drop files or click here to upload.' }}
                <input class="hidden" type="file" multiple="true" @change="uploadViaClick" />
            </label>
        </div>

        <template
            v-else
            v-for="(file, index) in files"
        >
            <div class="flex items-center w-full h-9">
                <div class="w-1/3 pr-4 truncate" :title="file.name">
                    {{ file.name }}
                </div>

                <div
                    class="pr-4 italic truncate"
                    :class="{ 'text-danger' : file.error }"
                >
                    {{ file.status }}
                </div>

                <div
                    v-if="file.progress < 100"
                    class="flex items-center w-1/3 ml-auto"
                >
                    <Progress :progress="file.progress" />
                </div>
                <div
                    v-else
                    class="flex items-center ml-auto"
                >
                    <div v-if="file.error">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="var(--danger)" d="M4.93 19.07A10 10 0 1 1 19.07 4.93 10 10 0 0 1 4.93 19.07zm1.41-1.41A8 8 0 1 0 17.66 6.34 8 8 0 0 0 6.34 17.66zM13.41 12l1.42 1.41a1 1 0 1 1-1.42 1.42L12 13.4l-1.41 1.42a1 1 0 1 1-1.42-1.42L10.6 12l-1.42-1.41a1 1 0 1 1 1.42-1.42L12 10.6l1.41-1.42a1 1 0 1 1 1.42 1.42L13.4 12z"/></svg>
                    </div>
                    <div v-else>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="var(--success)" d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-2.3-8.7l1.3 1.29 3.3-3.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-2-2a1 1 0 0 1 1.4-1.42z"/></svg>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
import Progress from './partials/ProgressBar'

export default {
    props: {
        path: String,
    },

    components: { Progress },

    data: () => ({
        files: [],
        successfulUploads: 0,
        dragActive: false,
    }),

    mounted() {
        let vm = this
        let uploader = this.$refs.uploader

        uploader.addEventListener('dragenter', e => {
            e.preventDefault()
            vm.dragActive = true
        })
        uploader.addEventListener('dragleave', e => {
            e.preventDefault()
            vm.dragActive = false
        })
        uploader.addEventListener('dragover', e => {
            e.preventDefault()
            vm.dragActive = true
        })
        uploader.addEventListener('drop', e => {
            e.preventDefault()
            vm.dragActive = false
            vm.uploadViaDrop(e)
        })
    },

    methods: {
        uploadViaClick(e) {
            this.uploadFiles(e.target.files)
        },

        uploadViaDrop(e) {
            this.uploadFiles(e.dataTransfer.files)
        },

        uploadFiles(files) {
            let uploads = []

            Array.from(files).forEach(file => {
                uploads.push({
                    name: file.name,
                    size: file.size,
                    progress: 0,
                    error: false,
                    status: 'Uploading...',
                    original: file,
                })
            })

            this.files = uploads
            uploads.forEach(file => this.upload(file))
        },

        upload(file) {
            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
                onUploadProgress: progress => {
                    file.progress = Math.round((progress.loaded * 100) / progress.total)
                },
            }

            let data = new FormData()

            data.append('path', this.path)

            data.append('file', file.original)

            Nova.request()
                .post('/nova-vendor/nova-asset-manager/files/upload', data, config)
                .then(response => {
                    switch (response.data) {
                        case 'file_exists':
                            file.error = true
                            file.status = 'Upload failed: a file with that name already exists here!'
                            this.forget(file)
                            break;
                        case 'success':
                            file.status = 'Uploaded!'
                            this.successfulUploads++
                            this.forget(file)
                            break;
                        default:
                            //
                    }
                })
                .catch(error => {
                    console.log(error)
                    file.error = true
                    file.status = 'Error!'
                    this.forget(file)
                })
        },

        forget(file) {
            setTimeout(() => {
                this.files = this.files.filter(f => f.id !== file.id)
                if (!this.files.length && this.successfulUploads) {
                    this.$emit('reload')
                }
            }, 5000)
        },
    },
};
</script>

<style scoped>
.uploader {
    background-color: var(--primary-10);
    min-height: 2rem;
    transition: min-height .2s ease-in-out;
}
.uploader.active {
    min-height: 20rem;
}
</style>

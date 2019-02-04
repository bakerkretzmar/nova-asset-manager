<template>
    <portal to="modals">
        <transition name="fade">

            <modal @modal-close="handleClose">

                <div class="bg-white rounded-lg shadow-lg overflow-hidden w-540">

                    <div class="p-8">
                        <heading :level="2" class="mb-6">Create folder</heading>
                        <input
                            ref="folderNameInput"
                            type="text"
                            class="w-full form-control form-input form-input-bordered py-4"
                            placeholder="Folder name"
                            v-model.trim="folderName"
                            required
                        >
                        <p v-if="hasError" class="help-text text-danger mt-2">{{ errorMessage }}</p>
                    </div>

                    <div class="bg-30 px-6 py-3 flex">
                        <div class="ml-auto">
                            <button type="button" ref="cancelButton" @click.prevent="handleClose" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">Cancel</button>
                            <progress-button
                                class="btn btn-default btn-primary"
                                @click.native="handleCreate"
                                :disabled="isSaving || ! folderName.length"
                                :processing="isSaving"
                            >
                                Create
                            </progress-button>
                        </div>
                    </div>

                </div>

            </modal>

        </transition>
    </portal>
</template>

<script>
export default {
    props: {
        fullPath: String,
    },

    data: () => ({
        folderName: '',
        hasError: false,
        errorMessage: '',
        isSaving: false,
    }),

    mounted() {
        this.$nextTick(() => {
            this.$refs.folderNameInput.focus()
        })
    },

    methods: {
        handleCreate() {
            if (! this.folderName.length) return

            Nova.request()
                .post('/nova-vendor/nova-asset-manager/folders/create', {
                    folder: this.folderName,
                    path: this.fullPath,
                })
                .then(result => {
                    switch (result.data) {
                        case 'folder_exists':
                            this.hasError = true
                            this.errorMessage = 'A folder with that name already exists here!'
                            break;
                        case 'success':
                            this.handleClose()
                            this.$toasted.show('Folder created!', { type: 'success' })
                            this.$emit('reload')
                            break;
                        default:
                            //
                    }
                })
                .catch(error => {
                    console.log(error)
                })
        },

        handleClose() {
            this.$emit('close')
        },
    },
};
</script>

<style scoped>
.w-540 {
    width: 540px;
}
</style>

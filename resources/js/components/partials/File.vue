<template>
    <div
        v-if="view == 'grid'"
        class="w-1/5 p-1 mb-2"
    >
        <div @click="preview" class="relative flex flex-col h-40 rounded-lg border-2 border-50 text-70 hover:text-primary shadow cursor-pointer overflow-hidden">
            <div v-if="loading" class="flex items-center justify-center absolute pin z-50 bg-white"><loader class="text-60" /></div>

            <file-icon
                v-if="showIcon"
                :type="file.mime"
                class="block w-1/3 m-auto"
            />

            <div
                v-show="!showIcon && !loading"
                class="image-preview-grid overflow-hidden"
            >
                <img :src="src" ref="image">
            </div>

            <div class="w-full text-center text-black text-sm p-1 h-6 bg-50 truncate" :title="file.name">
                {{ file.name }}
            </div>
        </div>
    </div>

    <div v-else class="w-full p-1 mb-2">
        <div @click="preview" class="relative flex h-12 border-2 border-50 rounded text-70 hover:text-primary shadow cursor-pointer overflow-hidden">

            <file-icon
                :type="file.mime"
                class="block h-4/5 w-12 ml-2 mr-6 my-auto"
            />

            <div class="text-black text-sm w-1/2 p-1 my-auto truncate mr-4" :title="file.name">
                {{ file.name }}
            </div>

            <div class="text-black text-sm w-1/6 p-1 my-auto truncate" :title="file.size_human">
                {{ file.size_human }}
            </div>

            <div class="text-black text-sm w-1/5 p-1 my-auto truncate" :title="file.last_modification">
                {{ file.last_modification }}
            </div>

        </div>
    </div>
</template>

<script>
import { Minimum } from 'laravel-nova'

export default {
    props: {
        file: Object,
        view: {
            type: String,
            default: 'grid',
        },
    },

    data: () => ({
        loading: true,
        showIcon: false,
        src: '',
    }),

    mounted() {
        if (this.file.mime != 'image') {
            this.showIcon = true
            this.loading = false
            return
        }

        Minimum(
            Nova.request().get(this.file.asset, {
                responseType: 'blob',
            })
        )
        .then(({ headers, data }) => {
            let blob = new Blob([data], {
                type: headers['content-type']
            })

            this.src = URL.createObjectURL(blob)

            this.loading = false
        })
        .catch(error => {
            console.log(error)
            this.showIcon = true
            this.loading = false
        })
    },

    methods: {
        preview() {
            this.$emit('preview', this.file)
        },
    },
};
</script>

<style scoped>
.w-1\/6 {
    width: 16.66667%;
}
.h-40 {
    height: 10rem;
}
.h-4\/5 {
    height: 80%;
}
.hover\:text-primary {
    transition: color .1s;
}
.image-preview-grid {
    height: 8.5rem;
}
.image-preview-list {
    height: 3rem;
}
.image-preview-grid >>> img,
.image-preview-list >>> img {
    object-fit: cover;
    width: 100%;
    height: 100%;
}
</style>

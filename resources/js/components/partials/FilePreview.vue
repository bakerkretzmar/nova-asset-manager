<template>
    <div :class="{ 'relative w-full h-full' : !icon }">
        <div v-if="loading" class="flex items-center justify-center absolute pin z-50 bg-white"><loader class="text-60" /></div>

        <file-icon
            v-if="icon"
            :type="'image'"
            class="block w-1/3 m-auto"
        />

        <img v-else class="object-cover w-full h-full" :src="src" />
    </div>
</template>

<script>
import { Minimum } from 'laravel-nova'

export default {
    props: {
        file: Object,
    },

    data: () => ({
        icon: false,
        loading: true,
        src: '',
    }),

    mounted() {
        if (!this.file.url) {
            this.src = this.file.path
            this.loading = false
            return
        }

        Minimum(
            Nova.request()
                .get(this.file.url, {
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
            console.error(error)
            this.icon = true
            this.loading = false
        })
    },
}
</script>

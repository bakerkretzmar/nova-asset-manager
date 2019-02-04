<template>
    <div class="relative">
        <div v-if="loading" class="flex items-center justify-center absolute pin z-50 bg-white"><loader class="text-60" /></div>

        <file-icon
            v-if="showIcon"
            :type="'pdf'"
            class="block w-1/4 m-auto"
        />

        <div ref="imageTarget"></div>

        <!-- <div class="missing p-8" v-if="missing">
            <p class="text-center leading-normal">
                <a :href="file.name" class="text-primary dim" target="_blank">{{__('This image')}}</a> {{__('could not be found.')}}
            </p>
        </div> -->
    </div>
</template>

<script>
import { Minimum } from 'laravel-nova'

export default {
    props: {
        file: Object,
    },

    data: () => ({
        loading: true,
        // missing: false,
        showIcon: false,
    }),

    mounted() {
        Minimum(
            Nova.request().get(this.file.url, {
                responseType: 'blob',
            })
        )
        .then(({ headers, data }) => {
            let blob = new Blob([data], {
                type: headers['content-type']
            })

            let img = new Image()
            img.src = URL.createObjectURL(blob)
            this.$refs.imageTarget.appendChild(img)

            this.loading = false
        })
        .catch(error => {
            // this.missing = true
            // this.$emit('missing')
            // this.loading = false

            console.log(error)

            this.showIcon = true

            // let img = new Image()
            // img.src = this.file.image
            // this.$refs.imageTarget.appendChild(img)

            this.loading = false
        })
    },
}
</script>

<template>
    <div class="listitem w-full p-1 border-b-2 border-50" @click="handleClick">
        <div class="flex h-12 text-70 hover:text-primary cursor-pointer overflow-hidden opacity">

            <file-icon
                :type="file.mime"
                class="h-3/4 w-12 ml-1 mr-8 my-auto"
            />

            <div class="w-1/2 py-1 mr-8 my-auto text-black truncate" :title="file.basename">
                {{ file.basename }}
            </div>

            <div class="w-24 py-1 mr-8 my-auto text-80 truncate" :title="file.size_string">
                {{ file.size_string }}
            </div>

            <div class="w-48 py-1 my-auto text-80 truncate" :title="formatDate(file.timestamp)">
                {{ formatDate(file.timestamp) }}
            </div>

            <div v-if="context == 'field' || context == 'cms'" class="py-1 my-auto ml-8 text-90 font-bold click-to-add">
                Click to add
            </div>

        </div>
    </div>
</template>

<script>
export default {
    props: {
        file: Object,
        context: String,
    },

    methods: {
        formatDate(timestamp) {
            return moment.unix(timestamp).format('ddd MMM D YYYY, h:mm A')
        },

        handleClick() {
            let event = this.file.mime == 'folder' ? 'navigate' : 'preview'
            this.$emit(event, this.file)
        },
    },
}
</script>

<style scoped>
.w-24 {
    width: 6rem;
}
.h-3\/4 {
    height: 75%;
}
.hover\:text-primary {
    transition: color .1s;
}
.listitem:last-child {
    border-bottom: none !important;
}

.opacity .click-to-add {
    opacity: 0;
    transition: all .1s;
}
.opacity:hover .click-to-add {
    opacity: 1;
}
</style>

<template>
    <div class="flex items-center overflow-hidden mr-4">
        <span
            class="text-primary dim cursor-pointer"
            @click="navigateToHome('/')"
        >
            Home
        </span>
        <span v-if="pathParts.length" class="text-60 mx-2">/</span>

        <div class="rtl truncate text-80 text-right whitespace-no-wrap">
            <template v-for="(part, index) in pathParts">
                <span v-if="index == pathParts.length-1">{{ part }}</span>

                <span
                    v-else
                    class="text-primary dim cursor-pointer"
                    @click="navigateToSegment(part)"
                >
                    {{ part }}
                </span>

                <span v-if="index < pathParts.length-1" class="text-60 mx-2">/</span>
            </template>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        path: String,
    },

    computed: {
        pathParts() {
            return this.path.split('/').filter(part => part != '')
        },
    },

    methods: {
        navigateToHome() {
            this.$emit('navigate', '/')
        },

        navigateToSegment(segment) {
            let precedingParts = this.pathParts.splice(0, this.pathParts.indexOf(segment))
            let path = precedingParts.join('/') + '/' + segment
            this.$emit('navigate', path)
        },
    },
}
</script>

<style scoped>
.overflow-x-auto {
    scrollbar-width: none;
    -ms-overflow-style: none;
}
.overflow-x-auto::-webkit-scrollbar {
    width: 0;
}
.rtl {
    direction: rtl;
}
</style>

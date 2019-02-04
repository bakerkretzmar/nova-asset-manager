<template>
    <div class="w-full h-2 rounded-full bg-20 overflow-hidden">
        <div class="progress-bar h-2 bg-primary-30 rounded-full" :style="`width: ${progress}%;`"></div>
    </div>
</template>

<script>
export default {
    props: {
        progress: Number,
    },
}
</script>

<style scoped>
.progress-bar {
    transition: width .1s;
}
.bg-primary-30 {
    background-color: var(--primary-30);
}
</style>

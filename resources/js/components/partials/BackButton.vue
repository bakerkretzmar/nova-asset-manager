<template>
    <button
        class="btn btn-default btn-primary flex items-center mr-4 px-3"
        @click.prevent="$emit('back')"
    >
        <svg class="svg-shadow pb-px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="currentColor" d="M5.41 11H21a1 1 0 0 1 0 2H5.41l5.3 5.3a1 1 0 0 1-1.42 1.4l-7-7a1 1 0 0 1 0-1.4l7-7a1 1 0 0 1 1.42 1.4L5.4 11z"/></svg>
    </button>
</template>

<template>
    <div class="flex items-center ml-auto">
        <button
            @click.prevent="$emit('set', 'list')"
            :class="{ 'text-black' : view == 'list' }"
            class="btn btn-link inline-flex items-center mr-4 text-60 hover:text-80"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="currentColor" d="M7.5,5H20c0.6,0,1,0.4,1,1s-0.4,1-1,1H7.5c-0.6,0-1-0.4-1-1S7,5,7.5,5z M4,5L4,5c0.6,0,1,0.4,1,1S4.6,7,4,7h0 C3.5,7,3,6.6,3,6S3.5,5,4,5z M4,11L4,11c0.6,0,1,0.4,1,1c0,0.6-0.4,1-1,1h0c-0.6,0-1-0.4-1-1C3,11.4,3.5,11,4,11z M4,17L4,17 c0.6,0,1,0.4,1,1s-0.4,1-1,1h0c-0.6,0-1-0.4-1-1S3.5,17,4,17z M7.5,11H20c0.6,0,1,0.4,1,1s-0.4,1-1,1H7.5c-0.6,0-1-0.4-1-1 S7,11,7.5,11z M7.5,17H20c0.6,0,1,0.4,1,1s-0.4,1-1,1H7.5c-0.6,0-1-0.4-1-1S7,17,7.5,17z"/></svg>
        </button>
        <button
            @click.prevent="$emit('set', 'grid')"
            :class="{ 'text-black' : view == 'grid' }"
            class="btn btn-link inline-flex items-center text-60 hover:text-80"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="currentColor" d="M5 3h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2zm0 2v4h4V5H5zm10-2h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2zm0 2v4h4V5h-4zM5 13h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4c0-1.1.9-2 2-2zm0 2v4h4v-4H5zm10-2h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-4c0-1.1.9-2 2-2zm0 2v4h4v-4h-4z"/></svg>
        </button>
    </div>
</template>

<script>
export default {
    props: {
        view: {
            type: String,
            default: 'grid',
        },
    },
}
</script>

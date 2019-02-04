<template>
    <component :is="computedType"/>
</template>

<script>
import File from './File'
import FileArchive from './FileArchive'
import FileAudio from './FileAudio'
import FileExcel from './FileExcel'
import FileImage from './FileImage'
import FilePdf from './FilePdf'
import FilePowerpoint from './FilePowerpoint'
import FileVideo from './FileVideo'
import FileWord from './FileWord'

export default {
    props: {
        type: String,
    },

    components: {
        'generic-file': File,
        'zip-file': FileArchive,
        'audio-file': FileAudio,
        'excel-file': FileExcel,
        'image-file': FileImage,
        'pdf-file': FilePdf,
        'powerpoint-file': FilePowerpoint,
        'video-file': FileVideo,
        'word-file': FileWord,
    },

    computed: {
        computedType() {
            if (['zip', 'audio', 'excel', 'image', 'pdf', 'powerpoint', 'video', 'word'].includes(this.type)) {
                return `${this.type}-file`
            }
            return 'generic-file'
        },
    },
}
</script>

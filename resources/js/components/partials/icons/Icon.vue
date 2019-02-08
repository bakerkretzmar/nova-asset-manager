<template>
    <component :is="computedType"/>
</template>

<script>
import File from './File'
import FileArchive from './FileArchive'
import FileAudio from './FileAudio'
import FileExcel from './FileExcel'
import FileFolder from './FileFolder'
import FileImage from './FileImage'
import FilePdf from './FilePdf'
import FilePowerpoint from './FilePowerpoint'
import FileText from './FileText'
import FileVideo from './FileVideo'
import FileWord from './FileWord'

export default {
    props: {
        type: String,
    },

    components: {
        'generic-file': File,
        'archive-file': FileArchive,
        'audio-file': FileAudio,
        'doc-excel-file': FileExcel,
        'folder-file': FileFolder,
        'image-file': FileImage,
        'pdf-file': FilePdf,
        'doc-powerpoint-file': FilePowerpoint,
        'text-file': FileText,
        'video-file': FileVideo,
        'doc-word-file': FileWord,
    },

    computed: {
        computedType() {
            if (['archive', 'audio', 'doc-excel', 'folder', 'image', 'pdf', 'doc-powerpoint', 'text', 'video', 'doc-word'].includes(this.type)) {
                return `${this.type}-file`
            }
            return 'generic-file'
        },
    },
}
</script>

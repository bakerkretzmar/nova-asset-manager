<template>
    <panel-item :field="field" :full-width-content="true">
        <template slot="value">
            <p v-if="!field.value || !field.value.length" class="text-60 italic">
                None
            </p>

            <div v-else class="flex flex-wrap">

                <DetailGridItem
                    v-for="file in field.value"
                    :key="file.id"
                    :file="file"
                />

            </div>
        </template>
    </panel-item>
</template>

<script>
import DetailGridItem from './partials/DetailFieldGridItem'

export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    components: { DetailGridItem },
}
</script>

<script setup>
import {PublishForm} from '@statamic/cms/ui';

defineProps([
    'blueprint', // Required
    'meta', // Required
    'submitUrl',
    'title',
    'values' // Required
]);
</script>

<template>
    <PublishForm
        :blueprint="blueprint"
        icon="dictionary-language-book"
        :initialMeta="meta"
        :initialValues="values"
        submitMethod="POST"
        :submitUrl="submitUrl"
        :title="title"
    >

    </PublishForm>
</template>

<style scoped>

</style>

<script setup>
import {Head} from '@statamic/cms/inertia';
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
    <Head :title="__('statamic-translation-manager::default.translation_manager')"/>

    <PublishForm
        :blueprint="blueprint"
        icon="dictionary-language-book"
        :initialMeta="meta"
        :initialValues="values"
        submitMethod="POST"
        :submitUrl="submitUrl"
        :title="title"
    />
</template>

<style scoped>

</style>

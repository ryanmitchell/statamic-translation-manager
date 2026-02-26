<script setup>
import {Head, Link} from '@statamic/cms/inertia';
import {
    Button,
    Card,
    CardPanel,
    Header,
    Heading,
    Icon,
    Panel,
    Subheading
} from '@statamic/cms/ui';

defineProps([
    'blueprintsUrl',
    'locales',
    'templatesUrl',
    'title',
]);
</script>

<template>
    <Head :title="__('Translation Manager')"/>

    <Header :title="__('Translation Manager')" icon="dictionary-language-book"/>

    <Panel heading="Manage Translations">
        <Card class="flex gap-4">
            <Button class="m-0" v-for="locale in locales" :href="`/translations-manager/${locale.name}/edit`" :text="locale.name" />
        </Card>
    </Panel>

    <CardPanel :heading="__('Check for missing translations')">
        <div class="flex flex-wrap starting-style-transition-children">

            <Link
                :href="blueprintsUrl"
                class="group w-full items-start rounded-md border border-transparent px-2 py-3 lg:p-4 hover:bg-gray-100 dark:hover:border-dark-400 dark:hover:bg-dark-575 md:flex lg:w-1/2"
            >
                <div class="size-6 text-gray-400 mt-1 mb-2 me-4">
                    <Icon :class="['size-6', 'text-gray-400', 'mt-1', 'mb-2', 'me-4']" name="blueprints" set="default"/>
                </div>
                <div class="flex-1 md:me-6">
                    <Heading size="lg" :text="__('statamic-translation-manager::default.scan_blueprints')"/>
                    <Subheading v-text="__('statamic-translation-manager::default.scan_blueprints_desc')"/>
                </div>
            </Link>

            <Link
                :href="templatesUrl"
                class="group w-full items-start rounded-md border border-transparent px-2 py-3 lg:p-4 hover:bg-gray-100 dark:hover:border-dark-400 dark:hover:bg-dark-575 md:flex lg:w-1/2"
            >
                <div class="size-6 text-gray-400 mt-1 mb-2 me-4">
                    <Icon class="size-6 text-gray-400 mt-1 mb-2 me-4" name="blueprints" set="default"/>
                </div>
                <div class="flex-1 md:me-6">
                    <Heading size="lg" :text="__('statamic-translation-manager::default.scan_templates')"/>
                    <Subheading v-text="__('statamic-translation-manager::default.scan_templates_desc')"/>
                </div>
            </Link>
        </div>
    </CardPanel>

</template>

<style scoped>

</style>

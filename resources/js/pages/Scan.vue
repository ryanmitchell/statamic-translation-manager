<script setup>
import {Head} from '@statamic/cms/inertia';
import {Alert, Button, Card, Header, Heading, Panel} from '@statamic/cms/ui';

defineProps([
    'addToPackText',
    'missing',
    'missingTranslationsHeading',
    'noneMissingMsg',
    'scanType',
    'title'
]);

</script>

<template>
    <Head :title="title"/>
    <Header icon="dictionary-language-book" :title="title"/>

    <Alert v-if="!Object.keys(missing).length"
           :heading="noneMissingMsg"
           icon="checkmark"
           variant="success"
    />

    <Panel v-else :heading="missingTranslationsHeading">
        <Card v-for="(terms, locale) in missing" :key="locale" class="mb-4">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <span class="font-bold mr-2 text-xl">{{ locale }}</span>
                    <span class="font-normal text-sm">[{{ terms.length }}]</span>
                </div>
                <Button
                    :href="`/cp/translations/${scanType}/${locale}/add`"
                    :text="addToPackText"
                />
            </div>
            <hr>
            <div style="max-height:400px; overflow-y:scroll">
                <ol style="list-style-type:decimal;margin-left:4rem;">
                    <li v-for="term in terms" class="p-2">
                        {{ term }}
                    </li>
                </ol>
            </div>
        </Card>

    </Panel>
</template>

<style scoped>

</style>

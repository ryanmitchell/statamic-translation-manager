import Index from './pages/Index.vue'

Statamic.booting(() => {
    Statamic.$inertia.register('statamic-translation-manager::Index', Index);
});

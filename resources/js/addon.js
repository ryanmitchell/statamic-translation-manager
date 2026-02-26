import Edit from './pages/Edit.vue'
import Index from './pages/Index.vue'

Statamic.booting(() => {
    Statamic.$inertia.register('statamic-translation-manager::Edit', Edit);
    Statamic.$inertia.register('statamic-translation-manager::Index', Index);
});

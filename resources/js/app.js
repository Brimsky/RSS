import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        // Resolve the page component from the pages directory
        const page = resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'));
        console.log(`Resolving component: ./Pages/${name}.vue`);  // Debugging line
        return page.then(module => {
            console.log(`Component ${name} loaded successfully.`);  // Debugging successful loading
            return module;
        });
    },
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);

        console.log('Vue app initialized with Inertia and ZiggyVue.');  // Debugging Vue initialization

        return vueApp;
    },
    progress: {
        color: '#4B5563',
    },
});

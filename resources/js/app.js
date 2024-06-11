import 'flowbite';
import vuetify from "../plugins/vuetify";
import '@mdi/font/css/materialdesignicons.css'

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
// import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

const appName = import.meta.env.VITE_APP_NAME || 'CSF';

createInertiaApp({
    title: (title) => `${appName} - ${title} `,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(vuetify)
            // .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#de2f14',
    },
    
});


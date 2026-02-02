import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import 'remixicon/fonts/remixicon.css'
// import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

const appName = 'CRMS';

createInertiaApp({
    title: (title) => title ? `${appName} | ${title}` : appName,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            // .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#de2f14',
    },

});


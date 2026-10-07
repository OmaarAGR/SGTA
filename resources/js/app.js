import '../css/app.css';

import { createInertiaApp, router } from '@inertiajs/vue3';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initFlowbite } from 'flowbite';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const displayDate = date => new Date(date).toLocaleString('es-CO', { day: 'numeric', month: 'long', year: 'numeric' });
const displayTime = (time, second) => new Date(time).toLocaleString('es-CO', { hour: '2-digit', minute: 'numeric', second });
const displayDatetime = (datetime, second) => new Date(datetime).toLocaleString('es-CO', { day: 'numeric', month: 'numeric', year: 'numeric', hour: '2-digit', minute: 'numeric', second });

const DisplayPlugin = {
    install: app => {
        app.config.globalProperties.displayDate = displayDate;
        app.config.globalProperties.displayTime = displayTime;
        app.config.globalProperties.displayDatetime = displayDatetime;
        app.provide('displayDate', displayDate);
        app.provide('displayTime', displayTime);
        app.provide('displayDatetime', displayDatetime);
    }
};

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(DisplayPlugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: true,
});

// Initialize Flowbite on first load
initFlowbite();

// Initialize Flowbite on every Inertia page load
router.on('finish', () => {
    initFlowbite();
});

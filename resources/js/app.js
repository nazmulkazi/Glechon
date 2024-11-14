import './bootstrap';
import '../css/app.css';

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap/dist/js/bootstrap.js';

// import '../css/bootstrap.min.css';
// import './bootstrap.bundle.min.js'

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, app, props, plugin }) {
        const vueApp = createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy);
        
        vueApp.config.globalProperties.can = function(ability) {
            const user = this.$page.props.user;
            const [action, domain] = ability.split('-');
            return user.role === 'admin' || (action === 'any' && user.permissions[domain]?.length > 0) || user.permissions[domain]?.includes(action);
        };
        
        return vueApp.mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });

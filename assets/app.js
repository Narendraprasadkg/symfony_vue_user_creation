/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue-next/dist/bootstrap-vue-next.css';
import './bootstrap';


import { createApp } from 'vue';
import App from './components/App.vue';
import router from './router';
import store from './store/store';

import { createBootstrap } from 'bootstrap-vue-next';
const bootstrap = createBootstrap();

const app = createApp(App)
    .use(store)
    .use(router)
    .use(bootstrap)
    .mount('#app');

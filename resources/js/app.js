/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */

import('./bootstrap');
import Vue from 'vue';

import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';

//Route information for Vue Router
import Routes from '@/js/routes';

//icons for material design
import 'material-design-icons/iconfont/material-icons.css'

//for http api
import axios from 'axios';

Vue.use(Vuetify, axios);
Vue.prototype.$http = axios;

//Component File
import App from '@/js/views/App';

//components:
//card component for departments
Vue.component(
    'departments',
    require('@/js/components/Department').default,
    );

const opts = {}

const app = new Vue({
    el:'#app',
    vuetify: new Vuetify(opts),
    router: Routes,
    render: h => h(App),
});

export default app;

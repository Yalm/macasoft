//import './bootstrap';
import './material';
import Vue from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import Axios from 'axios';
import VeeValidate, { Validator } from 'vee-validate';
import es from 'vee-validate/dist/locale/es';

Vue.component('router-link', Vue.options.components.RouterLink);
Vue.component('router-view', Vue.options.components.RouterView);

// Use VeeValidate in spanish
Vue.use(VeeValidate);
Validator.localize('es', es);

Vue.prototype.$http = Axios;
const token = localStorage.getItem('token');
if (token) {
    Vue.prototype.$http.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

Vue.component('pagination-material', require('./components/PaginationComponent.vue').default);

const app = new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app');

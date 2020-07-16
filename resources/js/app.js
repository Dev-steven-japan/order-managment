/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * ElementUI - biblioteca para interfaz usuario
 */
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import locale from 'element-ui/lib/locale/lang/es'

Vue.use(ElementUI, { locale })

/**
 * SweetAlert2 - biblioteca para ventanas emergentes
 */
import Swal from 'sweetalert2'
window.Swal = Swal;

/**
 * Vuesax - biblioteca para interfaz de usuario
 */
import Vuesax from 'vuesax'
import 'vuesax/dist/vuesax.css' //Vuesax styles
Vue.use(Vuesax, {
    // options here
})

/**
 * EventBus - biblioteca para comunicacion entre componentes
 */
export const EventBus = new Vue();
window.EventBus = EventBus;

/**
 * Howler - Biblioteca para reproducir audio desde el lado del cliente
 */
import Howler from 'howler'
window.Howler = Howler;

/**
 * moment- Biblioteca para formatear fechas y horas
 */
import moment from 'moment';
moment.locale('es');
window.moment = moment;

Vue.component('App', require('./components/App.vue').default);
Vue.component('Auth', require('./components/Auth.vue').default);


import router from './routes'

const app = new Vue({
    el: '#app',
    router
});
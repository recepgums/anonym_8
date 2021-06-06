require('./bootstrap');
import Vue from 'vue';

Vue.component('example-component',require('./components/ExampleComponent.vue').default);
Vue.component('privateroom', require('./components/PrivateRoom.vue'));
/*Vue.component('quiz', require('./components/quiz.vue'));*/
import privateroom from './components/PrivateRoom.vue' //Importing
import ExampleComponent from './components/ExampleComponent.vue' //Importing
import BootstrapVue from 'bootstrap-vue' //Importing

import moment from 'moment'
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';


Vue.prototype.moment = moment;

Vue.use(ElementUI);
Vue.use(BootstrapVue) // Telling Vue to use this in whole application
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    components:{ExampleComponent,privateroom}
});

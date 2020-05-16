import NotFound from "./components/NotFound";

require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import { Form, HasError, AlertError } from 'vform';
import moment from 'moment';
import VueProgressBar from 'vue-progressbar';
import Swal from 'sweetalert2'

import Gate from './Gate';
Vue.prototype.$gate = new Gate(window.user);


Vue.use(VueRouter);
Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '5px'
});

Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
Vue.component('pagination', require('laravel-vue-pagination'));

window.Form = Form;
window.Swal= Swal;
window.Fire = new Vue();

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

window.Toast= Toast;

import HomeComponent from "./components/HomeComponent";
import ProfileComponent from "./components/ProfileComponent";
import DeveloperComponent from "./components/DeveloperComponent";
import UserComponent from "./components/UserComponent";
import NotFoundComponent from "./components/NotFound";

const routes = [
    { path: '/home', component: HomeComponent },
    { path: '/developer', component: DeveloperComponent },
    { path: '/profile', component: ProfileComponent },
    { path: '/users', component: UserComponent },
    { path: '/*', component: NotFoundComponent }, // not found path define as *
];

const router = new VueRouter({
    routes,
    linkActiveClass: "active",
    mode: 'history'
});

Vue.filter('upText',function(text){
    return text.toUpperCase();
});

Vue.filter('myDate',function(created) {
    return moment(created).format('MMM Do YYYY');
});

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'not-found',
    require('./components/NotFound.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);

const app = new Vue({
    el: '#app',
    router,
    data : {
        search: ''
    },
    methods: {
        searchit (){
            setTimeout(function () {
                Fire.$emit('searching');
            }, 1000);
        }
    }
});

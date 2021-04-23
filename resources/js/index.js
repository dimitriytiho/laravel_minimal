// require('./bootstrap');
// window.Vue = require('vue');

// Замена Bootstrap JS
// npm install bootstrap.native
//window.BSN = require('bootstrap.native/dist/bootstrap-native.min')

// Animate On Scroll
// npm install aos
/*window.AOS = require('aos')
// data-aos="fade-up" fade-down-right flip-left zoom-in
AOS.init({
    duration: 500
})*/

// Parallax
// npm install simple-parallax-js
/*window.simpleParallax = require('simple-parallax-js')
var simpleParallax6 = document.getElementsByClassName('simple_parallax_6');
new simpleParallax(simpleParallax6, {
    delay: .6,
    transition: 'cubic-bezier(0,0,0,1)'
})*/

import './default'
import './components'
import './theme'



// import Vue from 'vue'

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));
// Vue.component('example-component', require('./components/ExampleComponent.vue').default)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/*new Vue({
    el: '#app'
})*/

require('./bootstrap');

import Vue from 'vue'
Vue.component('upload-component', require('./components/UploadComponent.vue').default);
Vue.component('modal-mapping', require('./components/MappingModalComponent.vue').default);
const app = new Vue({
    el: '#app',
});
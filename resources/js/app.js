require('./bootstrap');


// window.Vue = require('vue');
import  Vue from  'vue';

// Register our components (in the next step)

Vue.component('status-board', require('./components/status/BoardStatus.vue').default);
Vue.component('kanban-board', require('./components/tasks/kanbanBoard.vue').default);



const app = new Vue({
    el: "#app"
});
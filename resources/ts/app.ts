import Vue from 'vue';
import bootstrap from './bootstrap';
import EmployeesComponent from './components/EmployeesComponent.vue';
import EmployeeComponent from './components/EmployeeComponent.vue';
import Notifications from 'vue-notification';

bootstrap();

Vue.use(Notifications);

new Vue({
  el: '#app',
  components: { EmployeesComponent, EmployeeComponent },
});

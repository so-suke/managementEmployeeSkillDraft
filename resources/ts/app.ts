import Vue from 'vue';
import bootstrap from './bootstrap';
import EmployeesComponent from './views/EmployeesComponent.vue';
import EmployeeComponent from './views/EmployeeComponent.vue';
import Notifications from 'vue-notification';
import { extend, ValidationProvider, localize } from 'vee-validate';
import { required, min } from 'vee-validate/dist/rules';
import { exists } from "./utils/validation";
/* fontawesome */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { fab } from '@fortawesome/free-brands-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';


library.add(fas, far, fab);

extend('requiredSelect', {
  ...required,
  message(field, values) {
    return `${field}を選択しましょう`;
  }
});

const extendSkillUnique = (name: string, existsFunc: Function) => {
  extend(name, {
    validate: async (skillId) => {
      return await existsFunc(skillId);
    },
    message(field) {
      return `この${field}は既に登録されております`;
    }
  });
}

extendSkillUnique('uniqueSkillLanguage', exists.language);
extendSkillUnique('uniqueSkillFramework', exists.framework);
extendSkillUnique('uniqueSkillOther', exists.other);

bootstrap();

Vue.use(Notifications);

Vue.component('font-awesome-icon', FontAwesomeIcon)
new Vue({
  el: '#app',
  components: { EmployeesComponent, EmployeeComponent, ValidationProvider },
});

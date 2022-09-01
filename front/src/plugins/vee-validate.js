import Vue from 'vue';
import { ValidationObserver, ValidationProvider, extend, localize } from 'vee-validate';
import { required, max } from 'vee-validate/dist/rules';
import ru from 'vee-validate/dist/locale/ru.json';

extend('required', required);
extend('max', max);
localize('ru', ru);

Vue.component('veeObserver', ValidationObserver);
Vue.component('veeProvider', ValidationProvider);

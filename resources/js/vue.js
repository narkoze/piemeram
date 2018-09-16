/**
 * Vue is a modern JavaScript library for building interactive web interfaces
 * using reactive data binding and reusable components. Vue's API is clean
 * and simple, leaving you to focus on building your next great project.
 */

import Vue from 'vue'
import VueInternationalization from 'vue-i18n'
import lang from './vue-i18n-locales.generated'

Vue.use(VueInternationalization)

window.i18n = new VueInternationalization({
  locale: window.Laravel.locale,
  messages: lang
})

window.Vue = Vue

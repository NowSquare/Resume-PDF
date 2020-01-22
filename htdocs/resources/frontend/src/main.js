'use strict';

//import '@babel/polyfill'
import 'core-js/stable'
import 'regenerator-runtime/runtime'
import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import vuetify from './plugins/vuetify'
import VueAuth from '@websanova/vue-auth'
import VueAxios from 'vue-axios'
import VueRouter from 'vue-router'
import axios from './plugins/axios'
import i18n from '@/plugins/i18n'
import auth from '@/plugins/auth'
import AOS from 'aos'
import 'aos/dist/aos.css'
import '@/plugins/moment'
import '@/plugins/lodash'
import '@/components/components'
import 'roboto-fontface/css/roboto/roboto-fontface.css'
import '@mdi/font/css/materialdesignicons.css'

// Global Vue config
Vue.config.productionTip = false

// Global variables
Vue.prototype.$init = window.init

// Animate On Scroll
const aosPlugin = {
  install: () => {
    AOS.init({
      mirror: false,
      once: true
    })
  },
}

Vue.use(aosPlugin)

// Auth
Vue.router = router
Vue.use(VueRouter)
Vue.use(VueAxios, axios)
Vue.use(VueAuth, auth)

// Toggle .dark class on html tag
let dark = store.state.app.dark
var root = document.documentElement
if (dark) {
  root.classList.add('dark')
} else {
  root.classList.remove('dark')
}

// Check installation
axios
  .post('/ping')
  .then(response => {
    if (typeof response.data.redirect !== 'undefined') {
      router.push({ name: response.data.redirect })
    }
  })

new Vue({
  router,
  store,
  vuetify,
  axios,
  i18n,
  auth,
  render: h => h(App)
}).$mount('#app')

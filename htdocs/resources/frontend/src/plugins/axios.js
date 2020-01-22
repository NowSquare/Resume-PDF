'use strict';

import Vue from 'vue'
import VueAxios from 'vue-axios'

let axios = require('axios')
axios.defaults.baseURL = window.init.config.root + '/api' // process.env.VUE_APP_URL
axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest'
}

Vue.use(VueAxios, axios)

export default axios
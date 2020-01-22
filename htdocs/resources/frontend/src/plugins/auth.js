'use strict';

const config = {
  auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
  http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
  router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
  tokenDefaultName: 'auth',
  tokenStore: ['localStorage'],
  rolesVar: 'role',
  authRedirect: {path: '/login'},
  forbiddenRedirect: {path: '/404'}, 
  loginData: {url: 'auth/login', method: 'POST', redirect: '', fetchUser: true},
  logoutData: {url: 'auth/logout', method: 'POST', redirect: '/', makeRequest: true},
  fetchData: {url: 'auth/user', method: 'GET', enabled: true},
  refreshData: {url: 'auth/refresh', method: 'GET', enabled: true, interval: 30},
  notFoundRedirect: {path: '/404'}, // https://github.com/websanova/vue-auth/blob/master/docs/Privileges.md
}

export default config
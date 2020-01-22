'use strict';

import Vue from 'vue'
import VueRouter from 'vue-router'
import NProgress from 'nprogress'
import VueAnalytics from 'vue-analytics'

import MasterLayout from '../views/layouts/Master.vue'
import CleanLayout from '../views/layouts/Clean.vue'
import Home from '../views/Home.vue'

import i18n from '@/plugins/i18n'
import { loadLanguageAsync } from '@/plugins/i18n'

const routes = [
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '',
      name: 'home',
      components: {
        primary: Home
      }
    }],
    meta: {
      auth: null
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/about',
      name: 'about',
      components: {
        primary: () => import(/* webpackChunkName: "about" */ '../views/About.vue'),
      }
    }],
    meta: {
      auth: null
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/terms-and-conditions',
      name: 'terms',
      components: {
        primary: () => import(/* webpackChunkName: "terms" */ '../views/legal/Terms.vue'),
      }
    }],
    meta: {
      auth: null
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/privacy-policy',
      name: 'privacy',
      components: {
        primary: () => import(/* webpackChunkName: "privacy" */ '../views/legal/Privacy.vue'),
      }
    }],
    meta: {
      auth: null
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/faq',
      name: 'faq',
      components: {
        primary: () => import(/* webpackChunkName: "faq" */ '../views/contact/Faq.vue'),
      }
    }],
    meta: {
      auth: null
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/contact',
      name: 'contact',
      components: {
        primary: () => import(/* webpackChunkName: "contact" */ '../views/contact/Contact.vue'),
      }
    }],
    meta: {
      auth: null
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/login',
      name: 'login',
      components: {
        primary: () => import(/* webpackChunkName: "login" */ '../views/auth/Login.vue'),
      }
    }],
    meta: {
      auth: false
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/register',
      name: 'register',
      components: {
        primary: () => import(/* webpackChunkName: "register" */ '../views/auth/Register.vue'),
      }
    }],
    meta: {
      auth: false
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/password/reset',
      name: 'password.email',
      components: {
        primary: () => import(/* webpackChunkName: "password.email" */ '../views/auth/password/Email.vue'),
      }
    }],
    meta: {
      auth: false
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/password/reset/:token',
      name: 'password.reset',
      components: {
        primary: () => import(/* webpackChunkName: "password.reset" */ '../views/auth/password/Reset.vue'),
      }
    }],
    meta: {
      auth: false
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/account/profile',
      name: 'profile',
      components: {
        primary: () => import(/* webpackChunkName: "profile" */ '../views/auth/Profile.vue'),
      }
    }],
    meta: {
      auth: { roles: [1] }
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/profile',
      name: 'user.profile',
      components: {
        primary: () => import(/* webpackChunkName: "user.profile" */ '../views/user/Profile.vue'),
      }
    }],
    meta: {
      auth: { roles: [2] }
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/dashboard',
      name: 'user.dashboard',
      components: {
        primary: () => import(/* webpackChunkName: "user.dashboard" */ '../views/user/Dashboard.vue'),
      }
    }],
    meta: {
      auth: { roles: [2] }
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/tags',
      name: 'user.tags',
      components: {
        primary: () => import(/* webpackChunkName: "user.tags" */ '../views/user/Tags.vue'),
      }
    }],
    meta: {
      auth: { roles: [2] }
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/experience',
      name: 'user.experience',
      components: {
        primary: () => import(/* webpackChunkName: "user.experience" */ '../views/user/ResumeExperience.vue'),
      }
    }],
    meta: {
      auth: { roles: [2] }
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/projects',
      name: 'user.projects',
      components: {
        primary: () => import(/* webpackChunkName: "user.projects" */ '../views/user/ResumeProjects.vue'),
      }
    }],
    meta: {
      auth: { roles: [2] }
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/dashboard',
      name: 'user.dashboard',
      components: {
        primary: () => import(/* webpackChunkName: "user.dashboard" */ '../views/user/Dashboard.vue'),
      }
    }],
    meta: {
      auth: { roles: [2] }
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/admin/dashboard',
      name: 'admin.dashboard',
      components: {
        primary: () => import(/* webpackChunkName: "admin.dashboard" */ '../views/admin/Dashboard.vue'),
      }
    }],
    meta: {
      auth: { roles: [1] }
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/admin/users',
      name: 'admin.users',
      components: {
        primary: () => import(/* webpackChunkName: "admin.users" */ '../views/admin/Users.vue'),
      }
    }],
    meta: {
      auth: { roles: [1] }
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/404',
      name: '404',
      components: {
        primary: () => import(/* webpackChunkName: "404" */ '../views/error/404.vue'),
      }
    }],
    meta: {
      auth: null
    }
  },
  {
    path: '/',
    component: CleanLayout,
    children: [{
      path: '/install',
      name: 'install',
      components: {
        primary: () => import(/* webpackChunkName: "install" */ '../views/install/Install.vue'),
      }
    }],
    meta: {
      auth: null
    }
  },
  { path: '*', redirect: '/404', hidden: true } // Catch unkown routes
]

const router = new VueRouter({
  mode: 'history',
  scrollBehavior: () => ({ y: 0 }),
  routes: routes
})

if (typeof window.init.config.ga !== 'undefined' && window.init.config.ga != '') {
  Vue.use(VueAnalytics, {
    id: window.init.config.ga,
    router
  })
}

NProgress.configure({ showSpinner: false });

// This callback runs before every route change, including on page load.
router.beforeEach((to, from, next) => {
  loadLanguageAsync(i18n.locale).then(() => next())
})

router.beforeResolve((to, from, next) => {
  if (to.name != from.name) {
    NProgress.start()
    document.title = window.init.app.name
  }
  next()
})

router.afterEach((to) => {
  if (to.name) {
    NProgress.done()
  }
})

export default router

'use strict';

import Cookies from 'js-cookie'

const app = {
  state: {
    loading: true,
    init: window.init,
    dark: (Cookies.get('dark') === 'true') ? true : false || false,
    showCookieConsent: (Cookies.get('showCookieConsent') === 'false') ? false : true || true,
    language: Cookies.get('language') || (navigator.language || navigator.userLanguage),
    dashboardDrawer: false,
  },
  mutations: {
    SET_LOADING: (state, loading) => {
      state.loading = loading
    },
    SET_LANGUAGE: (state, language) => {
      state.language = language
      Cookies.set('language', language)
    },
    SET_COOKIE_CONSENT: (state, showCookieConsent) => {
      state.showCookieConsent = showCookieConsent
      Cookies.set('showCookieConsent', showCookieConsent, { expires: 360, path: '/' })
    },
    SET_DARK_THEME: (state, dark) => {
      state.dark = dark
      // Toggle .dark class on html tag
      var root = document.documentElement
      if (dark) {
        root.classList.add('dark')
      } else {
        root.classList.remove('dark')
      }
      Cookies.set('dark', dark, { expires: 360, path: '/' })
    },
    SET_DASHBOARD_DRAWER: (state, dashboardDrawer) => {
      state.dashboardDrawer = dashboardDrawer
    }
  },
  actions: {
    setLoading({ commit }, loading) {
      commit('SET_LOADING', loading)
    },
    setLanguage({ commit }, language) {
      commit('SET_LANGUAGE', language)
    },
    setCookieConsent({ commit }, showCookieConsent) {
      commit('SET_COOKIE_CONSENT', showCookieConsent)
    },
    setDark({ commit }, dark) {
      commit('SET_DARK_THEME', dark)
    },
    setDashboardDrawer({ commit }, dashboardDrawer) {
      commit('SET_DASHBOARD_DRAWER', dashboardDrawer)
    }
  }
}

export default app
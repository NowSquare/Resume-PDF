'use strict';

import Vue from 'vue'
import VueI18n from 'vue-i18n'
import axios from '@/plugins/axios'
import store from '@/store'
import { required, email, min, max } from 'vee-validate/dist/rules'
import { extend, ValidationObserver, ValidationProvider } from 'vee-validate'

Vue.use(VueI18n)

Vue.component('ValidationObserver', ValidationObserver)
Vue.component('ValidationProvider', ValidationProvider)

const fallbackLocale = window.init.config.language
const loadedLanguages = []

export const i18n = new VueI18n({
  silentTranslationWarn: true,
  locale: store.state.app.language,
  fallbackLocale: fallbackLocale
})

export function getAvailableLanguages () {
  return axios({ url: 'i18n/languages'})
    .then(response => {
      let languages = response.data
      return languages
    })/*
    .catch (function (error) {
      console.log('getAvailableLanguages error' + error)
    })*/
}

function setI18nLanguage (lang) {
  i18n.locale = lang
  i18n.fallbackLocale = fallbackLocale
  axios.defaults.headers.common['Accept-Language'] = lang
  document.querySelector('html').setAttribute('lang', lang)

  let veeValidateLang = lang
  if (veeValidateLang == 'pt') veeValidateLang = 'pt_BR'
  if (veeValidateLang == 'zh') veeValidateLang = 'zh_CN'

  import('vee-validate/dist/locale/' + veeValidateLang + '.json')
    .then(response => {
      let messages = response.messages
      setVeeValidateRules (messages)
    })
    .catch (function () {
      // Set default language
      import('vee-validate/dist/locale/' + window.init.config.language + '.json')
      .then(response => {
        let messages = response.messages
        setVeeValidateRules (messages)
      })
    })

  store.dispatch('setLoading', false)

  return lang
}

function setVeeValidateRules (messages) {
  extend('required', {
    ...required,
    message: messages['required']
  })

  extend('email', {
    ...email,
    message: messages['email']
  })

  extend('min', {
    ...min,
    message: messages['min']
  })

  extend('max', {
    ...max,
    message: messages['max']
  })

  /**
   * Custom rules that return always true,
   * used for server-side validation
   */

  extend('nullable', (value) => {
    return true;
  })

  extend('unique', (value) => {
    return true;
  })

  extend('not_in', (value) => {
    return true;
  })
}

export function loadLanguageAsync (lang, onlyLoadTranslation = false) {
  store.dispatch('setLoading', true)
  if (loadedLanguages.includes(lang)) {
    if (i18n.locale !== lang) setI18nLanguage(lang)
    store.dispatch('setLoading', false)
    return Promise.resolve()
  }

  return axios({ url: `i18n/${lang}`})
    .then(response => {
      let msgs = new Array
      msgs[lang] = response.data

      loadedLanguages.push(lang)
      i18n.setLocaleMessage(lang, msgs[lang])

      if (! onlyLoadTranslation) {
        setI18nLanguage(lang)
      }
    })
}

if (store.state.app.language == fallbackLocale) {
  /* Load and set language */
  loadLanguageAsync(store.state.app.language)
} else {
  /* Always load fallback lanuage */
  loadLanguageAsync(fallbackLocale, true)

  /* Load and set user selected language */
  loadLanguageAsync(store.state.app.language)
}

export default i18n

// Change language
/*
import { loadLanguageAsync } from '@/plugins/i18n'
loadLanguageAsync('en')
*/
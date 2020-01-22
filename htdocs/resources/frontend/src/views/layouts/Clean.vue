<template>
  <v-app>
    <v-content>
      <router-view name="primary"></router-view>
    </v-content>
    <v-overlay
      opacity="1"
      :dark="$store.state.app.dark"
      :color="($store.state.app.dark) ? 'grey darken-4' : 'grey lighten-5'"
      :value="$store.state.app.loading"
      z-index="99999"
    >
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
    <confirm ref="confirm"></confirm>
    <snackbar ref="snackbar"></snackbar>
  </v-app>
</template>
<script>
import { loadLanguageAsync } from '@/plugins/i18n'

export default {
  components: {
  },
  data: () => ({
  }),
  mounted () {
    this.$vuetify.theme.dark = this.$store.state.app.dark
    this.$root.$snackbar = this.$refs.snackbar.show
    this.$root.$confirm = this.$refs.confirm.open

    /* Set language */
    let language = this.$route.query.l || null
    if (language !== null) {
      loadLanguageAsync(language)
    }
  },
  methods: {
    switchDarkTheme () {
      let dark = this.$vuetify.theme.dark
      this.$vuetify.theme.dark = !dark
      this.$store.dispatch('setDark', !dark)
    },
    changeLang(language) {
      loadLanguageAsync(language)
      this.$store.dispatch('setLanguage', language)
    }
  },
  computed: {
    showCookieConsent () {
      return this.$store.state.app.showCookieConsent
    }
  }
};
</script>
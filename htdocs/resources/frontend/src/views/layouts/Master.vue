<template>
  <v-app
    v-resize="onResize"
  >
    <v-navigation-drawer
      v-model="drawer"
      fixed
      right
      floating
      temporary
    >
      <template v-slot:prepend>
        <div class="pa-2">
          <v-app-bar-nav-icon @click.stop="drawer = !drawer"><v-icon v-if="!drawer">mdi-minus</v-icon><v-icon v-if="drawer">mdi-close</v-icon></v-app-bar-nav-icon>
        </div>
      </template>

      <h3 class="title ma-4" v-html="$t('navigate')"></h3>

      <v-list
        class="ma-0 pa-0"
      >
        <v-list-item
          link
          exact 
          v-for="(item, i) in topMenu"
          :key="'nav_' + i"
          v-show="(item.showIfLoggedIn === true && $auth.check()) || (item.showIfNotLoggedIn === true && !$auth.check())"
          :to="(item.to) ? item.to : null"
          @click="(item.isLogout) ? $auth.logout() : null"
        >
          <v-list-item-content>
            <v-list-item-title>{{ item.label }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-navigation-drawer
      app
      v-if="$auth.check()"
      v-model="$store.state.app.dashboardDrawer"
      fixed
      left
      :temporary="$vuetify.breakpoint.mdAndDown"
    >
      <template v-slot:prepend>
        <div class="pa-2 text-right" v-if="$auth.check() && $vuetify.breakpoint.mdAndDown">
          <v-app-bar-nav-icon @click.stop="switchDashboardDrawer"><v-icon v-if="!$store.state.app.dashboardDrawer">mdi-minus</v-icon><v-icon v-if="$store.state.app.dashboardDrawer">mdi-close</v-icon></v-app-bar-nav-icon>
        </div>
      </template>

      <v-list
        shaped
      >
        <v-list-item-group color="primary">
          <template v-for="(item, index) in dashboardMenu">
              <v-layout
                v-if="item.heading"
                :key="item.heading"
              >
                <v-subheader class="text-uppercase">{{ item.heading }}</v-subheader>
              </v-layout>

              <v-list-group
                v-else-if="item.children"
                :key="'nav_parent_' + index"
                :value="item.opened"
                no-action
                :sub-group="false"
                :prepend-icon="item.icon"
              >
                <template #activator>
                  <v-list-item-content>
                    <v-list-item-title>{{ item.label }}</v-list-item-title>
                  </v-list-item-content>
                </template>

                <v-list-item
                  v-for="(child, i) in item.children"
                  :key="'nav_child_' + i"
                  :to="child.to"
                  exact
                >
                  <v-list-item-icon v-if="child.icon">
                    <v-icon>{{ child.icon }}</v-icon>
                  </v-list-item-icon>
                  <v-list-item-content>
                    <v-list-item-title>{{ child.label }}</v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </v-list-group>

              <v-list-item
                v-else
                :key="'nav_sub_' + index"
                :to="item.to"
                exact
              >
                <v-list-item-icon v-if="item.icon">
                  <v-icon>{{ item.icon }}</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title>{{ item.label }}</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
          </template>
        </v-list-item-group>
      </v-list>

      <template v-slot:append>
        <div class="pa-2" v-if="$auth.check()">
          <v-btn block large color="primary" v-if="!$auth.impersonating()" @click="$auth.logout()">{{ $t('logout') }}</v-btn>
          <v-btn block large color="primary" v-if="$auth.impersonating()" @click="$auth.unimpersonate({redirect: {name: 'admin.users'}})">{{ $t('logout') }}</v-btn>
        </div>
      </template>
    </v-navigation-drawer>
    <v-app-bar
      app
      clipped-right
      :height="appBarHeight"
      elevate-on-scroll
      style="z-index:6"
    >
      <v-container
        class="pa-0"
        :style="{height: appBarHeight + 'px'}"
      >
        <v-row
          no-gutters
          :style="{height: appBarHeight + 'px'}"
        >

          <div class="d-flex align-center mr-2" v-if="$auth.check()">
            <v-app-bar-nav-icon @click.stop="switchDashboardDrawer"><v-icon v-if="!$store.state.app.dashboardDrawer">mdi-menu</v-icon><v-icon v-if="$store.state.app.dashboardDrawer">mdi-menu-open</v-icon></v-app-bar-nav-icon>
          </div>

          <div class="d-flex align-center">
            <router-link v-if="$init.app.logo != '' && $init.app.logo != null" :to="{name: 'home'}"><v-img max-height="64" :src="$init.app.logo"/></router-link>
            <router-link v-if="$init.app.logo == '' || $init.app.logo == null" :to="{name: 'home'}" class="title font-weight-medium white--text text-decoration-none" style="color:inherit !important" v-html="$init.app.name"></router-link>
          </div>

          <v-spacer></v-spacer>

          <v-toolbar-items class="d-none d-md-flex d-lg-flex d-xl-flex navigation">
            <v-btn 
              text
              exact
              :ripple="false"
              v-for="(item, i) in topMenu"
              :key="'nav_' + i"
              v-show="(item.showIfLoggedIn === true && $auth.check()) || (item.showIfNotLoggedIn === true && !$auth.check())"
              :to="(item.to) ? item.to : null"
              @click="(item.isLogout) ? $auth.logout() : null"
              class="no-caps subtitle-1"
            >
            {{ item.label }}
            </v-btn>
          </v-toolbar-items>

          <div class="d-flex align-center ml-3" v-if="!$auth.check()">
            <v-btn color="blue darken-2 white--text" depressed class="no-caps" :to="{name: 'register'}">{{ $t('get_started') }}</v-btn>
          </div>

          <div class="align-center d-flex">
            <v-btn 
              icon
              class="ml-3 subtitle-1"
              @click="switchDarkTheme"
            >
              <v-icon v-if="$vuetify.theme.dark">mdi-lightbulb</v-icon>
              <v-icon v-if="!$vuetify.theme.dark">mdi-lightbulb-on-outline</v-icon>
            </v-btn>
          </div>

          <v-menu offset-y bottom left origin="top right" v-if="languages !== null && languages.length > 1">
            <template v-slot:activator="{ on }">
              <div class="d-flex align-center ml-3">
                <v-btn
                  icon
                  large
                  v-on="on"
                >
                  {{ $t('language_abbr') }}
                </v-btn>
              </div>
            </template>
            <v-list>
              <v-list-item @click="changeLang(item.code)" v-for="(item, index) in languages" :key="'languages_' + index">
                <v-list-item-content>
                  <v-list-item-title>{{ item.title }}</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </v-menu>
          <div class="d-md-none d-lg-none d-xl-none d-flex align-center ml-3">
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"><v-icon v-if="!drawer">mdi-menu</v-icon><v-icon v-if="drawer">mdi-menu-open</v-icon></v-app-bar-nav-icon>
          </div>

        </v-row>
      </v-container>
    </v-app-bar>
    <v-content>
      <router-view name="primary"></router-view>
    </v-content>

    <v-footer
      padless
      :style="{'margin-left': ($auth.check() && $store.state.app.dashboardDrawer && this.$vuetify.breakpoint.lgAndUp) ? '256px' : 0}"
    >
      <v-container>
        <v-row
          no-gutters
          class="px-5"
        >
          <v-col
            cols="6"
            xs="6"
            lg="3"
          >
            <h3 class="title secondary--text my-5" v-html="$t('navigate')"></h3>
            <ul class="nav-list">
              <li><router-link :to="{name: 'home'}" class="secondary--text text-decoration-none">{{ $t('home') }}</router-link></li>
              <li><router-link :to="{name: 'about'}" class="secondary--text text-decoration-none">{{ $t('about') }}</router-link></li>
              <li v-if="!$auth.check()"><router-link :to="{name: 'register'}" class="secondary--text text-decoration-none">{{ $t('get_started') }}</router-link></li>
              <li v-if="!$auth.check()"><router-link :to="{name: 'login'}" class="secondary--text text-decoration-none">{{ $t('login') }}</router-link></li>
              <li v-if="$auth.check()"><router-link :to="{name: ($auth.user().role == 1) ? 'admin.dashboard' : 'user.dashboard'}" class="secondary--text text-decoration-none">{{ $t('dashboard') }}</router-link></li>
            </ul>
          </v-col>
          <v-col
            cols="6"
            xs="6"
            lg="3"
          >
            <h3 class="title secondary--text my-5" v-html="$t('legal')"></h3>
            <ul class="nav-list">
              <li><router-link :to="{name: 'terms'}" class="secondary--text text-decoration-none">{{ $t('terms_and_conditions') }}</router-link></li>
              <li><router-link :to="{name: 'privacy'}" class="secondary--text text-decoration-none">{{ $t('privacy_policy') }}</router-link></li>
            </ul>
          </v-col>
          <v-col
            cols="6"
            xs="6"
            lg="3"
          >
            <h3 class="title secondary--text my-5" v-html="$t('contact')"></h3>
            <ul class="nav-list">
              <li><router-link :to="{name: 'faq'}" class="secondary--text text-decoration-none">{{ $t('faq') }}</router-link></li>
              <li><router-link :to="{name: 'contact'}" class="secondary--text text-decoration-none">{{ $t('contact') }}</router-link></li>
            </ul>
          </v-col>
          <v-col
            cols="6"
            xs="6"
            lg="3"
          >
            <div v-if="$init.app.social.length > 0">
              <h3 class="title secondary--text my-5" v-html="$t('follow_us')"></h3>

              <v-btn 
                v-for="(item, i) in $init.app.social"
                :key="'social_' + i"
                icon
                color="accent" 
                :href="item.url"
              ><v-icon>{{ item.icon }}</v-icon></v-btn>
            </div>
          </v-col>
        </v-row>
        <v-row
          no-gutters
        >
          <v-col
            class="text-center secondary--text body-2 pt-10 pb-5"
          >
            <div v-html="$t('footer_copyright')"/>
          </v-col>
        </v-row>
      </v-container>
    </v-footer>
    <v-snackbar
      v-model="showCookieConsent"
      :multi-line="true"
      :timeout="0"
      :bottom="true"
      :vertical="false"
      class="termsConsent"
    >
      {{ $t('legal_agreement_confirmation') }}
      <v-btn
        text
        :to="{ name: 'terms' }"
      >
      {{ $t('terms') }}
      </v-btn>
      <v-btn
        dark
        text
        icon
        @click="$store.dispatch('setCookieConsent', false)"
      >
        <v-icon>mdi-close</v-icon>
      </v-btn>
    </v-snackbar>
    <confirm ref="confirm"></confirm>
    <snackbar ref="snackbar"></snackbar>
    <v-overlay
      opacity="1"
      :dark="$store.state.app.dark"
      :color="($store.state.app.dark) ? 'grey darken-4' : 'grey lighten-5'"
      :value="$store.state.app.loading"
      z-index="99999"
    >
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-app>
</template>

<script>
import { getAvailableLanguages, loadLanguageAsync } from '@/plugins/i18n'

export default {
  components: {
  },
  data: () => ({
    appBarHeight: 84,
    drawer: false,
    dashboardDrawer: false,
    languages: null
  }),
  mounted () {
    this.$vuetify.theme.dark = this.$store.state.app.dark
    this.$root.$snackbar = this.$refs.snackbar.show
    this.$root.$confirm = this.$refs.confirm.open

    /* Get available translations */
    getAvailableLanguages().then(result => this.languages = result)

    /* Set language */
    let language = this.$route.query.l || null
    if (language !== null) {
      loadLanguageAsync(language)
    }
  },
  methods: {
    onResize () {
      if (window.innerWidth >= 960) this.drawer = false
      if (this.$vuetify.breakpoint.mdAndUp && this.$auth.check()) this.dashboardDrawer = true
    },
    switchDarkTheme () {
      let dark = this.$vuetify.theme.dark
      this.$vuetify.theme.dark = !dark
      this.$store.dispatch('setDark', !dark)
    },
    switchDashboardDrawer () {
      let dashboardDrawer = this.$store.state.app.dashboardDrawer
      this.$store.dispatch('setDashboardDrawer', !dashboardDrawer)
    },
    changeLang(language) {
      loadLanguageAsync(language)
      this.$store.dispatch('setLanguage', language)
    }
  },
  watch: {
    '$auth.watch.loaded': {
      handler() {
        if (this.$auth.ready()) {
          if (this.$vuetify.breakpoint.mdAndUp && this.$auth.check()) this.dashboardDrawer = true
        }
      },
      deep: true
    }
  },
  computed: {
    showCookieConsent () {
      return this.$store.state.app.showCookieConsent
    },
    topMenu () {
      return [
        {
          label: this.$t('home'),
          to: {name: 'home'},
          showIfLoggedIn: true,
          showIfNotLoggedIn: true
        },
        {
          label: this.$t('about'),
          to: {name: 'about'},
          showIfLoggedIn: true,
          showIfNotLoggedIn: true
        },
        {
          label: this.$t('login'),
          to: {name: 'login'},
          showIfLoggedIn: false,
          showIfNotLoggedIn: true
        },
        {
          label: this.$t('dashboard'),
          to: {name: (this.$auth.check() && this.$auth.user().role === 1)  ? 'admin.dashboard' : 'user.dashboard'},
          showIfLoggedIn: true,
          showIfNotLoggedIn: false
        }
      ]
    },
    dashboardMenu () {
      if (this.$auth.check() && this.$auth.user().role === 1) {
        return [
          {
            label: this.$t('dashboard'),
            icon: 'mdi-view-dashboard',
            to: {name: 'admin.dashboard'}
          },
          { heading: this.$t('admin') },
          {
            label: this.$t('users'),
            icon: 'mdi-account-multiple',
            to: {name: 'admin.users'}
          },
          { heading: this.$t('settings') },
          {
            label: this.$t('profile'),
            icon: 'mdi-account-circle',
            to: {name: 'profile'}
          }
        ]
      } else {
        return [
          {
            label: this.$t('my_resume'),
            icon: 'mdi-file-pdf-outline',
            to: {name: 'user.dashboard'}
          },
          { heading: this.$t('resume') },
          {
            label: this.$t('tags'),
            icon: 'mdi-tag-multiple',
            to: {name: 'user.tags'}
          },
          {
            label: this.$t('experience'),
            icon: 'mdi-history',
            to: {name: 'user.experience'}
          },
          {
            label: this.$t('projects'),
            icon: 'mdi-briefcase-outline',
            to: {name: 'user.projects'}
          },
          { heading: this.$t('settings') },
          {
            label: this.$t('profile'),
            icon: 'mdi-account-circle',
            to: {name: 'user.profile'}
          }
        ]
      }
    }
  }
};
</script>
<template>
  <div class="viewContainer">
    <v-container>
      <v-row>
        <v-col
          cols="12"
          sm="6"
          md="6"
          lg="4"
          xl="3"
        >
          <v-hover>
            <template v-slot:default="{ hover }">
              <v-card>
                <v-skeleton-loader
                  v-if="stats===null"
                  type="card"
                />
                  <div v-if="stats!==null">
                    <v-responsive :aspect-ratio="2.4">
                      <v-sparkline
                        :labels="stats.userChartLabels"
                        :value="stats.userChartValues"
                        type="trend"
                        smooth
                        stroke-linecap="round"
                        color="grey"
                        line-width="3"
                        padding="16"
                        height="100%"
                      ></v-sparkline>
                    </v-responsive>
                    <v-divider/>
                    <v-card-text>
                      <h2 class="title">{{ $t('users') }} <span>({{ stats.total.users }})</span></h2>
                      <span :class="{'red--text': stats.users.signupsChange < 0, 'green--text': stats.users.signupsChange > 0}">
                        <v-icon size="14" v-if="stats.users.signupsChange < 0" :class="{'red--text': stats.users.signupsChange < 0, 'green--text': stats.users.signupsChange > 0}">mdi-arrow-down</v-icon>
                        <v-icon size="14" v-if="stats.users.signupsChange > 0" :class="{'red--text': stats.users.signupsChange < 0, 'green--text': stats.users.signupsChange > 0}">mdi-arrow-up</v-icon>
                        {{ formatNumber(stats.users.signupsChange) }}
                      </span>
                      {{ $t('vs_past_7_days') }}
                    </v-card-text>
                  </div>
                <v-fade-transition>
                  <v-overlay
                    v-if="hover"
                    absolute
                  >
                    <v-btn x-large rounded :to="{ name: 'admin.users' }" color="primary">{{ $t('more') }} <v-icon size="15" class="ml-1">mdi-arrow-right</v-icon></v-btn>
                  </v-overlay>
                </v-fade-transition>
              </v-card>
            </template>
          </v-hover>
        </v-col>
        <v-col
          cols="12"
          sm="6"
          md="6"
          lg="4"
          xl="3"
        >
          <v-hover>
            <template v-slot:default="{ hover }">
              <v-card>
                <v-skeleton-loader
                  v-if="stats===null"
                  type="card"
                />
                  <div v-if="stats!==null">
                    <v-responsive :aspect-ratio="2.4" align="center" class="d-flex align-center">
                      <v-icon size="96" color="grey">mdi-account-circle</v-icon>
                    </v-responsive>
                    <v-divider/>
                    <v-card-text>
                      <h2 class="title">{{ $t('profile') }}</h2>
                      {{ $t('profile_info') }}
                    </v-card-text>
                  </div>
                <v-fade-transition>
                  <v-overlay
                    v-if="hover"
                    absolute
                  >
                    <v-btn x-large rounded :to="{ name: 'profile' }" color="primary">{{ $t('more') }} <v-icon size="15" class="ml-1">mdi-arrow-right</v-icon></v-btn>
                  </v-overlay>
                </v-fade-transition>
              </v-card>
            </template>
          </v-hover>
        </v-col>
        <v-col
          cols="12"
          sm="6"
          md="6"
          lg="4"
          xl="3"
        >
          <v-card>
            <v-skeleton-loader
              v-if="stats===null"
              type="card"
            />
              <div v-if="stats!==null">
                <v-responsive :aspect-ratio="2.4" align="center" class="d-flex align-center">
                  <v-icon size="96" color="grey">mdi-information-outline</v-icon>
                </v-responsive>
                <v-divider/>
                <v-card-text>
                  <h2 class="title">{{ $t('version') }} {{ stats.version }}</h2>
                  {{ $t('version_info') }}
                </v-card-text>
              </div>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
export default {
  data: () => ({
    locale: 'en',
    stats: null
  }),
  created () {
    // Show left drawer
    if (this.$vuetify.breakpoint.mdAndUp) this.$store.dispatch('setDashboardDrawer', true)

    // Set locale
    let locale = Intl.DateTimeFormat().resolvedOptions().locale || 'en'
    locale = (this.$auth.check()) ? this.$auth.user().locale : locale
    this.locale = locale

    this.moment.locale(this.locale.substr(0,2))

    // Load dashboard stats
    this.loadStats()
  },
  methods: {
    loadStats () {
      this.axios
        .get('/admin/stats', { params: { locale: this.$i18n.locale }})
        .then(response => {
          let stats = response.data
          this.stats = stats

          let userChartLabels = this.$_.map(stats.users.signupsCurrentPeriod, (count, date) => {
            return this.moment(date).format('D')
          })

          let userChartValues = this.$_.map(stats.users.signupsCurrentPeriod, (count, date) => {
            return count
          })

          this.stats.userChartLabels = userChartLabels
          this.stats.userChartValues = userChartValues

          this.overlay = false
          this.loading = false
        })
    },
    formatNumber (number) {
      return new Intl.NumberFormat(this.locale.replace('_', '-')).format(number)
    },
  }
};
</script>

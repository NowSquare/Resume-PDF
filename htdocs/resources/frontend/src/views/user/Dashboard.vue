<template>
  <div class="viewContainer">
    <v-container>
      <v-row>
        <v-col
          align-self="center"
          xs="12"
          offset-xs="0"
          sm="10"
          offset-sm="1"
          md="8"
          offset-md="2"
          lg="8"
          offset-lg="2"
          xl="6"
          offset-xl="3"
        >
          <v-container class="pa-0">
            <v-row dense>
              <v-col cols="4">
                <v-menu offset-y>
                  <template v-slot:activator="{ on }">
                    <v-btn large rounded block color="primary" class="no-caps mb-3" v-on="on">
                      <v-icon class="mr-1" size="16">mdi-file-pdf</v-icon> {{ $t('download_pdf') }}
                    </v-btn>
                  </template>
                  <v-list>
                    <v-subheader class="text-uppercase caption">{{ $t('format') }}</v-subheader>
                    <v-list-item @click="downloadResume('A4')"><v-list-item-title>A4</v-list-item-title></v-list-item>
                    <v-list-item @click="downloadResume('letter')"><v-list-item-title>US Letter</v-list-item-title></v-list-item>
                  </v-list>
                </v-menu>
              </v-col>
            </v-row>
          </v-container>

          <v-card elevation="14" :outlined="false" class="mt-2 mb-5">
            <v-hover>
              <template v-slot:default="{ hover }">
                <div>
                  <v-card elevation="0" tile color="transparent">
                    <v-row>
                      <v-col cols="4" sm="3">
                        <v-avatar
                          class="ml-3"
                          color="grey"
                          size="128"
                        >
                          <v-img :src="$auth.user().avatar"></v-img>
                        </v-avatar>
                      </v-col>
                      <v-col cols="8" sm="9">
                        <div class="ma-2">
                          <div class="title" v-html="$auth.user().name" />
                          <div v-html="$auth.user().job_title" class="mb-1 body-1" />
                          <div v-html="$auth.user().bio" class="body-2 mb-1" />
                          <div v-if="resume !== null">
                            <v-chip v-for="(tag, index) in resume.tags" :key="index" label class="mr-2 mt-2" small v-html="tag" />
                          </div>                          
                        </div>
                      </v-col>
                    </v-row>
                    <v-fade-transition>
                      <v-overlay
                        v-if="hover"
                        absolute
                      >
                        <v-btn :to="{ name: 'user.profile' }" color="primary" rounded><v-icon class="mr-2" size="14">mdi-pencil</v-icon> {{ $t('edit_profile') }}</v-btn>
                      </v-overlay>
                    </v-fade-transition>
                  </v-card>
                </div>
              </template>
            </v-hover>

            <v-divider/>
            <v-card-actions class="pa-4">
              <v-btn color="primary" x-large outlined rounded block :to="{name: 'user.experience'}"><v-icon class="mr-2">mdi-history</v-icon> {{ $t('experience') }}</v-btn>
            </v-card-actions>
            <v-divider/>

            <v-container v-if="resume !== null && Array.isArray(resume.experience) && resume.experience.length > 0">
              <v-row dense v-for="(item, i) in resume.experience" :key="i">
                <v-col cols="12">
                  <v-hover>
                    <template v-slot:default="{ hover }">
                      <div>
                        <v-card :elevation="hover ? 2 : 0">
                          <table class="pa-1">
                            <tr>
                              <td width="220" valign="top">
                                <table>
                                  <tr>
                                    <td valign="top" width="19"><v-icon size="14">mdi-calendar-range</v-icon></td>
                                    <td valign="top"><span v-html="item.date" class="subtitle-2"/></td>
                                  </tr>
                                  <tr>
                                    <td valign="top" width="19">
                                      <v-icon size="14" v-if="item.type === 'education'">mdi-school</v-icon>
                                      <v-icon size="14" v-if="item.type === 'work'">mdi-domain</v-icon>
                                    </td>
                                    <td valign="top"><span v-html="item.name" class="subtitle-2"/></td>
                                  </tr>
                                  <tr v-if="item.location">
                                    <td valign="top" width="19"><v-icon size="14">mdi-map-marker</v-icon></td>
                                    <td valign="top"><span v-html="item.location" class="subtitle-2"/></td>
                                  </tr>
                                </table>
                              </td>
                              <td valign="top">
                                <div class="body-2" v-html="item.description"/>
                              </td>
                            </tr>
                          </table>
                          <v-fade-transition>
                            <v-overlay
                              v-if="hover"
                              absolute
                            >
                              <v-btn :to="{ name: 'user.experience' }" color="primary" rounded><v-icon class="mr-2" size="14">mdi-history</v-icon> {{ $t('experience') }}</v-btn>
                            </v-overlay>
                          </v-fade-transition>
                        </v-card>
                      </div>
                    </template>
                  </v-hover>
                </v-col>
              </v-row>
            </v-container>

            <v-divider/>
            <v-card-actions class="pa-4">
              <v-btn color="primary" x-large outlined rounded block :to="{name: 'user.projects'}"><v-icon class="mr-2">mdi-briefcase-outline</v-icon> {{ $t('projects') }}</v-btn>
            </v-card-actions>
            <v-divider/>

            <v-container v-if="resume !== null && Array.isArray(resume.projects) && resume.projects.length > 0">
              <v-row dense v-for="(item, i) in resume.projects" :key="i">
                <v-col cols="12">
                  <v-hover>
                    <template v-slot:default="{ hover }">
                      <div>
                        <v-card :elevation="hover ? 2 : 0">
                          <table class="pa-3">
                            <tr>
                              <td valign="top">
                                <v-img v-if="item.image" contain max-width="200px" :src="item.image" class="mr-3" />
                              </td>
                              <td valign="top">
                                <div class="subtitle-1" v-if="item.title" v-html="item.title"/>
                                <div v-html="item.date" class="caption"/>
                                <div class="mt-1 body-2" v-if="item.description" v-html="item.description"/>
                                <div v-if="item.tags">
                                  <v-chip v-for="(tag, index) in item.tags" :key="index" label class="mr-2 mb-2" small v-html="tag" />
                                </div>
                              </td>
                            </tr>
                          </table>
                          <v-fade-transition>
                            <v-overlay
                              v-if="hover"
                              absolute
                            >
                              <v-btn :to="{ name: 'user.projects' }" color="primary" rounded><v-icon class="mr-2" size="14">mdi-briefcase-outline</v-icon> {{ $t('projects') }}</v-btn>
                            </v-overlay>
                          </v-fade-transition>
                        </v-card>
                      </div>
                    </template>
                  </v-hover>
                </v-col>
              </v-row>
            </v-container>

            <v-overlay
              v-if="resume === null || resumeLoading"
              absolute
            >
              <v-progress-circular indeterminate size="64"></v-progress-circular>
            </v-overlay>
          </v-card>
        </v-col>
      </v-row>
    </v-container>

  </div>
</template>
<script>
import { strToSlug } from '@/utils/helpers';

export default {
  data: () => ({
    locale: 'en',
    key: 0,
    resumeLoading: false,
    resume: null
  }),
  created () {
    // Show left drawer
    if (this.$vuetify.breakpoint.mdAndUp) this.$store.dispatch('setDashboardDrawer', true)

    // Set locale
    let locale = Intl.DateTimeFormat().resolvedOptions().locale || 'en'
    locale = (this.$auth.check()) ? this.$auth.user().locale : locale
    this.locale = locale

    this.moment.locale(this.locale.substr(0,2))

    this.getResume()
  },
  methods: {
    strToSlug,
    formatNumber (number) {
      return new Intl.NumberFormat(this.locale.replace('_', '-')).format(number)
    },
    getResume () {
      this.axios
        .get('/user/resume', { params: { locale: this.$i18n.locale }})
        .then(response => {
          this.resume = response.data
          this.resumeLoading = false
        })
      
    },
    downloadResume (size) {
      this.$store.dispatch('setLoading', true)

      this.axios({
        url: '/user/download-resume',
        method: 'GET',
        responseType: 'blob',
        params: {
          locale: this.$i18n.locale,
          size: size
        }
      })
      .then(res => {
        const url = window.URL.createObjectURL(new Blob([res.data]))
        const link = document.createElement('a')

        // Name for PDF
        let name = strToSlug (this.$auth.user().name)
        if (this.$auth.user().job_title !== null) name += '-' + strToSlug (this.$auth.user().job_title)
        let today = new Date()
        let date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate()
        name += '-' + date

        link.href = url
        link.setAttribute('download', name + '.pdf')
        document.body.appendChild(link)
        link.click()
      })
      .catch(err => {
        if (err.response.data.status === 'error') {
          this.$root.$snackbar(err.response.data.msg)
        }
      })
      .finally(() => this.$store.dispatch('setLoading', false))
    }
  }
}
</script>
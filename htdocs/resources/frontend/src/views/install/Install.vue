<template>
  <div class="viewContainer">
    <v-container
    >
      <v-row
        class="mb-5 mt-10"
      >
        <v-col
          align-self="center"
          xs="12"
          offset-xs="0"
          sm="8"
          offset-sm="2"
          md="8"
          offset-md="2"
          lg="6"
          offset-lg="3"
          xl="4"
          offset-xl="4"
        >
          <ValidationObserver ref="form1" v-slot="{ invalid }">
            <v-form 
              :model="form1" 
              @submit.prevent="submit('form1')"
              autocomplete="off"
              method="post"
            >
              <v-card elevation="20" :outlined="false">
                <v-toolbar flat>
                  <v-toolbar-title>Installation</v-toolbar-title>
                  <v-spacer></v-spacer>
                  <div class="align-center d-flex">
                    <v-btn 
                      icon
                      class="ml-3 no-caps subtitle-1"
                      @click="switchDarkTheme"
                    >
                      <v-icon v-if="$vuetify.theme.dark">mdi-lightbulb</v-icon>
                      <v-icon v-if="!$vuetify.theme.dark">mdi-lightbulb-on-outline</v-icon>
                    </v-btn>
                  </div>
                </v-toolbar>
                <v-divider/>

                <div v-if="installed">
                  <v-card-text>
                    <v-alert
                      type="success"
                      class="ma-0"
                    >
                      The script is installed succesfully. You can now log in with the credentials provided during installation.
                    </v-alert>
                  </v-card-text>
                  <v-divider/>
                  <v-card-actions>
                    <v-btn color="primary" x-large block :to="{name: 'home'}">Visit homepage</v-btn>
                  </v-card-actions>
                </div>

                <div v-if="form1.loading" style="height: 400px">
                  <v-overlay :absolute="true" align="center">
                    <v-progress-circular indeterminate size="32"></v-progress-circular>
                    <br><br>
                    Please be patient, installation may take a few minutes.
                  </v-overlay>
                </div>

                <v-stepper v-model="step" vertical style="border-top-left-radius: 0; border-top-right-radius: 0" v-if="!installed && !form1.loading">
                  <v-stepper-step :complete="step > 1" step="1">
                    Requirements
                  </v-stepper-step>
                  <v-stepper-content step="1">
                    <v-alert
                      :value="!allRequirementsMet"
                      type="error"
                    >
                      Install missing requirements below and refresh this page to try again.
                    </v-alert>
                    <v-list flat dense _color="transparent" min-height="200">
                      <v-list-item-group>
                        <v-list-item
                          v-for="(item, i) in requirements"
                          :key="i"
                        >
                          <v-list-item-icon>
                            <v-icon v-if="item.found" class="green--text">mdi-check</v-icon>
                            <v-icon v-if="!item.found" class="red--text">mdi-close</v-icon>
                          </v-list-item-icon>
                          <v-list-item-content :class="(item.found) ? 'green--text' : 'red--text'">
                            <v-list-item-title v-text="item.text"></v-list-item-title>
                          </v-list-item-content>
                        </v-list-item>
                      </v-list-item-group>
                      <v-overlay :value="requirements.length === 0" :absolute="true">
                        <v-progress-circular indeterminate size="32"></v-progress-circular>
                      </v-overlay>
                    </v-list>

                    <p class="mt-5">We need to know your web server distribution in order to generate PDFs. If you are not sure, you can contact your hosting provider. Or test different configurations after installation.</p>
                    <p>You can change this in the <code>.env</code> file in the web root which is generated after this installation.</p>

                    <x-autocomplete
                      v-model="form1.WEB_SERVER"
                      ref="form1.WEB_SERVER"
                      id="form1.WEB_SERVER"
                      :items="webServers"
                      item-value="0" 
                      item-text="1"
                      label="Web server"
                      name="Web server"
                      rules="required"
                    />

                    <div class="my-5">
                      <v-btn color="primary" @click="step = 2" :disabled="!allRequirementsMet">Next <v-icon class="ml-1" size="15">mdi-arrow-right</v-icon></v-btn>
                    </div>
                  </v-stepper-content>

                  <v-stepper-step :complete="step > 2" step="2">
                    App
                  </v-stepper-step>
                  <v-stepper-content step="2">
                    <x-text-field
                      type="text"
                      v-model="form1.APP_NAME"
                      ref="form1.APP_NAME"
                      id="form1.APP_NAME"
                      label="Name"
                      rules="required|min:8|max:24"
                    />
                    <x-text-field
                      type="text"
                      v-model="form1.APP_LOGO"
                      ref="form1.APP_LOGO"
                      id="form1.APP_LOGO"
                      label="Logo"
                      rules="nullable|max:250"
                      hint="Url to image, or leave empty for no logo (e.g. https://example.com/logo.svg)."
                      persistent-hint
                    />
                    <x-text-field
                      type="text"
                      v-model="form1.APP_URL"
                      ref="form1.APP_URL"
                      id="form1.APP_URL"
                      label="URL"
                      placeholder="https://"
                      rules="required|min:8|max:250"
                      hint="Url where the script is installed, including http(s)://. This can't be a subdirectory."
                      persistent-hint
                    />
                    <x-text-field
                      type="email"
                      v-model="form1.APP_CONTACT_EMAIL"
                      ref="form1.APP_CONTACT_EMAIL"
                      id="form1.APP_CONTACT_EMAIL"
                      label="Public e-mail address"
                      name="Public e-mail"
                      rules="required|email"
                      hint="This e-mail address is visible on the website for visitors to contact you."
                      persistent-hint
                    />
                    <div class="my-5">
                      <v-btn color="secondary" @click="step = 1" class="mr-2"><v-icon class="mr-1" size="15">mdi-arrow-left</v-icon> Back</v-btn>
                      <v-btn color="primary" @click="step = 3">Next <v-icon class="ml-1" size="15">mdi-arrow-right</v-icon></v-btn>
                    </div>
                  </v-stepper-content>

                  <v-stepper-step :complete="step > 3" step="3">
                    Localization
                  </v-stepper-step>
                  <v-stepper-content step="3">
                    <x-autocomplete
                      v-model="form1.DEFAULT_LOCALE"
                      :items="locales"
                      item-value="0" 
                      item-text="1"
                      :label="$t('locale')"
                      :name="$t('locale')"
                      rules="required"
                      :hint="$t('locale_hint')"
                      :persistent-hint="true"
                    />
                    <x-autocomplete
                      v-model="form1.DEFAULT_TIMEZONE"
                      :items="timezones"
                      item-value="0" 
                      item-text="1"
                      :label="$t('timezone')"
                      :name="$t('timezone')"
                      rules="required"
                    />
                    <div class="my-5">
                      <v-btn color="secondary" @click="step = 2" class="mr-2"><v-icon class="mr-1" size="15">mdi-arrow-left</v-icon> Back</v-btn>
                      <v-btn color="primary" @click="step = 4">Next <v-icon class="ml-1" size="15">mdi-arrow-right</v-icon></v-btn>
                    </div>
                  </v-stepper-content>

                  <v-stepper-step :complete="step > 4" step="4">
                    Login
                  </v-stepper-step>
                  <v-stepper-content step="4">
                    <p>With these credentials you can login after installation.</p>
                    <x-text-field
                      v-model="form1.adminName"
                      ref="form1.adminName"
                      id="form1.adminName"
                      label="Name"
                      rules="required|min:8|max:24"
                    />
                    <x-text-field
                      type="email"
                      v-model="form1.adminEmail"
                      ref="form1.adminEmail"
                      id="form1.adminEmail"
                      label="E-mail address"
                      rules="required|email|max:64"
                    />
                    <x-password
                      v-model="form1.adminPassword"
                      ref="form1.adminPassword"
                      id="form1.adminPassword"
                      label="Password"
                      rules="required|min:8|max:24"
                    />
                    <div class="my-5">
                      <v-btn color="secondary" @click="step = 3" class="mr-2"><v-icon class="mr-1" size="15">mdi-arrow-left</v-icon> Back</v-btn>
                      <v-btn color="primary" @click="step = 5">Next <v-icon class="ml-1" size="15">mdi-arrow-right</v-icon></v-btn>
                    </div>
                  </v-stepper-content>

                  <v-stepper-step :complete="step > 5" step="5">
                    Database
                  </v-stepper-step>
                  <v-stepper-content step="5">
                    <x-text-field
                      v-model="form1.DB_HOST"
                      ref="form1.DB_HOST"
                      id="form1.DB_HOST"
                      label="Host"
                      rules="required"
                    />
                    <x-text-field
                      type="number"
                      v-model="form1.DB_PORT"
                      ref="form1.DB_PORT"
                      id="form1.DB_PORT"
                      label="Port"
                      rules="required"
                    />
                    <x-text-field
                      v-model="form1.DB_DATABASE"
                      ref="form1.DB_DATABASE"
                      id="form1.DB_DATABASE"
                      label="Database name"
                      rules="required"
                    />
                    <x-text-field
                      v-model="form1.DB_USERNAME"
                      ref="form1.DB_USERNAME"
                      id="form1.DB_USERNAME"
                      label="Database username"
                      rules="required"
                    />
                    <x-password
                      v-model="form1.DB_PASSWORD"
                      ref="form1.DB_PASSWORD"
                      id="form1.DB_PASSWORD"
                      label="Database password"
                      rules="nullable"
                    />
                    <div class="my-5">
                      <v-btn color="secondary" @click="step = 4" class="mr-2"><v-icon class="mr-1" size="15">mdi-arrow-left</v-icon> Back</v-btn>
                      <v-btn color="primary" @click="step = 6">Next <v-icon class="ml-1" size="15">mdi-arrow-right</v-icon></v-btn>
                    </div>
                  </v-stepper-content>

                  <v-stepper-step :complete="step > 6" step="6">
                    E-mail
                  </v-stepper-step>
                  <v-stepper-content step="6">
                    <p>By default the "mail" driver is used, you can change this in the <code>.env</code> file in the webroot after installation.</p>
                    <x-text-field
                      v-model="form1.MAIL_FROM_NAME"
                      ref="form1.MAIL_FROM_NAME"
                      id="form1.MAIL_FROM_NAME"
                      label="Sender name"
                      rules="required|min:1|max:64"
                    />
                    <x-text-field
                      type="email"
                      v-model="form1.MAIL_FROM_ADDRESS"
                      ref="form1.MAIL_FROM_ADDRESS"
                      id="form1.MAIL_FROM_ADDRESS"
                      label="Sender e-mail address"
                      rules="required|email|max:64"
                    />
                    <div class="my-5">
                      <v-btn color="secondary" @click="step = 5" class="mr-2"><v-icon class="mr-1" size="15">mdi-arrow-left</v-icon> Back</v-btn>
                      <v-btn color="primary" @click="step = 7">Next <v-icon class="ml-1" size="15">mdi-arrow-right</v-icon></v-btn>
                    </div>
                  </v-stepper-content>

                  <v-stepper-step :complete="step > 7" step="7">
                    Social
                  </v-stepper-step>
                  <v-stepper-content step="7">
                    <p>Enter the full url to your social account.</p>
                    <x-text-field
                      v-model="form1.SOCIAL_TWITTER"
                      ref="form1.SOCIAL_TWITTER"
                      id="form1.SOCIAL_TWITTER"
                      label="Twitter"
                      rules="nullable"
                    />
                    <x-text-field
                      v-model="form1.SOCIAL_YOUTUBE"
                      ref="form1.SOCIAL_YOUTUBE"
                      id="form1.SOCIAL_YOUTUBE"
                      label="Youtube"
                      rules="nullable"
                    />
                    <x-text-field
                      v-model="form1.SOCIAL_FACEBOOK"
                      ref="form1.SOCIAL_FACEBOOK"
                      id="form1.SOCIAL_FACEBOOK"
                      label="Facebook"
                      rules="nullable"
                    />
                    <x-text-field
                      v-model="form1.SOCIAL_INSTAGRAM"
                      ref="form1.SOCIAL_INSTAGRAM"
                      id="form1.SOCIAL_INSTAGRAM"
                      label="Instagram"
                      rules="nullable"
                    />
                    <x-text-field
                      v-model="form1.SOCIAL_MEDIUM"
                      ref="form1.SOCIAL_MEDIUM"
                      id="form1.SOCIAL_MEDIUM"
                      label="Medium"
                      rules="nullable"
                    />
                    <x-text-field
                      v-model="form1.SOCIAL_SNAPCHAT"
                      ref="form1.SOCIAL_SNAPCHAT"
                      id="form1.SOCIAL_SNAPCHAT"
                      label="Snapchat"
                      rules="nullable"
                    />
                    <x-text-field
                      v-model="form1.SOCIAL_LINKEDIN"
                      ref="form1.SOCIAL_LINKEDIN"
                      id="form1.SOCIAL_LINKEDIN"
                      label="LinkedIn"
                      rules="nullable"
                    />
                    <x-text-field
                      v-model="form1.SOCIAL_GITHUB"
                      ref="form1.SOCIAL_GITHUB"
                      id="form1.SOCIAL_GITHUB"
                      label="Github"
                      rules="nullable"
                    />
                    <div class="my-5">
                      <v-btn color="secondary" @click="step = 6" class="mr-2"><v-icon class="mr-1" size="15">mdi-arrow-left</v-icon> Back</v-btn>
                      <v-btn color="primary" @click="step = 8">Next <v-icon class="ml-1" size="15">mdi-arrow-right</v-icon></v-btn>
                    </div>
                  </v-stepper-content>

                  <v-stepper-step :complete="step > 8" step="8">
                    Install
                  </v-stepper-step>
                  <v-stepper-content step="8">
                    
                    <v-alert
                      :value="requirements.length === 0 || !allRequirementsMet || invalid"
                      type="error"
                    >
                      Some fields are missing or incorrect. Click the "BACK" button and fix all errors.
                    </v-alert>
                    
                    <div
                      v-if="allRequirementsMet && !invalid"
                    >
                      <p>All settings can be changed after installation in the <code>.env</code> file in the root.</p>
                      <p>Click the "INSTALL" button to finalize installation.</p>
                    </div>

                    <div class="my-5">
                      <v-btn color="secondary" @click="step = 7" class="mr-2"><v-icon class="mr-1" size="15">mdi-arrow-left</v-icon> Back</v-btn>
                      <v-btn color="green white--text" :loading="form1.loading" :disabled="requirements.length === 0 || !allRequirementsMet || form1.loading || invalid" type="submit"><v-icon class="mr-1" size="15">mdi-check</v-icon> Install</v-btn>
                    </div>
                  </v-stepper-content>
                </v-stepper>
              </v-card>
              <v-btn href="https://nowsquare.com" target="_blank" large block text color="accent" class="no-caps mt-3">A NowSquare Production</v-btn>
            </v-form>
          </ValidationObserver>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>
<script>
export default {
  data: () => ({
    installed: false,
    step: 1,
    requirements: [],
    allRequirementsMet: true,
    locales: null,
    timezones: null,
    webServers: null,
    form1: {
      loading: false,
      WEB_SERVER: '',
      APP_NAME: 'My Business',
      APP_LOGO: '',
      APP_URL: location.protocol + '//' + location.hostname + (location.port ? ':' + location.port: ''),
      APP_CONTACT_EMAIL: '',
      adminName: 'Administrator',
      adminEmail: '',
      adminPassword: '',
      DEFAULT_LOCALE: '',
      DEFAULT_TIMEZONE: '',
      DB_HOST: '127.0.0.1',
      DB_PORT: '3306',
      DB_DATABASE: '',
      DB_USERNAME: '',
      DB_PASSWORD: '',
      MAIL_FROM_NAME: 'My Business',
      MAIL_FROM_ADDRESS: 'noreply@' + location.hostname,
      SOCIAL_TWITTER: '',
      SOCIAL_YOUTUBE: '',
      SOCIAL_FACEBOOK: '',
      SOCIAL_INSTAGRAM: '',
      SOCIAL_MEDIUM: '',
      SOCIAL_SNAPCHAT: '',
      hasError: false,
      errors: {},
      success: false
    }
  }),
  created() {
    // Check installation
    this.axios
      .post('/ping', {
        referrer: 'install'
      })
      .then(response => {
        if (typeof response.data.redirect !== 'undefined') {
          this.$router.push({ name: response.data.redirect })
        } else {

          let res = response.data
          let found

          this.webServers = this.$_.toPairs(res.servers)

          found = (res.binaries_executable) ? true : false
          if (! found) this.allRequirementsMet = false
          this.requirements.push({'text': 'CHMOD 774 all files in /resources/wkhtmltopdf', 'found': found})

          found = (res.php) ? true : false
          if (! found) this.allRequirementsMet = false
          this.requirements.push({'text': 'PHP version 7.2 or higher', 'found': found})

          found = (res.bcmath) ? true : false
          if (! found) this.allRequirementsMet = false
          this.requirements.push({'text': 'BCMath PHP Extension', 'found': found})

          found = (res.ctype) ? true : false
          if (! found) this.allRequirementsMet = false
          this.requirements.push({'text': 'Ctype PHP Extension', 'found': found})

          found = (res.json) ? true : false
          if (! found) this.allRequirementsMet = false
          this.requirements.push({'text': 'JSON PHP Extension', 'found': found})

          found = (res.mbstring) ? true : false
          if (! found) this.allRequirementsMet = false
          this.requirements.push({'text': 'Mbstring PHP Extension', 'found': found})

          found = (res.openssl) ? true : false
          if (! found) this.allRequirementsMet = false
          this.requirements.push({'text': 'OpenSSL PHP Extension', 'found': found})

          found = (res.PDO) ? true : false
          if (! found) this.allRequirementsMet = false
          this.requirements.push({'text': 'PDO PHP Extension', 'found': found})

          found = (res.tokenizer) ? true : false
          if (! found) this.allRequirementsMet = false
          this.requirements.push({'text': 'Tokenizer PHP Extension', 'found': found})

          found = (res.xml) ? true : false
          if (! found) this.allRequirementsMet = false
          this.requirements.push({'text': 'XML PHP Extension', 'found': found})

          found = (res.gd) ? true : false
          if (! found) this.allRequirementsMet = false
          this.requirements.push({'text': 'GD PHP Extension', 'found': found})
        }
      })

    this.axios
      .get('/localization/locales', { params: { locale: this.$i18n.locale }})
      .then(response => {
        this.locales = this.$_.toPairs(response.data)
      })
    this.axios
      .get('/localization/timezones', { params: { locale: this.$i18n.locale }})
      .then(response => {
        this.timezones = this.$_.toPairs(response.data)
      })

    this.form1.DEFAULT_LOCALE = Intl.DateTimeFormat().resolvedOptions().locale.replace('-', '_') || null
    this.form1.DEFAULT_TIMEZONE = Intl.DateTimeFormat().resolvedOptions().timeZone || null
  },
  mounted() {
    // Validate
    this.$refs['form1.WEB_SERVER'].validate()
    this.$refs['form1.APP_NAME'].validate()
    this.$refs['form1.APP_URL'].validate()
    this.$refs['form1.APP_CONTACT_EMAIL'].validate()
    this.$refs['form1.adminName'].validate()
    this.$refs['form1.adminEmail'].validate()
    this.$refs['form1.adminPassword'].validate()
    this.$refs['form1.DB_HOST'].validate()
    this.$refs['form1.DB_PORT'].validate()
    this.$refs['form1.DB_DATABASE'].validate()
    this.$refs['form1.DB_USERNAME'].validate()
    this.$refs['form1.MAIL_FROM_NAME'].validate()
    this.$refs['form1.MAIL_FROM_ADDRESS'].validate()
  },
  methods: {
    async submit (formName) {
      // Reset form validation
      this.$refs[formName].reset()

      // Form defaults
      let form = this[formName]
      form.hasError = false
      form.loading = true

      this.axios
        .post('/install', {
          install: this.form1
        })
        .then(response => {
          if (response.data.status === 'success') {
            this.installed = true
          }
        })
        .catch(error => {
          let errors = error.response.data.errors || []

          for (let field in errors) {
            this.$refs[formName + '.' + field].applyResult({
              errors: errors[field],
              valid: false,
              failedRules: {}
            })
          }

          if (errors.length === 0) {
            form.hasError = true
          }

          form.loading = false
        })
        .finally(() => form.loading = false)

    },
    switchDarkTheme () {
      let dark = this.$vuetify.theme.dark
      this.$vuetify.theme.dark = !dark
      this.$store.dispatch('setDark', !dark)
    }
  }
};
</script>

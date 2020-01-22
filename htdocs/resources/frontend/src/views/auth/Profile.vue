<template>
  <div class="viewContainer">
    <v-container>
      <v-row
        no-gutters
      >
        <v-col
          cols="12"
        >
          <v-card>
            <v-toolbar tabs flat>
              <v-toolbar-title>{{ $t('profile') }}</v-toolbar-title>
              <v-spacer></v-spacer>
              <template v-slot:extension>
                <v-tabs 
                  v-model="selected_tab" 
                  show-arrows
                  >
                  <v-tab :href="'#general'">
                    {{ $t('general') }}
                  </v-tab>
                  <v-tab :href="'#'">
                    {{ $t('localization') }}
                  </v-tab>
                  <v-tab :href="'#'">
                    {{ $t('change_password') }}
                  </v-tab>
                </v-tabs>
              </template>
            </v-toolbar>

            <ValidationObserver ref="form1" v-slot="{ invalid }">
              <v-form 
                :model="form1" 
                id="form1"
                lazy-validation
                @submit.prevent="submitForm('form1')"
                autocomplete="off"
                method="post"
                accept-charset="UTF-8" 
                enctype="multipart/form-data"
              >
                <v-divider></v-divider>
                <v-card-text>
                  <v-alert
                    :value="form1.has_error && !form1.success"
                    type="error"
                    class="mb-4"
                  >
                    <span v-if="form1.error === 'registration_validation_error'">{{ $t('server_error') }}</span>
                    <span v-else-if="form1.error === 'demo'">This is a demo user. You can't update or delete anything this account. If you want to test all user features, sign up with a new account.</span>
                    <span v-else>{{ $t('correct_errors') }}</span>
                  </v-alert>
                  <v-alert
                    :value="form1.success"
                    type="success"
                    class="mb-4"
                  >
                    {{ $t('update_success') }}
                  </v-alert>
                  <v-tabs-items v-model="selected_tab" :touchless="false">
                    <v-tab-item :value="'general'">
                      <x-text-field 
                        v-model="form1.name"
                        ref="form1.name"
                        id="form1.name"
                        :label="$t('name')"
                        rules="required|min:2|max:32"
                      />
                      <x-text-field 
                        type="email"
                        v-model="form1.email"
                        ref="form1.email"
                        id="form1.email"
                        :label="$t('email_address')"
                        rules="required|max:64|email"
                      />
                    </v-tab-item>
                    <v-tab-item :value="'localization'">
                      <x-autocomplete
                        v-model="form1.locale"
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
                        v-model="form1.timezone"
                        :items="timezones"
                        item-value="0" 
                        item-text="1"
                        :label="$t('timezone')"
                        :name="$t('timezone')"
                        rules="required"
                      />
                    </v-tab-item>
                    <v-tab-item :value="'password'">
                      <x-password
                        v-model="form1.new_password"
                        ref="form1.new_password"
                        id="form1.new_password"
                        :label="$t('change_password')"
                        :name="$t('password')"
                        rules="min:8|max:24"
                        :hint="$t('leave_empty_for_no_change')"
                        :persistent-hint="true"
                      />
                    </v-tab-item>
                  </v-tabs-items>
                  <x-password
                    v-model="form1.current_password"
                    ref="form1.current_password"
                    id="form1.current_password"
                    :label="$t('current_password')"
                    :name="$t('current_password')"
                    rules="required|min:8|max:24"
                  />
                </v-card-text>
                <v-card-actions class="mx-2">
                  <v-spacer></v-spacer>
                  <v-btn color="primary" large :loading="form1.loading" :disabled="form1.loading || invalid" type="submit" class="mb-2">{{ $t('update') }}</v-btn>
                </v-card-actions>
              </v-form>
            </ValidationObserver>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        selected_tab: 'general',
        locales: [],
        timezones: [],
        currencies: [],
        form1: {
          loading: false,
          name: this.$auth.user().name,
          email: this.$auth.user().email,
          locale: this.$auth.user().locale,
          timezone: this.$auth.user().timezone,
          new_password: null,
          current_password: null,
          has_error: false,
          error: null,
          success: false
        }
      }
    },
    mounted () {
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

      // Validate current password
      this.$refs['form1.current_password'].validate()
    },
    created () {
        this.showDeleteAvatar = (this.$_.startsWith(this.form1.avatar_media_url, 'data:image/png;base64')) ? false : true
    },
    methods: {
      async submitForm(formName) {
        // Reset form validation
        this.$refs[formName].reset()

        this[formName].success = false
        this[formName].has_error = false
        this[formName].loading = true

        this.updateProfile(formName)
      },
      updateProfile(formName) {
        var app = this[formName]

        let settings = { headers: { 'content-type': 'multipart/form-data' } }

        // Remove image urls
        let formModel = Object.assign({}, this.form1);

        let formData = new FormData(document.getElementById('form1'));

        for (let field in formModel) {
          formData.append(field, formModel[field])
        }

        this.axios
          .post('/auth/profile', formData, settings)
          .then(response => {
            if (response.data.status === 'success') {
              app.success = true
              app.new_password = null
              app.current_password = null
              this.$nextTick(() => this.$refs[formName].reset())

              // Update auth object
              this.$auth.user(response.data.user)
            }
          })
          .catch(error => {
            app.has_error = true
            if (error.response.data.status === 'error') {
              if (typeof error.response.data.error !== 'undefined') app.error = error.response.data.error
              this.errorMsg = error.response.data.error

              let errors = error.response.data.errors || []

              for (let field in errors) {
                this.$refs[formName + '.' + field].applyResult({
                  errors: errors[field],
                  valid: false,
                  failedRules: {}
                })
              }
            }
          })
          .finally(() => { 
            window.scrollTo(0,0)
            app.loading = false
          })
      }
    },
    computed: {
      app () {
        return this.$store.getters.app
      }
    }
  }
</script>
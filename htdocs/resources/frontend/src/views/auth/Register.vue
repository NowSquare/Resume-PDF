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
          md="6"
          offset-md="3"
          lg="4"
          offset-lg="4"
        >
          <ValidationObserver ref="form1" v-slot="{ invalid }">
            <v-form 
              :model="form1" 
              @submit.prevent="submit('form1')"
              autocomplete="off"
              method="post"
            >
              <v-card elevation="3" :outlined="false">
                <v-toolbar flat>
                  <v-toolbar-title>{{ $t('create_new_account') }}</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                  <v-alert
                    :value="form1.hasError && !form1.success"
                    type="error"
                    class="mb-4"
                  >
                    <span v-if="form1.error == 'registration_validation_error'">{{ $t('server_error') }}</span>
                    <span v-else>{{ $t('correct_errors') }}</span>
                  </v-alert>
                  <x-text-field 
                    v-model="form1.name"
                    ref="form1.name"
                    id="form1.name"
                    :label="$t('enter_your_name')"
                    :name="$t('name')"
                    rules="required|min:2|max:32"
                  />
                  <x-text-field 
                    type="email"
                    v-model="form1.email"
                    ref="form1.email"
                    id="form1.email"
                    :label="$t('enter_email')"
                    :name="$t('email_address')"
                    rules="required|max:64|email"
                  />
                  <x-password
                    v-model="form1.password"
                    ref="form1.password"
                    id="form1.password"
                    :label="$t('enter_password')"
                    :name="$t('password')"
                    rules="required|min:8|max:24"
                  />
                  <x-checkbox
                    v-model="form1.terms"
                    ref="form1.terms"
                    id="form1.terms"
                    :label="$t('i_agree_to_terms')"
                    :name="$t('agree_to_terms')"
                    rules="required"
                    false-value=""
                    true-value="1"
                  />
                </v-card-text>
                <v-card-actions>
                  <v-btn color="primary" x-large block :loading="form1.loading" :disabled="form1.loading || invalid" type="submit">{{ $t('create') }}</v-btn>
                </v-card-actions>
              </v-card>
              <v-btn :to="{name: 'login'}" :disabled="form1.loading" large block text color="accent" class="no-caps mt-3"><v-icon class="mr-1" size="14">mdi-arrow-left</v-icon> {{ $t('log_in_to_account') }}</v-btn>
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
    form1: {
      loading: false,
      terms: '',
      name: '',
      email: '',
      password: '',
      locale: '',
      timezone: '',
      hasError: false,
      error: '',
      success: false
    }
  }),
  created () {
    this.form1.locale = Intl.DateTimeFormat().resolvedOptions().locale || null
    this.form1.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone || null
  },
  methods: {
    async submit (formName) {
      // Reset form validation
      this.$refs[formName].reset()

      // Form defaults
      let form = this[formName]
      form.hasError = false
      form.loading = true

      this.$auth.register({
        data: {
          language: this.$i18n.locale,
          name: form.name,
          email: form.email,
          password: form.password,
          locale: form.locale,
          timezone: form.timezone,
          terms: form.terms
        },
        success: function () {
          form.success = true

          this.$auth.login({
            rememberMe: true,
            fetchUser: true,
            params: {
              locale: this.$i18n.locale,
              email: form.email,
              password: form.password,
              remember: true
            },
            success () {
              // Handle redirection
              let redirectTo = this.$auth.user().role === 1 ? 'admin.dashboard' : 'user.dashboard'
              // Show left drawer
              if (this.$vuetify.breakpoint.mdAndUp) this.$store.dispatch('setDashboardDrawer', true)
              // Redirect
              this.$router.push({name: redirectTo})
            }
          })
          //this.$router.push({name: 'login', params: {successRegistrationRedirect: true, email: form.email}})
        },
        error: function (error) {
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
        }
      })

    }
  }
};
</script>

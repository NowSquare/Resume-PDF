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
                  <v-toolbar-title>{{ $t('log_in_to_account') }}</v-toolbar-title>
                </v-toolbar>

                <v-card-text>
                  <v-alert
                    :value="form1.hasError"
                    type="error"
                    class="mb-4"
                  >
                    <span v-html="$t('login_not_recognized')"/>
                  </v-alert>

                  <v-alert
                    :value="successRegistrationRedirect"
                    type="success"
                    class="mb-4"
                  >
                    {{ $t('successfully_registered_info') }}
                  </v-alert>

                  <v-alert
                    :value="successResetRedirect"
                    type="success"
                    class="mb-4"
                  >
                    {{ $t('reset_email_sent') }}
                  </v-alert>

                  <v-alert
                    :value="successResetUpdateRedirect"
                    type="success"
                    class="mb-4"
                    >
                    {{ $t('password_reset_success') }}
                  </v-alert>

                  <div v-if="$init.config.demo">
                    <v-alert
                      type="info"
                      class="mb-7"
                      border="left"
                      colored-border
                      elevation="7"
                    >
                      <div class="mb-4">
                        The system is in demo mode. You can <router-link :to="{name: 'register'}">create a new account</router-link>, or:
                      </div>
                      <v-btn color="primary" small block class="mb-3 no-caps" @click="form1.email='admin@example.com';form1.password='welcome123';submit('form1')">Log in as admin <v-icon class="ml-1" size="14">mdi-arrow-right</v-icon></v-btn>
                      <v-btn color="primary" small block class="no-caps" @click="form1.email='user@example.com';form1.password='welcome123';submit('form1')">Log in as user <v-icon class="ml-1" size="14">mdi-arrow-right</v-icon></v-btn>
                    </v-alert>
                  </div>

                  <x-text-field 
                    type="email"
                    v-model="form1.email"
                    ref="form1.email"
                    id="form1.email"
                    :label="$t('email_address')"
                    rules="required|email"
                  />

                  <x-password
                    v-model="form1.password"
                    ref="form1.password"
                    id="form1.password"
                    :label="$t('password')"
                    rules="required|min:8|max:24"
                  />

                  <v-layout align-center justify-end row>
                    <v-btn text small :to="{name: 'password.email'}" tabindex="-1" color="accent" class="mr-3 no-caps">{{ $t('forgot_password') }}</v-btn>
                  </v-layout>

                  <x-checkbox
                    v-model="form1.rememberMe"
                    ref="form1.rememberMe"
                    id="form1.rememberMe"
                    :label="$t('remember_me')"
                  />
                </v-card-text>
                <v-card-actions>
                  <v-btn color="primary" x-large block :loading="form1.loading" :disabled="form1.loading || invalid" type="submit">{{ $t('login') }}</v-btn>
                </v-card-actions>
              </v-card>
              <v-btn :to="{name: 'register'}" :disabled="form1.loading" large block text color="accent" class="no-caps mt-3">{{ $t('create_new_account') }} <v-icon class="ml-1" size="14">mdi-arrow-right</v-icon></v-btn>
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
    successRegistrationRedirect: false,
    successResetRedirect: false,
    successResetUpdateRedirect: false,
    form1: {
      loading: false,
      email: '',
      password: '',
      rememberMe: true,
      hasError: false,
    }
  }),
  created () {
      this.successRegistrationRedirect = this.$route.params.successRegistrationRedirect || false
      this.form1.email = this.$route.params.email || null
      this.successResetRedirect = this.$route.params.successResetRedirect || false
      this.successResetUpdateRedirect = this.$route.params.successResetUpdateRedirect || false
  },
  methods: {
    async submit (formName) {
      // Get the redirect object
      let redirect = this.$auth.redirect()

      // Reset form validation
      this.$refs[formName].reset()

      // Form defaults
      let form = this[formName]
      form.hasError = false
      form.loading = true

      this.$auth.login({
        rememberMe: form.rememberMe,
        fetchUser: true,
        params: {
          locale: this.$i18n.locale,
          email: form.email,
          password: form.password,
          remember: form.rememberMe
        },
        success () {
          // Handle redirection
          let redirectTo
          if (redirect) {
            redirectTo = redirect.from.name
          } else {
            redirectTo = this.$auth.user().role === 1 ? 'admin.dashboard' : 'user.dashboard'
          }
          // Show left drawer
          if (this.$vuetify.breakpoint.mdAndUp) this.$store.dispatch('setDashboardDrawer', true)
          // Redirect
          this.$router.push({name: redirectTo})
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

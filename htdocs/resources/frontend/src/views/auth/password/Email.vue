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
                  <v-toolbar-title>{{ $t('set_a_new_password') }}</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                  <v-alert
                    :value="form1.hasError"
                    type="error"
                    class="mb-1"
                  >
                    {{ errorMsg }}
                  </v-alert>
                  <p class="body-1">{{ $t('reset_password_info') }}</p>
                  <x-text-field 
                    type="email"
                    v-model="form1.email"
                    ref="form1.email"
                    id="form1.email"
                    :label="$t('enter_email')"
                    :name="$t('email_address')"
                    rules="required|email"
                  />
                </v-card-text>
                <v-card-actions>
                  <v-btn color="primary" x-large block :loading="form1.loading" :disabled="form1.loading || invalid" type="submit">{{ $t('send_reset_link') }}</v-btn>
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
    errorMsg: '',
    form1: {
      loading: false,
      email: null,
      hasError: false,
    }
  }),
  created () {
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
        .post('/auth/password/reset', {
          locale: this.$i18n.locale,
          email: form.email
        })
        .then(response => {
          if (response.data.status === 'success') {
            this.$router.push({name: 'login', params: {successResetRedirect: true}})
          } else if (typeof response.data.error !== 'undefined') {
            form.hasError = true
            this.errorMsg = response.data.error
          }
        })
        .catch(() => {
          form.hasError = true
        })
        .finally(() => form.loading = false)

    }
  }
};
</script>

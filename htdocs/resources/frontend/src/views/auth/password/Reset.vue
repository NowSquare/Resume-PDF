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
                    :value="invalidToken"
                    type="error"
                  >
                    {{ $t('invalid_token') }}
                  </v-alert>
                  <el-alert
                    type="error"
                    v-if="form1.hasError && !form1.success"
                    class="mb-1"
                    show-icon
                  >
                    {{ $t('correct_errors') }}
                  </el-alert>
                  <x-password
                    v-if="!invalidToken"
                    v-model="form1.password"
                    ref="form1.password"
                    id="form1.password"
                    :label="$t('enter_new_password')"
                    :name="$t('password')"
                    rules="required|min:8|max:24"
                  />
                </v-card-text>
                <v-card-actions v-if="!invalidToken">
                  <v-btn color="primary" x-large block :loading="form1.loading" :disabled="form1.loading || invalid" type="submit">{{ $t('update_password') }}</v-btn>
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
    invalidToken: false,
    form1: {
      loading: false,
      password: '',
      hasError: false,
      errors: {},
      success: false
    }
  }),
  created() {
    // Verify token
    let token = this.$route.params.token
    this.axios
      .post('/auth/password/reset/validate-token', {
        locale: this.$i18n.locale,
        token: token
      })
      .then(response => {
        if (response.data.status === 'success') {
          this.invalidToken = false
        } else {
          this.invalidToken = true
        }
      })
      .catch(() => {
        this.invalidToken = true
      })
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
        .post('/auth/password/update', {
          locale: this.$i18n.locale,
          password: form.password,
          token: this.$route.params.token
        })
        .then(response => {
          if (response.data.status === 'success') {
            this.$router.push({name: 'login', params: {successResetUpdateRedirect: true}})
          }
        })
        .catch(error => {
          form.hasError = true
          form.errors = error.response.data.errors || {}
        })
        .finally(() => form.loading = false)
    }
  }
};
</script>

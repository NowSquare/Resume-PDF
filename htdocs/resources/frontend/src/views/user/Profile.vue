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
                  <v-tab :href="'#page'">
                    {{ $t('profile') }}
                  </v-tab>
                  <v-tab :href="'#localization'">
                    {{ $t('localization') }}
                  </v-tab>
                  <v-tab :href="'#login'">
                    {{ $t('login') }}
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
                    <v-tab-item :value="'page'">
                      <v-row>
                        <v-col cols="12" sm="8" class="order-1 order-sm-0">
                          <x-text-field 
                            v-model="form1.name"
                            ref="form1.name"
                            id="form1.name"
                            :label="$t('name')"
                            rules="required|min:2|max:64"
                          />
                          <x-text-field 
                            v-model="form1.job_title"
                            ref="form1.job_title"
                            id="form1.job_title"
                            :label="$t('job_title')"
                            :name="$t('job_title')"
                            rules="nullable|max:250"
                          />
                          <x-text-field 
                            v-model="form1.bio"
                            ref="form1.bio"
                            id="form1.bio"
                            :label="$t('bio')"
                            :name="$t('bio')"
                            rules="nullable|max:250"
                          />
                          
                          <v-row>
                            <v-col cols="8" class="py-0">
                              <x-autocomplete
                                v-model="form1.tags"
                                ref="form1.tags"
                                id="form1.tags"
                                :label="$t('tags')"
                                :name="$t('tags')"
                                :items="tags"
                                item-text="val"
                                item-value="pk"
                                hide-no-data
                                hide-selected
                                chips
                                multiple
                                small-chips
                                deletable-chips
                              />
                            </v-col>
                            <v-col cols="4" class="py-0">
                              <v-btn class="no-caps" style="height: 55px" large :to="{ name: 'user.tags' }">{{ $t('manage_tags') }}</v-btn>
                            </v-col>
                          </v-row>

                        </v-col>
                        <v-col cols="12" sm="4" class="order-0 order-sm-1">
                          <v-subheader v-html="$t('avatar') + ' <small class=\'ml-2\'>(512 x 512px)</small>'"/>
                          <v-hover>
                            <template v-slot:default="{ hover }">
                              <v-avatar size="150">
                                <v-img :src="form1.avatar_media_url">
                                  <v-fade-transition>
                                    <v-overlay
                                      v-if="hover"
                                      absolute
                                    >
                                    <v-btn-toggle rounded>
                                      <v-btn @click="pickFile('avatar')" x-small color="primary" rounded><v-icon class="mr-1" size="14">mdi-upload</v-icon> {{ $t('upload') }}</v-btn>
                                      <v-btn v-if="showDeleteAvatar" @click="form1.avatar_media_url = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAA9JREFUeNpiuHbtGkCAAQAFCAKDZcGh3gAAAABJRU5ErkJggg=='; form1.avatar_media_changed = true; showDeleteAvatar = false" x-small color="red" rounded><v-icon size="14">mdi-close</v-icon></v-btn>
                                    </v-btn-toggle>
                                    </v-overlay>
                                  </v-fade-transition>
                                </v-img>
                              </v-avatar>
                            </template>
                          </v-hover>
                          <input
                            type="file"
                            style="display: none"
                            id="avatar"
                            name="avatar"
                            accept="image/*"
                            @change="onFilePicked"
                          >
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6">
                          <x-text-field 
                            v-model="form1.contact_phone"
                            ref="form1.contact_phone"
                            id="form1.contact_phone"
                            :label="$t('phone')"
                            :name="$t('phone')"
                            rules="nullable|max:64"
                          />
                          <x-text-field 
                            v-model="form1.website"
                            ref="form1.website"
                            id="form1.website"
                            :label="$t('website')"
                            :name="$t('website')"
                            rules="nullable|max:250"
                          />
                          <x-date-picker 
                            v-model="form1.date_of_birth"
                            ref="form1.date_of_birth"
                            id="form1.date_of_birth"
                            :label="$t('birthday')"
                            :name="$t('birthday')"
                            rules="nullable"
                          />
                          <x-text-field 
                            v-model="form1.address1"
                            ref="form1.address1"
                            id="form1.address1"
                            :label="$t('address_line') + ' 1'"
                            :name="$t('address_line') + ' 1'"
                            rules="nullable|max:200"
                          />
                          <x-text-field 
                            v-model="form1.address2"
                            ref="form1.address2"
                            id="form1.address2"
                            :label="$t('address_line') + ' 2'"
                            :name="$t('address_line') + ' 2'"
                            rules="nullable|max:200"
                          />
                          <x-text-field 
                            v-model="form1.address3"
                            ref="form1.address3"
                            id="form1.address3"
                            :label="$t('address_line') + ' 3'"
                            :name="$t('address_line') + ' 3'"
                            rules="nullable|max:200"
                          />
                        </v-col>
                        <v-col cols="12" sm="6">
                          <x-text-field 
                            v-model="form1.contact_email"
                            ref="form1.contact_email"
                            id="form1.contact_email"
                            :label="$t('email_address')"
                            :name="$t('email_address')"
                            rules="nullable|max:128"
                          />
                          <x-text-field 
                            v-model="form1.linkedin"
                            ref="form1.linkedin"
                            id="form1.linkedin"
                            :label="$t('linkedin')"
                            :name="$t('linkedin')"
                            rules="nullable|max:250"
                          />
                          <x-text-field 
                            v-model="form1.languages"
                            ref="form1.languages"
                            id="form1.languages"
                            :label="$t('language_skills')"
                            :name="$t('language_skills')"
                            rules="nullable|max:500"
                          />
                        </v-col>
                      </v-row>
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
                    <v-tab-item :value="'login'">
                      <x-text-field 
                        type="email"
                        v-model="form1.email"
                        ref="form1.email"
                        id="form1.email"
                        :label="$t('email_address')"
                        rules="required|max:64|email"
                      />
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
                  </v-tabs-items><!--
                  <x-password
                    v-model="form1.current_password"
                    ref="form1.current_password"
                    id="form1.current_password"
                    :label="$t('current_password')"
                    :name="$t('current_password')"
                    rules="required|min:8|max:24"
                  />-->
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
        activeFilePickerId: null,
        showDeleteAvatar: false,
        showDeleteCover: false,
        selected_tab: 'page',
        locales: [],
        timezones: [],
        currencies: [],
        tags: [],
        form1: {
          loading: false,
          name: this.$auth.user().name,
          job_title: this.$auth.user().job_title,
          bio: this.$auth.user().bio,
          tags: this.$auth.user().tags,
          date_of_birth: this.$auth.user().date_of_birth,
          contact_email: this.$auth.user().contact_email,
          contact_phone: this.$auth.user().contact_phone,
          address1: this.$auth.user().address1,
          address2: this.$auth.user().address2,
          address3: this.$auth.user().address3,
          linkedin: this.$auth.user().linkedin,
          languages: this.$auth.user().languages,
          website: this.$auth.user().website,
          email: this.$auth.user().email,
          avatar_media_url: this.$auth.user().avatar,
          avatar_media_changed: false,
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
      this.axios
        .get('/user/tags', { params: { locale: this.$i18n.locale }})
        .then(response => {
          this.tags = response.data
        })

      // Validate current password
      this.$refs['form1.current_password'].validate()
    },
    created () {
      this.showDeleteAvatar = (this.$_.startsWith(this.form1.avatar_media_url, 'data:image/png;base64')) ? false : true
      this.showDeleteCover = (this.form1.cover_media_url == null) ? false : true
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
        formModel.avatar_media_url = null;

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
              this.form1.avatar_media_url = this.$auth.user().avatar
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
      },
      pickFile (id) {
        this.activeFilePickerId = id
        document.getElementById(id).click();
      },
      onFilePicked (e) {
        const files = e.target.files
        if(files[0] !== undefined) {
          if(files[0].name.lastIndexOf('.') <= 0) {
            return
          }
          const fr = new FileReader ()
          fr.readAsDataURL(files[0])
          fr.addEventListener('load', () => {
            this.form1[this.activeFilePickerId + '_media_url'] = fr.result
            this.form1[this.activeFilePickerId + '_media_file'] = files[0] // this is an image file that can be sent to server...
            this.form1[this.activeFilePickerId + '_media_changed'] = true

            switch (this.activeFilePickerId) {
              case 'avatar':
                this.showDeleteAvatar = true
                break;
            }
          })
        } else {
          this.form1[this.activeFilePickerId + '_media_file'] = ''
          this.form1[this.activeFilePickerId + '_media_url'] = ''
          this.form1[this.activeFilePickerId + '_media_changed'] = true
        }
      }
    },
    computed: {
      app () {
        return this.$store.getters.app
      }
    }
  }
</script>
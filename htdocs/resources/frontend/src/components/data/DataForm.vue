<template>
  <v-card>
    <v-toolbar :tabs="tabCount > 1" flat v-if="!loadingForm">
      <v-toolbar-title>{{ (uuid === null) ? translations.create_item : translations.edit_item }}</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn icon @click="$emit('data-list-events', {'closeDialog': true, 'reload': false})">
        <v-icon>mdi-close</v-icon>
      </v-btn>
      <template v-slot:extension v-if="tabCount > 1 && ! limitReached">
        <v-tabs
          slot="extension"
          v-model="selectedTab"
          show-arrows
          v-if="tabCount > 1"
          >
          <v-tab :key="'tab_' + tab_index" :href="'#' + tab_index" v-for="(tab, tab_index) in form.items">{{ tab.text }}</v-tab>
        </v-tabs>
      </template>
    </v-toolbar>

    <div v-if="loadingForm" class="px-4 py-3">
      <v-progress-linear :indeterminate="true" color="primary"></v-progress-linear>
    </div>

    <div v-if="!loadingForm && limitReached">
      <v-card-text class="pt-0">
        <p class="subtitle-1">You have reached the maximum of {{ max }} {{ translations.items_lowercase }}.</p>
      </v-card-text>
    </div>

    <ValidationObserver ref="formModel" v-slot="{ invalid }">
      <v-form 
        :model="formModel" 
        v-if="!loadingForm && ! limitReached"
        @submit.prevent="submitForm('formModel')"
        autocomplete="off"
        method="post"
        id="formModel"
        accept-charset="UTF-8" 
        enctype="multipart/form-data"
      >

        <v-divider></v-divider>

        <v-card-text :style="{ 'height': settings.dialog_height + 'px' || 'auto', 'max-width': '800px', 'overflow-y': 'auto' }">
          <v-tabs-items v-model="selectedTab" :touchless="true" class="mx-2">
            <v-tab-item :value="tab_index" v-for="(tab, tab_index) in form.items" :key="tab_index" :eager="true">

              <div v-if="tab.description" v-html="tab.description" class="subtitle-1 mb-3"></div>

              <v-card v-if="Object.keys(form.items[tab_index].subs).length > 1" class="mb-3 elevation-1">
                <v-tabs
                  v-if="Object.keys(form.items[tab_index].subs).length > 1"
                  v-model="selectedSubTab[tab_index]"
                  hide-slider
                  color="primary"
                  background-color="grey lighten-4"
                  show-arrows
                  >
                  <v-tab :key="'sub_tab_' + sub_index" :href="'#' + sub_index" v-for="(sub, sub_index) in tab.subs">{{ sub.text }}</v-tab>
                </v-tabs>
              </v-card>

                <v-tabs-items v-model="selectedSubTab[tab_index]" :touchless="true">
                  <v-tab-item :value="sub_index" v-for="(sub, sub_index) in tab.subs" :key="sub_index" :eager="true">

                    <div v-if="sub.description" v-html="sub.description" class="subtitle-1 mb-3"></div>
  <!--
                <div v-for="(sub, sub_index) in tab.subs">
                  <div class="headline mt-2 mb-3" v-if="sub.text">{{ sub.text }}</div>
  -->
                  <div v-for="(form_item, form_index) in sub.items" :key="'form_' + form_index">

                    <div v-if="form_item.type == 'description'">
                      <v-sheet
                          v-html="form_item.text"
                          class="pa-3 mb-3 subtitle-1 elevation-1"
                          color="grey lighten-4"
                        >
                      </v-sheet>
                    </div>

                    <div v-if="form_item.type == 'text' || form_item.type == 'email' || form_item.type == 'number'">
                      <x-text-field
                        :type="form_item.type"
                        v-model="formModel[form_item.column]"
                        :ref="form_item.column"
                        :id="form_item.column"
                        :rules="form_item.validate"
                        :label="(form_item.required) ? form_item.text + $t('_required_') : form_item.text"
                        :name="form_item.text"
                        :prepend-inner-icon="form_item.icon"
                        :placeholder="form_item.placeholder"
                        :hint="form_item.hint"
                        :persistent-hint="true"
                        :prefix="form_item.prefix"
                        :suffix="form_item.suffix"
                      />
                    </div>

                    <div v-if="form_item.type == 'password'">
                      <x-password
                        v-model="formModel[form_item.column]"
                        :ref="form_item.column"
                        :id="form_item.column"
                        :rules="form_item.validate"
                        :label="(form_item.required) ? form_item.text + $t('_required_') : form_item.text"
                        :name="form_item.text"
                        :prepend-inner-icon="form_item.icon"
                        :placeholder="form_item.placeholder"
                        :hint="form_item.hint"
                        :persistent-hint="true"
                        :prefix="form_item.prefix"
                        :suffix="form_item.suffix"
                      />
                    </div>

                    <div v-if="form_item.type == 'boolean'">
                      <x-checkbox
                        v-model="formModel[form_item.column]"
                        :ref="form_item.column"
                        :id="form_item.column"
                        :label="(form_item.required) ? form_item.text + $t('_required_') : form_item.text"
                        :name="form_item.text"
                        :hint="form_item.hint"
                        :persistent-hint="true"
                      />
                    </div>

                    <div v-if="form_item.type == 'wysiwyg'">
                      <x-editor 
                        v-model="formModel[form_item.column]"
                        :ref="form_item.column"
                        :id="form_item.column"
                        :rules="form_item.validate"
                        :config="form_item.config"
                        :label="(form_item.required) ? form_item.text + $t('_required_') : form_item.text"
                        :name="form_item.text"
                        :hint="form_item.hint"
                        :persistent-hint="true"
                      />
                    </div>

                    <div v-if="form_item.type == 'enum'">
                      <x-autocomplete
                        v-model="formModel[form_item.column]"
                        :ref="form_item.column"
                        :id="form_item.column"
                        :rules="form_item.validate"
                        :label="(form_item.required) ? form_item.text + $t('_required_') : form_item.text"
                        :name="form_item.text"
                        :hint="form_item.hint"
                        :persistent-hint="true"
                        :placeholder="form_item.placeholder"
                        :prepend-inner-icon="form_item.icon"
                        :items="relations[form_item.column].items"
                        :loading="formModel[form_item.column + '_loading']"
                        :prefix="form_item.prefix"
                        :suffix="form_item.suffix"
                        item-text="val"
                        item-value="pk"
                        hide-no-data
                        hide-selected
                        clearable
                      />
                    </div>

                    <div v-if="form_item.type == 'date'">
                      <x-date-picker
                        v-model="formModel[form_item.column]"
                        :ref="form_item.column"
                        :id="form_item.column"
                        :rules="form_item.validate"
                        :label="(form_item.required) ? form_item.text + $t('_required_') : form_item.text"
                        :name="form_item.text"
                        :prepend-inner-icon="form_item.icon"
                        :placeholder="form_item.placeholder"
                        :hint="form_item.hint"
                        :locale="$auth.user().locale.substring(0,2)"
                        _format="form_item.format"
                        :persistent-hint="true"
                        :prefix="form_item.prefix"
                        :suffix="form_item.suffix"
                      />
                    </div>

                    <div v-if="form_item.type == 'relation' && form_item.relation.type == 'belongsToMany'">
                      <x-autocomplete
                        v-model="formModel[form_item.relation.with]"
                        :ref="form_item.relation.with"
                        :id="form_item.relation.with"
                        :rules="form_item.validate"
                        :label="(form_item.required) ? form_item.text + $t('_required_') : form_item.text"
                        :name="form_item.text"
                        :hint="form_item.hint"
                        :persistent-hint="true"
                        :placeholder="form_item.placeholder"
                        :prepend-inner-icon="form_item.icon"
                        :items="relations[form_item.relation.with].items"
                        :prefix="form_item.prefix"
                        :suffix="form_item.suffix"
                        item-text="val"
                        item-value="pk"
                        hide-no-data
                        hide-selected
                        chips
                        multiple
                        small-chips
                        deletable-chips
                      />
                    </div>

                    <div v-if="form_item.type == 'relation' && (form_item.relation.type == 'hasOne' || form_item.relation.type == 'belongsTo')">
                      <x-autocomplete
                        v-model="formModel[form_item.column]"
                        :ref="form_item.column"
                        :id="form_item.column"
                        :rules="form_item.validate"
                        :label="(form_item.required) ? form_item.text + $t('_required_') : form_item.text"
                        :name="form_item.text"
                        :hint="form_item.hint"
                        :persistent-hint="true"
                        :placeholder="form_item.placeholder"
                        :prepend-inner-icon="form_item.icon"
                        :items="relations[form_item.column].items"
                        :loading="formModel[form_item.column + '_loading']"
                        :prefix="form_item.prefix"
                        :suffix="form_item.suffix"
                        item-text="val"
                        item-value="pk"
                        hide-no-data
                        hide-selected
                        clearable
                      />
                    </div>

                    <div v-if="form_item.type == 'image' || form_item.type == 'avatar'">

                      <v-hover>
                        <template v-slot:default="{ hover }">
                          <v-img :src="formModel[form_item.column + '_media_url']" class="mt-5 mb-3 elevation-3" :class="form_item.class" contain :style="{'width': form_item.image.thumb_width, 'height': form_item.image.thumb_height, 'max-width': form_item.image.thumb_max_width, 'max-height': form_item.image.thumb_max_height}" v-if="formModel[form_item.column + '_media_url']">
                            <v-fade-transition>
                              <v-overlay
                                v-if="hover"
                                absolute
                              >
                                <v-btn-toggle rounded>
                                  <v-btn @click="pickFile(form_item.column)" x-small color="primary" rounded><v-icon size="14">mdi-folder-open</v-icon></v-btn>
                                  <v-btn @click="formModel[form_item.column + '_media_name'] = ''; formModel[form_item.column + '_media_url'] = ''; formModel[form_item.column + '_media_changed'] = true;" x-small color="red" rounded><v-icon size="14">mdi-close</v-icon></v-btn>
                                </v-btn-toggle>
                              </v-overlay>
                            </v-fade-transition>
                          </v-img>
                        </template>
                      </v-hover>

                      <x-text-field
                        @click="pickFile(form_item.column)"
                        type="text"
                        readonly
                        v-model="formModel[form_item.column + '_media_name']"
                        :ref="form_item.column + '_placeholder'"
                        :id="form_item.column + '_placeholder'"
                        :rules="form_item.validate"
                        :label="(form_item.required) ? form_item.text + $t('_required_') : form_item.text"
                        :prepend-inner-icon="form_item.icon"
                        :placeholder="form_item.placeholder"
                        :hint="form_item.hint"
                        :persistent-hint="true"
                        :prefix="form_item.prefix"
                        :suffix="form_item.suffix"
                      >
                        <template slot="append">
                            <v-icon v-if="formModel[form_item.column + '_media_name'] != ''" @click="formModel[form_item.column + '_media_name'] = ''; formModel[form_item.column + '_media_url'] = ''; formModel[form_item.column + '_media_changed'] = true;">mdi-close</v-icon>
                        </template>
                      </x-text-field>

                      <input
                        type="file"
                        style="display: none"
                        :id="form_item.column"
                        :name="form_item.column"
                        accept="image/*"
                        @change="onFilePicked"
                      >
                    </div>
  <!--


                    <div v-if="form_item.type == 'currency'">
                      <v-text-field
                        type="number"
                        ___autofocus="form_index == 0 && uuid == null"
                        v-model="formModel[form_item.column]"
                        :ref="form_item.column"
                        :data-vv-name="form_item.column"
                        :data-vv-as="form_item.text.toLowerCase()"
                        v-validate="form_item.validate"
                        :label="(form_item.required) ? form_item.text + $t('_required_') : form_item.text"
                        :error-messages="errors.collect('formModel.' + form_item.column)"
                        :prepend-inner-icon="form_item.icon"
                        :placeholder="form_item.placeholder"
                        :hint="form_item.hint"
                        :persistent-hint="true"
                        :prefix="form_item.prefix"
                        :suffix="form_item.suffix"
                      ></v-text-field>
                    </div>

                    <div v-if="form_item.type == 'color'">

                      <color-picker
                        ___autofocus="form_index == 0 && uuid == null"
                        v-model="formModel[form_item.column]"
                        :color="formModel[form_item.column]"
                        :mode="form_item.mode || 'hexa'"
                        :ref="form_item.column"
                        :data-vv-name="form_item.column"
                        :data-vv-as="form_item.text.toLowerCase()"
                        v-validate="form_item.validate"
                        :label="(form_item.required) ? form_item.text + $t('_required_') : form_item.text"
                        :error-messages="errors.collect('formModel.' + form_item.column)"
                        :prepend-inner-icon="form_item.icon"
                        :placeholder="form_item.placeholder"
                        :hint="form_item.hint"
                        :persistent-hint="true"
                        :prefix="form_item.prefix"
                        :suffix="form_item.suffix"
                      >
                      </color-picker>

                    </div>

                    <div v-if="form_item.type == 'slider'">
                      <v-slider
                        ___autofocus="form_index == 0 && uuid == null"
                        v-model="formModel[form_item.column]"
                        :ref="form_item.column"
                        :min="form_item.min"
                        :max="form_item.max"
                        :step="form_item.step"
                        :data-vv-name="form_item.column"
                        :data-vv-as="form_item.text.toLowerCase()"
                        v-validate="form_item.validate"
                        :label="(form_item.required) ? form_item.text + $t('_required_') : form_item.text"
                        :error-messages="errors.collect('formModel.' + form_item.column)"
                        :prepend-inner-icon="form_item.icon"
                        :placeholder="form_item.placeholder"
                        :hint="form_item.hint"
                        :persistent-hint="true"
                        :prefix="form_item.prefix"
                        :suffix="form_item.suffix"
                      >

                        <template #append>
                          <v-text-field
                            v-model="formModel[form_item.column]"
                            class="pt-0 mt-0"
                            hide-details
                            single-line
                            type="number"
                            style="width: 60px"
                          ></v-text-field>
                        </template>

                      </v-slider>
                    </div>

  -->
                  </div>

                </v-tab-item>
              </v-tabs-items>

  <!--              </div>-->

            </v-tab-item>
          </v-tabs-items>
        </v-card-text>

        <v-divider v-if="settings.dialog_height"></v-divider>

        <v-card-actions v-if="!loadingForm">
          <v-spacer></v-spacer>
          <v-btn color="primary" text :loading="form.loading" :disabled="form.loading || invalid"  large type="submit" class="ml-0">{{ (uuid === null) ? $t('create') : $t('save') }}</v-btn>
          <v-btn color="grey" text :disabled="form.loading" large @click="$emit('data-list-events', {'closeDialog': true, 'reload': false})">{{ $t('close') }}</v-btn>
        </v-card-actions>
      </v-form>
    </ValidationObserver>
    <v-overlay :value="form.loading" v-if="!loadingForm">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-card>
</template>
<script>
  export default {
    data: () => {
      return {
        tabCount: 1,
        selectedTab: 'tab1',
        selectedSubTab: {
          tab1: 'sub1',
          tab2: 'sub1',
          tab3: 'sub1',
          tab4: 'sub1',
          tab5: 'sub1',
          tab6: 'sub1',
          tab7: 'sub1',
          tab8: 'sub1',
          tab9: 'sub1',
          tab10: 'sub1',
          tab11: 'sub1',
          tab12: 'sub1'
        },
        count: null,
        max: null,
        limitReached: null,
        activeFilePickerColumn: null,
        formModel: {},
        loadingForm: true,
        loading: true,
        settings: [],
        form: [],
        translations: [],
        relations: []
      }
    },
    props: {
      api: {
        default: '/app/data-form',
        required: false,
        type: String
      },
      model: {
        default: '',
        required: false,
        type: String
      },
      uuid: {
        default: null,
        required: false,
        type: String
      }
    },
    computed: {
      app () {
        return this.$store.getters.app
      }
    },
    created() {
      this.moment.locale(this.$auth.user().locale)
    },
    beforeMount () {
      this.getDataFromApi()
        .then(data => {
          this.form = data.form
          this.tabCount = Object.keys(this.form.items).length
        })
    },
    methods: {
      submitForm(scope) {
        this.form.has_error = false
        this.form.loading = true

        this.saveForm()
/*
        if (this.tabCount > 1) {
          for (let i = 2; i <= this.tabCount; i++) {

          }
        }

        this.$validator.validateAll(scope).then((valid) => {
          if (valid) {
            this.saveForm()
          } else {
            // Get first error and select tab where error occurs
            let field = this.errors.items[0].field
            let el = (typeof this.$refs[field] !== 'undefined') ? this.$refs[field] : null
            let subtab = (el !== null) ? el[0].$parent.$vnode.key : null
            let tab = (el !== null) ? el[0].$parent.$parent.$parent.$vnode.key : null

            if (tab !== null) this.selectedTab = tab
            if (tab !== null && subtab !== null) this.selectedSubTab[tab] = subtab

            this.form.loading = false
            return false
          }
        })*/
      },
      saveForm() {
        this.loading = true
        let that = this

        let settings = { headers: { 'content-type': 'multipart/form-data' } }

        // Remove image urls
        let formModel = Object.assign({}, this.formModel)
        for (let field in this.formModel) {
          if (this.$_.endsWith(field, '_media_url') || this.$_.endsWith(field, '_media_name') || this.$_.endsWith(field, '_media_file') || field == 'avatar') {
            formModel[field] = null
          }
        }

        let formData = new FormData(document.getElementById('formModel'))
        formData.append('locale', this.$i18n.locale)
        formData.append('model', this.model)
        formData.append('formModel', JSON.stringify(formModel))
        formData.append('uuid', this.uuid)

        this.axios.post(this.api + '/save', formData, settings)
        .then(res => {
          if (res.data.status === 'success') {
            let action = (this.uuid === null) ? 'item_created' : 'item_saved'
            this.$root.$snackbar(this.$t(action))
            this.$emit('data-list-events', {'closeDialog': true, 'reload': true})
          }
        })
        .catch(err => {
          if (typeof err.response !== 'undefined') {
            if (typeof err.response.status !== 'undefined' && typeof err.response.data.msg !== 'undefined') {
              if (err.response.status == 422) {
                this.$root.$snackbar(err.response.data.msg)
                return
              }
            }
            this.formModel.has_error = true
            this.formModel.error = err.response.data.error
            this.formModel.errors = err.response.data.errors || {}

            // Get first error and select tab where error occurs
            let field = Object.keys(this.formModel.errors)[0]
            let el = (typeof this.$refs[field] !== 'undefined') ? this.$refs[field] : null
            let tab = (el !== null) ? el[0].$parent.$parent.$parent.$vnode.key : null
            if (tab !== null) this.selectedTab = tab

            for (let field in this.formModel.errors) {
              this.$refs[field][0].applyResult({
                errors: this.formModel.errors[field],
                valid: false,
                failedRules: {}
              })
            }
          }
        })
        .finally(() => {
          that.loading = false
          that.form.loading = false
        })
      },
      getDataFromApi () {
        this.loading = true
        return new Promise((resolve, reject) => {
          let that = this
          this.axios.get(this.api, {
            params: {
              locale: this.$i18n.locale,
              model: this.model,
              uuid: this.uuid
            }
            })
          .then(res => {
            if (res.data.status === 'success') {
              let form = {}

              form.items = res.data.form
              form.loading = false
              form.error = ''
              form.errors = {}
              form.has_error = false
              form.success = false

              that.settings = res.data.settings
              that.formModel = res.data.values
              that.translations = res.data.translations
              that.relations = res.data.relations
              that.count = res.data.count
              that.max = res.data.max
              that.limitReached = res.data.limitReached
              that.loading = false
              that.loadingForm = false

              // Dates
              for (let date of res.data.dates) {
                if (that.formModel[date] !== null) {
                  that.formModel[date] =  new Date(that.formModel[date])
                }
              }

              // Relations
              /*
              for (let relation of res.data.relations) {
                this.getRelatedData(relation.column, relation)
              }*/

              resolve({
                form
              })
            }
          })
          .catch(err => console.log(err.response.data))
          .finally(() => that.loading = false)
        })
      },
      pickFile (column) {
        this.activeFilePickerColumn = column
        document.getElementById(column).click()
      },
      onFilePicked (e) {
        const files = e.target.files
        if(files[0] !== undefined) {
          this.formModel[this.activeFilePickerColumn + '_media_name'] = files[0].name
          if(this.formModel[this.activeFilePickerColumn + '_media_name'].lastIndexOf('.') <= 0) {
            return
          }
          const fr = new FileReader ()
          fr.readAsDataURL(files[0])
          fr.addEventListener('load', () => {
            this.formModel[this.activeFilePickerColumn + '_media_url'] = fr.result
            this.formModel[this.activeFilePickerColumn + '_media_file'] = files[0] // this is an image file that can be sent to server...
            this.formModel[this.activeFilePickerColumn + '_media_changed'] = true
          })
        } else {
          this.formModel[this.activeFilePickerColumn + '_media_name'] = ''
          this.formModel[this.activeFilePickerColumn + '_media_file'] = ''
          this.formModel[this.activeFilePickerColumn + '_media_url'] = ''
          this.formModel[this.activeFilePickerColumn + '_media_changed'] = true
        }
      },
      getRelatedData (column, relation) {
        let that = this
        this.axios.post(this.api + '/relation', {
          locale: this.$i18n.locale,
          model: this.model,
          uuid: this.uuid,
          relation: relation
        })
        .then(res => {
          if (res.data.status === 'success') {
            that.formModel[column + '_items'] = res.data.fields
            that.formModel[column + '_loading'] = false
          }
        })
        .catch(err => console.log(err.response.data))
      }
    }
  }
</script>
<style scoped>
</style>
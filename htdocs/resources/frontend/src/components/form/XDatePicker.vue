<template>
  <v-menu
    v-model="dialog"
    :close-on-content-click="false"
    max-width="290"
  >
    <template v-slot:activator="{ on }">
      <div v-on="on">
        <ValidationProvider :name="$attrs.name || $attrs.label" :rules="rules" :ref="$attrs.id" v-slot="{ errors, valid }">
          <v-text-field
            clearable
            readonly
            v-model="localizedValue"
            :error-messages="errors"
            :_success="(rules === '') ? null : valid"
            v-bind="$attrs"
            v-on="$listeners"
            :name="null"
            filled
            @click:clear="innerValue = null"
          ></v-text-field>
        </ValidationProvider>
      </div>
    </template>
    <v-date-picker
      v-model="innerValue"
      v-bind="$attrs"
      @change="dialog = false"
    ></v-date-picker>
  </v-menu>
</template>

<script>
export default {
  props: {
    rules: {
      type: [Object, String],
      default: ""
    },
    // must be included in props
    value: {
      type: null
    }
  },
  data: () => ({
    dialog: false,
    innerValue: ""
  }),
  watch: {
    // Handles internal model changes.
    innerValue(newVal) {
      this.$emit("input", newVal)
    },
    // Handles external model changes.
    value(newVal) {
      this.innerValue = newVal
    }
  },
  created() {
    if (this.value) {
      let date = this.value.split(' ')
      this.innerValue = date[0] || this.value
    }
  },
  methods: {
    applyResult(result) {
      this.$refs[this.$attrs.id].applyResult(result)
    },
    validate() {
      this.$refs[this.$attrs.id].validate()
    }
  },
  computed: {
    localizedValue: {
      get (){
        return this.innerValue ? this.moment(this.innerValue).format('LL') : ''
      },
      set (newName){
        return newName
      } 
    }
  }
};
</script>

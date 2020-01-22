<template>
  <ValidationProvider :name="$attrs.name || $attrs.label" :rules="rules" :ref="$attrs.id" v-slot="{ errors, valid }">
    <v-checkbox
      v-model="innerValue"
      :error-messages="errors"
      :_success="(rules === '') ? null : valid"
      v-bind="$attrs"
      v-on="$listeners"
      :name="null"
      true-value="1"
    ></v-checkbox>
  </ValidationProvider>
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
      this.innerValue = this.value
    }
  },
  methods: {
    applyResult(result) {
      this.$refs[this.$attrs.id].applyResult(result)
    },
    validate() {
      this.$refs[this.$attrs.id].validate()
    }
  }
};
</script>

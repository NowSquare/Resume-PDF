<template>
  <ValidationProvider :name="$attrs.name || $attrs.label" :rules="rules" :ref="$attrs.id" v-slot="{ errors }">
    <div class="v-label v-label pb-2" v-html="$attrs.label"/>
    <ckeditor
      :editor="ClassicEditor" 
      v-model="innerValue"
      :error-messages="errors"
      v-bind="$attrs"
      v-on="$listeners"
      :name="null"
      :config="config"
    >
    </ckeditor>
    <div class="v-messages error--text mt-2 ml-3" role="alert" v-if="errors.length > 0">
      <div class="v-messages__wrapper">
        <div class="v-messages__message" v-for="(error, index) in errors" :key="index">{{ error }}</div>
      </div>
    </div>
  </ValidationProvider>
</template>

<script>
import Vue from 'vue'
import CKEditor from '@ckeditor/ckeditor5-vue'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import '@ckeditor/ckeditor5-theme-lark'

Vue.use(CKEditor)

export default {
  props: {
    rules: {
      type: [Object, String],
      default: ""
    },
    // must be included in props
    value: {
      type: null
    },
    config: {
      type: [Object, String, Array],
      default: function () {
        return {
          toolbar: {
            items: [
              'bold',
              'italic',
              /*'link',*/
              '|',
              'undo',
              'redo'
            ]
          }
        }
      }
    },
  },
  data: () => ({
    ClassicEditor: ClassicEditor,
    innerValue: "",
  }),
  watch: {
    // Handles internal model changes.
    innerValue(newVal) {
      this.$emit("input", newVal)
    },
    // Handles external model changes.
    value(newVal) {
      this.innerValue = newVal;
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
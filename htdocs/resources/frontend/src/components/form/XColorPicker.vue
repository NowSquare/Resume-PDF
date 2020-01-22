<template>
  <div>
    <ValidationProvider :name="$attrs.name || $attrs.label" :rules="rules" :ref="$attrs.id" v-slot="{ errors, valid }">
      <v-text-field
        v-model="color"
        :value="color"
        :error-messages="errors"
        :_success="(rules === '') ? null : valid"
        v-bind="$attrs"
        v-on="$listeners"
        :name="null"
        filled
        readonly
      >
        <template slot="prepend-inner">
          <div @click="showPicker" style="width: 24px; height: 24px; display: inline-block; border: thin solid #949494; border-radius: 4px; margin-right: 5px; cursor: pointer" :style="{'background-color': (color == null || color == '') ? 'transparent' : color }"></div>
        </template>
      </v-text-field>
    </ValidationProvider>
    <v-dialog
      v-model="display"
      @keydown.esc="display = false"
      eager
      persistent
      :width="width"
    >
      <v-card>
        <v-card-text style="padding: 10px 10px 0">
          <v-color-picker
            v-model="thisColor"
            :mode="mode"
            flat
            :hide-canvas="false"
            :hide-inputs="false"
            :hide-mode-switch="true"
            :show-swatches="true"
          ></v-color-picker>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <slot name="actions"
            :parent="this"
          >
            <v-btn color="primary" text @click="okHandler">{{ $t('ok') }}</v-btn>
            <v-btn color="red" text @click.native="clearHandler">{{ $t('clear') }}</v-btn>
            <v-btn color="grey" text @click.native="cancelHandler">{{ $t('cancel') }}</v-btn>
          </slot>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
const DEFAULT_COLOR = '#FFFFFF'

export default {
  model: {
    prop: 'color',
    event: 'input'
  },
  props: {
    rules: {
      type: [Object, String],
      default: ""
    },
    // must be included in props
    value: {
      type: null
    },
    color: {
      type: [String],
      default: null
    },
    mode: {
      type: [String],
      default: 'hexa'
    },
    width: {
      type: Number,
      default: 320
    }
  },
  data: () => ({
    oldValue: "",
    innerValue: "",
    selectedValue: "",
    display: false,
    colorSelected: false
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
  computed: {
    thisColor: {
      get () {
        let v = this.innerValue || this['hex'] || DEFAULT_COLOR
        return v.substr(0, 7)
      },
      set (val) {
        this.colorSelected = true
        this.selectedValue = val['hex'] || val
      }
    }
  },
  created () {
    this.innerValue = this.color
    this.oldValue = this.innerValue || ''
  },
  methods: {
    applyResult(result) {
      this.$refs[this.$attrs.id].applyResult(result)
    },
    validate () {
      this.$refs[this.$attrs.id].validate()
    },
    showPicker () {
      this.key++
      this.display = true
    },
    okHandler () {
      this.innerValue = this.selectedValue.substr(0, 7)
      this.display = false
    },
    cancelHandler () {
      this.thisColor = this.innerValue
      this.display = false
    },
    clearHandler () {
      this.selectedValue = DEFAULT_COLOR
      this.thisColor = DEFAULT_COLOR
      this.innerValue = ''
      this.colorSelected = false
      this.display = false
    }
  }
};
</script>

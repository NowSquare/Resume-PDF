'use strict';

import Vue from 'vue'

// Data
import DataTable from '@/components/data/DataTable.vue';
Vue.component('data-table', DataTable)

import DataForm from '@/components/data/DataForm.vue';
Vue.component('data-form', DataForm)

// Form elements
import XTextField from '@/components/form/XTextField.vue'
Vue.component('x-text-field', XTextField)

import XTextArea from '@/components/form/XTextArea.vue'
Vue.component('x-text-area', XTextArea)

import XPassword from '@/components/form/XPassword.vue'
Vue.component('x-password', XPassword)

import XCheckbox from '@/components/form/XCheckbox.vue'
Vue.component('x-checkbox', XCheckbox)

import XSelect from '@/components/form/XSelect.vue'
Vue.component('x-select', XSelect)

import XAutoComplete from '@/components/form/XAutoComplete.vue'
Vue.component('x-autocomplete', XAutoComplete)

import XEditor from '@/components/form/XEditor.vue'
Vue.component('x-editor', XEditor)

import XColorPicker from '@/components/form/XColorPicker.vue'
Vue.component('x-color-picker', XColorPicker)

import XDatePicker from '@/components/form/XDatePicker.vue'
Vue.component('x-date-picker', XDatePicker)

// Snackbar
import Snackbar from '@/components/ui/Snackbar.vue';
Vue.component('snackbar', Snackbar)

// Confirm
import Confirm from '@/components/ui/Confirm.vue';
Vue.component('confirm', Confirm)
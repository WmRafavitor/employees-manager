<template>
  <v-menu
    v-model="openMenuDatePicker"
    :close-on-content-click="false"
    transition="scale-transition"
    offset-y
    max-width="290px"
    min-width="290px"
  >
    <template v-slot:activator="{ on }">
      <v-text-field
        autocomplete="new-password"
        :rules="rules"
        v-model="dateFormatted"
        :label="label"
        append-icon="mdi-calendar"
        v-mask="'##/##/####'"
        @blur="setDateValue"
        :error-messages="errorMessages"
        @change="onChange"
        v-on="on"
      ></v-text-field>
    </template>
    <v-date-picker v-model="dateValue" no-title @input="openMenuDatePicker = false"></v-date-picker>
  </v-menu>
</template>

<script>
import { mask } from "vue-the-mask";
import { parse, format } from "fecha";

export default {
  name: 'TextDatePicker',
  directives: {
    mask
  },
  props: {
    value: String,
    label: String,
    rules: Array,
    errorMessages: Array,
  },
  data: () => ({
    openMenuDatePicker: false,
    dateFormatted: '',
  }),
  computed: {
    dateValue: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit('input', value);
      }
    }
  },
  methods: {
    formatDate(date) {
      if (!date) return null;

      return format(parse(date, "YYYY-MM-DD"), "DD/MM/YYYY");
    },
    parseDate(date) {
      if (!date) return null;

      return format(parse(date, "DD/MM/YYYY"), "YYYY-MM-DD");
    },
    setDateValue() {
      this.dateValue = this.parseDate(this.dateFormatted);
    },
    onChange(event) {
      this.$emit('change', event);
    }
  },
  watch: {
    dateValue() {
      this.dateFormatted = this.formatDate(this.dateValue);
    },
  }
}
</script>

<style>

</style>
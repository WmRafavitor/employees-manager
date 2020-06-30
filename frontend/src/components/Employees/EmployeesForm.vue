<template>
  <v-form v-model="valid">
    <v-alert ref="messageAlert" v-if="error" type="error">{{error}}</v-alert>
    <v-row>
      <v-col cols="12" sm="12" md="4">
        <v-text-field
          label="Nome"
          name="name"
          :rules="rules.name"
          type="text"
          v-model="employee.name"
          :error-messages="responseErrors.name"
          @change="removeErrors('name')"
          required
        />
      </v-col>
      <v-col cols="12" sm="6" md="4">
        <v-text-field
          label="Role"
          name="role"
          :rules="rules.role"
          type="text"
          v-model="employee.role"
          :error-messages="responseErrors.role"
          @change="removeErrors('role')"
          required
        />
      </v-col>
      <v-col cols="12" sm="6" md="4">
        <DatePicker
          label="Hired at"
          :rules="rules.hired_at"
          v-model="employee.hired_at"
          :error-messages="responseErrors.hired_at"
          @change="removeErrors('hired_at')"
        ></DatePicker>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" sm="6" md="4">
        <v-select v-model="employee.sex" :items="sexOptions" label="Sex"></v-select>
      </v-col>
      <v-col cols="12" sm="6" md="4">
        <DatePicker
          label="Date Birth"
          :rules="rules.date_birth"
          v-model="employee.date_birth"
          :error-messages="responseErrors.date_birth"
          @change="removeErrors('date_birth')"
        ></DatePicker>
      </v-col>
      <v-col cols="12" sm="12" md="4">
        <v-text-field
          label="Document"
          name="document"
          v-mask="'###.###.###-##'"
          :rules="rules.document"
          type="text"
          v-model="employee.document"
          :error-messages="responseErrors.document"
          @change="removeErrors('document')"
          required
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="6">
        <v-text-field
          label="Email"
          name="email"
          :rules="rules.email"
          type="text"
          v-model="employee.email"
          :error-messages="responseErrors.email"
          @change="removeErrors('email')"
          required
        />
      </v-col>
      <v-col cols="12" md="6">
        <v-text-field
          label="Phone number"
          name="phone"
          v-mask="['(##) ####-####', '(##) #####-####']"
          :rules="rules.phone"
          type="text"
          v-model="employee.phone"
          :error-messages="responseErrors.phone"
          @change="removeErrors('phone')"
          autocomplete="new-password"
        />
      </v-col>
    </v-row>

    <br />
    <v-btn :loading="loading" :disabled="!valid" color="primary" @click="submit()">{{submitLabel}}</v-btn>
  </v-form>
</template>

<script>
import { mask } from "vue-the-mask";
import DatePicker from "@/components/Shared/DatePicker/Index";

export default {
  name: "EmployeesForm",
  components: {
    DatePicker
  },
  directives: {
    mask
  },
  props: {
    value: Object,
    submitLabel: String,
    error: null,
    responseErrors: Object,
    loading: Boolean,
  },
  data: () => ({
    valid: true,
    sexOptions: [
      {
        text: "Male",
        value: "m"
      },
      {
        text: "Female",
        value: "f"
      }
    ],
    rules: {
      name: [
        v => !!v || "The name is required",
        v => (v && v.length >= 4) || "The name must be at least 4 characters",
        v =>
          (v && v.length <= 191) ||
          "The name must be a maximum of 191 characters"
      ],
      sex: [v => !!v || "The sex is required"],
      date_birth: [v => !!v || "The birth date is required"],
      role: [
        v => !!v || "The role is required",
        v =>
          (v && v.length <= 80) || "The role must be a maximum of 80 characters"
      ],
      phone: [v => !!v || "The phone is required"],
      email: [
        v => !!v || "The email is required",
        v =>
          !v || /.+@.+\..+/.test(v) || "The email must be a valid email address"
      ],
      hired_at: [v => !!v || "The hired at is required"]
    }
  }),
  computed: {
    employee: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit("input", value);
      }
    }
  },
  methods: {
    submit() {
      this.$emit("submit", this.employee);
    },
    removeErrors() {

    },
  }
};
</script>

<style>
</style>
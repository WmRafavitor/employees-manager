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
        <v-menu
          v-model="menuDatePickerHired"
          :close-on-content-click="false"
          transition="scale-transition"
          offset-y
          max-width="290px"
          min-width="290px"
        >
          <template v-slot:activator="{ on }">
            <v-text-field
              autocomplete="new-password"
              :rules="rules.hired_at"
              v-model="hiredAtFormatted"
              label="Hired at"
              append-icon="mdi-calendar"
              v-mask="'##/##/####'"
              @blur="closeDatePickerHired"
              :error-messages="responseErrors.hired_at"
              @change="removeErrors('hired_at')"
              v-on="on"
            ></v-text-field>
          </template>
          <v-date-picker v-model="employee.hired_at" no-title @input="menuDatePickerHired = false"></v-date-picker>
        </v-menu>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" sm="6" md="4">
        <v-select v-model="employee.sex" :items="sexOptions" label="Sex"></v-select>
      </v-col>
      <v-col cols="12" sm="6" md="4">
        <v-menu
          ref="menuDatePicker"
          v-model="menuDatePicker"
          :close-on-content-click="false"
          transition="scale-transition"
          offset-y
          max-width="290px"
          min-width="290px"
        >
          <template v-slot:activator="{ on }">
            <v-text-field
              autocomplete="new-password"
              :rules="rules.date_birth"
              v-model="dateBirthFormatted"
              label="Date Birth"
              append-icon="mdi-calendar"
              v-mask="'##/##/####'"
              @blur="closeDatePicker"
              :error-messages="responseErrors.date_birth"
              @change="removeErrors('date_birth')"
              v-on="on"
            ></v-text-field>
          </template>
          <v-date-picker v-model="employee.date_birth" no-title @input="menuDatePicker = false"></v-date-picker>
        </v-menu>
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
    <v-btn :loading="loading" :disabled="!valid" color="primary" @click="create()">Create</v-btn>
  </v-form>
</template>

<script>
import http from "@/services/http";
import messageAlertMixin from "@/mixins/messageAlert";
import { mask } from "vue-the-mask";
import { parse, format } from "fecha";

export default {
  name: "add-employee",
  mixins: [messageAlertMixin],
  directives: {
    mask
  },
  data: () => ({
    loading: false,
    valid: true,
    employee: {
      date_birth: "",
      hired_at: "",
    },
    responseErrors: {
      email: [],
      phone: [],
      name: [],
      document: []
    },
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
      hired_at: [v => !!v || "The hired at is required"],
    },
    menuDatePicker: false,
    dateBirthFormatted: "",
    menuDatePickerHired: false,
    hiredAtFormatted: ""
  }),
  computed: {
    dateBirth() {
      return this.employee.date_birth;
    },
    hiredAt() {
      return this.employee.hiredAt;
    }
  },
  methods: {
    create() {
      this.resetError();
      this.loading = true;
      http
        .post("employees", this.employee)
        .then(response => {
          if (response.data.error) {
            this.showError(response.data.error);
            return;
          }
          this.$router.push({ name: "employees-list" });
        })
        .catch(error => {
          this.showError('An error occurred while trying to create an employee.');
          if (error.response.data.errors) {
            for (const errorProperty in error.response.data.errors) {
              this.$set(
                this.responseErrors,
                errorProperty,
                error.response.data.errors[errorProperty]
              );
            }
            this.valid = true;
          }
        })
        .finally(() => {
          this.loading = false;
        });
    },
    removeErrors(field) {
      if (this.responseErrors[field]) {
        this.responseErrors[field] = [];
      }
    },
    formatDate(date) {
      if (!date) return null;

      return format(parse(date, "YYYY-MM-DD"), "DD/MM/YYYY");
    },
    parseDate(date) {
      if (!date) return null;

      return format(parse(date, "DD/MM/YYYY"), "YYYY-MM-DD");
    },
    closeDatePicker() {
      this.employee.date_birth = this.parseDate(this.dateBirthFormatted);
    },
    closeDatePickerHired() {
      this.employee.hired_at = this.parseDate(this.hiredAtFormatted);
    }
  },
  watch: {
    dateBirth() {
      this.dateBirthFormatted = this.formatDate(this.employee.date_birth);
    },
    hiredAt() {
      this.hiredAtFormatted = this.formatDate(this.employee.hired_at);
    }
  }
};
</script>

<style>
</style>
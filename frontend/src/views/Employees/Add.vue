<template>
  <EmployeesForm
    v-model="employee"
    @submit="create"
    :error="error"
    :responseErrors="responseErrors"
    :loading="loading"
    submitLabel="Create"
  ></EmployeesForm>
</template>

<script>
import http from '@/services/http';
import messageAlertMixin from '@/mixins/messageAlert';
import EmployeesForm from '@/components/Employees/EmployeesForm';

export default {
  name: "add-employee",
  components: {
    EmployeesForm,
  },
  mixins: [messageAlertMixin],
  data: () => ({
    loading: false,
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
    }
  }
};
</script>

<style>
</style>
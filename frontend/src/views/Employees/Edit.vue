<template>
  <div>
    <v-btn
      color="secondary"
      @click="goToList()"
    >
      <v-icon>mdi-arrow-left</v-icon>
      Return to List
    </v-btn>
    <EmployeesForm
      v-model="employee"
      :error="error"
      submitLabel="Update"
      @submit="update"
      :loading="loading"
      :responseErrors.sync="responseErrors"></EmployeesForm>
  </div>
</template>

<script>
import http from "@/services/http";
import messageAlertMixin from "@/mixins/messageAlert";
import EmployeesForm from "@/components/Employees/EmployeesForm";

export default {
  name: "edit-employee",
  props: {
    id: String
  },
  data: () => ({
    loading: false,
    employee: {},
    responseErrors: {
      email: [],
      phone: [],
      name: [],
      document: []
    },
  }),
  components: {
    EmployeesForm
  },
  mixins: [messageAlertMixin],
  created() {
    this.getEmployee();
  },
  methods: {
    getEmployee() {
      http
        .get(`employees/${this.id}`)
        .then(response => {
          this.employee = response.data;
        })
        .catch(() => {
          this.showError(
            "An error occurred while trying to retrieve an employee."
          );
        });
    },
    update() {
      this.resetError();
      this.loading = true;
      http
        .put(`employees/${this.id}`, this.employee)
        .then(response => {
          if (response.data.error) {
            this.showError(response.data.error);
            return;
          }
          this.$router.push({ name: "employees-list" });
        })
        .catch(error => {
          this.showError(
            "An error occurred while trying to update the employee."
          );
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
    goToList() {
      this.$router.push({
        name: 'employees-list'
      })
    },
  }
};
</script>

<style>
</style>
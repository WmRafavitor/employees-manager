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
      @submit="create"
      :error="error"
      :responseErrors.sync="responseErrors"
      :loading="loading"
      submitLabel="Create"
    ></EmployeesForm>
  </div>
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
    employee: {},
    responseErrors: {
      email: [],
      phone: [],
      name: [],
      document: []
    },
  }),
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
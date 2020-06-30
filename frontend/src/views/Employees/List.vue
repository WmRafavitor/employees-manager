<template>
  <div>
    <v-card>
      <v-card-title>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Seach employees"
          single-line
          hide-details
        ></v-text-field>
      </v-card-title>
      <v-data-table :headers="headers" :items="employees" :search="search" :loading="loading">
        <template v-slot:item.action="{ item }">
          <v-icon class="mr-2" @click="editItem(item)">mdi-pencil</v-icon>
          <v-icon @click="showConfirmDelete(item)">mdi-delete</v-icon>
        </template>
        <template v-slot:item.hired_at="{ item }">
          <span>{{formatDate(item.hired_at)}}</span>
        </template>
      </v-data-table>
    </v-card>
    <v-btn fab dark color="primary" fixed right bottom @click="newEmployee()">
      <v-icon>mdi-plus</v-icon>
    </v-btn>
    <v-dialog v-model="dialog" max-width="500">
      <v-card>
        <v-card-title class="headline">Warning</v-card-title>
        <v-card-text>
          <v-alert ref="messageAlert" v-if="error" type="error">{{error}}</v-alert>
          Are you sure you want to remove {{selectedEmployee ? selectedEmployee.name : '' }}?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" text @click="dialog = false">Não</v-btn>
          <v-btn color="green darken-1" :loading="loadingDelete" text @click="deleteItem()">Sim</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import http from '@/services/http';
import messageAlertMixin from '@/mixins/messageAlert';
import { parse, format } from 'fecha';

export default {
  name: "list-employees",
  mixins: [messageAlertMixin],
  data: () => ({
    loading: false,
    search: "",
    dialog: false,
    selectedEmployee: null,
    loadingDelete: false,
    employees: [],
    headers: [
      {
        text: "Id",
        value: "id"
      },
      {
        text: "Name",
        value: "name"
      },
      {
        text: "Document",
        value: "document"
      },
      { text: "Email", value: "email" },
      {
        text: "Hired at",
        value: "hired_at"
      },
      { text: "Actions", value: "action", sortable: false }
    ]
  }),
  created() {
    this.listEmployees();
  },
  methods: {
    newEmployee() {
      this.$router.push({ name: "add-employee" });
    },
    editItem(customer) {
      this.$router.push({ name: "edit-employee", params: { id: customer.id } });
    },
    showConfirmDelete(employee) {
      this.selectedEmployee = employee;
      this.dialog = true;
    },
    deleteItem() {
      if (!this.selectedEmployee) {
        return;
      }
      this.loadingDelete = true;
      this.resetError();
      http.delete(`employees/${this.selectedEmployee.id}`)
        .then(() => {
          this.dialog = false;
          this.listEmployees();
        }).catch(() => {
          this.showError("Não foi possível excluir o cliente. Tente novamente.");
        }).finally(() => {
          this.loadingDelete = false;
        });
    },
    listEmployees() {
      this.loading = true;
      http.get("employees").then(response => {
        this.employees = response.data;
      }).finally(() => {
        this.loading = false;
      });
    },
    formatDate(date) {
      return format(parse(date, 'YYYY-MM-DD'), 'DD/MM/YYYY');
    }
  },
};
</script>

<style>
</style>
<template>
  <v-app>
    <v-main>
      <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="8" md="4">
            <v-card class="elevation-12">
              <v-toolbar color="primary" dark flat>
                <v-toolbar-title>Login</v-toolbar-title>
              </v-toolbar>
              <v-card-text>
                <v-alert v-if="error" type="error">{{error}}</v-alert>
                <v-form v-model="valid">
                  <v-text-field
                    label="Login"
                    name="login"
                    prepend-icon="mdi-account"
                    type="text"
                    v-model="email"
                    :rules="rules.email"
                    required
                  ></v-text-field>

                  <v-text-field
                    id="password"
                    label="Password"
                    name="password"
                    prepend-icon="mdi-lock"
                    type="password"
                    v-model="password"
                    :rules="rules.password"
                    required
                  ></v-text-field>
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" :loading="loading" :disabled="!valid" @click="login()">Login</v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import authenticationService from "@/services/authentication";

export default {
  name: "Login",
  data: () => ({
    error: null,
    valid: true,
    loading: false,
    email: "",
    password: "",
    rules: {
      password: [v => !!v || "The field password is required."],
      email: [
        v => !!v || "The field email is required.",
        v =>
          /.+@.+\..+/.test(v) ||
          "The field email should has a valid email address."
      ]
    }
  }),
  created() {
    if (authenticationService.hasTokenValid()) {
      this.$router.push({ name: "home" });
    }
  },
  methods: {
    login() {
      this.error = null;
      this.loading = true;
      authenticationService.login(this.email, this.password).then(response => {
        this.loading = false;
        if (response.data.error) {
          this.error = response.data.error;
          return;
        }

        this.$router.push({ name: "home" });
      });
    }
  }
};
</script>
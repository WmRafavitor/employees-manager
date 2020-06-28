<template>
  <v-app>
    <v-app-bar app color="indigo" dark>
      <v-toolbar-title>Employees Manager</v-toolbar-title>

      <v-spacer></v-spacer>

      <v-menu left bottom>
        <template v-slot:activator="{ on }">
          <v-btn text v-on="on">
            {{ userName }}
            <v-icon>mdi-account</v-icon>
          </v-btn>
        </template>

        <v-list>
          <v-list-item @click="logout()">
            <v-list-item-icon>
              <v-icon>mdi-arrow-right</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Sair</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>

    <v-main>
      <v-container fluid>
        <router-view></router-view>
      </v-container>
    </v-main>
    <v-footer color="indigo" app>
      <span class="white--text">&copy; 2020</span>
    </v-footer>
  </v-app>
</template>

<script>
import { mapGetters } from 'vuex'
import authenticationService from '@/services/authentication';

export default {
  computed: {
    ...mapGetters([
      'userName',
    ]),
  },
  created() {
    this.$store.dispatch("getLoggedUser");
  },
  methods: {
    logout() {
      authenticationService.logout().then(() => {
        this.$router.push({ name: "login" });
        this.$store.dispatch('resetLoggedUser');
      });
    },
  },
};
</script>
import http from "@/services/http";

export default {
  state: {
    user: null
  },
  getters: {
    userName(state) {
      if (!state.user) {
        return "";
      }
      return state.user.name;
    },
  },
  mutations: {
    setUser(store, user) {
      store.user = user;
    }
  },
  actions: {
    getLoggedUser({ commit }) {
      return http.post("auth/me").then(response => {
        const user = response.data;

        commit('setUser', user);
      });
    },
    resetLoggedUser({ commit }) {
      commit('setUser', null);
    }
  }
}
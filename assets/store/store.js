import { createStore } from 'vuex';

export default createStore({
  state: {
    users:[]
  },
  mutations: {
    setUsers(state,users) {
      state.users = users;
    },
    updateUser(state,user) {
      const index = state.users.findIndex(u => u.id === user.id);
      if (index !== -1) {
        state.users[index] = user;
      }
    },
    addNewUser(state,user) {
      state.users.push(user);
    },
    deleteUser(state,user) {
      state.users = state.users.filter(u => u.id !== user.id);
    }
  },
  actions: {
    setUsers({ commit },payload) {
      commit('setUsers',payload.users);
    },
    updateUser({ commit },payload) {
      commit('updateUser',payload.user);
    },
    addNewUser({ commit },payload) {
      commit('addNewUser',payload.user);
    },
    deleteUser({ commit },payload) {
      commit('deleteUser',payload.user);
    }
  },
  getters: {
    getCount: (state) => state.users
  },
  modules: {
    // Add additional modules here
  },
});
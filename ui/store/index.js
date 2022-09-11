export const actions = {
  async env ({ commit }) {
    const response = await this.$axios.$get('env.json')
    if (response.isGuest) {
      commit('user/setToken', undefined)
    }
    commit('user/setEmail', response.email)
    return response
  }
}

export const getters = {
  isGuest (state) {
    return !state.user.token
  }
}

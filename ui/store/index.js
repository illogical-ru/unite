export const state = () => ({
  notify: undefined
})

export const actions = {
  async env ({ commit }) {
    const response = await this.$axios.$get('env.json')
    if (response.isGuest) {
      commit('user/setToken', undefined)
    }
    commit('user/setEmail', response.email)
    commit('user/setAvatar', response.avatar)
    return response
  }
}

export const mutations = {
  setNotify (state, opts) {
    state.notify = opts
  },
  setError (state, error) {
    this.commit('setNotify', {
      type: 'error',
      message: error
    })
  }
}

export const getters = {
  isGuest (state) {
    return !state.user.token
  }
}

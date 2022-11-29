export const state = () => ({
  theme: process.client &&
    window.localStorage &&
    localStorage.getItem('theme'),
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
  },

  setTheme (state, theme) {
    state.theme = theme
    if (process.client && window.localStorage) {
      localStorage.setItem('theme', theme)
    }
  }
}

export const getters = {
  isGuest (state) {
    return !state.user.token
  }
}

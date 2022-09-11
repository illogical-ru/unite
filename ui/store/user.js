export const state = () => ({
  email: undefined,
  token: process.client &&
    window.localStorage &&
    localStorage.getItem('token')
})

export const actions = {
  async signUp ({ commit }, data) {
    return await this.$axios.$post('signup.json', data)
  },
  async signIn ({ commit }, data) {
    const response = await this.$axios.$post('signin.json', data)
    if (response.token) {
      commit('setToken', response.token)
    }
    return response
  },
  async signOut ({ commit }) {
    const response = await this.$axios.$post('signout.json')
    if (response.success) {
      commit('setToken', undefined)
    }
    return response
  }
}

export const mutations = {
  setToken (state, token) {
    state.token = token
    if (process.client && window.localStorage) {
      if (token) {
        localStorage.setItem('token', token)
      } else {
        localStorage.removeItem('token')
      }
    }
  },
  setEmail (state, email) {
    state.email = email
  }
}

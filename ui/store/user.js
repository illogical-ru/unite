export const state = () => ({
  email: undefined,
  avatar: undefined,
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
  },

  async setAvatar ({ commit }, data) {
    const formData = new FormData()
    formData.append('avatar', data)

    const response = await this.$axios.$post(
      'avatar.json',
      formData,
      { headers: { 'Content-Type': 'multipart/form-data' } }
    )
    if (response.success) {
      commit('setAvatar', response.avatar)
    }
    return response
  },
  async delAvatar ({ commit }) {
    const response = await this.$axios.$delete('avatar.json')
    if (response.success) {
      commit('setAvatar', undefined)
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
  setEmail (state, val) {
    state.email = val
  },
  setAvatar (state, val) {
    state.avatar = val
  }
}

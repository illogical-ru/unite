<template lang="pug">
  v-app(dark)
    v-navigation-drawer(v-model='drawer' fixed app)
      v-list
        v-list-item(
          v-for='(item, i) in items'
          :key='i'
          :to='item.to'
          router
          exact
        )
          v-list-item-action
            v-icon {{ item.icon }}
          v-list-item-content
            v-list-item-title(v-text='item.title')
      template(v-slot:append)
        v-select(
          v-model='theme'
          :items='normThemes'
          item-text='name'
          item-value='id'
          filled
          dense
          :label='$dict.tr("Theme")'
          @change='setTheme'
        )
    v-app-bar(fixed app)
      v-app-bar-nav-icon(
        @click.stop='drawer = !drawer'
        :disabled='!isOk'
      )
      v-toolbar-title(v-text='title')
      v-spacer
      v-btn(
        v-if='$store.getters.isGuest'
        icon
        to='signin'
        :disabled='!isOk'
      )
        v-icon mdi-account-circle-outline
      v-menu(v-else v-model='userMenu')
        template(v-slot:activator='{ on, attrs }')
          v-btn(v-bind='attrs' v-on='on' icon)
            v-icon mdi-account-circle
        v-card
          v-list
            v-list-item
              v-list-item-avatar
                v-avatar(color='grey')
                  img(
                    v-if='$store.state.user.avatar'
                    :src='$store.state.user.avatar'
                  )
              v-list-item-content
                v-list-item-title {{ $store.state.user.email }}
          v-divider
          v-list(dense min-width='250')
            v-list-item(
              link
              v-for='(item, i) in userItems'
              :key='i'
              :to='item.to'
            )
              v-list-item-icon
                v-icon(v-text='item.icon')
              v-list-item-content
                v-list-item-title(v-text='$dict.tr(item.text)')
    v-main
      v-container
        v-overlay(v-if='isLoading' value='1')
          v-progress-circular(indeterminate size='64')
        v-alert(
          v-if='fatal'
          prominent
          dense
          type='error'
          border='left'
        )
          v-row(align='center')
            v-col.grow {{ $dict.trError(fatal) }}
            v-col.shrink
              v-btn(text @click='getEnv') {{ $dict.tr('Refresh') }}
        div(v-show='isOk')
          nuxt
    v-snackbar(
      v-model='notify.flag'
      tile
      :color='notify.type == "error" ? "red" : ""'
    )
      | {{ $dict.tr(notify.message, notify.type) }}
      template(v-slot:action='{ attrs }')
        v-btn(
          text
          v-bind='attrs'
          x-small
          @click='notify.flag = false'
        )
          | {{ $dict.tr('Close') }}
</template>

<script>
export default {
  data () {
    return {

      drawer: false,
      items: [],

      theme: undefined,
      themes: ['Dark', 'Light'],

      userMenu: false,
      userItems: [
        {
          icon: 'mdi-badge-account-outline',
          text: 'Profile',
          to: 'profile'
        },
        {
          icon: 'mdi-logout',
          text: 'Sign Out',
          to: 'signout'
        }
      ],

      isLoading: true,

      fatal: undefined,

      notify: {
        flag: false,
        message: undefined
      }
    }
  },
  computed: {
    isOk () {
      return !this.isLoading && !this.fatal
    },
    title () {
      return this.$root.$options.head.title
    },
    normThemes () {
      return this.themes.map(val => ({
        id: val,
        name: this.$dict.tr(val, 'theme')
      }))
    }
  },
  watch: {
    '$store.state.notify' (data) {
      if (data && data.message) {
        this.notify.type = data.type
        this.notify.message = data.message
        this.notify.flag = true
        this.$store.commit('setNotify', undefined)
      }
    },
    '$store.getters.isGuest' () {
      this.init()
    },
    theme (val) {
      this.$vuetify.theme.dark = val === 'Dark'
    }
  },
  mounted () {
    this.theme = this.$store.state.theme || this.themes[0]
    this.init()
  },
  methods: {
    init () {
      this.$axios.setToken(
        this.$store.state.user.token,
        'Bearer'
      )
      this.getEnv()
    },
    getEnv () {
      this.isLoading = true
      this.fatal = undefined
      this.$store
        .dispatch('env')
        .catch(() => {
          this.fatal = 'Request failed'
        })
        .then(() => {
          this.isLoading = false
        })
    },
    setTheme (theme) {
      this.$store.commit('setTheme', theme)
    }
  }
}
</script>

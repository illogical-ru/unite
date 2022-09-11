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
    v-app-bar(fixed app)
      v-app-bar-nav-icon(@click.stop='drawer = !drawer' :disabled='!isOk')
      v-toolbar-title(v-text='title')
      v-spacer
      v-btn(v-if='$store.getters.isGuest' icon to='signin' :disabled='!isOk')
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
        v-alert(v-if='fatal' prominent dense type='error' border='left')
          v-row(align='center')
            v-col.grow {{ $dict.trError(fatal) }}
            v-col.shrink
              v-btn(text @click='getEnv') {{ $dict.tr('Refresh') }}
        nuxt(v-else)
</template>

<script>
export default {
  data () {
    return {
      drawer: false,
      items: [],
      userMenu: false,
      userItems: [
        {
          icon: 'mdi-logout',
          text: 'Sign Out',
          to: 'signout'
        }
      ],
      isLoading: true,
      fatal: undefined
    }
  },
  computed: {
    isOk () {
      return !this.isLoading && !this.fatal
    },
    title () {
      return this.$root.$options.head.title
    }
  },
  watch: {
    '$store.getters.isGuest' () {
      this.init()
    }
  },
  mounted () {
    this.init()
  },
  methods: {
    init () {
      this.$axios.interceptors.request.use((config) => {
        if (this.$store.state.user.token) {
          config.headers.Authorization = `Bearer ${this.$store.state.user.token}`
        } else {
          delete config.headers.Authorization
        }
        return config
      })
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
    }
  }
}
</script>

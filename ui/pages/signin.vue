<template lang="pug">
  v-card.mx-auto(
    v-show='$store.getters.isGuest'
    style='max-width: 320px'
  )
    v-card-title
      | {{ $dict.tr('Sign In') }}
    v-divider
    v-card-text
      SignIn(@success='success')
    v-divider
    v-card-text(align='center')
      | {{ $dict.tr('No account yet') }}?
      |
      NuxtLink(to='signup') {{ $dict.tr('Sign Up') }}
</template>

<script>
export default {
  mounted () {
    if (!this.$store.getters.isGuest) {
      this.$nuxt.error({
        statusCode: 404,
        message: 'This page could not be found'
      })
    }
  },
  methods: {
    success () {
      this.$router.push('/')
    }
  }
}
</script>

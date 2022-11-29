<template lang="pug">
  v-card.mx-auto(
    v-show='!$store.getters.isGuest'
  )
    v-card-title
      | {{ $dict.tr('Profile') }}
    v-divider
    v-card-text
      Profile
</template>

<script>
export default {
  mounted () {
    if (this.$store.getters.isGuest) {
      this.$nuxt.error({
        statusCode: 404,
        message: 'This page could not be found'
      })
    }
  }
}
</script>

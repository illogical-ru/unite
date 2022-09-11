<template lang="pug">
</template>

<script>
export default {
  mounted () {
    if (this.$store.getters.isGuest) {
      this.$nuxt.error({
        statusCode: 404,
        message: 'This page could not be found'
      })
    } else {
      this.$store
        .dispatch('user/signOut')
        .then((result) => {
          if (result.success) {
            this.$router.push('/')
          }
        })
    }
  }
}
</script>

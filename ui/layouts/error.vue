<template lang="pug">
  v-card.mx-auto(
    style='max-width: 320px'
    outlined
  )
    v-card-title.text-h5
      | {{ errorCode }}
    v-card-subtitle
      | {{ $dict.trError(error.message) }}
    v-divider
    v-card-actions
      v-btn(
        to='/'
        block
        color='primary'
      )
        | {{ $dict.tr('Home page') }}
</template>

<script>
export default {
  layout: 'empty',
  props: {
    error: {
      type: Object,
      default: null
    }
  },
  head () {
    const title = this.errorCode
    return { title }
  },
  computed: {
    errorCode () {
      return this.error.statusCode + ' ' + this.$dict.tr('Error')
    }
  }
}
</script>

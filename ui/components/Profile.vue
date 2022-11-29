<template lang="pug">
  v-row
    v-col(cols='12' sm='4' align='center')
      div(style='max-width: 170px')
        v-avatar(color='grey' size='160')
          img(
            v-if='$store.state.user.avatar'
            :src='$store.state.user.avatar'
          )
        v-file-input(
          v-model='avatar.form'
          :disabled='avatar.isLoading'
          hide-input
          accept='image/*'
          prepend-icon='mdi-image-edit-outline'
          style='float: left; margin-top: -14px'
          @change='setAvatar'
        )
        v-btn(
          v-if='$store.state.user.avatar'
          :disabled='avatar.isLoading'
          icon
          small
          style='float: right'
          @click='delAvatar'
        )
          v-icon mdi-trash-can-outline
    v-col(cols='12' sm='8')
      v-form(disabled)
        v-text-field(
          :value='$store.state.user.email'
        )
        v-btn(
          disabled
          type='submit'
          color='primary'
        )
          | {{ $dict.tr('Submit') }}
</template>

<script>
export default {
  data: () => ({
    avatar: {
      form: undefined,
      isLoading: false
    }
  }),
  methods: {
    setAvatar () {
      this.avatar.isLoading = true
      this.$store
        .dispatch('user/setAvatar', this.avatar.form)
        .then((result) => {
          if (result.errors && result.errors.avatar) {
            this.$store.commit(
              'setError',
              'avatar_' + result.errors.avatar
            )
          }
        })
        .catch(() => {
          this.$store.commit('setError', 'Request failed')
        })
        .then(() => {
          this.avatar.isLoading = false
        })
    },
    delAvatar () {
      this.avatar.isLoading = true
      this.$store
        .dispatch('user/delAvatar')
        .catch(() => {
          this.$store.commit('setError', 'Request failed')
        })
        .then(() => {
          this.avatar.isLoading = false
        })
    }
  }
}
</script>

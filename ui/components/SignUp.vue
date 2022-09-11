<template lang="pug">
  div
    v-alert(v-if='fatal' type='error')
      | {{ $dict.trError(fatal) }}
    v-form(
      ref='form'
      v-model='isValid'
      @submit.prevent='submit'
    )
      v-text-field(
        v-model='form.email'
        filled
        :label='$dict.tr("Email")'
        type='email'
        :error-messages='$dict.trError(errors.email)'
        @input='errors.email = undefined'
      )
      v-text-field(
        v-model='form.password'
        filled
        :label='$dict.tr("Password")'
        type='password'
        :error-messages='$dict.trError(errors.password)'
        @input='errors.password = undefined'
      )
      v-btn(
        type='submit'
        :disabled='!isValid'
        :loading='isLoading'
        color='blue-grey'
        block
      )
        | {{ $dict.tr('Submit') }}
</template>

<script>
export default {
  data: () => ({
    form: {
      email: undefined,
      password: undefined
    },
    isLoading: false,
    isValid: false,
    errors: {},
    fatal: undefined
  }),
  methods: {
    submit () {
      this.isLoading = true
      this.fatal = undefined
      this.$store
        .dispatch('user/signUp', this.form)
        .then((result) => {
          if (result.success) {
            this.$emit('success')
          } else if (result.errors) {
            this.errors = result.errors
          }
        })
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

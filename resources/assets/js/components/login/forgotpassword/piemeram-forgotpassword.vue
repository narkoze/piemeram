<template>
  <form>
    <h1>{{ $t('piemeram-forgotpassword.title') }}</h1>

    <div :class="['ui', 'form', { loading: disabled }]">
      <div :class="['field', { error: errors.email }]">
        <label>{{ $t('piemeram-forgotpassword.email') }}
          <input
            v-model="email"
            @keyup.enter="sendPasswordReset"
            type="email"
            name="email"
          >
        </label>
        <small v-if="errors.email" class="error">{{ errors.email.join() }}</small>
      </div>

      <a
        @click="sendPasswordReset"
        class="ui button red"
      >
        {{ $t('piemeram-forgotpassword.sendpasswordlink') }}
      </a>
    </div>
  </form>
</template>

<script>
  import axios from '../../../axios'

  export default {
    i18n: window.i18n,
    props: {
      loginEmail: {
        type: String,
        default: ''
      }
    },
    data: () => ({
      email: null,
      disabled: false,
      errors: {}
    }),
    created () {
      this.setEmail()
    },
    watch: {
      'loginEmail': 'setEmail'
    },
    methods: {
      setEmail () {
        this.email = this.loginEmail
      },
      sendPasswordReset () {
        this.disabled = true

        axios
          .post('/password/email', {
            email: this.email
          })
          .then(() => {
            this.disabled = false
            this.$emit('linksent')
          })
          .catch(error => {
            this.disabled = false
            this.errors = error.response.data.errors
          })
      }
    }
  }
</script>


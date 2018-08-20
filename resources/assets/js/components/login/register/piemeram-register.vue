<template>
  <form>
    <h1>{{ $t('piemeram-register.title') }}</h1>

    <div :class="['ui', 'form', { loading: disabled }]">
      <div :class="['field', { error: errors.name }]">
        <label>{{ $t('piemeram-register.name') }}
          <input
            v-model="name"
            type="text"
          >
        </label>
        <small v-if="errors.name" class="error">{{ errors.name.join() }}</small>
      </div>

      <div :class="['field', { error: errors.email }]">
        <label>{{ $t('piemeram-register.email') }}
          <input
            v-model="email"
            type="email"
            name="email"
          >
        </label>
        <small v-if="errors.email" class="error">{{ errors.email.join() }}</small>
      </div>

      <div :class="['field', { error: errors.password }]">
        <label>{{ $t('piemeram-register.password') }}
          <input
            v-model="password"
            type="password"
            autocomplete="new-password"
          >
        </label>
        <small v-if="errors.password" class="error">{{ errors.password.join() }}</small>
      </div>

      <div class="field">
        <label>{{ $t('piemeram-register.passwordagain') }}
          <input
            v-model="password_confirmation"
            @keyup.enter="register"
            type="password"
          >
        </label>
      </div>

      <a
        @click="register"
        class="ui button red"
      >
        {{ $t('piemeram-register.register') }}
      </a>
    </div>
  </form>
</template>

<script>
  import axios from '../../../axios'

  export default {
    i18n: window.i18n,
    data: () => ({
      name: null,
      email: null,
      password: null,
      password_confirmation: null,
      disabled: false,
      errors: {}
    }),
    methods: {
      register () {
        this.disabled = true

        axios
          .post('/register', {
            name: this.name,
            email: this.email,
            password: this.password,
            password_confirmation: this.password_confirmation
          })
          .then(() => {
            this.$emit('registered')
          })
          .catch(error => {
            this.disabled = false
            this.errors = error.response.data.errors
          })
      }
    }
  }
</script>


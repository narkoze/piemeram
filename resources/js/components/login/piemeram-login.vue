<template>
  <form>
    <h1>{{ $t('piemeram-login.title') }}</h1>

    <div :class="['ui', 'form', { loading: disabled }]">
      <div :class="['field', { error: errors.email }]">
        <label>{{ $t('piemeram-login.email') }}
          <input
            v-model="email"
            type="email"
            name="email"
          >
        </label>
        <small v-if="errors.email" class="error">{{ errors.email.join() }}</small>
      </div>

      <div :class="['field', { error: errors.password }]">
        <label>{{ $t('piemeram-login.password') }}
          <input
            v-model="password"
            @keyup.enter="login"
            type="password"
            name="password"
          >
        </label>
        <small v-if="errors.password" class="error">{{ errors.password.join() }}</small>
        <small>
          <a
            href=""
            @click.prevent="showForgotPassword = true; $emit('close')"
          >
            {{ $t('piemeram-login.forgotpassword') }}
          </a>
        </small>
      </div>

      <a
        @click="login"
        class="ui button red"
      >
        {{ $t('piemeram-login.login') }}
      </a>

      <a
        @click="showRegister = true; $emit('close')"
        class="ui button right floated"
      >
        {{ $t('piemeram-login.register') }}
      </a>
    </div>

    <piemeram-modal
      name="register"
      :show="showRegister"
      @close="showRegister = false"
      size="tiny"
    >
      <piemeram-register @registered="$emit('loggedin')"></piemeram-register>
    </piemeram-modal>

    <piemeram-modal
      name="forgotPassword"
      :show="showForgotPassword"
      @close="showForgotPassword = false"
      size="tiny"
    >
      <piemeram-forgotpassword
        :login-email="email"
        @linksent="showForgotPassword = false"
      >
      </piemeram-forgotpassword>
    </piemeram-modal>
  </form>
</template>

<script>
  import axios from '../../axios'
  import PiemeramModal from '../modal/piemeram-modal.vue'
  import PiemeramRegister from './register/piemeram-register.vue'
  import PiemeramForgotpassword from './forgotpassword/piemeram-forgotpassword.vue'

  export default {
    i18n: window.i18n,
    components: {
      PiemeramModal,
      PiemeramRegister,
      PiemeramForgotpassword
    },
    data: () => ({
      email: 'demo@piemeram.lv',
      password: 'demons',
      disabled: false,
      showRegister: false,
      showForgotPassword: false,
      errors: {}
    }),
    methods: {
      login () {
        this.disabled = true

        axios
          .post('/login', {
            email: this.email,
            password: this.password
          })
          .then(() => {
            this.$emit('loggedin')
          })
          .catch(error => {
            this.disabled = false
            this.errors = error.response.data.errors
          })
      }
    }
  }
</script>

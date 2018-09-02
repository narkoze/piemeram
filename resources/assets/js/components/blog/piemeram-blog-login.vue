<template>
  <form>
    <h1 class="title">{{ $t('blog.blog-login.title') }}</h1>

    <div class="field">
      <label class="label">
        {{ $t('blog.blog-login.email') }}
        <p class="control has-icons-left">
          <input
            v-model="email"
            name="email"
            type="email"
            :class="['input', { 'is-danger': errors.email }]"
            :disabled="disabled"
            autofocus
          >
          <span class="icon is-small is-left">
            <i class="fas fa-envelope"></i>
          </span>
        </p>
      </label>
      <p v-if="errors.email" class="help is-danger">{{ errors.email.join() }}</p>
    </div>


    <div class="field">
      <label class="label">
        {{ $t('blog.blog-login.password') }}
        <p class="control has-icons-left">
          <input
            v-model="password"
            @keyup.enter="login"
            type="password"
            name="password"
            :class="['input', { 'is-danger': errors.password }]"
            :disabled="disabled"
          >
          <span class="icon is-small is-left">
            <i class="fas fa-lock"></i>
          </span>
        </p>
      </label>
      <p v-if="errors.password" class="help is-danger">{{ errors.password.join() }}</p>
    </div>

    <div class="field">
      <a
        @click="login"
        :class="['button', 'is-info', { 'is-loading': disabled }]"
      >
        <span class="icon">
          <i class="fas fa-sign-in-alt"></i>
        </span>
        <span>{{ $t('blog.blog-login.login') }}</span>
      </a>
    </div>
  </form>
</template>

<script>
  import AxiosErrorHandler from '../mixins/AxiosErrorHandler'
  import axios from '../../axios'

  export default {
    mixins: [
      AxiosErrorHandler,
    ],
    data: () => ({
      email: 'demo@piemeram.lv',
      password: 'demons'
    }),
    methods: {
      login () {
        this.disabled = true
        this.errors = {}

        axios
          .post('login', {
            email: this.email,
            password: this.password
          })
          .then(response => {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = response.data.csrf_token

            this.$root.auth = response.data.auth

            this.$root.showModals.splice('login', 1)
          })
          .catch(this.handleAxiosError)
      }
    }
  }
</script>


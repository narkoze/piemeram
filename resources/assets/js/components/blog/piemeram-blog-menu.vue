<template>
  <nav class="navbar is-fixed-top is-dark">
    <slot></slot>
    <div class="navbar-brand">
      <a
        class="navbar-item"
        href="/blog"
      >
        <b>{{ $t('blog.blog-menu.title') }}</b>
      </a>

      <div
        @click="isMenuActive = !isMenuActive"
        :class="['navbar-burger', 'burger', { 'is-active': isMenuActive }]"
      >
        <span></span><span></span><span></span>
      </div>
    </div>

    <div :class="['navbar-menu', { 'is-active': isMenuActive }]">
      <div class="navbar-start">
        <a
          @click="() => {
            $root.showView = ''
            $nextTick(() => {
              $root.showView = 'public-view-posts'
              isMenuActive = false
            })
          }"
          class="navbar-item"
        >
          {{ $t('blog.blog-menu.posts') }}
        </a>
      </div>

      <div class="navbar-end">
        <div
          v-if="$root.auth"
          class="navbar-item"
        >
          {{ $t('blog.blog-menu.greeting', { name: $root.auth.name }) }}
        </div>

        <div
          v-if="$root.auth"
          class="navbar-item"
        >
          <p>
            <a
              @click="logout"
              :class="['bd-tw-button', 'button', 'is-info', { 'is-loading': disabledLogout }]"
            >
              <span class="icon">
                <i class="fas fa-sign-in-alt"></i>
              </span>
              <span>{{ $t('blog.blog-menu.logout') }}</span>
            </a>

            <a
              @click="() => {
                $root.showView = 'admin-view-posts'
                $root.activeSection = 'admin-view-posts'
                isMenuActive = false
              }"
              class="bd-tw-button button is-link"
            >
              <span class="icon">
                <i class="fas fa-cogs"></i>
              </span>
              <span>{{ $t('blog.blog-menu.admin') }}</span>
            </a>
          </p>
        </div>

        <div
          v-if="!$root.auth"
          class="navbar-item"
        >
          <a
            @click="$root.showModals.push('login')"
            class="bd-tw-button button is-info"
          >
            <span class="icon">
              <i class="fas fa-sign-in-alt"></i>
            </span>
            <span>{{ $t('blog.blog-menu.login') }}</span>
          </a>
        </div>


        <div class="navbar-item has-dropdown is-hoverable">
          <a
            v-if="$i18n.locale === 'lv'"
            class="navbar-link"
          >
            <span class="flag-icon flag-icon-lv"></span> LV
          </a>
          <a
            v-else
            class="navbar-link"
          >
            <span class="flag-icon flag-icon-gb"></span> EN
          </a>
          <div class="navbar-dropdown is-boxed">
            <a
              v-if="$i18n.locale === 'en'"
              @click="setLocale('lv')"
              class="navbar-item"
            >
              <span class="flag-icon flag-icon-lv"></span> LV
            </a>
            <a
              v-else
              @click="setLocale('en')"
              class="navbar-item"
            >
              <span class="flag-icon flag-icon-gb"></span> EN
            </a>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
  import axios from '../../axios'

  export default {
    data: () => ({
      isMenuActive: false,
      disabledLogout: false
    }),
    methods: {
      setLocale (locale) {
        axios
          .post(`locale/${locale}`)
          .then(() => { this.$i18n.locale = locale })
      },
      logout () {
        this.disabledLogout = true

        axios
          .post('logout')
          .then(response => {
            this.disabledLogout = false

            axios.defaults.headers.common['X-CSRF-TOKEN'] = response.data.csrf_token

            this.$root.auth = null
          })
      }
    }
  }
</script>

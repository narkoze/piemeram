<template>
  <div>
    <piemeram-blog-menu
      :auth="auth"
      @login="showLogin = true"
      @loggedout="auth = null; show = 'public'"
      @showAdmin="show = 'admin'"
    >
      <piemeram-blog-notification
        v-if="notification"
        :notification="notification"
        @closed="notification = null"
      >
      </piemeram-blog-notification>
    </piemeram-blog-menu>

    <piemeram-blog-admin v-show="show === 'admin'"></piemeram-blog-admin>
    <piemeram-blog-public
      v-show="show === 'public'"
      :authenticated="Boolean(this.auth)"
    >
    </piemeram-blog-public>

    <piemeram-blog-modal
      v-if="showLogin"
      @close="showLogin = false"
    >
      <piemeram-blog-login @loggedin="(loggedin) => { auth = loggedin; showLogin = false }"></piemeram-blog-login>
    </piemeram-blog-modal>
  </div>
</template>

<script>
  import PiemeramBlogNotification from './piemeram-blog-notification.vue'
  import PiemeramBlogPublic from './public/piemeram-blog-public.vue'
  import PiemeramBlogAdmin from './admin/piemeram-blog-admin.vue'
  import PiemeramBlogModal from './piemeram-blog-modal.vue'
  import PiemeramBlogLogin from './piemeram-blog-login.vue'
  import PiemeramBlogMenu from './piemeram-blog-menu.vue'

  export default {
    i18n: window.i18n,
    components: {
      PiemeramBlogNotification,
      PiemeramBlogPublic,
      PiemeramBlogModal,
      PiemeramBlogLogin,
      PiemeramBlogAdmin,
      PiemeramBlogMenu
    },
    props: [
      'authUser',
    ],
    data: function () {
      return {
        auth: this.authUser,
        notification: null,
        showLogin: false,
        show: 'public'
      }
    },
    created () {
      window.blogBus.$on('notification', notification => {
        this.notification = notification
      })
      window.blogBus.$on('showPublic', () => {
        this.show = 'public'
      })
      window.blogBus.$on('showPublicPost', () => {
        this.show = 'public'
      })
      window.blogBus.$on('editAdminPost', post => {
        this.show = 'admin'
        window.blogBus.$emit('editPublicPost', post)
      })
    }
  }
</script>

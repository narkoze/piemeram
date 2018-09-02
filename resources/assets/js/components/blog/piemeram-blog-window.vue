<template>
  <div>
    <piemeram-blog-menu></piemeram-blog-menu>

    <piemeram-blog-admin v-if="$root.show.side === 'admin'"></piemeram-blog-admin>
    <piemeram-blog-public v-if="$root.show.side === 'public'"></piemeram-blog-public>

    <piemeram-blog-modal
      v-if="$root.showModals.includes('login')"
      @close="$root.showModals.splice('login', 1)"
    >
      <piemeram-blog-login></piemeram-blog-login>
    </piemeram-blog-modal>
  </div>
</template>

<script>
  import PiemeramBlogPublic from './public/piemeram-blog-public.vue'
  import PiemeramBlogAdmin from './admin/piemeram-blog-admin.vue'
  import PiemeramBlogModal from './piemeram-blog-modal.vue'
  import PiemeramBlogLogin from './piemeram-blog-login.vue'
  import PiemeramBlogMenu from './piemeram-blog-menu.vue'

  export default {
    i18n: window.i18n,
    components: {
      PiemeramBlogPublic,
      PiemeramBlogModal,
      PiemeramBlogLogin,
      PiemeramBlogAdmin,
      PiemeramBlogMenu
    }
  }
</script>

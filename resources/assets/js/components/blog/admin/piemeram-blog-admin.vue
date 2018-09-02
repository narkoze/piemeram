<template>
  <div>
    <piemeram-blog-admin-menu-mobile class="is-hidden-desktop"></piemeram-blog-admin-menu-mobile>

    <div class="columns is-gapless is-fullheight">
      <div class="column is-2 is-hidden-touch">
        <piemeram-blog-admin-menu-full></piemeram-blog-admin-menu-full>
      </div>

      <div class="column">
        <piemeram-blog-admin-views></piemeram-blog-admin-views>
      </div>
    </div>
  </div>
</template>

<script>
  import PiemeramBlogAdminMenuMobile from './piemeram-blog-admin-menu-mobile.vue'
  import PiemeramBlogAdminMenuFull from './piemeram-blog-admin-menu-full.vue'
  import PiemeramBlogAdminViews from './piemeram-blog-admin-views.vue'

  export default {
    components: {
      PiemeramBlogAdminMenuMobile,
      PiemeramBlogAdminMenuFull,
      PiemeramBlogAdminViews
    }
  }
</script>

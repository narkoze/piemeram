<template>
  <div>
    <piemeram-blog-admin-menu-mobile class="is-hidden-desktop"></piemeram-blog-admin-menu-mobile>

    <div class="columns is-gapless is-fullheight">
      <div class="column is-2 is-hidden-touch">
        <piemeram-blog-admin-menu-full></piemeram-blog-admin-menu-full>
      </div>

      <div class="column">
        <section class="section">
          <div class="container">
            <piemeram-blog-admin-view-dashboard v-if="$root.showView === 'admin-view-dashboard'"></piemeram-blog-admin-view-dashboard>
            <piemeram-blog-admin-view-users v-if="$root.showView === 'admin-view-users'"></piemeram-blog-admin-view-users>
            <piemeram-blog-admin-view-roles v-if="$root.showView === 'admin-view-roles'"></piemeram-blog-admin-view-roles>
            <piemeram-blog-admin-view-posts v-if="$root.showView === 'admin-view-posts'"></piemeram-blog-admin-view-posts>
            <piemeram-blog-admin-view-post v-if="$root.showView === 'admin-view-post'"></piemeram-blog-admin-view-post>
            <piemeram-blog-admin-view-categories v-if="$root.showView === 'admin-view-categories'"></piemeram-blog-admin-view-categories>
          </div>
        </section>
      </div>
    </div>
  </div>
</template>

<script>
  import piemeramBlogAdminViewCategories from './views/piemeram-blog-admin-view-categories.vue'
  import piemeramBlogAdminViewDashboard from './views/piemeram-blog-admin-view-dashboard.vue'
  import PiemeramBlogAdminMenuMobile from './piemeram-blog-admin-menu-mobile.vue'
  import piemeramBlogAdminViewUsers from './views/piemeram-blog-admin-view-users.vue'
  import piemeramBlogAdminViewRoles from './views/piemeram-blog-admin-view-roles.vue'
  import piemeramBlogAdminViewPosts from './views/piemeram-blog-admin-view-posts.vue'
  import piemeramBlogAdminViewPost from './views/piemeram-blog-admin-view-post.vue'
  import PiemeramBlogAdminMenuFull from './piemeram-blog-admin-menu-full.vue'

  export default {
    components: {
      piemeramBlogAdminViewCategories,
      piemeramBlogAdminViewDashboard,
      PiemeramBlogAdminMenuMobile,
      piemeramBlogAdminViewUsers,
      piemeramBlogAdminViewRoles,
      piemeramBlogAdminViewPosts,
      piemeramBlogAdminViewPost,
      PiemeramBlogAdminMenuFull
    }
  }
</script>

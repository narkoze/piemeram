<template>
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
</template>

<script>
  import piemeramBlogAdminViewCategories from './views/piemeram-blog-admin-view-categories.vue'
  import piemeramBlogAdminViewDashboard from './views/piemeram-blog-admin-view-dashboard.vue'
  import piemeramBlogAdminViewUsers from './views/piemeram-blog-admin-view-users.vue'
  import piemeramBlogAdminViewRoles from './views/piemeram-blog-admin-view-roles.vue'
  import piemeramBlogAdminViewPosts from './views/piemeram-blog-admin-view-posts.vue'
  import piemeramBlogAdminViewPost from './views/piemeram-blog-admin-view-post.vue'

  export default {
    components: {
      piemeramBlogAdminViewCategories,
      piemeramBlogAdminViewDashboard,
      piemeramBlogAdminViewUsers,
      piemeramBlogAdminViewRoles,
      piemeramBlogAdminViewPosts,
      piemeramBlogAdminViewPost
    }
  }
</script>

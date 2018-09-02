<template>
  <section class="section">
    <div class="container">
      <piemeram-blog-admin-view-posts v-if="$root.showView === 'admin-view-posts'"></piemeram-blog-admin-view-posts>
      <piemeram-blog-admin-view-post v-if="$root.showView === 'admin-view-post'"></piemeram-blog-admin-view-post>
      <piemeram-blog-admin-view-categories v-if="$root.showView === 'admin-view-categories'"></piemeram-blog-admin-view-categories>
    </div>
  </section>
</template>

<script>
  import piemeramBlogAdminViewCategories from './views/piemeram-blog-admin-view-categories.vue'
  import piemeramBlogAdminViewPosts from './views/piemeram-blog-admin-view-posts.vue'
  import piemeramBlogAdminViewPost from './views/piemeram-blog-admin-view-post.vue'

  export default {
    components: {
      piemeramBlogAdminViewCategories,
      piemeramBlogAdminViewPosts,
      piemeramBlogAdminViewPost
    }
  }
</script>

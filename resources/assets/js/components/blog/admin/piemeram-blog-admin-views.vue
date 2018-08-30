<template>
  <section class="section">
    <div class="container">
      <piemeram-blog-admin-view-posts
        v-if="showAdmin === 'admin-view-posts'"
        @editPost="editPost"
        @showPublicPost="showPublicPost"
      >
      </piemeram-blog-admin-view-posts>

      <piemeram-blog-admin-view-post
        v-if="['admin-view-post', 'admin-view-post-edit'].includes(showAdmin)"
        :edit-post="showAdmin === 'admin-view-post-edit' ? post : null"
        @deleted="postDeleted"
        @showPublicPost="showPublicPost"
      >
      </piemeram-blog-admin-view-post>
    </div>
  </section>
</template>

<script>
  import piemeramBlogAdminViewPosts from './views/piemeram-blog-admin-view-posts.vue'
  import piemeramBlogAdminViewPost from './views/piemeram-blog-admin-view-post.vue'

  export default {
    components: {
      piemeramBlogAdminViewPosts,
      piemeramBlogAdminViewPost
    },
    data: () => ({
      showAdmin: null,
      post: null
    }),
    created () {
      window.blogBus.$on('showAdmin', showAdmin => {
        this.showAdmin = showAdmin
      })
      window.blogBus.$on('editPublicPost', post => {
        this.editPost(post)
      })
    },
    methods: {
      showPublicPost (post) {
        window.blogBus.$emit('showPublicPost', post)
      },
      editPost (post) {
        this.post = post
        this.showAdmin = 'admin-view-post-edit'
      },
      postDeleted () {
        this.post = null
        window.blogBus.$emit('showAdmin', 'admin-view-posts')
      }
    }
  }
</script>

<template>
  <div class="public">
    <section class="hero is-dark is-medium is-bold">
      <div class="hero-body">
        <div class="container has-text-centered">
          <h1 class="title">
            {{ $t('blog.public.blog-public.title') }}
            <br>
            {{ $t('blog.public.blog-public.subtitle') }}
          </h1>
        </div>
      </div>
    </section>

    <div class="container">
      <piemeram-blog-public-view-posts
        v-if="showPublic === 'public-view-posts'"
        :authenticated="authenticated"
        @showPost="showPost"
        @editAdminPost="editAdminPost"
      >
      </piemeram-blog-public-view-posts>

      <piemeram-blog-public-view-post
        v-if="showPublic === 'public-view-post'"
        :post="post"
        :authenticated="authenticated"
        @editAdminPost="editAdminPost"
      >
      </piemeram-blog-public-view-post>
    </div>
  </div>
</template>

<script>
  import piemeramBlogPublicViewPosts from './views/piemeram-blog-public-view-posts.vue'
  import piemeramBlogPublicViewPost from './views/piemeram-blog-public-view-post.vue'

  export default {
    props: [
      'authenticated',
    ],
    components: {
      piemeramBlogPublicViewPosts,
      piemeramBlogPublicViewPost
    },
    data: () => ({
      showPublic: 'public-view-posts',
      post: null
    }),
    created () {
      window.blogBus.$on('showPublic', showPublic => {
        this.showPublic = showPublic
      })
      window.blogBus.$on('showPublicPost', post => {
        this.post = post
        this.showPublic = 'public-view-post'
      })
    },
    methods: {
      showPost (post) {
        this.post = post
        this.showPublic = 'public-view-post'
      },
      editAdminPost (post) {
        window.blogBus.$emit('editAdminPost', post)
      }
    }
  }
</script>


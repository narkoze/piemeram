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
      <piemeram-blog-public-view-posts v-if="$root.showView === 'public-view-posts'"></piemeram-blog-public-view-posts>
      <piemeram-blog-public-view-post v-if="$root.showView === 'public-view-post'"></piemeram-blog-public-view-post>
    </div>
  </div>
</template>

<script>
  import piemeramBlogPublicViewPosts from './views/piemeram-blog-public-view-posts.vue'
  import piemeramBlogPublicViewPost from './views/piemeram-blog-public-view-post.vue'

  export default {
    components: {
      piemeramBlogPublicViewPosts,
      piemeramBlogPublicViewPost
    }
  }
</script>


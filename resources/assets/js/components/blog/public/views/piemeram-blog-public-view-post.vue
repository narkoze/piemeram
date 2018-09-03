<template>
  <section class="articles">
    <div class="column is-8 is-offset-2">
      <div class="card article">
        <div class="card-content">
          <div class="has-text-right">
            <a
              @click="copy($root.post.id)"
              class="copy"
              :title="$t('blog.public.views.blog-public-view-posts.copy')"
            >
              <i class="fas fa-link"></i>
            </a>
          </div>

          <div class="media">
            <div class="media-content has-text-centered">
              <p class="title article-title">
                {{ $root.post.title }}
              </p>
              <p class="subtitle is-6 article-subtitle">
                <b>{{ $root.post.author.name }}</b>,
                <span
                  v-if="$root.post.published_at"
                  :title="$root.post.published_at"
                >
                  {{ $root.post.published_at | dateString }}
                </span>
                <span
                  v-else
                  :title="$root.post.updated_at"
                >
                  {{ $root.post.updated_at | dateString }}
                </span>
              </p>
            </div>
          </div>

          <div class="content article-body article-body-margin is-hidden-touch">
            <p>{{ $root.post.content }}</p>
          </div>

          <div class="content article-body is-hidden-desktop">
            <p>{{ $root.post.content }}</p>
          </div>


          <div class="article additional">
            <span
              v-for="(category, index) in $root.post.categories"
              :key="category.id"
              class="categories"
            >
              {{ category.name }}<span v-if="$root.post.categories.length - index !== 1">,</span>
            </span>
          </div>

          <div
            v-if="$root.auth"
            class="article actions"
          >
            <a
              @click="() => {
                $root.activeSection = 'admin-view-posts'
                $root.showView = 'admin-view-post'
              }"
              class="button is-info"
            >
              <span class="icon">
                <i class="fas fa-pencil-alt"></i>
              </span>
              <span>
                {{ $t('blog.public.views.blog-public-view-post.edit') }}
              </span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
  import mixins from './mixins'

  export default {
    mixins: [
      mixins,
    ]
  }
</script>

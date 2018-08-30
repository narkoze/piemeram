<template>
  <section class="articles">
    <div class="column is-8 is-offset-2">
      <div class="card article">
        <div class="card-content">
          <div class="media">
            <div class="media-content has-text-centered">
              <p class="title article-title">
                {{ post.title }}
              </p>
              <p class="subtitle is-6 article-subtitle">
                <b>{{ post.author.name }}</b>,
                <span
                  v-if="post.published_at"
                  :title="post.published_at"
                >
                  {{ post.published_at | dateString }}
                </span>
                <span
                  v-else
                  :title="post.updated_at"
                >
                  {{ post.updated_at | dateString }}
                </span>
              </p>
            </div>
          </div>

          <div class="content article-body article-body-margin is-hidden-touch">
            <p>{{ post.content }}</p>
          </div>

          <div class="content article-body is-hidden-desktop">
            <p>{{ post.content }}</p>
          </div>

          <a
            v-if="authenticated"
            @click="$emit('editAdminPost', post)"
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
  </section>
</template>

<script>
  export default {
    props: [
      'post',
      'authenticated',
    ]
  }
</script>

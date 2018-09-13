<style scoped>
  .fade-enter { opacity: 0; }
  .fade-enter-active { transition: opacity .7s; }
</style>

<template>
  <transition appear name="fade">
    <section class="articles">
      <div class="column is-8 is-offset-2">
        <div
          v-if="disabled"
          class="card article"
        >
          <div class="card-content">
            <div class="media">
              <div class="media-content has-text-centered">
                <p class="title article-title">
                  <a class="is-link-reversed">
                    <i class="fa fa-spinner fa-pulse"></i>
                  </a>
                </p>
              </div>
            </div>
          </div>
        </div>

        <div
          v-for="post in posts"
          :key="post.id"
          class="card article"
        >
          <a class="anchor" :id="`posts-${post.id}`"></a>

          <div class="card-content">
            <div class="has-text-right">
              <a
                @click="copy(post.id)"
                class="copy"
                :title="$t('blog.public.views.blog-public-view-posts.copy')"
              >
                <i class="fas fa-link"></i>
              </a>
            </div>

            <div class="media">
              <div class="media-content has-text-centered">
                <p class="title article-title">
                  <a
                    @click="() => {
                      $root.post = post
                      $root.showView = 'public-view-post'
                      $root.anchor = `post-${post.id}`
                    }"
                    class="is-link-reversed"
                  >
                    {{ post.title }}
                  </a>
                </p>
                <p class="subtitle is-6 article-subtitle">
                  <b>{{ post.author.name }}</b>,
                  <span :title="post.published_at">{{ post.published_at | dateString }}</span>
                </p>
              </div>
            </div>

            <div class="content article-body is-clearfix">
              <div v-html="pagebrake(post.content)"></div>
            </div>

            <div
              v-if="hasPagebreak(post.content)"
              class="pagebreak-link"
            >
              <a
                @click="$root.post = post; $root.showView = 'public-view-post'"
                class="is-link-reversed"
              >
                <i class="fas fa-arrow-right"></i> {{ $t('blog.public.views.blog-public-view-posts.pagebreak') }}
              </a>
            </div>

            <div class="article additional">
              <span
                v-for="(category, index) in post.categories"
                :key="category.id"
                class="categories"
              >
                {{ category.name }}<span v-if="post.categories.length - index !== 1">,</span>
              </span>
              <a
                @click="() => {
                  $root.post = post
                  $root.showView = 'public-view-post'
                  $root.anchor = 'comments'
                }"
                :class="['commentcount', { rightcommentcount: post.categories.length }]"
              >
                {{ $t('blog.public.views.blog-public-view-posts.comments', { count: post.comments_count }) }}
              </a>
            </div>

            <div
              v-if="$root.auth"
              class="article actions"
            >
              <a
                @click="() => {
                  $root.activeSection = 'admin-view-posts'
                  $root.showView = 'admin-view-post'
                  $root.post = post
                }"
                class="button is-info"
              >
                <span class="icon">
                  <i class="fas fa-pencil-alt"></i>
                </span>
                <span>
                  {{ $t('blog.public.views.blog-public-view-posts.edit') }}
                </span>
              </a>
            </div>
          </div>
        </div>
        <i
          @click="scrolltop"
          ref="scrolltop"
          class="fas fa-arrow-alt-circle-up scrolltop"
        >
        </i>
      </div>
    </section>
  </transition>
</template>

<script>
  import AxiosErrorHandler from '../../../mixins/AxiosErrorHandler'
  import mixins from './mixins'
  import axios from 'axios'

  export default {
    mixins: [
      AxiosErrorHandler,
      mixins,
    ],
    data: () => ({
      posts: {}
    }),
    created () {
      this.loadPosts()
    },
    mounted () {
      window.onscroll = () => {
        if (document.body.scrollTop > 352 || document.documentElement.scrollTop > 352) {
          this.$refs.scrolltop.style.display = 'block'
        } else {
          this.$refs.scrolltop.style.display = 'none'
        }
      }
    },
    methods: {
      loadPosts () {
        this.disabled = true

        axios
          .get('blog/api/post')
          .then(response => {
            this.disabled = false

            this.posts = response.data

            if (this.$root.anchor) {
              this.$nextTick(() => {
                document.getElementById(this.$root.anchor).scrollIntoView()
              })
            }
          })
          .catch(this.handleAxiosError)
      },
      hasPagebreak: (postContent) => postContent.includes('<!-- pagebreak -->'),
      pagebrake: (postContent) => postContent.split('<!-- pagebreak -->')[0],
      scrolltop () {
        document.querySelector('.articles').scrollIntoView({ behavior: 'smooth', block: 'start' })
      }
    }
  }
</script>

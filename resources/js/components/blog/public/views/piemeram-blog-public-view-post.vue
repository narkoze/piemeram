<style scoped>
  .fade-enter { opacity: 0; }
  .fade-enter-active { transition: opacity 1s ease; }
</style>

<template>
  <transition appear name="fade">
    <section class="articles">
      <a class="anchor" :id="`post-${$root.post.id}`"></a>

      <div class="column is-8 is-offset-2">
        <div class="card article">
          <div class="card-content">
            <div>
              <a
                @click="() => {
                  $root.showView = 'public-view-posts'
                  $root.anchor = `posts-${$root.post.id}`
                }"
                class="is-link-reversed"
              >
                <i class="fas fa-arrow-left"></i>
              </a>
              <a
                @click="copy($root.post.id)"
                class="copy right"
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

            <div class="content article-body is-clearfix">
              <div v-html="$root.post.content"></div>
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

        <a class="anchor" id="comments"></a>
        <div class="card comments">
          <div class="card-content">
            <div class="media">
              <div class="media-content">
                <p class="title comment-title">
                  {{ $t('blog.public.views.blog-public-view-post.comments') }}
                </p>
              </div>
            </div>

            <div class="content comment-body">
              <div v-if="$root.auth && $root.auth.email_verified_at">
                <label>
                  {{ $t('blog.public.views.blog-public-view-post.commentas', { name: $root.auth.name }) }}
                </label>

                <div class="field">
                  <label>
                    <textarea
                      v-model="comment.comment"
                      :class="['textarea', { 'is-danger': errors.comment }]"
                      ref="comment"
                      rows="3"
                      :disabled="disabled"
                    >
                    </textarea>
                    <p v-if="errors.comment" class="help is-danger">{{ errors.comment.join() }}</p>
                  </label>
                </div>

                <div class="field">
                  <a
                    v-if="comment.id"
                    @click="editComment"
                    :class="['button', 'is-info', { 'is-loading': disabled }]"
                    :disabled="disabled"
                  >
                    {{ $t('blog.public.views.blog-public-view-post.edit') }}
                  </a>

                  <a
                    v-if="comment.id"
                    @click="cancelEditComment"
                    class="button"
                    :disabled="disabled"
                  >
                    {{ $t('blog.public.views.blog-public-view-post.canceledit') }}
                  </a>

                  <a
                    v-else
                    @click="saveComment"
                    :class="['button', 'is-info', { 'is-loading': disabled }]"
                    :disabled="disabled"
                  >
                    {{ $t('blog.public.views.blog-public-view-post.comment') }}
                  </a>
                </div>
              </div>

              <p>
                <i v-if="!$root.auth">{{ $t('blog.public.views.blog-public-view-post.logintocomment') }}</i>

                <a
                  v-if="$root.auth && !$root.auth.email_verified_at"
                  href="email/verify"
                >
                  <i>{{ $t('blog.public.views.blog-public-view-post.verifytocomment') }}</i>
                </a>

              </p>

              <br>

              <article
                v-for="comment in comments"
                :key="comment.id"
                @mouseover="mouseover = comment.id"
                @mouseout="mouseover = null"
                class="media"
              >
                <div class="media-content">
                  <div class="content">
                    <p>
                      <strong>{{ comment.author.name }}</strong>
                      <small>{{ comment.updated_at }}</small>
                      <small v-if="comment.is_edited">
                        <i>{{ $t('blog.public.views.blog-public-view-post.modified') }}</i>
                      </small>

                      <span
                        v-if="mouseover === comment.id && ($root.auth && $root.auth.id === comment.author.id)"
                        class="right"
                      >
                        <a
                          v-if="!disabled"
                          @click="setComment(comment)"
                        >
                          <small>{{ $t('blog.public.views.blog-public-view-post.edit') }}</small>
                        </a>
                        <span class="link-divider">|</span>
                        <a
                          v-if="!disabled"
                          @click="destroyComment(comment)"
                        >
                          <small>{{ $t('blog.public.views.blog-public-view-post.delete') }}</small>
                        </a>
                      </span>
                      <br>
                      {{ comment.comment }}
                    </p>
                  </div>
                </div>
              </article>
            </div>
          </div>
        </div>
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
      comments: [],
      commentsLoading: false,
      comment: {},
      commentComment: null,
      mouseover: null
    }),
    created () {
      this.loadComments()
    },
    mounted () {
      if (this.$root.anchor) document.getElementById(this.$root.anchor).scrollIntoView()
    },
    methods: {
      focusCommment () {
        this.$refs.comment.focus()
      },
      saveComment () {
        this.disabled = true
        this.errors = {}

        axios
          .post(`blog/api/post/${this.$root.post.id}/comment`, this.comment)
          .then(() => {
            this.disabled = false

            this.loadComments()
          })
          .catch(this.handleAxiosError)
      },
      loadComments () {
        this.commentsLoading = true

        axios
          .get(`blog/api/post/${this.$root.post.id}/comment`)
          .then(response => {
            this.commentsLoading = false
            this.comment = {}
            this.comments = response.data
          })
          .catch(error => {
            this.commentsLoading = false
            this.handleAxiosError(error)
          })
      },
      setComment (comment) {
        this.comment.comment = this.commentComment
        this.commentComment = comment.comment
        this.comment = comment
        this.focusCommment()
      },
      editComment () {
        this.disabled = true

        axios
          .put(`blog/api/post/${this.$root.post.id}/comment/${this.comment.id}`, this.comment)
          .then(response => {
            this.disabled = false
            this.comment = {}
            this.loadComments()

            this.$nextTick(() => {
              this.focusCommment()
            })
          })
          .catch(this.handleAxiosError)
      },
      cancelEditComment () {
        this.comment.comment = this.commentComment
        this.comment = {}
        this.focusCommment()
      },
      destroyComment (comment) {
        this.disabled = true

        if (!confirm(window.i18n.t('blog.public.views.blog-public-view-post.confirm', { comment: comment.comment }))) {
          this.disabled = false
          return
        }

        axios
          .delete(`blog/api/post/${this.$root.post.id}/comment/${comment.id}`)
          .then(() => {
            this.disabled = false
            this.loadComments()

            this.$nextTick(() => {
              this.focusCommment()
            })
          })
          .catch(this.handleAxiosError)
      }
    }
  }
</script>

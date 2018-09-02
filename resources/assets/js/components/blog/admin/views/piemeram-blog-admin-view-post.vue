<template>
  <div>
    <h1
      v-if="post.id"
      class="title"
    >
      {{ $t('blog.admin.views.blog-admin-view-post.title.edit') }}
    </h1>
    <h1
      v-else
      class="title"
    >
      {{ $t('blog.admin.views.blog-admin-view-post.title.new') }}
    </h1>

    <div class="columns">
      <div class="column">
        <div class="field">
          <input
            v-model="post.title"
            type="text"
            ref="title"
            :class="['input', 'is-medium', { 'is-danger': errors.title }]"
            :disabled="disabled"
            :placeholder="$t('blog.admin.views.blog-admin-view-post.posttitle')"
          >
          <p v-if="errors.title" class="help is-danger">{{ errors.title.join() }}</p>
        </div>

        <div class="field">
          <textarea
            v-model="post.content"
            :class="['textarea', { 'is-danger': errors.content }]"
            :disabled="disabled"
            rows="15"
          >
          </textarea>
          <p v-if="errors.content" class="help is-danger">{{ errors.content.join() }}</p>
        </div>

        <div>
          <a
            @click="publish()"
            :class="['button', 'is-info', { 'is-loading': publishing }]"
            :disabled="disabled"
          >
            {{ $t('blog.admin.views.blog-admin-view-post.' + (post.published_at ? 'update' : 'publish')) }}
          </a>

          <a
            @click="publish(true)"
            :class="['button', { 'is-loading': saving }]"
            :disabled="disabled"
          >
            {{ $t('blog.admin.views.blog-admin-view-post.save') }}
          </a>

          <a
            v-if="post.id"
            @click="$root.showView = 'public-view-post'; $root.post = post"
            class="button"
            :disabled="disabled"
          >
            <span v-if="post.published_at">{{ $t('blog.admin.views.blog-admin-view-post.view') }}</span>
            <span v-else>{{ $t('blog.admin.views.blog-admin-view-post.preview') }}</span>
          </a>

          <a
            v-if="post.id"
            @click="destroy"
            :class="['button', 'is-danger', 'right', { 'is-loading': deleting }]"
            :disabled="disabled"
          >
            {{ $t('blog.admin.views.blog-admin-view-post.delete') }}
          </a>
        </div>
      </div>

      <div class="column is-4">
        <div
          v-if="post.id"
          class="card card-margin"
        >
          <header class="card-header">
            <p class="card-header-title">
              {{ $t('blog.admin.views.blog-admin-view-post.infocard.title') }}
            </p>
          </header>
          <div class="card-content">
            <div class="content">
              <div class="columns is-gapless is-mobile is-marginless">
                <div class="column">{{ $t('blog.admin.views.blog-admin-view-post.infocard.published_at') }}</div>
                <div
                  v-if="post.published_at"
                  class="column"
                >
                  <span
                    class="hover"
                    :title="post.published_at"
                  >
                    {{ post.published_at | dateString }}
                  </span>
                </div>
                <div
                  v-else
                  class="column"
                >
                  -
                </div>
              </div>

              <div class="columns is-gapless is-mobile is-marginless">
                <div class="column">{{ $t('blog.admin.views.blog-admin-view-post.infocard.updated_at') }}</div>
                <div class="column">
                  <span
                    class="hover"
                    :title="post.updated_at"
                  >
                    {{ post.updated_at | dateString }}
                  </span>
                </div>
              </div>

              <div class="columns is-gapless is-mobile is-marginless">
                <div class="column">{{ $t('blog.admin.views.blog-admin-view-post.infocard.author') }}</div>
                <div class="column">{{ post.author.name }}</div>
              </div>
            </div>
          </div>
        </div>

        <div class="card card-margin">
          <header class="card-header">
            <p class="card-header-title">
              {{ $t('blog.admin.views.blog-admin-view-post.categories.title') }}
              &nbsp;
              <i
                v-if="categoriesLoading"
                class="fas fa-spinner fa-spin"
              >
              </i>
            </p>
          </header>
          <div class="card-content card-content-nopadding">
            <div class="content">
              <div class="select is-multiple select-is-fullwidth">

                <select
                  v-model="selectedCategories"
                  class="select-is-fullwidth"
                  :size="categories.length > 8 ? 8 : categories.length"
                  :disabled="disabled"
                  multiple
                >
                  <option
                    v-for="category in categories"
                    :key="category.id"
                    :value="category.id"
                  >
                    {{ category.name }}
                  </option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import AxiosErrorHandler from '../../../mixins/AxiosErrorHandler'
  import axios from '../../../../axios'

  export default {
    mixins: [
      AxiosErrorHandler,
    ],
    data: () => ({
      post: {},
      publishing: false,
      updating: false,
      saving: false,
      deleting: false,
      categories: [],
      selectedCategories: [],
      categoriesLoading: false
    }),
    created () {
      this.post = this.$root.post || {
        title: null,
        content: null,
        categories: []
      }

      this.loadCategories()
    },
    mounted () {
      this.$refs.title.focus()
    },
    methods: {
      publish (draft = false) {
        this.disabled = true
        draft ? this.saving = true : this.publishing = true
        this.errors = {}

        let method = this.post.id ? 'put' : 'post'
        let route = this.post.id
          ? `blog/api/admin/post/${this.post.id}`
          : 'blog/api/admin/post'

        axios[method](route, {
          ...this.post,
          ...{draft},
          categories: this.selectedCategories
        })
          .then(response => {
            this.$root.activeSection = 'admin-view-posts'

            let notify = 'updated'
            if (this.saving) notify = 'saved'
            if (this.publishing && !this.post.published_at) notify = 'published'

            window.notify(this.$t(`blog.admin.views.blog-admin-view-post.${notify}`, { title: response.data.title }), 'is-primary')

            this.saving = false
            this.publishing = false
            this.disabled = false

            this.post = response.data
          })
          .catch(error => {
            this.saving = false
            this.publishing = false
            this.handleAxiosError(error)
          })
      },
      destroy () {
        this.disabled = this.deleting = true

        if (!confirm(window.i18n.t('blog.admin.views.blog-admin-view-post.confirm', { title: this.post.title }))) {
          this.disabled = this.deleting = false
          return
        }

        axios
          .delete(`blog/api/admin/post/${this.post.id}`)
          .then(() => {
            this.$root.showView = 'admin-view-posts'

            window.notify(this.$t('blog.admin.views.blog-admin-view-post.deleted'), 'is-primary')
          })
          .catch(this.handleAxiosError)
      },
      loadCategories () {
        this.categoriesLoading = true

        axios
          .get('blog/api/admin/category')
          .then(response => {
            this.categoriesLoading = false
            this.categories = response.data
            this.selectedCategories = this.post.categories.map(({ id }) => id)
          })
          .catch(error => {
            this.categoriesLoading = false
            this.handleAxiosError(error)
          })
      }
    }
  }
</script>

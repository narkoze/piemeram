<template>
  <div>
    <h1 class="title">{{ $t('blog.admin.views.blog-admin-view-categories.title') }}</h1>

    <div class="field">
      <input
        v-model="category.name"
        type="text"
        ref="name"
        :class="['input', 'is-medium', { 'is-danger': errors.name }]"
        :placeholder="$t('blog.admin.views.blog-admin-view-categories.name')"
        :disabled="disabled"
      >
      <p v-if="errors.name" class="help is-danger">{{ errors.name.join() }}</p>
    </div>

    <div class="field">
      <a
        v-if="category.id"
        @click="edit"
        :class="['button', 'is-info', { 'is-loading': disabled }]"
        :disabled="disabled"
      >
        {{ $t('blog.admin.views.blog-admin-view-categories.edit') }}
      </a>

      <a
        v-else
        @click="add"
        :class="['button', 'is-info', { 'is-loading': disabled }]"
        :disabled="disabled"
      >
        {{ $t('blog.admin.views.blog-admin-view-categories.add') }}
      </a>
    </div>

    <table class="table is-striped is-narrow is-hoverable is-fullwidth">
      <thead>
        <tr>
          <th>{{ $t('blog.admin.views.blog-admin-view-categories.category') }}</th>
          <th>{{ $t('blog.admin.views.blog-admin-view-categories.postcount') }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="categoriesLoading">
          <td
            class="has-text-centered is-size-4"
            colspan="3"
          >
            <i class="fas fa-spinner fa-pulse"></i>
          </td>
        </tr>

        <tr
          v-else
          v-for="category in categories"
          :key="category.id"
          @mouseover="mouseover = category.id"
          @mouseout="mouseover = null"
        >
          <td>
            <a @click="setCategory(category)">
              <b>{{ category.name }}</b>
            </a>

            <div v-if="mouseover === category.id">
              <a @click="setCategory(category)">
                <small>{{ $t('blog.admin.views.blog-admin-view-categories.edit') }}</small>
              </a>
              <span class="link-divider">|</span>
              <a @click="destroyCategory(category)">
                <small>{{ $t('blog.admin.views.blog-admin-view-categories.delete') }}</small>
              </a>
            </div>
            <div v-else>&nbsp;</div>
          </td>
          <td>
            {{ category.published_posts_count }} / {{ category.draft_posts_count }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
  import AxiosErrorHandler from '../../../mixins/AxiosErrorHandler'
  import axios from 'axios'

  export default {
    mixins: [
      AxiosErrorHandler,
    ],
    data: () => ({
      category: {},
      categories: [],
      categoriesLoading: false,
      mouseover: null,
      deleting: false
    }),
    created () {
      this.loadCategories()
    },
    mounted () {
      this.focus()
    },
    methods: {
      focus () {
        this.$refs.name.focus()
      },
      add () {
        this.disabled = true

        axios
          .post('blog/api/admin/category', this.category)
          .then(response => {
            this.disabled = false
            this.category = {}
            this.loadCategories()

            window.notify(this.$t('blog.admin.views.blog-admin-view-categories.added', { category: response.data.name }), 'is-primary')
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
          })
          .catch(error => {
            this.categoriesLoading = false
            this.handleAxiosError(error)
          })
      },
      setCategory (category) {
        this.category = category
        this.focus()
      },
      edit () {
        this.disabled = true

        axios
          .put(`blog/api/admin/category/${this.category.id}`, this.category)
          .then(response => {
            this.disabled = false
            this.category = {}
            this.loadCategories()

            window.notify(this.$t('blog.admin.views.blog-admin-view-categories.edited', { category: response.data.name }), 'is-primary')

            this.$nextTick(() => {
              this.focus()
            })
          })
          .catch(this.handleAxiosError)
      },
      destroyCategory (category) {
        this.deleting = true

        if (!confirm(window.i18n.t('blog.admin.views.blog-admin-view-categories.confirm', { name: category.name }))) {
          this.deleting = false
          return
        }

        this.categoriesLoading = true

        axios
          .delete(`blog/api/admin/category/${category.id}`)
          .then(() => {
            this.deleting = false
            this.categoriesLoading = false
            this.category = {}
            this.loadCategories()

            window.notify(this.$t('blog.admin.views.blog-admin-view-categories.deleted'), 'is-primary')

            this.$nextTick(() => {
              this.focus()
            })
          })
          .catch(error => {
            this.deleting = false
            this.categoriesLoading = false
            this.handleAxiosError(error)
          })
      }
    }
  }
</script>

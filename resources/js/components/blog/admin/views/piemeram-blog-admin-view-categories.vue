<template>
  <div>
    <h1 class="title">
      {{ $t('blog.admin.views.blog-admin-view-categories.title') }}
      <i  v-if="categoriesLoading && !sorting && !pageChanging" class="fas fa-spinner fa-pulse"></i>
    </h1>

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
        v-if="category.id"
        @click="cancel"
        class="button"
        :disabled="disabled"
      >
        {{ $t('blog.admin.views.blog-admin-view-categories.canceledit') }}
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

    <div class="scrollable">
      <table class="table is-striped is-narrow is-hoverable is-fullwidth">
        <thead>
          <tr>
            <piemeram-blog-shared-sort
              column="name"
              :sort="params.sortBy"
              :direction="params.sortDirection"
              :disabled="sorting"
              @changed="sort"
            >
              {{ $t('blog.admin.views.blog-admin-view-categories.category') }}
            </piemeram-blog-shared-sort>
            <piemeram-blog-shared-sort
              column="total"
              :sort="params.sortBy"
              :direction="params.sortDirection"
              :disabled="sorting"
              @changed="sort"
              class="has-text-right"
            >
              {{ $t('blog.admin.views.blog-admin-view-categories.postcount') }}
            </piemeram-blog-shared-sort>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="category in categories.data"
            :key="category.id"
            @mouseover="mouseover = category.id"
            @mouseout="mouseover = null"
          >
            <td>
              <a @click="(disabled || deleting) || setCategory(category)">
                <b>{{ category.name }}</b>
              </a>

              <div v-if="mouseover === category.id">
                <a @click="(disabled || deleting) || setCategory(category)">
                  <small>{{ $t('blog.admin.views.blog-admin-view-categories.edit') }}</small>
                </a>
                <span class="link-divider">|</span>
                <a @click="(disabled || deleting) || destroyCategory(category)">
                  <small>{{ $t('blog.admin.views.blog-admin-view-categories.delete') }}</small>
                </a>
              </div>
              <div v-else>&nbsp;</div>
            </td>
            <td class="has-text-right">
              {{ category.published_posts_count }} / {{ category.draft_posts_count }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <br>
    <piemeram-blog-shared-paginate
      :paginator="categories"
      :changing="pageChanging"
      @changed="setPage"
    >
    </piemeram-blog-shared-paginate>
  </div>
</template>

<script>
  import PiemeramBlogSharedPaginate from '../../shared/piemeram-blog-shared-paginate.vue'
  import PiemeramBlogSharedSort from '../../shared/piemeram-blog-shared-sort.vue'
  import AxiosErrorHandler from '../../../mixins/AxiosErrorHandler'
  import SortHandler from '../../../mixins/SortHandler'
  import axios from 'axios'

  export default {
    components: {
      PiemeramBlogSharedPaginate,
      PiemeramBlogSharedSort
    },
    mixins: [
      AxiosErrorHandler,
      SortHandler,
    ],
    data: () => ({
      category: {},
      categoryName: null,
      categories: [],
      categoriesLoading: false,
      mouseover: null,
      deleting: false,
      pageChanging: false
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
      sort (by) {
        this.sorting = true
        this.sortHandler(by)
        this.loadCategories()
      },
      setPage (page) {
        this.pageChanging = true
        this.loadCategories(page)
      },
      add () {
        this.disabled = true
        this.errors = {}

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
      loadCategories (page = 1) {
        this.categoriesLoading = true
        this.params.page = page

        axios
          .get('blog/api/admin/category', { params: this.params })
          .then(response => {
            this.categoriesLoading = false
            this.pageChanging = false
            this.sorting = false

            this.categories = response.data.categories
            this.params = response.data.params
          })
          .catch(error => {
            this.categoriesLoading = false
            this.pageChanging = false
            this.sorting = false

            this.handleAxiosError(error)
          })
      },
      setCategory (category) {
        this.category.name = this.categoryName
        this.categoryName = category.name
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
      },
      cancel () {
        this.category.name = this.categoryName
        this.category = {}
        this.focus()
      }
    }
  }
</script>

<template>
  <div>
    <h1 class="title">
      {{ $t('blog.admin.views.blog-admin-view-categories.title') }}
      <i
        v-if="categoriesLoading"
        class="fas fa-spinner fa-pulse"
      >
      </i>
    </h1>

    <div class="columns">
      <div class="column is-3">
        <div class="sticky is-marginless">
          <div class="field">
            <label>
              {{ category.id ? $t('blog.admin.views.blog-admin-view-categories.editcategory') : $t('blog.admin.views.blog-admin-view-categories.newcategory') }}
              <input
                v-model="category.name"
                type="text"
                ref="name"
                :class="['input', { 'is-danger': errors.name }]"
                :placeholder="$t('blog.admin.views.blog-admin-view-categories.name')"
                :disabled="disabled"
              >
            </label>
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
        </div>
      </div>

      <div class="column">
        <div class="columns is-marginless-bottom">
          <div class="column">
            <div class="field">
              <label>{{ $t('blog.admin.views.blog-admin-view-categories.search') }}
                <input
                  :value="params.search"
                  @input="search"
                  type="text"
                  class="input"
                >
              </label>
            </div>
          </div>
          <div class="column is-2">
            <label>&nbsp;
              <div class="field is-clearfix">
                <piemeram-blog-shared-excel
                  url="blogv1/api/admin/category/excel"
                  :params="params"
                  class="is-pulled-right"
                >
                </piemeram-blog-shared-excel>
              </div>
            </label>
          </div>
        </div>

        <div class="is-scrollable">
          <table class="table is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
              <tr>
                <piemeram-blog-shared-th
                  column="name"
                  :sort="params.sortBy"
                  :direction="params.sortDirection"
                  :disabled="sorting"
                  @changed="sort"
                >
                  {{ $t('blog.admin.views.blog-admin-view-categories.category') }}
                </piemeram-blog-shared-th>
                <piemeram-blog-shared-th
                  column="total"
                  :sort="params.sortBy"
                  :direction="params.sortDirection"
                  :disabled="sorting"
                  @changed="sort"
                  class="has-text-right"
                >
                  {{ $t('blog.admin.views.blog-admin-view-categories.postsdrafts') }}
                </piemeram-blog-shared-th>
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
                    <b v-html="$options.filters.highlight(category.name, params.search)"></b>
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
    </div>
  </div>
</template>

<script>
  import PiemeramBlogSharedPaginate from '../../shared/piemeram-blog-shared-paginate.vue'
  import PiemeramBlogSharedExcel from '../../shared/piemeram-blog-shared-excel.vue'
  import PiemeramBlogSharedTh from '../../shared/piemeram-blog-shared-th.vue'
  import AxiosErrorHandler from '../../../mixins/AxiosErrorHandler'
  import SortHandler from '../../../mixins/SortHandler'
  import debounce from 'lodash/debounce'
  import axios from 'axios'

  export default {
    components: {
      PiemeramBlogSharedPaginate,
      PiemeramBlogSharedExcel,
      PiemeramBlogSharedTh
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
          .post('blogv1/api/admin/category', this.category)
          .then(response => {
            this.disabled = false
            this.category = {}
            this.loadCategories()

            window.notify(this.$t('blog.admin.views.blog-admin-view-categories.added', { category: response.data.name }), 'is-primary')
          })
          .catch(this.handleAxiosError)
      },
      search: debounce(function (e) {
        this.params.search = e.target.value
        this.loadCategories()
      }, 500),
      loadCategories (page = 1) {
        this.categoriesLoading = true
        this.params.page = page

        axios
          .get('blogv1/api/admin/category', { params: this.params })
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
          .put(`blogv1/api/admin/category/${this.category.id}`, this.category)
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
          .delete(`blogv1/api/admin/category/${category.id}`)
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

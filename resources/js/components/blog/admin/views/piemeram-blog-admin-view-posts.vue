<template>
  <div>
    <h1 class="title">
      {{ $t('blog.admin.views.blog-admin-view-posts.title') }}
      <i
        v-if="disabled"
        class="fas fa-spinner fa-pulse"
      >
      </i>
    </h1>

    <div class="columns">
      <div class="column is-3">
        <div class="sticky is-marginless">
          <piemeram-blog-shared-datepicker
            :label="$t('blog.admin.views.blog-admin-view-posts.datefrom')"
            :value="params.from"
            @selected="date => params.from = date"
          >
          </piemeram-blog-shared-datepicker>

          <piemeram-blog-shared-datepicker
            :label="$t('blog.admin.views.blog-admin-view-posts.dateto')"
            :value="params.to"
            @selected="date => params.to = date"
          >
          </piemeram-blog-shared-datepicker>

          <div class="field">
            <label>{{ $t('blog.admin.views.blog-admin-view-posts.status') }}
              <div
                ref="dropdown"
                :class="['dropdown', { 'is-active': dropdownStatusIsActive }]"
              >
                <div class="dropdown-trigger">
                  <button
                    @click="dropdownStatusIsActive = !dropdownStatusIsActive"
                    class="button"
                  >
                    <span
                      v-if="$te(`blog.admin.views.blog-admin-view-posts.${params.status}`) && !dropdownStatusIsActive"
                    >
                      {{ $t(`blog.admin.views.blog-admin-view-posts.${params.status}`) }}
                    </span>
                    <span class="icon">
                      <i class="fas fa-angle-down fa-lg"></i>
                    </span>
                  </button>
                </div>
                <div class="dropdown-menu">
                  <div class="dropdown-content">
                    <a
                      v-for="status in [
                        'published',
                        'draft',
                      ]"
                      :key="status"
                      :class="['dropdown-item', { 'is-active': params.status === status }]"
                      @click="params.status === status ? params.status = null : params.status = status"
                    >
                      {{ $te(`blog.admin.views.blog-admin-view-posts.${status}`) ? $t(`blog.admin.views.blog-admin-view-posts.${status}`) : '' }}
                    </a>
                  </div>
                </div>
              </div>
            </label>
          </div>

          <piemeram-blog-shared-select-user
            :label="$t('blog.admin.views.blog-admin-view-posts.author')"
            :selected="selectedAuthor"
            @selected="author => {
              selectedAuthor = author
              params.authorId = author.id
            }"
          >
          </piemeram-blog-shared-select-user>

          <piemeram-blog-shared-categories
            only="posts"
            :selected="params.categories"
            @selected="categories => params.categories = categories"
          >
          </piemeram-blog-shared-categories>

          <a
            @click="loadPosts"
            class="button is-info"
          >
            {{ $t('blog.admin.views.blog-admin-view-posts.filter') }}
          </a>

          <a
            @click="removeFilters"
            class="button"
          >
            {{ $t('blog.admin.views.blog-admin-view-posts.removefilters') }}
          </a>
        </div>
      </div>

      <div class="column">
        <div class="columns is-marginless-bottom">
          <div class="column">
            <div class="field">
              <label>{{ $t('blog.admin.views.blog-admin-view-posts.search') }}
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
                  url="blog/api/admin/post/excel"
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
                  column="title"
                  :sort="params.sortBy"
                  :direction="params.sortDirection"
                  :disabled="sorting"
                  @changed="sort"
                >
                  {{ $t('blog.admin.views.blog-admin-view-posts.posttitle') }}
                </piemeram-blog-shared-th>
                <piemeram-blog-shared-th
                  column="categories_abc"
                  :sort="params.sortBy"
                  :direction="params.sortDirection"
                  :filtered="filteredByCategories"
                  :disabled="sorting"
                  @changed="sort"
                >
                  {{ $t('blog.admin.views.blog-admin-view-posts.categories') }}
                </piemeram-blog-shared-th>
                <piemeram-blog-shared-th
                  column="authors.name"
                  :sort="params.sortBy"
                  :direction="params.sortDirection"
                  :filtered="filteredByAuthor"
                  :disabled="sorting"
                  @changed="sort"
                >
                  {{ $t('blog.admin.views.blog-admin-view-posts.author') }}
                </piemeram-blog-shared-th>
                <piemeram-blog-shared-th
                  column="dates"
                  :sort="params.sortBy"
                  :direction="params.sortDirection"
                  :filtered="filteredByDate"
                  :disabled="sorting"
                  @changed="sort"
                >
                  {{ $t('blog.admin.views.blog-admin-view-posts.date') }}
                </piemeram-blog-shared-th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="post in posts.data"
                :key="post.id"
                @mouseover="mouseover = post.id"
                @mouseout="mouseover = null"
              >
                <td>
                  <a @click="() => {
                    $root.activeSection = 'admin-view-posts'
                    $root.showView = 'admin-view-post'
                    $root.post = post
                  }">
                    <b v-html="$options.filters.highlight(post.title, params.search)"></b>
                  </a>

                  <span v-if="!post.published_at">
                    - {{ $t('blog.admin.views.blog-admin-view-posts.draft') }}
                  </span>

                  <div v-show="mouseover === post.id">
                    <a @click="() => {
                      $root.activeSection = 'admin-view-posts'
                      $root.showView = 'admin-view-post'
                      $root.post = post
                    }">
                      <small>{{ $t('blog.admin.views.blog-admin-view-posts.edit') }}</small>
                    </a>
                    <span class="link-divider">|</span>
                    <a @click="() => {
                      $root.showView = 'public-view-post'
                      $root.post = post
                    }">
                      <small v-if="post.published_at">{{ $t('blog.admin.views.blog-admin-view-posts.view') }}</small>
                      <small v-else>{{ $t('blog.admin.views.blog-admin-view-posts.preview') }}</small>
                    </a>
                  </div>
                </td>

                <td>
                  <span
                    v-for="(category, index) in post.categories"
                    :key="category.id"
                  >
                    <a
                      @click="() => {
                        params.categories = [category.id]
                        loadPosts()
                      }"
                      class="displayicon"
                    >
                      {{ category.name }}</a><i class="fa fa-filter fa-xs"></i><span v-if="post.categories.length - index !== 1">,</span>
                  </span>
                </td>

                <td>
                  <a
                    @click="() => {
                      selectedAuthor = post.author
                      params.authorId = post.author.id
                      loadPosts()
                    }"
                    class="hover visibleicon"
                    :title="post.author.email_masked"
                  >
                    {{ post.author.name }}
                  </a>
                  <i class="fas fa-filter fa-xs"></i>
                </td>

                <td>
                  <div v-if="post.published_at">
                    {{ $t('blog.admin.views.blog-admin-view-posts.published') }}
                    <br>
                    <a
                      @click="() => {
                        params.from = params.to = post.published_at
                        loadPosts()
                      }"
                      class="hover visibleicon"
                      :title="post.published_at"
                    >
                      {{ post.published_at | dateString }}
                    </a>
                    <i class="fas fa-filter fa-xs"></i>
                  </div>
                  <div v-else>
                    {{ $t('blog.admin.views.blog-admin-view-posts.saved') }}
                    <br>
                    <a
                      @click="() => {
                        params.from = params.to = post.updated_at
                        loadPosts()
                      }"
                      class="hover visibleicon"
                      :title="post.updated_at"
                    >
                      {{ post.updated_at | dateString }}
                    </a>
                    <i class="fas fa-filter fa-xs"></i>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <br>
        <piemeram-blog-shared-paginate
          :paginator="posts"
          :changing="pageChanging"
          @changed="setPage"
        >
        </piemeram-blog-shared-paginate>
      </div>
    </div>
  </div>
</template>

<script>
  import PiemeramBlogSharedDatepicker from '../../shared/piemeram-blog-shared-datepicker.vue'
  import PiemeramBlogSharedSelectUser from '../../shared/piemeram-blog-shared-select-user.vue'
  import PiemeramBlogSharedCategories from '../../shared/piemeram-blog-shared-categories.vue'
  import PiemeramBlogSharedPaginate from '../../shared/piemeram-blog-shared-paginate.vue'
  import PiemeramBlogSharedExcel from '../../shared/piemeram-blog-shared-excel.vue'
  import PiemeramBlogSharedTh from '../../shared/piemeram-blog-shared-th.vue'
  import AxiosErrorHandler from '../../../mixins/AxiosErrorHandler'
  import SortHandler from '../../../mixins/SortHandler'
  import debounce from 'lodash/debounce'
  import axios from 'axios'

  export default {
    components: {
      PiemeramBlogSharedDatepicker,
      PiemeramBlogSharedSelectUser,
      PiemeramBlogSharedCategories,
      PiemeramBlogSharedPaginate,
      PiemeramBlogSharedExcel,
      PiemeramBlogSharedTh
    },
    mixins: [
      AxiosErrorHandler,
      SortHandler,
    ],
    data: () => ({
      posts: [],
      mouseover: null,
      selectedAuthor: {},
      pageChanging: false,
      filteredByDate: false,
      filteredByAuthor: false,
      filteredByCategories: false,
      dropdownStatusIsActive: false
    }),
    created () {
      this.loadPosts()

      window.addEventListener('click', this.closeDropDowns)
    },
    methods: {
      closeDropDowns (e) {
        if (!this.$refs.dropdown.contains(e.target)) {
          this.dropdownStatusIsActive = false
        }
      },
      sort (by) {
        this.sorting = true

        this.sortHandler(by)
        this.loadPosts()
      },
      setPage (page) {
        this.pageChanging = true
        this.loadPosts(page)
      },
      removeFilters () {
        this.params.categories = []

        this.params.authorId = null
        this.selectedAuthor = {}

        this.params.from = null
        this.params.to = null

        this.params.status = null

        this.loadPosts()
      },
      search: debounce(function (e) {
        this.params.search = e.target.value
        this.loadPosts()
      }, 500),
      loadPosts (page = 1) {
        this.disabled = true
        this.params.page = page

        axios
          .get('blog/api/admin/post', { params: this.params })
          .then(response => {
            this.pageChanging = false
            this.disabled = false
            this.sorting = false

            this.posts = response.data.posts
            this.params = response.data.params

            this.filteredByCategories = this.params.categories.length
            this.filteredByAuthor = this.params.authorId
            this.filteredByDate = this.params.from || this.params.to
          })
          .catch(error => {
            this.pageChanging = false
            this.sorting = false

            this.handleAxiosError(error)
          })
      }
    },
    beforeDestroy () {
      window.removeEventListener('click', this.closeDropDowns)
    }
  }
</script>

<template>
  <div>
    <h1 class="title">
      {{ $t('blog.admin.views.blog-admin-view-posts.title') }}
      <i  v-if="disabled && !sorting && !pageChanging" class="fas fa-spinner fa-pulse"></i>
    </h1>

    <div class="columns">
      <div class="column">
        <div class="scrollable ">
          <table class="table is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
              <tr>
                <piemeram-blog-shared-sort
                  column="title"
                  :sort="params.sortBy"
                  :direction="params.sortDirection"
                  :disabled="sorting"
                  @changed="sort"
                >
                  {{ $t('blog.admin.views.blog-admin-view-posts.posttitle') }}
                </piemeram-blog-shared-sort>
                <piemeram-blog-shared-sort
                  column="categories"
                  :sort="params.sortBy"
                  :direction="params.sortDirection"
                  :disabled="sorting"
                  @changed="sort"
                >
                  {{ $t('blog.admin.views.blog-admin-view-posts.categories') }}
                </piemeram-blog-shared-sort>
                <piemeram-blog-shared-sort
                  column="authors.name"
                  :sort="params.sortBy"
                  :direction="params.sortDirection"
                  :disabled="sorting"
                  @changed="sort"
                >
                  {{ $t('blog.admin.views.blog-admin-view-posts.author') }}
                </piemeram-blog-shared-sort>
                <piemeram-blog-shared-sort
                  column="dates"
                  :sort="params.sortBy"
                  :direction="params.sortDirection"
                  :disabled="sorting"
                  @changed="sort"
                >
                  {{ $t('blog.admin.views.blog-admin-view-posts.date') }}
                </piemeram-blog-shared-sort>
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
                    <b>{{ post.title }}</b>
                  </a>

                  <span v-if="!post.published_at">
                    - {{ $t('blog.admin.views.blog-admin-view-posts.draft') }}
                  </span>

                  <div v-if="mouseover === post.id">
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
                    <a @click="loadCategory = [category.id]">
                      {{ category.name }}</a><span v-if="post.categories.length - index !== 1">,</span>
                  </span>
                </td>

                <td>{{ post.author.name }}</td>

                <td>
                  <div v-if="post.published_at">
                    {{ $t('blog.admin.views.blog-admin-view-posts.published') }}
                    <br>
                    <span
                      class="hover"
                      :title="post.published_at"
                    >
                      {{ post.published_at | dateString }}
                    </span>
                  </div>
                  <div v-else>
                    {{ $t('blog.admin.views.blog-admin-view-posts.saved') }}
                    <br>
                    <span
                      class="hover"
                      :title="post.updated_at"
                    >
                      {{ post.updated_at | dateString }}
                    </span>
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

      <div class="column is-4">
        <div class="sticky is-marginless">
          <piemeram-blog-shared-categories
            class="is-marginless"
            only="posts"
            :postCategories="loadCategory"
            :filtering="disabled && !sorting && !pageChanging"
            @selectedCategories="(categories) => { selectedCategories = categories }"
            @filter="filterPosts"
          >
          </piemeram-blog-shared-categories>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import PiemeramBlogSharedCategories from '../../shared/piemeram-blog-shared-categories.vue'
  import PiemeramBlogSharedPaginate from '../../shared/piemeram-blog-shared-paginate.vue'
  import PiemeramBlogSharedSort from '../../shared/piemeram-blog-shared-sort.vue'
  import AxiosErrorHandler from '../../../mixins/AxiosErrorHandler'
  import SortHandler from '../../../mixins/SortHandler'
  import axios from 'axios'

  export default {
    components: {
      PiemeramBlogSharedCategories,
      PiemeramBlogSharedPaginate,
      PiemeramBlogSharedSort
    },
    mixins: [
      AxiosErrorHandler,
      SortHandler,
    ],
    data: () => ({
      posts: [],
      mouseover: null,
      selectedCategories: [],
      loadCategory: null,
      pageChanging: false
    }),
    created () {
      this.loadPosts()
    },
    methods: {
      sort (by) {
        this.sorting = true

        this.sortHandler(by)
        this.loadPosts()
      },
      setPage (page) {
        this.pageChanging = true
        this.loadPosts(page)
      },
      filterPosts () {
        this.params['categories'] = this.selectedCategories
        this.loadPosts()
      },
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
          })
          .catch(error => {
            this.pageChanging = false
            this.sorting = false

            this.handleAxiosError(error)
          })
      }
    }
  }
</script>

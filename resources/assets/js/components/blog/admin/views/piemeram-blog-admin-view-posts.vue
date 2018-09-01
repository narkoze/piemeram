<template>
  <div>
    <h1 class="title">{{ $t('blog.admin.views.blog-admin-view-posts.title') }}</h1>

    <table class="table is-striped is-narrow is-hoverable is-fullwidth">
      <thead>
        <tr>
          <th>{{ $t('blog.admin.views.blog-admin-view-posts.posttitle') }}</th>
          <th>{{ $t('blog.admin.views.blog-admin-view-posts.categories') }}</th>
          <th>{{ $t('blog.admin.views.blog-admin-view-posts.author') }}</th>
          <th>{{ $t('blog.admin.views.blog-admin-view-posts.date') }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="disabled">
          <td
            class="has-text-centered is-size-4"
            colspan="3"
          >
            <i class="fas fa-spinner fa-spin"></i>
          </td>
        </tr>
        <tr
          v-for="post in posts"
          :key="post.id"
          @mouseover="mouseover = post.id"
          @mouseout="mouseover = null"
        >
          <td>
            <a @click="$emit('editPost', post)">
              <b>{{ post.title }}</b>
            </a>

            <div v-if="mouseover === post.id">
              <a @click="$emit('editPost', post)">
                <small>{{ $t('blog.admin.views.blog-admin-view-posts.edit') }}</small>
              </a>
              <span class="link-divider">|</span>
              <a @click="$emit('showPublicPost', post)">
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
              {{ category.name }}<span v-if="post.categories.length - index !== 1">,</span>
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
</template>

<script>
  import AxiosErrorHandler from '../../../mixins/AxiosErrorHandler'
  import axios from '../../../../axios'

  export default {
    mixins: [
      AxiosErrorHandler,
    ],
    data: () => ({
      posts: [],
      mouseover: null
    }),
    created () {
      this.loadPosts()
    },
    methods: {
      loadPosts () {
        this.disabled = true

        axios
          .get('blog/api/admin/post')
          .then(response => {
            this.disabled = false

            this.posts = response.data
          })
          .catch(this.handleAxiosError)
      }
    }
  }
</script>

<template>
  <aside class="menu is-fullheight">
    <p class="menu-label">
      {{ $t('blog.admin.blog-admin-menu.title') }}
    </p>
    <ul class="menu-list">
      <li>
        <a
          @click="posts"
          :class="{ 'is-active': showAdmin === 'admin-view-posts' }"
        >
          {{ $t('blog.admin.blog-admin-menu.posts') }}
        </a>
      </li>
      <li>
        <a
          @click="post"
          :class="{ 'is-active': showAdmin === 'admin-view-post' }"
        >
          <i class="fas fa-plus"></i>
          {{ $t('blog.admin.blog-admin-menu.post') }}
        </a>
      </li>
    </ul>
  </aside>
</template>

<script>
  import Methods from './blog-admin-menu'

  export default {
    mixins: [
      Methods,
    ],
    created () {
      window.blogBus.$on('showAdmin', showAdmin => {
        this.showAdmin = showAdmin
      })
    }
  }
</script>

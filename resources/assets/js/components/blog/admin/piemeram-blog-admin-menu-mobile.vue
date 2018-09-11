<template>
  <div :class="['dropdown', 'blog-dropdown', { 'is-active': isAdminDropdownOpen }]">
    <div class="blog-dropdown-trigger">
      <a
        @click="isAdminDropdownOpen = !isAdminDropdownOpen"
        class="button"
      >
        <span class="icon is-small">
          <i v-if="isAdminDropdownOpen" class="fas fa-times"></i>
          <i v-else class="fas fa-bars"></i>
        </span>
        <span>
          {{ $t('blog.admin.blog-admin-menu.title') }}
        </span>
      </a>
    </div>

    <div class="dropdown-menu blog-dropdown-menu">
      <a
        @click="dashboard(); isAdminDropdownOpen = false"
        class="dropdown-item"
      >
        {{ $t('blog.admin.blog-admin-menu.dashboard') }}
      </a>

      <a
        @click="posts(); isAdminDropdownOpen = false"
        class="dropdown-item"
      >
        {{ $t('blog.admin.blog-admin-menu.posts') }}
      </a>

      <a
        @click="post(); isAdminDropdownOpen = false"
        class="dropdown-item"
      >
        <i class="fas fa-plus"></i>
        {{ $t('blog.admin.blog-admin-menu.post') }}
      </a>

      <a
        @click="categories(); isAdminDropdownOpen = false"
        class="dropdown-item"
      >
        {{ $t('blog.admin.blog-admin-menu.categories') }}
      </a>
    </div>
  </div>
</template>

<script>
  import mixins from './mixins'

  export default {
    mixins: [
      mixins,
    ],
    data: () => ({
      isAdminDropdownOpen: false
    })
  }
</script>

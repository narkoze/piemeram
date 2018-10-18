<template>
  <div class="submenu">
    <a
      @click="dashboard"
      :class="{ 'is-active': $root.activeSection === 'admin-view-dashboard' }"
    >
      {{ $t('blog.admin.blog-admin-menu.dashboard') }}
    </a>
    <a
      @click="users"
      :class="{ 'is-active': $root.activeSection === 'admin-view-users' }"
    >
      {{ $t('blog.admin.blog-admin-menu.users') }}
    </a>
    <a
      @click="roles"
      :class="{ 'is-active': $root.activeSection === 'admin-view-roles' }"
    >
      {{ $t('blog.admin.blog-admin-menu.roles') }}
    </a>
    <a
      @click="posts"
      :class="{ 'is-active': $root.activeSection === 'admin-view-posts' }"
    >
      {{ $t('blog.admin.blog-admin-menu.posts') }}
    </a>
    <a
      @click="post"
      :class="{ 'is-active': $root.activeSection === 'admin-view-post' }"
    >
      <i class="fas fa-plus"></i>
      {{ $t('blog.admin.blog-admin-menu.post') }}
    </a>
    <a
      @click="categories"
      :class="{ 'is-active': $root.activeSection === 'admin-view-categories' }"
    >
      {{ $t('blog.admin.blog-admin-menu.categories') }}
    </a>
    <a
      @click="images"
      :class="{ 'is-active': $root.activeSection === 'admin-view-images' }"
    >
      {{ $t('blog.admin.blog-admin-menu.images') }}
    </a>
  </div>
</template>

<script>
  import mixins from './mixins'

  export default {
    mixins: [
      mixins,
    ]
  }
</script>

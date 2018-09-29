<template>
  <aside class="menu is-fullheight">
    <ul class="menu-list">
      <li>
        <a
          @click="dashboard"
          :class="{ 'is-active': $root.activeSection === 'admin-view-dashboard' }"
        >
          {{ $t('blog.admin.blog-admin-menu.dashboard') }}
        </a>
      </li>
    </ul>

    <p class="menu-label">
      {{ $t('blog.admin.blog-admin-menu.usertitle') }}
    </p>
    <ul class="menu-list">
      <li>
        <a
          @click="users"
          :class="{ 'is-active': $root.activeSection === 'admin-view-users' }"
        >
          {{ $t('blog.admin.blog-admin-menu.users') }}
        </a>
      </li>
      <li>
        <a
          @click="roles"
          :class="{ 'is-active': $root.activeSection === 'admin-view-roles' }"
        >
          {{ $t('blog.admin.blog-admin-menu.roles') }}
        </a>
      </li>
    </ul>

    <p class="menu-label">
      {{ $t('blog.admin.blog-admin-menu.posttitle') }}
    </p>
    <ul class="menu-list">
      <li>
        <a
          @click="posts"
          :class="{ 'is-active': $root.activeSection === 'admin-view-posts' }"
        >
          {{ $t('blog.admin.blog-admin-menu.posts') }}
        </a>
      </li>

      <li>
        <a
          @click="post"
          :class="{ 'is-active': $root.activeSection === 'admin-view-post' }"
        >
          <i class="fas fa-plus"></i>
          {{ $t('blog.admin.blog-admin-menu.post') }}
        </a>
      </li>

      <li>
        <a
          @click="categories"
          :class="{ 'is-active': $root.activeSection === 'admin-view-categories' }"
        >
          {{ $t('blog.admin.blog-admin-menu.categories') }}
        </a>
      </li>
    </ul>
  </aside>
</template>

<script>
  import mixins from './mixins'

  export default {
    mixins: [
      mixins,
    ]
  }
</script>

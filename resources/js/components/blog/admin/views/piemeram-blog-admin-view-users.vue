<template>
  <div>
    <h1 class="title">{{ $t('blog.admin.views.blog-admin-view-users.title') }}</h1>

    <div class="scrollable">
      <table class="table is-striped is-narrow is-hoverable is-fullwidth">
        <thead>
          <tr>
            <th>{{ $t('blog.admin.views.blog-admin-view-users.name') }}</th>
            <th>{{ $t('blog.admin.views.blog-admin-view-users.email') }}</th>
            <th>{{ $t('blog.admin.views.blog-admin-view-users.role') }}</th>
            <th class="has-text-right">{{ $t('blog.admin.views.blog-admin-view-users.posts') }}</th>
            <th class="has-text-right">{{ $t('blog.admin.views.blog-admin-view-users.comments') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="usersLoading">
            <td
              class="has-text-centered is-size-4"
              colspan="5"
            >
              <i class="fas fa-spinner fa-pulse"></i>
            </td>
          </tr>

          <tr
            v-for="user in users"
            :key="user.id"
          >
            <td>{{ user.name }}</td>
            <td> {{ user.email }}</td>
            <td>
              <div v-if="rolesLoading">
                <i class="fas fa-spinner fa-pulse"></i>
              </div>

              <div
                v-else
                class="select is-small"
              >
                <select
                  v-model="user.blog_role_id"
                  :disabled="user.blog_role_changing"
                >
                  <option :value="null">{{ $t('blog.admin.views.blog-admin-view-users.user') }}</option>
                  <option
                    v-for="role in roles"
                    :key="role.id"
                    :value="role.id"
                  >
                    {{ role.name }}
                  </option>
                </select>
              </div>
              <a
                v-if="user.blog_role_id !== user.blog_role.id"
                @click="updateUser(user)"
                :class="['button is-info is-small', { 'is-loading': user.blog_role_changing }]"
              >
                {{ $t('blog.admin.views.blog-admin-view-users.save') }}
              </a>
            </td>
            <td class="has-text-right">
              {{ user.blog_posts_count || '' }}
            </td>
            <td class="has-text-right">
              {{ user.blog_comments_count || '' }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
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
      users: [],
      roles: [],
      usersLoading: false,
      rolesLoading: false
    }),
    created () {
      this.loadUsers()
      this.loadRoles()
    },
    methods: {
      loadUsers () {
        this.usersLoading = true

        axios
          .get('blog/api/admin/user')
          .then(response => {
            this.usersLoading = false

            this.users = response.data

            this.users.map(user => {
              user.blog_role = user.blog_role || { id: null }
              this.$set(user, 'blog_role_changing', false)
            })
          })
          .catch(this.handleAxiosError)
      },
      loadRoles () {
        this.rolesLoading = true

        axios
          .get('blog/api/admin/role')
          .then(response => {
            this.rolesLoading = false

            this.roles = response.data
          })
          .catch(error => {
            this.rolesLoading = false
            this.handleAxiosError(error)
          })
      },
      updateUser (user) {
        user.blog_role_changing = true

        axios
          .put(`blog/api/admin/user/${user.id}`, {
            blogRole: user.blog_role_id
          })
          .then(response => {
            user.blog_role_changing = false

            response.data.blog_role = response.data.blog_role || { id: null }

            Object.assign(user, response.data)

            window.notify(this.$t('blog.admin.views.blog-admin-view-users.rolechanged'), 'is-primary')
          })
          .catch(error => {
            user.blog_role_changing = false
            this.handleAxiosError(error)
          })
      }
    }
  }
</script>

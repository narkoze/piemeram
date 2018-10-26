<template>
  <div>
    <h1 class="title">
      {{ $t('blog.admin.views.blog-admin-view-users.title') }}
      <i
        v-if="usersLoading"
        class="fas fa-spinner fa-pulse"
      >
      </i>
    </h1>

    <div class="is-overflow-hidden">
      <piemeram-blog-shared-excel
        url="blogv1/api/admin/user/excel"
        :params="params"
        class="is-pulled-right"
      >
      </piemeram-blog-shared-excel>
    </div>

    <div class="is-scrollable">
      <table class="table is-striped is-narrow is-hoverable is-fullwidth">
        <thead>
          <tr>
            <piemeram-blog-shared-th
              column="name"
              :sort="params.sortBy"
              :direction="params.sortDirection"
              :disabled="usersLoading"
              @changed="sort"
            >
              {{ $t('blog.admin.views.blog-admin-view-users.name') }}
            </piemeram-blog-shared-th>
            <piemeram-blog-shared-th
              column="email"
              :sort="params.sortBy"
              :direction="params.sortDirection"
              :disabled="usersLoading"
              @changed="sort"
            >
              {{ $t('blog.admin.views.blog-admin-view-users.email') }}
            </piemeram-blog-shared-th>
            <piemeram-blog-shared-th
              column="blog_roles.name"
              :sort="params.sortBy"
              :direction="params.sortDirection"
              :disabled="sorting"
              @changed="sort"
            >
              {{ $t('blog.admin.views.blog-admin-view-users.role') }}
            </piemeram-blog-shared-th>
            <piemeram-blog-shared-th
              column="blog_posts_count"
              :sort="params.sortBy"
              :direction="params.sortDirection"
              :disabled="sorting"
              @changed="sort"
              class="has-text-right"
            >
              {{ $t('blog.admin.views.blog-admin-view-users.posts') }}
            </piemeram-blog-shared-th>
            <piemeram-blog-shared-th
              column="blog_comments_count"
              :sort="params.sortBy"
              :direction="params.sortDirection"
              :disabled="sorting"
              @changed="sort"
              class="has-text-right"
            >
              {{ $t('blog.admin.views.blog-admin-view-users.comments') }}
            </piemeram-blog-shared-th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="user in users.data"
            :key="user.id"
          >
            <td>{{ user.name }}</td>
            <td>{{ user.email_masked }}</td>
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
    <br>
    <piemeram-blog-shared-paginate
      :paginator="users"
      :changing="pageChanging"
      @changed="setPage"
    >
    </piemeram-blog-shared-paginate>
  </div>
</template>

<script>
  import PiemeramBlogSharedPaginate from '../../shared/piemeram-blog-shared-paginate.vue'
  import PiemeramBlogSharedExcel from '../../shared/piemeram-blog-shared-excel.vue'
  import PiemeramBlogSharedTh from '../../shared/piemeram-blog-shared-th.vue'
  import AxiosErrorHandler from '../../../mixins/AxiosErrorHandler'
  import SortHandler from '../../../mixins/SortHandler'
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
      users: [],
      roles: [],
      usersLoading: false,
      rolesLoading: false,
      pageChanging: false
    }),
    created () {
      this.loadUsers()
      this.loadRoles()
    },
    methods: {
      sort (by) {
        this.sorting = true

        this.sortHandler(by)
        this.loadUsers()
      },
      setPage (page) {
        this.pageChanging = true
        this.loadUsers(page)
      },
      loadUsers (page = 1) {
        this.usersLoading = true
        this.params.page = page

        axios
          .get('blogv1/api/admin/user', { params: this.params })
          .then(response => {
            this.usersLoading = false
            this.pageChanging = false
            this.sorting = false

            this.users = response.data.users
            this.params = response.data.params

            this.users.data.map(user => {
              user.blog_role = user.blog_role || { id: null }
              this.$set(user, 'blog_role_changing', false)
            })
          })
          .catch(error => {
            this.usersLoading = false
            this.pageChanging = false
            this.sorting = false

            this.handleAxiosError(error)
          })
      },
      loadRoles () {
        this.rolesLoading = true

        axios
          .get('blogv1/api/admin/role', { params: {
            all: true
          }})
          .then(response => {
            this.rolesLoading = false

            this.roles = response.data.roles
          })
          .catch(error => {
            this.rolesLoading = false
            this.handleAxiosError(error)
          })
      },
      updateUser (user) {
        user.blog_role_changing = true

        axios
          .put(`blogv1/api/admin/user/${user.id}`, {
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

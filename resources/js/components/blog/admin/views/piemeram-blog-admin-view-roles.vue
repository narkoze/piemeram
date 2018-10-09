<template>
  <div>
    <h1 class="title">
      {{ $t('blog.admin.views.blog-admin-view-roles.title') }}
      <i v-if="rolesLoading && !sorting && !pageChanging" class="fas fa-spinner fa-pulse"></i>
    </h1>

    <div class="field">
      <input
        v-model="role.name"
        type="text"
        ref="name"
        :class="['input', 'is-medium', { 'is-danger': errors.name }]"
        :placeholder="$t('blog.admin.views.blog-admin-view-roles.name')"
        :disabled="disabled"
      >
      <p v-if="errors.name" class="help is-danger">{{ errors.name.join() }}</p>
    </div>

    <div class="field">
      <textarea
        v-model="role.description"
        :class="['textarea', { 'is-danger': errors.description }]"
        :placeholder="$t('blog.admin.views.blog-admin-view-roles.description')"
        :disabled="disabled"
        rows="2"
      >
      </textarea>
      <p v-if="errors.description" class="help is-danger">{{ errors.description.join() }}</p>
    </div>

    <div class="field">
      <a
        v-if="role.id"
        @click="edit"
        :class="['button', 'is-info', { 'is-loading': disabled }]"
        :disabled="disabled"
      >
        {{ $t('blog.admin.views.blog-admin-view-roles.edit') }}
      </a>

      <a
        v-if="role.id"
        @click="cancel"
        class="button"
        :disabled="disabled"
      >
        {{ $t('blog.admin.views.blog-admin-view-roles.canceledit') }}
      </a>

      <a
        v-else
        @click="add"
        :class="['button', 'is-info', { 'is-loading': disabled }]"
        :disabled="disabled"
      >
        {{ $t('blog.admin.views.blog-admin-view-roles.add') }}
      </a>
    </div>

    <div class="is-overflow-hidden">
      <piemeram-blog-shared-excel
        url="blog/api/admin/role/excel"
        :params="params"
        class="is-pulled-right"
      >
      </piemeram-blog-shared-excel>
    </div>

    <div class="scrollable">
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
              {{ $t('blog.admin.views.blog-admin-view-roles.role') }}
            </piemeram-blog-shared-th>
            <piemeram-blog-shared-th
              column="description"
              :sort="params.sortBy"
              :direction="params.sortDirection"
              :disabled="sorting"
              @changed="sort"
            >
              {{ $t('blog.admin.views.blog-admin-view-roles.description') }}
            </piemeram-blog-shared-th>
            <piemeram-blog-shared-th
              column="users_count"
              :sort="params.sortBy"
              :direction="params.sortDirection"
              :disabled="sorting"
              @changed="sort"
              class="has-text-right"
            >
              {{ $t('blog.admin.views.blog-admin-view-roles.usercount') }}
            </piemeram-blog-shared-th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="role in roles.data"
            :key="role.name"
            @mouseover="mouseover = role.id"
            @mouseout="mouseover = null"
          >
            <td>
              <a @click="(disabled || deleting) || setRole(role)">
                <b>{{ role.name }}</b>
              </a>

              <div v-if="mouseover === role.id">
                <a @click="(disabled || deleting) || setRole(role)">
                  <small>{{ $t('blog.admin.views.blog-admin-view-roles.edit') }}</small>
                </a>
                <span class="link-divider">|</span>
                <a @click="(disabled || deleting) || destroyRole(role)">
                  <small>{{ $t('blog.admin.views.blog-admin-view-roles.delete') }}</small>
                </a>
              </div>
              <div v-else>&nbsp;</div>
            </td>
            <td>{{ role.description }}</td>
            <td class="has-text-right">{{ role.users_count || '' }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <br>
    <piemeram-blog-shared-paginate
      :paginator="roles"
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
      roles: [],
      rolesLoading: false,
      role: {},
      roleCopy: {},
      mouseover: null,
      deleting: false,
      pageChanging: false
    }),
    created () {
      this.loadRoles()
    },
    mounted () {
      this.focus()
    },
    methods: {
      sort (by) {
        this.sorting = true
        this.sortHandler(by)
        this.loadRoles()
      },
      setPage (page) {
        this.pageChanging = true
        this.loadRoles(page)
      },
      loadRoles (page = 1) {
        this.rolesLoading = true
        this.params.page = page

        axios
          .get('blog/api/admin/role', { params: this.params })
          .then(response => {
            this.rolesLoading = false
            this.pageChanging = false
            this.sorting = false

            this.roles = response.data.roles
            this.params = response.data.params
          })
          .catch(error => {
            this.rolesLoading = false
            this.pageChanging = false
            this.sorting = false

            this.handleAxiosError(error)
          })
      },
      focus () {
        this.$refs.name.focus()
      },
      add () {
        this.disabled = true
        this.errors = {}

        axios
          .post('blog/api/admin/role', this.role)
          .then(response => {
            this.disabled = false
            this.role = {}
            this.loadRoles()

            window.notify(this.$t('blog.admin.views.blog-admin-view-roles.added', { role: response.data.name }), 'is-primary')
          })
          .catch(this.handleAxiosError)
      },
      setRole (role) {
        this.role.name = this.roleCopy.name
        this.role.description = this.roleCopy.description

        this.roleCopy.name = role.name
        this.roleCopy.description = role.description

        this.role = role
        this.focus()
      },
      edit () {
        this.disabled = true

        axios
          .put(`blog/api/admin/role/${this.role.id}`, this.role)
          .then(response => {
            this.disabled = false
            this.role = {}
            this.loadRoles()

            window.notify(this.$t('blog.admin.views.blog-admin-view-roles.edited', { role: response.data.name }), 'is-primary')

            this.$nextTick(() => {
              this.focus()
            })
          })
          .catch(this.handleAxiosError)
      },
      destroyRole (role) {
        this.deleting = true

        if (!confirm(window.i18n.t('blog.admin.views.blog-admin-view-roles.confirm', { name: role.name }))) {
          this.deleting = false
          return
        }

        this.rolesLoading = true

        axios
          .delete(`blog/api/admin/role/${role.id}`)
          .then(() => {
            this.deleting = false
            this.role = {}
            this.loadRoles()

            window.notify(this.$t('blog.admin.views.blog-admin-view-roles.deleted'), 'is-primary')

            this.$nextTick(() => {
              this.focus()
            })
          })
          .catch(error => {
            this.deleting = false
            this.loadRoles()

            this.handleAxiosError(error)
          })
      },
      cancel () {
        this.role.name = this.roleCopy.name
        this.role.description = this.roleCopy.description
        this.role = {}
        this.focus()
      }
    }
  }
</script>

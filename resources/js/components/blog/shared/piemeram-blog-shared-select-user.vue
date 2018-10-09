<template>
  <div class="field">
    <label>{{ label }}</label>
    <multiselect
      :options="users"
      :value="selected"
      :custom-label="customLabel"
      :local-search="false"
      :show-labels="false"
      :show-no-options="false"
      track-by="id"
      placeholder=""
      class="is-multiselect"
      @search-change="search"
      @open="search"
      @input="user => $emit('selected', user || {})"
    >
      <span slot="noResult">
        {{ $t('blog.shared.blog-shared-select-user.noresult') }}
      </span>

      <span
        slot="caret"
        class="arrow"
      >
        <i
          v-if="disabled"
          class="fas fa-spinner fa-pulse"
        >
        </i>
        <i
          v-else
          class="fas fa-angle-down fa-lg"
        >
        </i>
      </span>
    </multiselect>
  </div>
</template>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<script>
  import AxiosErrorHandler from '../../mixins/AxiosErrorHandler'
  import Multiselect from 'vue-multiselect'
  import debounce from 'lodash/debounce'
  import axios from 'axios'

  export default {
    mixins: [
      AxiosErrorHandler,
    ],
    components: {
      Multiselect
    },
    props: {
      selected: Object,
      label: String
    },
    data: () => ({
      users: []
    }),
    methods: {
      search: debounce(function (search) {
        this.disabled = true
        let params = {
          all: true,
          limit: 5
        }
        if (search) params.search = search

        axios
          .get('blog/api/admin/user', { params })
          .then(response => {
            this.disabled = false
            this.users = response.data.users
          })
          .catch(this.handleAxiosError)
      }, 500),
      customLabel (user) {
        if (user.hasOwnProperty('name') && user.hasOwnProperty('email_masked')) {
          return `${user.name} (${user.email_masked})`
        }
        return ''
      }
    }
  }
</script>

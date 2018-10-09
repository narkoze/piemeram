<template>
  <div class="card field card-categories">
    <label>
        {{ $t('blog.admin.views.blog-admin-view-post.categories.title') }}
        <i
          v-if="disabled"
          class="fas fa-spinner fa-pulse"
        >
        </i>

      <div class="card-content-categories is-paddingless">
      <div class="content">
        <div class="select is-multiple select-is-fullwidth">
          <select
            v-model="selectedCategories"
            class="select-is-fullwidth"
            :size="categories.length"
            :disabled="disabled"
            multiple
          >
            <option
              v-for="category in categories"
              :key="category.id"
              :value="category.id"
            >
              {{ category.name }}
            </option>
          </select>
        </div>
      </div>
    </div>
    </label>
  </div>
</template>

<script>
  import AxiosErrorHandler from '../../mixins/AxiosErrorHandler'
  import axios from 'axios'

  export default {
    mixins: [
      AxiosErrorHandler,
    ],
    props: {
      postCategories: Array,
      selected: {
        type: Array,
        default: () => []
      },
      only: String
    },
    data: () => ({
      categories: [],
      selectedCategories: []
    }),
    created () {
      this.loadCategories()
    },
    watch: {
      selected () {
        this.selectedCategories = this.selected
      },
      selectedCategories () {
        this.$emit('selected', this.selectedCategories)
      }
    },
    methods: {
      loadCategories () {
        this.disabled = true

        let params = {}
        if (this.only) params['only'] = this.only

        axios
          .get('blog/api/category', { params })
          .then(response => {
            this.disabled = false
            this.categories = response.data

            if (this.$root.categories.length && this.$root.show.side === 'public') {
              this.$nextTick(() => {
                this.selectedCategories = this.$root.categories
              })
            }

            if (this.postCategories) {
              this.selectedCategories = this.postCategories.map(({ id }) => id)
            }
          })
          .catch(this.handleAxiosError)
      }
    }
  }
</script>

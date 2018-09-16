<template>
  <div class="card card-margin">
    <header class="card-header">
      <p class="card-header-title">
        {{ $t('blog.admin.views.blog-admin-view-post.categories.title') }}
        &nbsp;
        <i
          v-if="disabled || filtering"
          class="fas fa-spinner fa-pulse"
        >
        </i>
      </p>
    </header>

    <div class="card-content card-content-nopadding">
      <div class="content">
        <div class="select is-multiple select-is-fullwidth">
          <select
            v-model="selectedCategories"
            class="select-is-fullwidth"
            :size="categories.length"
            :disabled="disabled || filtering"
            multiple
          >
            <option
              v-for="category in categories"
              :key="category.id"
              :value="category.id"
              :checked="selectedCategories.includes(category.id)"
            >
              {{ category.name }}
            </option>
          </select>
        </div>
      </div>
    </div>

    <footer
      v-if="actions"
      class="card-footer"
    >
      <a
        @click="() => {
          filtered = true
          $emit('filter')
          $root.categories = selectedCategories
        }"
        :class="['button is-info', { 'is-loading': disabled || filtering }]"
        :disabled="disabled || filtering || (!selectedCategories.length && !filtered)"
      >
        {{ $t('blog.shared.blog-shared-categories.filter') }}
      </a>

      <a
        v-if="filtered"
        @click="() => {
          filtered = false
          selectedCategories = []
          $root.categories = []
          $root.anchor = null
          $emit('selectedCategories', selectedCategories)
          $emit('filter')
        }"
        :class="['button', { 'is-loading': disabled || filtering }]"
        :disabled="disabled || filtering"
      >
        {{ $t('blog.shared.blog-shared-categories.removefilter') }}
      </a>
    </footer>
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
      postCategories: {
        type: Array,
        default: () => []
      },
      filtering: {
        type: Boolean,
        default: false
      },
      actions: {
        type: Boolean,
        default: true
      },
      only: {
        type: String,
        default: ''
      }
    },
    data: () => ({
      categories: [],
      selectedCategories: [],
      filtered: false
    }),
    created () {
      this.loadCategories()
    },
    watch: {
      postCategories () {
        if (this.postCategories.length) {
          this.selectedCategories = this.postCategories
          this.$emit('selectedCategories', this.postCategories)
          this.$emit('filter')
          this.filtered = true
        }
      },
      selectedCategories () {
        this.$emit('selectedCategories', this.selectedCategories)
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

            if (this.$root.categories.length) {
              this.$nextTick(() => {
                this.selectedCategories = this.$root.categories
                this.filtered = true
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

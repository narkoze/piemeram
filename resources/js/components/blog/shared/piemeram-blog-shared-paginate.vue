<template>
  <nav class="pagination is-centered">
    <a
      @click="previous"
      :class="['pagination-previous button', { 'is-light is-loading': changingPrevious }]"
      :disabled="!paginator.prev_page_url || changing"
    >
      <i class="fas fa-angle-double-left"></i>
    </a>

    <ul class="pagination-list">
      <li
        v-for="page in pages"
        :key="page"
      >
        <span
          v-if="!page"
          class="pagination-ellipsis"
        >
          &hellip;
        </span>

        <a
          v-else
          @click="paginator.current_page === page || set(page)"
          :class="['pagination-link button', {
            'is-light is-loading': !changingPrevious && settingPage === page && !changingNext,
            'is-info': paginator.current_page === page && !changing
          }]"
          :disabled="changing"
        >
          {{ page }}
        </a>
      </li>
    </ul>

    <a
      @click="next"
      :class="['pagination-next button', { 'is-light is-loading': changingNext }]"
      :disabled="!paginator.next_page_url || changing"
    >
      <i class="fas fa-angle-double-right"></i>
    </a>
  </nav>
</template>

<script>
  export default {
    props: [
      'paginator',
      'changing',
    ],
    data: () => ({
      changingPrevious: false,
      changingNext: false,
      settingPage: 0
    }),
    watch: {
      changing (bool) {
        if (bool) return

        this.changingPrevious = false
        this.changingNext = false
        this.settingPage = 0
      }
    },
    computed: {
      pages () {
        let pages = []
        let totalPages = this.paginator.last_page

        if (totalPages <= 8) {
          for (let i = 1; i <= totalPages; i++) pages.push(i)
          return pages
        }

        let to = this.paginator.current_page + 3
        if (to > totalPages - 3) to = totalPages

        let from = this.paginator.current_page - 3
        if (from <= 3) from = 1
        if (from > 3) pages.push(1, 2, null)
        if (from === 1) to = 8

        if (to === totalPages) from = totalPages - 8

        while (from <= to) {
          pages.push(from)
          from++
        }

        if (to < totalPages) pages.push(null, totalPages - 1, totalPages)

        return pages
      }
    },
    methods: {
      previous () {
        if (!this.paginator.prev_page_url || this.changing) return

        this.changingPrevious = true
        this.paginator.current_page -= 1
        this.$emit('changed', this.paginator.current_page)
      },
      set (page) {
        if (this.changing) return

        this.settingPage = page
        this.paginator.current_page = page
        this.$emit('changed', this.paginator.current_page)
      },
      next () {
        if (!this.paginator.next_page_url || this.changing) return

        this.changingNext = true
        this.paginator.current_page += 1
        this.$emit('changed', this.paginator.current_page)
      }
    }
  }
</script>

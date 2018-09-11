<template>
  <div>
    <h1 class="title">{{ $t('blog.admin.views.blog-admin-view-dashboard.title') }}</h1>

    <div class="columns">
      <div class="column">
        <div class="card data">
          <header class="card-header">
            <p class="card-header-title">
              {{ $t('blog.admin.views.blog-admin-view-dashboard.users') }}
            </p>
          </header>

          <div class="card-content">
            <div class="content">
              <i
                v-if="disabled"
                class="fas fa-spinner fa-pulse"
              >
              </i>

              <a
                @click="showUserChart"
                v-else
              >
                {{ dashboard.users_count }}
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="column">
        <div class="card data">
          <header class="card-header">
            <p class="card-header-title">
              {{ $t('blog.admin.views.blog-admin-view-dashboard.posts') }}
            </p>
          </header>

          <div class="card-content">
            <div class="content">
              <i
                v-if="disabled"
                class="fas fa-spinner fa-pulse"
              >
              </i>

              <a
                v-else
                @click="showPostChart"
              >
                {{ dashboard.posts_count }}/{{ dashboard.drafts_count }}
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="column">
        <div class="card data">
          <header class="card-header">
            <p class="card-header-title">
              {{ $t('blog.admin.views.blog-admin-view-dashboard.comments') }}
            </p>
          </header>

          <div class="card-content">
            <div class="content">
              <i
                v-if="disabled"
                class="fas fa-spinner fa-pulse"
              >
              </i>

              <a
                v-else
                @click="showCommentChart"
              >
                {{ dashboard.comments_count }}
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="column">
        <div class="card data">
          <header class="card-header">
            <p class="card-header-title">
              {{ $t('blog.admin.views.blog-admin-view-dashboard.categories') }}
            </p>
          </header>

          <div class="card-content">
            <div class="content">
              <i
                v-if="disabled"
                class="fas fa-spinner fa-pulse"
              >
              </i>

              <a
                v-else
                @click="showCategoryChart"
              >
                {{ dashboard.categories_count }}
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <h1
      v-if="chart.isLoading"
      class="title has-text-centered"
    >
      <i class="fas fa-spinner fa-pulse"></i>
    </h1>

    <canvas
      v-show="!chart.isLoading"
      height="110vh"
      ref="chart"
    >
    </canvas>

    <a class="anchor" id="chart"></a>
  </div>
</template>

<script>
  import AxiosErrorHandler from '../../../mixins/AxiosErrorHandler'
  import Chart from 'chart.js'
  import axios from 'axios'

  export default {
    mixins: [
      AxiosErrorHandler,
    ],
    data: () => ({
      dashboard: {},
      chart: {
        name: null,
        ctx: null,
        isLoading: false
      }
    }),
    created () {
      this.loadDashboard()

      window.blogBus.$on('localeChanged', () => {
        switch (this.chart.name) {
          case 'users': this.showUserChart(); break
          case 'posts': this.showPostChart(); break
          case 'comments': this.showCommentChart(); break
          case 'categories': this.showCategoryChart(); break
        }
      })
    },
    mounted () {
      this.showPostChart()
    },
    methods: {
      loadDashboard () {
        this.disabled = true

        axios
          .get('blog/api/admin/dashboard')
          .then(response => {
            this.disabled = false

            this.dashboard = response.data
          })
          .catch(this.handleAxiosError)
      },
      createChart (name, type) {
        this.chart.isLoading = true
        this.chart.name = name

        if (this.chart.ctx) this.chart.ctx.destroy()

        this.chart.ctx = new Chart(this.$refs.chart.getContext('2d'), {
          type: type,
          options: {
            title: {
              display: true,
              text: [
                this.$t(`blog.admin.views.blog-admin-view-dashboard.${name}`),
                this.$t(`blog.admin.views.blog-admin-view-dashboard.${name}description`)
              ]
            }
          }
        })
      },
      showUserChart () {
        this.createChart('users', 'bar')

        axios
          .get('blog/api/admin/dashboard/users')
          .then(response => {
            this.chart.isLoading = false

            let currentMonth = window.moment().month() + 1
            let currentYear = window.moment().year()
            response.data.months.forEach(month => {
              this.chart.ctx.data.labels.push([
                month <= currentMonth ? currentYear : currentYear - 1,
                this.$t(`blog.admin.views.blog-admin-view-dashboard.${month}`)
              ])
            })

            this.chart.ctx.data.datasets.push({
              data: response.data.counts,
              backgroundColor: [
                'hsl(14, 100%, 53%)',
                'hsl(48, 100%, 67%)',
                'hsl(141, 71%, 48%)',
                'hsl(171, 100%, 41%)',
                'hsl(204, 86%, 53%)',
                'hsl(217, 71%, 53%)',
                'hsl(271, 100%, 71%)',
                'hsl(348, 100%, 61%)',
                'hsl(14, 100%, 53%)',
                'hsl(48, 100%, 67%)',
                'hsl(141, 71%, 48%)',
                'hsl(171, 100%, 41%)',
              ]
            })

            this.chart.ctx.options.legend.display = false
            this.chart.ctx.options.scales = {
              yAxes: [{
                ticks: {
                  beginAtZero: true,
                  stepSize: 1
                }
              }]
            }

            this.chart.ctx.update()
          })
          .catch(this.handleAxiosError)
      },
      showPostChart () {
        this.createChart('posts', 'line')

        axios
          .get('blog/api/admin/dashboard/posts')
          .then(response => {
            this.chart.isLoading = false

            let currentMonth = window.moment().month() + 1
            let currentYear = window.moment().year()
            response.data.months.forEach(month => {
              this.chart.ctx.data.labels.push([
                month <= currentMonth ? currentYear : currentYear - 1,
                this.$t(`blog.admin.views.blog-admin-view-dashboard.${month}`)
              ])
            })

            this.chart.ctx.data.datasets.push({
              label: this.$t(`blog.admin.views.blog-admin-view-dashboard.postlabel`),
              data: response.data.posts,
              backgroundColor: 'hsl(171, 100%, 41%)',
              borderColor: 'hsl(171, 100%, 41%)',
              fill: false
            })

            this.chart.ctx.data.datasets.push({
              label: this.$t(`blog.admin.views.blog-admin-view-dashboard.draftlabel`),
              data: response.data.drafts,
              backgroundColor: 'hsl(0, 0%, 86%)',
              borderColor: 'hsl(0, 0%, 86%)',
              fill: false
            })

            this.chart.ctx.options.scales = {
              yAxes: [{
                ticks: {
                  beginAtZero: true,
                  stepSize: 1
                }
              }]
            }

            this.chart.ctx.options.tooltips = {
              mode: 'nearest',
              intersect: false
            }

            this.chart.ctx.update()
          })
          .catch(this.handleAxiosError)
      },
      showCommentChart () {
        this.createChart('comments', 'pie')

        axios
          .get('blog/api/admin/dashboard/comments')
          .then(response => {
            this.chart.isLoading = false

            this.chart.ctx.data.labels = response.data.posts

            this.chart.ctx.data.datasets.push({
              data: response.data.counts,
              backgroundColor: [
                'hsl(14, 100%, 53%)',
                'hsl(48, 100%, 67%)',
                'hsl(141, 71%, 48%)',
                'hsl(171, 100%, 41%)',
                'hsl(204, 86%, 53%)',
                'hsl(217, 71%, 53%)',
                'hsl(271, 100%, 71%)',
                'hsl(348, 100%, 61%)',
                'hsl(14, 100%, 53%)',
                'hsl(48, 100%, 67%)',
              ]
            })

            this.chart.ctx.update()
          })
          .catch(this.handleAxiosError)
      },
      showCategoryChart () {
        this.createChart('categories', 'horizontalBar')

        axios
          .get('blog/api/admin/dashboard/categories')
          .then(response => {
            this.chart.isLoading = false

            this.chart.ctx.data.labels = response.data.categories

            let posts = []
            let drafts = []
            response.data.counts.forEach(counts => {
              posts.push(counts[0])
              drafts.push(counts[1])
            })
            this.chart.ctx.data.datasets.push({
              label: this.$t(`blog.admin.views.blog-admin-view-dashboard.postlabel`),
              data: posts,
              backgroundColor: 'hsl(171, 100%, 41%)',
              borderColor: 'hsl(171, 100%, 41%)'
            })
            this.chart.ctx.data.datasets.push({
              label: this.$t(`blog.admin.views.blog-admin-view-dashboard.draftlabel`),
              data: drafts,
              backgroundColor: 'hsl(0, 0%, 86%)',
              borderColor: 'hsl(0, 0%, 86%)'
            })

            this.chart.ctx.options.tooltips = {
              mode: 'index',
              intersect: false
            }

            this.chart.ctx.options.scales = {
              xAxes: [{
                stacked: true,
                ticks: {
                  beginAtZero: true,
                  stepSize: 1
                }
              }],
              yAxes: [{
                stacked: true
              }]
            }

            this.chart.ctx.update()
          })
          .catch(this.handleAxiosError)
      }
    },
    beforeDestroy () {
      window.blogBus.$off('localeChanged')
      this.chart.ctx.destroy()
    }
  }
</script>

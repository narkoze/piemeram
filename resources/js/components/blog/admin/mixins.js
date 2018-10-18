export default {
  methods: {
    reset () {
      this.$root.showView = ''
      this.$root.activeSection = ''
      this.$root.post = {}
    },
    dashboard () {
      this.reset()
      this.$nextTick(() => {
        this.$root.showView = 'admin-view-dashboard'
        this.$root.activeSection = 'admin-view-dashboard'
      })
    },
    users () {
      this.reset()
      this.$nextTick(() => {
        this.$root.showView = 'admin-view-users'
        this.$root.activeSection = 'admin-view-users'
      })
    },
    roles () {
      this.reset()
      this.$nextTick(() => {
        this.$root.showView = 'admin-view-roles'
        this.$root.activeSection = 'admin-view-roles'
      })
    },
    posts () {
      this.reset()
      this.$nextTick(() => {
        this.$root.showView = 'admin-view-posts'
        this.$root.activeSection = 'admin-view-posts'
      })
    },
    post () {
      this.reset()
      this.$nextTick(() => {
        this.$root.showView = 'admin-view-post'
        this.$root.activeSection = 'admin-view-post'
      })
    },
    categories () {
      this.reset()
      this.$nextTick(() => {
        this.$root.showView = 'admin-view-categories'
        this.$root.activeSection = 'admin-view-categories'
      })
    },
    images () {
      this.reset()
      this.$nextTick(() => {
        this.$root.showView = 'admin-view-images'
        this.$root.activeSection = 'admin-view-images'
      })
    }
  }
}

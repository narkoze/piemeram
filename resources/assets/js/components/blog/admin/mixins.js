export default {
  methods: {
    reset () {
      this.$root.showView = ''
      this.$root.activeSection = ''
      this.$root.post = null
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
    }
  }
}

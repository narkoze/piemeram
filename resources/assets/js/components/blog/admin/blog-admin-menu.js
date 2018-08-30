export default {
  data: () => ({
    showAdmin: null
  }),
  methods: {
    reset () {
      this.showAdmin = null
      window.blogBus.$emit('showAdmin', null)
    },
    posts () {
      this.reset()
      this.$nextTick(() => {
        this.showAdmin = 'admin-view-posts'
        window.blogBus.$emit('showAdmin', 'admin-view-posts')
      })
    },
    post () {
      this.reset()
      this.$nextTick(() => {
        this.showAdmin = 'admin-view-post'
        window.blogBus.$emit('showAdmin', 'admin-view-post')
      })
    }
  }
}

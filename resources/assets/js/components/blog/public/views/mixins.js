export default {
  methods: {
    copy (postId) {
      const el = document.createElement('textarea')
      el.value = window.location.href + `/post/${postId}`
      document.body.appendChild(el)
      el.select()
      document.execCommand('copy')
      document.body.removeChild(el)

      window.notify(this.$t('blog.public.views.blog-public-view-posts.copied'), 'is-primary')
    }
  }
}

export default {
  data: () => ({
    errors: {},
    disabled: false
  }),
  methods: {
    handleAxiosError (error) {
      this.disabled = false

      if (error.response.status === 422) {
        this.errors = error.response.data.errors
      } else {
        window.notify(this.$t(`${error.response.status}: ${error.response.statusText}`), 'is-danger')
      }
    }
  }
}

export default {
  data: () => ({
    errors: {},
    disabled: false
  }),
  methods: {
    handleAxiosError (error) {
      this.disabled = false
      if (error.response) {
        if (error.response.status === 422) {
          this.errors = error.response.data.errors
        } else {
          window.notify(`${error.response.status}: ${error.response.statusText}, ${error.response.data.message}`, 'is-danger')
        }
      }
    }
  }
}

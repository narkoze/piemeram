<template>
  <a
    @click="disabled || downloadExcel()"
    :class="['button is-primary is-outlined', {
      'is-loading': disabled
    }]"
    :disabled="disabled"
  >
    <i class="fas fa-file-download"></i>
    Excel
  </a>
</template>

<script>
  import AxiosErrorHandler from '../../mixins/AxiosErrorHandler'
  import axios from 'axios'

  export default {
    mixins: [
      AxiosErrorHandler,
    ],
    props: [
      'url',
      'params',
    ],
    methods: {
      downloadExcel () {
        this.disabled = true

        axios({
          method: 'get',
          url: this.url,
          responseType: 'blob',
          params: this.params
        })
          .then(response => {
            this.disabled = false

            let blob = new Blob([response.data])
            let link = document.createElement('a')
            link.href = window.URL.createObjectURL(blob)
            link.download = response.headers['content-disposition'].split(';')[1].split('=')[1]
            link.click()
          })
          .catch(this.handleAxiosError)
      }
    }
  }
</script>


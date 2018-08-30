<template>
  <div
    @click="close"
    :class="['notification', 'blog-notification', notification.color]"
    ref="notification"
  >
    <button
      @click="close"
      class="delete"
    >
    </button>

    <span v-if="notification.color === 'is-danger'">Error!</span>
    {{ notification.text }}
  </div>
</template>

<script>
  export default {
    props: [
      'notification',
    ],
    data: () => ({
      forceClose: false
    }),
    mounted () {
      if (this.notification.color === 'is-danger') return

      let timeout = setTimeout(() => {
        this.forceClose ? clearTimeout(timeout) : this.close()
      }, 5000)
    },
    methods: {
      close () {
        this.forceClose = true

        this.$refs.notification.style.opacity = 0
        setTimeout(() => {
          this.$emit('closed')
        }, 1000)
      }
    }
  }
</script>

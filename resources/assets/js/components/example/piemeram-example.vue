<template>
  <div>
    Hello {{ greetings }}
  </div>
</template>

<script>
  export default {
    props: {
      text: {
        type: String,
        default: 'world'
      }
    },
    data: () => ({
      greetings: ''
    }),
    mounted () {
      this.greetings = `${this.text}!`
    }
  }
</script>

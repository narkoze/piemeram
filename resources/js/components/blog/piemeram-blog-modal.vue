<template>
  <div class="modal is-active">
    <div
      @click="$emit('close')"
      class="modal-background"
    >
    </div>

    <div :class="['modal-card', size]">
      <section class="modal-card-body">
        <slot></slot>
      </section>
    </div>

    <button
      @click="$emit('close')"
      class="modal-close is-large"
    >
    </button>
  </div>
</template>

<script>
  export default {
    props: {
      size: {
        type: String,
        default: ''
      }
    }
  }
</script>

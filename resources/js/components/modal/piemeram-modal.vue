<template>
  <div :class="['ui', 'coupled', name, 'modal', size]">
    <div class="content">
      <slot></slot>
    </div>

    <i class="close icon"></i>
  </div>
</template>

<script>
  export default {
    props: {
      name: {
        type: String,
        default: 'first'
      },
      show: {
        type: Boolean,
        default: false
      },
      size: {
        type: String,
        default: 'fullscreen'
      }
    },
    watch: {
      show () {
        window.$(`.ui.coupled.${this.name}.modal`)
          .modal({
            allowMultiple: true,
            onHide: () => {
              this.$emit('close')
            }
          })
          .modal(this.show ? 'show' : 'hide')
      }
    }
  }
</script>
<template>
  <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="pswp__bg"></div>

    <div class="pswp__scroll-wrap">
      <div class="pswp__container">
        <div class="pswp__item"></div>
        <div class="pswp__item"></div>
        <div class="pswp__item"></div>
      </div>

      <div class="pswp__ui pswp__ui--hidden">
        <div class="pswp__top-bar">
          <div class="pswp__counter"></div>
          <button class="pswp__button pswp__button--close" :title="$t('blog.shared.blog-shared-photoswipe.close')"></button>
          <button class="pswp__button pswp__button--fs" :title="$t('blog.shared.blog-shared-photoswipe.fullscreen')"></button>
          <button class="pswp__button pswp__button--zoom" :title="$t('blog.shared.blog-shared-photoswipe.zoom')"></button>
          <div class="pswp__preloader">
            <div class="pswp__preloader__icn">
              <div class="pswp__preloader__cut">
                <div class="pswp__preloader__donut"></div>
              </div>
            </div>
          </div>
        </div>

        <button class="pswp__button pswp__button--arrow--left" :title="$t('blog.shared.blog-shared-photoswipe.previous')"></button>

        <button class="pswp__button pswp__button--arrow--right" :title="$t('blog.shared.blog-shared-photoswipe.next')"></button>

        <div class="pswp__caption">
          <div class="pswp__caption__center"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<style src="photoswipe/dist/photoswipe.css"></style>
<style src="photoswipe/dist/default-skin/default-skin.css"></style>
<script>
  import PhotoSwipe from 'photoswipe'
  import PhotoSwipeUIDefault from 'photoswipe/dist/photoswipe-ui-default.js'

  export default {
    props: {
      items: {
        type: Array,
        required: true,
        default: () => []
      },
      i: {
        type: Number,
        default: 0
      }
    },
    data: function () {
      return {
        options: {
          index: this.i,
          history: false,
          closeOnScroll: false
        },
        gallery: null
      }
    },
    mounted () {
      if (this.items.length) {
        this.gallery = new PhotoSwipe(this.$el, PhotoSwipeUIDefault, this.items, this.options)

        this.gallery.listen('close', () => this.$emit('close'))

        this.gallery.init()
      }
    }
  }
</script>

<template>
  <div class="modal is-active">
    <div
      @click="$emit('close')"
      class="modal-background"
    >
    </div>

    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">
          {{ $t('blog.admin.views.blog-admin-view-image-select-modal.title') }}
          <i
            v-if="disabled"
            class="fas fa-spinner fa-pulse"
          >
          </i>
        </p>
      </header>

      <section class="modal-card-body">
        <div
          v-for="(image, i) in images"
          :key="i"
          :class="['card card-square', {
            'is-selected': selectedImages.includes(image.id)
          }]"
        >
          <div class="card-content card-content-square">
            <div
              v-if="selectedImages.includes(image.id)"
              class="card-square-actions"
            >
              <a @click="toggleSelect(image.id)">
                <i class="far fa-check-square fa-lg is-marginless"></i>
              </a>
            </div>

            <img
              @click="toggleSelect(image.id)"
              :src="image.medium"
              class="image image-square"
              draggable="false"
            >
          </div>
        </div>
      </section>

      <footer class="modal-card-foot">
        <a
          class="button is-info"
        >
          {{ $t('blog.admin.views.blog-admin-view-image-select-modal.insert') }}
        </a>
      </footer>
    </div>

    <button
      @click="$emit('close')"
      class="modal-close is-large"
    >
    </button>
  </div>
</template>

<script>
  import PiemeramBlogSharedPhotoswipe from '../../shared/piemeram-blog-shared-photoswipe.vue'
  import AxiosErrorHandler from '../../../mixins/AxiosErrorHandler'
  import PiemeramBlogModal from '../../piemeram-blog-modal.vue'
  import axios from 'axios'

  export default {
    components: {
      PiemeramBlogSharedPhotoswipe,
      PiemeramBlogModal
    },
    mixins: [
      AxiosErrorHandler,
    ],
    data: () => ({
      images: [],
      selectedImages: [],
      params: {
        limit: 32
      },
      showImageUpload: false
    }),
    created () {
      this.loadImages()
    },
    methods: {
      loadImages () {
        this.disabled = true

        axios
          .get('blog/api/admin/image', { params: this.params })
          .then(response => {
            this.disabled = false

            this.images = response.data.images
            this.params = response.data.params
          })
          .catch(this.handleAxiosError)
      },
      toggleSelect (imageId) {
        if (this.selectedImages.includes(imageId)) {
          this.selectedImages.splice(this.selectedImages.indexOf(imageId), 1)
        } else {
          this.selectedImages.push(imageId)
        }
      }
    }
  }
</script>

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
        <div class="field">
          <a
            @click="showImageUpload = true"
            class="button is-primary"
          >
            <i class="fas fa-plus"></i>
            {{ $t('blog.admin.views.blog-admin-view-images.upload') }}
          </a>
        </div>

        <div
          v-for="(image, i) in images"
          :key="i"
          :class="['card card-square', {
            'is-selected': selectedImages.filter(img => img.id === image.id).length > 0
          }]"
        >
          <div class="card-content card-content-square">
            <div
              v-if="selectedImages.filter(img => img.id === image.id).length > 0"
              class="card-square-actions"
            >
              <a @click="toggleSelect(image)">
                <i class="far fa-check-square fa-lg is-marginless"></i>
              </a>
            </div>

            <img
              @click="toggleSelect(image)"
              :src="image.medium"
              class="image image-square"
              draggable="false"
            >
          </div>
        </div>
      </section>

      <footer class="modal-card-foot">
        <a
          @click="$emit('selected', selectedImages)"
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

    <piemeram-blog-shared-image-upload-modal
      v-if="showImageUpload"
      @close="closeImageUpload"
      @uploaded="imagesWasUploaded = true"
    >
    </piemeram-blog-shared-image-upload-modal>
  </div>
</template>

<script>
  import PiemeramBlogSharedImageUploadModal from '../../shared/piemeram-blog-shared-image-upload-modal.vue'
  import PiemeramBlogSharedPhotoswipe from '../../shared/piemeram-blog-shared-photoswipe.vue'
  import AxiosErrorHandler from '../../../mixins/AxiosErrorHandler'
  import PiemeramBlogModal from '../../piemeram-blog-modal.vue'
  import axios from 'axios'

  export default {
    components: {
      PiemeramBlogSharedImageUploadModal,
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
      showImageUpload: false,
      imagesWasUploaded: false
    }),
    created () {
      this.loadImages()
    },
    methods: {
      loadImages () {
        this.disabled = true

        axios
          .get('blogv1/api/admin/image', { params: this.params })
          .then(response => {
            this.disabled = false

            this.images = response.data.images
            this.params = response.data.params
          })
          .catch(this.handleAxiosError)
      },
      toggleSelect (image) {
        if (this.selectedImages.filter(img => img.id === image.id).length > 0) {
          this.selectedImages = this.selectedImages.filter(img => img.id !== image.id)
        } else {
          this.selectedImages.push(image)
        }
      },
      closeImageUpload () {
        this.showImageUpload = false

        if (this.imagesWasUploaded) {
          this.imagesWasUploaded = false
          this.loadImages()
        }
      }
    }
  }
</script>

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
          {{ $t('blog.shared.blog-shared-image-upload-modal.title') }}
        </p>
      </header>

      <section class="modal-card-body">
        <div :class="['card card-square-input card-square-droparea', {
          'card-square-droparea-hover': isOver
        }]">
          <div class="card-content card-content-square-input">
            <div
              ref="droparea"
              class="droparea"
            >
              <input
                type="file"
                accept="image/*"
                title=" "
                multiple
                @change="addImages"
              />

              <div
                v-if="isOver"
                class="icon-square"
              >
                <i class="fas fa-arrow-down fa-lg faa-falling animated is-block"></i>
                <i class="fas fa-people-carry fa-3x faa-horizontal animated is-block"></i>
                {{ $t('blog.shared.blog-shared-image-upload-modal.drophere') }}
              </div>
              <div
                v-else
                class="icon-square"
              >
                <i class="fas fa-plus fa-3x"></i>
              </div>
            </div>
          </div>
        </div>

        <div
          v-for="(image, i) in images"
          :key="i"
          :class="['card card-square-input', {
            'is-error': image.src && image.image.size > 5242880
          }]"
        >
          <div class="card-content card-content-square-input">
            <div class="card-square-actions">
              <a
                v-if="!image.uploading"
                @click="removeImage(i)"
                :title="$t('blog.shared.blog-shared-image-upload-modal.remove')"
              >
                <i class="fas fa-times fa-lg is-marginless"></i>
              </a>

              <a
                v-if="image.uploading && !image.uploaded"
                @click="cancelUpload"
                :title="$t('blog.shared.blog-shared-image-upload-modal.cancel')"
              >
                <i class="fas fa-times fa-lg is-marginless"></i>
              </a>
            </div>

            <div
              v-if="image.uploaded"
              class="card-square-success"
            >
              <div class="triangle"></div>
              <i class="fas fa-check is-marginless"></i>
            </div>

            <img
              v-if="image.src"
              @click="imagePreviewIndex = i; showPhotoswipe = true"
              :src="image.src"
              class="image image-square-input zoom-in"
            >

            <div
              v-else
              class="image-square-loading"
            >
              <i class="fas fa-spinner fa-pulse"></i>
            </div>

            <div
              v-if="image.src && image.image.size > 5242880"
              class="card-square-error"
            >
              {{ $t('blog.shared.blog-shared-image-upload-modal.istoolarge') }}
            </div>

            <progress
              v-if="image.uploading"
              :value="image.progressValue"
              max="100"
              class="progress has-value"
            >
            </progress>
          </div>
          <footer class="card-footer">
            <input
              v-model="image.name"
              :ref="`input${i}`"
              :class="['input', {
                'input-image': image.name && !image.errors.hasOwnProperty('name'),
                'is-danger': !image.name || image.errors.hasOwnProperty('name')
              }]"
              :disabled="image.disabled || image.uploaded"
            >
            <p v-if="image.errors.name" class="help is-danger is-marginless">{{ image.errors.name.join() }}</p>
          </footer>
        </div>
      </section>

      <footer class="modal-card-foot">
        <a
          v-if="cancel"
          @click="cancelUpload"
          class="button is-danger"
        >
          {{ $t('blog.shared.blog-shared-image-upload-modal.cancel') }}
        </a>

        <a
          v-else
          @click="upload"
          class="button is-info"
        >
          {{ $t('blog.shared.blog-shared-image-upload-modal.upload') }}
        </a>
      </footer>

      <piemeram-blog-shared-photoswipe
        v-if="showPhotoswipe"
        :items="imagesPreview"
        :i="imagePreviewIndex"
        @close="showPhotoswipe = false"
      >
      </piemeram-blog-shared-photoswipe>
    </div>

    <button
      @click="$emit('close')"
      class="modal-close is-large"
    >
    </button>
  </div>
</template>

<script>
  import PiemeramBlogSharedPhotoswipe from './piemeram-blog-shared-photoswipe.vue'
  import AxiosErrorHandler from '../../mixins/AxiosErrorHandler'
  import axios from 'axios'
  import exif from 'exif-js'

  export default {
    components: {
      PiemeramBlogSharedPhotoswipe
    },
    mixins: [
      AxiosErrorHandler,
    ],
    data: () => ({
      images: [],
      imagePreviewIndex: 0,
      showPhotoswipe: false,
      isOver: false,
      cancel: null,
      uploadInLoop: false
    }),
    mounted () {
      let droparea = this.$refs.droparea
      droparea.addEventListener('dragover', this.dragOver)
      droparea.addEventListener('dragleave', this.dragLeave)
      droparea.addEventListener('drop', this.dragLeave)

      document.getElementsByTagName('html')[0].classList.add('is-modal-active')
    },
    watch: {
      uploadInLoop (bool) {
        if (!bool) window.notify(this.$t('blog.shared.blog-shared-image-upload-modal.uploadfinished'), 'is-primary')
      }
    },
    computed: {
      imagesPreview () {
        let images = []
        this.images.forEach(image => {
          images.push({
            title: image.name,
            src: image.src,
            w: image.width,
            h: image.height
          })
        })
        return images
      }
    },
    methods: {
      addImages (e) {
        Array.from(e.target.files).forEach(file => {
          if (!file.size) return
          if (!file.type.match('image.*')) return

          let image = {
            name: this.$options.filters.filename(file.name),
            image: file,
            progressValue: 0,
            uploading: false,
            uploaded: false,
            disabled: false,
            errors: {}
          }

          this.$set(image, 'src', null)
          this.$set(image, 'width', 0)
          this.$set(image, 'height', 0)

          this.images.push(image)

          let reader = new FileReader()
          reader.onloadend = () => {
            let img = new Image()
            img.onload = () => {
              let orientation = null
              exif.getData(img, () => {
                orientation = exif.getAllTags(img).Orientation
              })
              if (orientation > 4 && orientation < 9) {
                let imgFixed = this.fixImgOrientation(img, img.width, img.height, orientation)
                image.src = imgFixed.src
                image.width = imgFixed.width
                image.height = imgFixed.height
              } else {
                image.src = img.src
                image.width = img.width
                image.height = img.height
              }
            }
            img.src = reader.result
          }
          reader.readAsDataURL(file)
        })
      },
      removeImage (i) {
        this.images.splice(i, 1)
      },
      upload () {
        let image = null
        this.images.forEach(f => {
          if (image) return
          if (!f.name || f.name.length > 255 || f.image.size > 5242880) return
          if (!f.uploaded) image = f
        })
        if (!image) {
          this.uploadInLoop = false
          return
        }

        this.uploadInLoop = true

        this.cancel = null
        image.disabled = image.uploading = true
        image.progressValue = 0
        image.errors = {}

        const data = new FormData()
        data.append('image', image.image)
        data.append('name', image.name)

        const CancelToken = axios.CancelToken
        axios
          .post('blogv1/api/admin/image', data, {
            cancelToken: new CancelToken(cancel => {
              this.cancel = cancel
            }),
            onUploadProgress: progress => {
              image.progressValue = parseInt(Math.round((progress.loaded * 100) / progress.total))
            }
          })
          .then(response => {
            this.cancel = null
            image.disabled = image.uploading = false
            image.uploaded = true

            this.$emit('uploaded')
            this.upload()
          })
          .catch(error => {
            this.cancel = null

            image.disabled = image.uploading = false

            if (!axios.isCancel(error)) {
              this.uploadInLoop = false

              if (error.response.status === 422) {
                image.errors = error.response.data.errors
              } else {
                this.handleAxiosError(error)
              }
            }
          })
      },
      cancelUpload () {
        this.cancel()

        this.images.forEach(image => {
          if (!image.uploaded) image.uploading = false
        })
      },
      dragOver () {
        this.isOver = true
      },
      dragLeave () {
        this.isOver = false
      },
      fixImgOrientation (image, width, height, orientation) {
        let canvas = document.createElement('canvas')
        canvas.width = image.height
        canvas.height = image.width

        let ctx = canvas.getContext('2d')
        switch (orientation) {
          case 2: ctx.transform(-1, 0, 0, 1, image.width, 0); break
          case 3: ctx.transform(-1, 0, 0, -1, image.width, image.height); break
          case 4: ctx.transform(1, 0, 0, -1, 0, image.height); break
          case 5: ctx.transform(0, 1, 1, 0, 0, 0); break
          case 6: ctx.transform(0, 1, -1, 0, image.height, 0); break
          case 7: ctx.transform(0, -1, -1, 0, image.height, image.width); break
          case 8: ctx.transform(0, -1, 1, 0, 0, image.width); break
          default: ctx.transform(1, 0, 0, 1, 0, 0)
        }
        ctx.drawImage(image, 0, 0, image.width, image.height)

        return {
          src: canvas.toDataURL('image/jpeg', 0.1),
          width: canvas.width,
          height: canvas.height
        }
      }
    },
    beforeDestroy () {
      if (this.cancel) this.cancelUpload()

      document.getElementsByTagName('html')[0].classList.remove('is-modal-active')
    }
  }
</script>

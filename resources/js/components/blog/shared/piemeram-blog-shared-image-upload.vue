<template>
  <div>
    <h1 class="title is-3">
      {{ $t('blog.shared.blog-shared-image-upload.title') }}
    </h1>

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
        class="text hover"
      >
        <i class="fas fa-arrow-down fa-lg faa-falling animated is-block"></i>
        <i class="fas fa-people-carry fa-3x faa-horizontal animated is-block"></i>
        {{ $t('blog.shared.blog-shared-image-upload.drophere') }}
      </div>
      <div
        v-else
        class="text"
      >
        {{ $t('blog.shared.blog-shared-image-upload.dropimageshere') }}
      </div>
    </div>

    <div v-if="images.length">
      <br>

      <div class="columns is-tablet is-multiline">
        <div
          v-for="(image, i) in images"
          :key="i"
          class="column is-one-quarter"
        >
          <div class="card">
            <div class="card-content image-content">
              <div>

                <a
                  v-if="!image.uploading"
                  @click="removeImage(i)"
                  :title="$t('blog.shared.blog-shared-image-upload.remove')"
                  class="has-text-grey-light is-image-button"
                >
                  <i
                    v-if="image.src"
                    class="far fa-times-circle fa-lg"
                  >
                  </i>
                  <i v-else class="fas fa-spinner fa-pulse fa-lg fa-image-loader"></i>
                </a>

                <a
                  v-if="image.uploading && !image.uploaded"
                  @click="cancelUpload"
                  :title="$t('blog.shared.blog-shared-image-upload.cancel')"
                  class="has-text-danger is-image-button"
                >
                  <i class="far fa-stop-circle fa-lg"></i>
                </a>

                <i
                  v-if="image.uploaded"
                  class="fas fa-check has-text-primary is-image-success fa-lg is-marginless"
                >
                </i>
              </div>

              <img
                @click="imagePreviewIndex = i; showPhotoswipe = true"
                :src="image.src"
                class="image zoom-in"
              >

              <p v-if="image.src && image.image.size > 5242880" class="help is-danger is-marginless">
                {{ $t('blog.shared.blog-shared-image-upload.istoolarge') }}
              </p>

              <progress
                v-if="(image.uploading || image.progressValue === 100) && !image.errors.hasOwnProperty('name')"
                :value="image.progressValue"
                :text="image.progressValue === 100 ? '100%' : image.progressLoaded"
                max="100"
                :class="['progress has-value', {
                  'is-primary': !image.errors.hasOwnProperty('name'),
                  'is-danger': image.errors.hasOwnProperty('name')
                }]"
              >
              </progress>
            </div>
            <footer class="card-footer card-footer-image">
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
        </div>
      </div>

      <br>

      <div class="field">
        <a
          v-if="cancel"
          @click="cancelUpload"
          class="button is-danger"
        >
          {{ $t('blog.shared.blog-shared-image-upload.cancel') }}
        </a>

        <a
          v-else
          @click="upload"
          class="button is-info"
        >
          {{ $t('blog.shared.blog-shared-image-upload.upload') }}
        </a>
      </div>
    </div>

    <piemeram-blog-shared-photoswipe
      v-if="showPhotoswipe"
      :items="imagesPreview"
      :i="imagePreviewIndex"
      @close="showPhotoswipe = false"
    >
    </piemeram-blog-shared-photoswipe>
  </div>
</template>

<script>
  import PiemeramBlogSharedPhotoswipe from './piemeram-blog-shared-photoswipe.vue'
  import AxiosErrorHandler from '../../mixins/AxiosErrorHandler'
  import PiemeramBlogModal from '../piemeram-blog-modal.vue'
  import axios from 'axios'
  import exif from 'exif-js'

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
      imagePreviewIndex: 0,
      showPhotoswipe: false,
      isOver: false,
      cancel: null,
      w: null
    }),
    mounted () {
      let droparea = this.$refs.droparea
      droparea.addEventListener('dragover', this.dragOver)
      droparea.addEventListener('dragleave', this.dragLeave)
      droparea.addEventListener('drop', this.dragLeave)
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
            progressLoaded: null,
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
        if (!image) return

        this.cancel = null
        image.disabled = image.uploading = true
        image.progressValue = 0
        image.errors = {}

        const data = new FormData()
        data.append('image', image.image)
        data.append('name', image.name)

        const CancelToken = axios.CancelToken
        axios
          .post('blog/api/admin/image', data, {
            cancelToken: new CancelToken(cancel => {
              this.cancel = cancel
            }),
            onUploadProgress: progress => {
              image.progressValue = parseInt(Math.round((progress.loaded * 100) / progress.total))
              image.progressLoaded = `${this.$options.filters.filesize(progress.loaded)} / ${this.$options.filters.filesize(progress.total)}`
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
    }
  }
</script>

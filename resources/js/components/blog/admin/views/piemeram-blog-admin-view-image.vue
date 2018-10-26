<template>
  <div>
    <div class="field">
      <input
        v-model="name"
        class="input is-large"
        type="text"
        :disabled="disabled || deleting"
      >
    </div>

    <div class="columns">
      <div class="column">
        <div class="image-container">
          <img
            @click="showPhotoswipe = true"
            :src="image.medium"
            class="image zoom-in"
          >
        </div>
      </div>

      <div class="column is-4">
        <div class="card">
          <header class="card-header">
            <p class="card-header-title">
              {{ $t('blog.admin.views.blog-admin-view-image.infocard.title') }}
            </p>
          </header>
          <div class="card-content">
            <div class="content">
              <div class="columns is-gapless is-mobile is-marginless">
                <div class="column">{{ $t('blog.admin.views.blog-admin-view-image.infocard.author') }}</div>
                <div class="column">{{ image.author.name }}</div>
              </div>

              <div class="columns is-gapless is-mobile is-marginless">
                <div class="column">{{ $t('blog.admin.views.blog-admin-view-image.infocard.created_at') }}</div>
                <div class="column">
                  <span
                    class="hover"
                    :title="image.created_at"
                  >
                    {{ image.created_at | dateString }}
                  </span>
                </div>
              </div>

              <div class="columns is-gapless is-mobile is-marginless">
                <div class="column">{{ $t('blog.admin.views.blog-admin-view-image.infocard.updated_at') }}</div>
                <div class="column">
                  <span
                    class="hover"
                    :title="image.updated_at"
                  >
                    {{ image.updated_at | dateString }}
                  </span>
                </div>
              </div>

              <div class="columns is-gapless is-mobile is-marginless">
                <div class="column">{{ $t('blog.admin.views.blog-admin-view-image.infocard.type') }}</div>
                <div class="column">{{ image.type }}</div>
              </div>

              <div class="columns is-gapless is-mobile is-marginless">
                <div class="column">{{ $t('blog.admin.views.blog-admin-view-image.infocard.size') }}</div>
                <div class="column">{{ image.size | filesize }}</div>
              </div>

              <div class="columns is-gapless is-mobile is-marginless">
                <div class="column">{{ $t('blog.admin.views.blog-admin-view-image.infocard.dimensions') }}</div>
                <div class="column">{{ image.dimensions }}</div>
              </div>

              <div class="columns is-gapless is-mobile is-marginless">
                <div class="column">{{ $t('blog.admin.views.blog-admin-view-image.infocard.model') }}</div>
                <div class="column">{{ image.model || '- ' }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="field">
      <a
        @click="update"
        :class="['button is-info', { 'is-loading': disabled }]"
        :disabled="disabled || deleting"
      >
        {{ $t('blog.admin.views.blog-admin-view-image.update') }}
      </a>

      <a
        @click="destroy"
        :class="['button is-danger is-pulled-right', { 'is-loading': deleting }]"
        :disabled="disabled || deleting"
      >
        {{ $t('blog.admin.views.blog-admin-view-image.delete') }}
      </a>
    </div>

    <piemeram-blog-shared-photoswipe
      v-if="showPhotoswipe"
      :items="[{
        title: image.name,
        src: image.original,
        w: image.width,
        h: image.height
      }]"
      @close="showPhotoswipe = false"
    >
    </piemeram-blog-shared-photoswipe>
  </div>
</template>

<script>
  import PiemeramBlogSharedPhotoswipe from '../../shared/piemeram-blog-shared-photoswipe.vue'
  import AxiosErrorHandler from '../../../mixins/AxiosErrorHandler'
  import axios from 'axios'

  export default {
    components: {
      PiemeramBlogSharedPhotoswipe
    },
    mixins: [
      AxiosErrorHandler,
    ],
    props: [
      'image',
    ],
    data: function () {
      return {
        name: this.image.name,
        deleting: false,
        showPhotoswipe: false
      }
    },
    methods: {
      update () {
        this.disabled = true

        axios
          .put(`blogv1/api/admin/image/${this.image.id}`, {
            name: this.name
          })
          .then(response => {
            this.disabled = false

            this.$emit('updated')

            window.notify(this.$t('blog.admin.views.blog-admin-view-image.updated', { name: response.data.name }), 'is-primary')
          })
          .catch(this.handleAxiosError)
      },
      destroy () {
        this.deleting = true

        if (!confirm(this.$t('blog.admin.views.blog-admin-view-image.confirm', { name: this.image.name }))) {
          this.deleting = false
          return
        }

        axios
          .delete(`blogv1/api/admin/image/${this.image.id}`)
          .then(() => {
            this.$emit('deleted')

            window.notify(this.$t('blog.admin.views.blog-admin-view-image.deleted'), 'is-primary')
          })
          .catch(this.handleAxiosError)
      }
    }
  }
</script>

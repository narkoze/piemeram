<template>
  <div>
    <h1 class="title">
      {{ $t('blog.admin.views.blog-admin-view-images.title') }}
      <i
        v-if="disabled"
        class="fas fa-spinner fa-pulse"
      >
      </i>
    </h1>

    <div class="field">
      <a
        @click="showImageUpload = true"
        class="button is-primary"
      >
        <i class="fas fa-plus"></i>
        {{ $t('blog.admin.views.blog-admin-view-images.upload') }}
      </a>
    </div>

    <div class="columns">
      <div class="column is-3">
        <div class="sticky is-marginless">
          <piemeram-blog-shared-datepicker
            :label="$t('blog.admin.views.blog-admin-view-images.datefrom')"
            :value="params.from"
            @selected="date => params.from = date"
          >
          </piemeram-blog-shared-datepicker>

          <piemeram-blog-shared-datepicker
            :label="$t('blog.admin.views.blog-admin-view-images.dateto')"
            :value="params.to"
            @selected="date => params.to = date"
          >
          </piemeram-blog-shared-datepicker>

          <piemeram-blog-shared-select-user
            :label="$t('blog.admin.views.blog-admin-view-images.author')"
            :selected="selectedAuthor"
            @selected="author => {
              selectedAuthor = author
              params.authorId = author.id
            }"
          >
          </piemeram-blog-shared-select-user>

          <a
            @click="loadImages"
            class="button is-info"
          >
            {{ $t('blog.admin.views.blog-admin-view-images.filter') }}
          </a>

          <a
            @click="removeFilters"
            class="button"
          >
            {{ $t('blog.admin.views.blog-admin-view-images.removefilters') }}
          </a>
        </div>
      </div>

      <div class="column">
        <div class="columns is-marginless-bottom">
          <div class="column">
            <div class="field">
              <label>{{ $t('blog.admin.views.blog-admin-view-images.search') }}
                <input
                  :value="params.search"
                  @input="search"
                  type="text"
                  class="input"
                >
              </label>
            </div>
          </div>
        </div>

        <div class="is-scrollable">
          <table class="table is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
              <tr>
                <piemeram-blog-shared-th
                  column="name"
                  :sort="params.sortBy"
                  :direction="params.sortDirection"
                  :disabled="sorting"
                  @changed="sort"
                >
                  {{ $t('blog.admin.views.blog-admin-view-images.name') }}
                </piemeram-blog-shared-th>
                <piemeram-blog-shared-th
                  column="authors.name"
                  :sort="params.sortBy"
                  :direction="params.sortDirection"
                  :filtered="filteredByAuthor"
                  :disabled="sorting"
                  @changed="sort"
                >
                  {{ $t('blog.admin.views.blog-admin-view-images.author') }}
                </piemeram-blog-shared-th>
                <piemeram-blog-shared-th
                  column="updated_at"
                  :sort="params.sortBy"
                  :direction="params.sortDirection"
                  :filtered="filteredByUpdated"
                  :disabled="sorting"
                  @changed="sort"
                >
                  {{ $t('blog.admin.views.blog-admin-view-images.updated_at') }}
                </piemeram-blog-shared-th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="image in images.data"
                :key="image.id"
                @mouseover="mouseover = image.id"
                @mouseout="mouseover = null"
              >
                <td>
                  <a
                    @click="showImage(image)"
                  >
                    <img
                      class="image small"
                      :src="image.small"
                    >
                    <b v-html="$options.filters.highlight(image.name, params.search)"></b>
                  </a>
                  <div v-if="mouseover === image.id">
                    <a @click="showImage(image)">
                      <small>{{ $t('blog.admin.views.blog-admin-view-posts.edit') }}</small>
                    </a>
                  </div>
                </td>
                <td>
                  <a
                    @click="() => {
                      selectedAuthor = image.author
                      params.authorId = image.author.id
                      loadImages()
                    }"
                    class="hover visibleicon"
                    :title="image.author.email_masked"
                  >
                    {{ image.author.name }}
                  </a>
                  <i class="fas fa-filter fa-xs"></i>
                </td>
                <td>
                  <a
                    @click="() => {
                      params.from = params.to = image.updated_at
                      loadImages()
                    }"
                    class="visibleicon"
                  >
                    {{ image.updated_at | dateString }}
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <br>
        <piemeram-blog-shared-paginate
          :paginator="images"
          :changing="pageChanging"
          @changed="setPage"
        >
        </piemeram-blog-shared-paginate>
      </div>
    </div>

    <piemeram-blog-modal
      v-if="showImageUpload"
      size="medium"
      @close="closeImageUpload"
    >
      <piemeram-blog-shared-image-upload @uploaded="imagesWasUploaded = true"></piemeram-blog-shared-image-upload>
    </piemeram-blog-modal>

    <piemeram-blog-modal
      v-if="showImageModal"
      size="medium"
      @close="closeImage"
    >
      <piemeram-blog-admin-view-image
        :image="image"
        @updated="imageWasUpdated = true"
        @deleted="imageWasUpdated = true; closeImage()"
      >
      </piemeram-blog-admin-view-image>
    </piemeram-blog-modal>
  </div>
</template>

<script>
  import PiemeramBlogSharedImageUpload from '../../shared/piemeram-blog-shared-image-upload.vue'
  import PiemeramBlogSharedSelectUser from '../../shared/piemeram-blog-shared-select-user.vue'
  import PiemeramBlogSharedDatepicker from '../../shared/piemeram-blog-shared-datepicker.vue'
  import PiemeramBlogSharedPaginate from '../../shared/piemeram-blog-shared-paginate.vue'
  import PiemeramBlogAdminViewImage from './piemeram-blog-admin-view-image.vue'
  import PiemeramBlogSharedTh from '../../shared/piemeram-blog-shared-th.vue'
  import AxiosErrorHandler from '../../../mixins/AxiosErrorHandler'
  import PiemeramBlogModal from '../../piemeram-blog-modal.vue'
  import SortHandler from '../../../mixins/SortHandler'
  import debounce from 'lodash/debounce'
  import axios from 'axios'

  export default {
    components: {
      PiemeramBlogSharedImageUpload,
      PiemeramBlogSharedSelectUser,
      PiemeramBlogSharedDatepicker,
      PiemeramBlogSharedPaginate,
      PiemeramBlogAdminViewImage,
      PiemeramBlogSharedTh,
      PiemeramBlogModal
    },
    mixins: [
      AxiosErrorHandler,
      SortHandler,
    ],
    data: () => ({
      images: [],
      image: {},
      selectedAuthor: {},
      pageChanging: false,
      filteredByAuthor: false,
      filteredByUpdated: false,
      showImageModal: false,
      showImageUpload: false,
      imageWasUpdated: false,
      imagesWasUploaded: false,
      mouseover: null
    }),
    created () {
      this.loadImages()
    },
    methods: {
      sort (by) {
        this.sorting = true

        this.sortHandler(by)
        this.loadImages()
      },
      setPage (page) {
        this.pageChanging = true
        this.loadImages(page)
      },
      removeFilters () {
        this.params.authorId = null
        this.selectedAuthor = {}

        this.params.from = null
        this.params.to = null

        this.loadImages()
      },
      search: debounce(function (e) {
        this.params.search = e.target.value
        this.loadImages()
      }, 500),
      loadImages (page = 1) {
        this.disabled = true

        this.params.page = page

        axios
          .get('blog/api/admin/image', { params: this.params })
          .then(response => {
            this.pageChanging = false
            this.disabled = false
            this.sorting = false

            this.images = response.data.images
            this.params = response.data.params

            this.filteredByAuthor = this.params.authorId
            this.filteredByUpdated = this.params.from || this.params.to
          })
          .catch(error => {
            this.pageChanging = false
            this.sorting = false

            this.handleAxiosError(error)
          })
      },
      closeImageUpload () {
        this.showImageUpload = false

        if (this.imagesWasUploaded) {
          this.imagesWasUploaded = false
          this.loadImages()
        }
      },
      showImage (image) {
        this.image = image
        this.showImageModal = true
      },
      closeImage () {
        this.showImageModal = false

        if (this.imageWasUpdated) {
          this.imageWasUpdated = false
          this.loadImages()
        }
      }
    }
  }
</script>

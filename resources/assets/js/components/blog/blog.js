import PiemeramBlogWindow from './piemeram-blog-window.vue'
import axios from 'axios'

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrfToken

window.Vue.component('piemeram-blog-login', require('./piemeram-blog-login.vue'))
window.Vue.component('piemeram-blog-modal', require('./piemeram-blog-modal.vue'))
window.Vue.component('piemeram-blog-menu', require('./piemeram-blog-menu.vue'))

window.Vue.filter('dateString', (value) => {
  let date = window.moment(value)

  if (!date.isValid()) return ''

  return date.format('YYYY-MM-DD')
})

window.blogBus = new window.Vue()

const blog = new window.Vue({
  data: () => ({
    auth: window.auth,
    show: {
      side: 'public',
      section: 'public-view-posts'
    },
    showView: 'public-view-posts',
    showModals: [],
    anchor: null,
    activeSection: null,
    post: {}
  }),
  el: '#blog',
  components: {
    PiemeramBlogWindow
  },
  watch: {
    showView () {
      this.show.side = this.showView.split('-')[0] === 'public' ? 'public' : 'admin'
      this.show.section = this.showView
    },
    auth () {
      if (this.show.side === 'admin') this.showView = 'public-view-posts'
    }
  },
  created () {
    if (window.post) {
      this.post = window.post
      this.showView = 'public-view-post'
      window.history.replaceState({}, null, '/blog')
    }
  }
})

export default { blog }

let notifyTimer = null
window.notify = function (text, color) {
  let notification = document.getElementsByClassName('notification')[0]

  while (notification.firstChild) notification.firstChild.remove()

  let hide = function () {
    notification.style.visibility = 'hidden'
    notification.style.opacity = 0
  }

  if (notifyTimer) {
    clearTimeout(notifyTimer)
    notifyTimer = null
  }

  if (color !== 'is-danger') {
    notifyTimer = window.setTimeout(function () {
      hide()
    }, 5000)
  }

  notification.className = 'notification'
  notification.classList.add(color)

  let textNode = document.createTextNode(text)
  notification.appendChild(textNode)

  let button = document.createElement('button')
  button.classList.add('delete')
  notification.appendChild(button)

  notification.style.visibility = 'visible'
  notification.style.opacity = 1

  notification.addEventListener('click', function () {
    clearTimeout(notifyTimer)
    hide()
  })
}

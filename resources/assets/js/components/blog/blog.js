window.blogBus = new window.Vue()

window.Vue.component('piemeram-blog-notification', require('./piemeram-blog-notification.vue'))
window.Vue.component('piemeram-blog-window', require('./piemeram-blog-window.vue'))
window.Vue.component('piemeram-blog-login', require('./piemeram-blog-login.vue'))
window.Vue.component('piemeram-blog-modal', require('./piemeram-blog-modal.vue'))
window.Vue.component('piemeram-blog-menu', require('./piemeram-blog-menu.vue'))

window.Vue.filter('dateString', (value) => {
  let date = window.moment(value)

  if (!date.isValid()) return ''

  return date.format('YYYY-MM-DD')
})

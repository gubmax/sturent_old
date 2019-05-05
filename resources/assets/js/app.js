window.Vue = require('vue');
Vue.use(require('vue-resource'));
import Field from './directives/Field.js';
import ClickOutside from './directives/ClickOutside.js';
import Ripple from './directives/Ripple.js';

/**
 * Directives
 */
Vue.directive('field', Field);
Vue.directive('click-outside', ClickOutside);
Vue.directive('ripple', Ripple);

/**
 * Components
 */
Vue.component('notifications', require('./components/Notifications.vue'));
Vue.component('img-slider', require('./components/ImgSlider.vue'));
Vue.component('masonry-filters', require('./components/MasonryFilters.vue'));
Vue.component('y-map', require('./components/YMap.vue'));
Vue.component('y-map-select', require('./components/YMapSelect.vue'));
Vue.component('avatar-uploader', require('./components/AvatarUploader.vue'));
Vue.component('ajax-form', require('./components/AjaxForm.vue'));
Vue.component('autosize-textarea', require('./components/AutosizeTextarea.vue'));
Vue.component('send-complaint', require('./components/SendComplaint.vue'));
Vue.component('address-suggest', require('./components/AddressSuggest.vue'));

Vue.component('modal-block', require('./components/ModalBlock.vue'));
Vue.component('drop-down-item', require('./components/DropDownItem.vue'));
Vue.component('popup-item', require('./components/PopupItem.vue'));

Vue.component('select-option', require('./components/FormSelect/FormSelectOption.vue'));
Vue.component('form-select', require('./components/FormSelect/FormSelect.vue'));

Vue.component('img-uploader', require('./components/ImgUploader/ImgUploader.vue'));

Vue.component('masonry', require('./components/Masonry/Masonry.vue'));
Vue.component('masonry-item', require('./components/Masonry/MasonryItem.vue'));

Vue.component('steps', require('./components/Steps/Steps.vue'));
Vue.component('step', require('./components/Steps/Step.vue'));

Vue.component('tabs-header', require('./components/Tabs/TabsHeader.vue'));
Vue.component('tab-title', require('./components/Tabs/TabTitle.vue'));
Vue.component('tabs', require('./components/Tabs/Tabs.vue'));
Vue.component('tab', require('./components/Tabs/Tab.vue'));

Vue.component('transition-expand', {
	functional: true,
  render: function (createElement, context) {
    var data = {
      props: {
        name: 'expand',
      },
      on: {
				enter: function(el) {
			    el.style.height = 'auto'
			    var endHeight = getComputedStyle(el).height
			    el.style.height = '1px'
			    el.offsetHeight
			    el.style.height = endHeight;
			  },
			  afterEnter: function(el) {
			    el.style.height = 'auto'
			  },
			  beforeLeave: function(el) {
			    el.style.height = getComputedStyle(el).height
			    el.offsetHeight
			    el.style.height = '0px'
			  },
      },
    }
    return createElement('transition', data, context.children)
  }
});

/**
 * App
 */
const app = new Vue({
  el: '#app',
	data: {
		activeNavLink: '',
		showSideNavbar: false,
		scrollbarWidth: undefined,
		selectedInChanger: Number,
		showModal: 0,
		showYMap: false,
		scrollTop: 0,
		switchPledge: Boolean
	},

	beforeCreate() {
		// Service Worker
		if ('serviceWorker' in navigator) {
			console.log("Will the service worker register?")
			navigator.serviceWorker.register('service-worker.js')
				.then(function(reg){
					console.log("Yes, it did.")
				}).catch(function(err) {
					console.log("No it didn't. This happened: ", err)
				})
		}
	},

	created() {
		window.addEventListener('scroll', this.showNavbar, false)
		//Lazy load images
		window.addEventListener('load', this.loadImages)
		// Check current location
		this.activeNavLink = window.location.pathname.substr(1).split('/')[0]

		this.$on('toggleModal', this.toggleModal)
	},

	methods: {
		loadImages() {
			let imgDefer = document.getElementsByTagName('img')

			for (let i = 0; i < imgDefer.length; i++) {
				if(imgDefer[i].getAttribute('data-src')) {
					imgDefer[i].setAttribute('src', imgDefer[i].getAttribute('data-src'))
					imgDefer[i].onload = () => {
						imgDefer[i].removeAttribute('data-src')
					}
				}
			}
		},

		showNavbar() {
			let scrollTop = window.pageYOffset

			if (scrollTop > document.documentElement.clientHeight / 2 && scrollTop < this.scrollTop)
				this.$refs.fixedHeader.classList.add('is-show')
			else if (scrollTop < 56 || scrollTop > this.scrollTop)
				this.$refs.fixedHeader.classList.remove('is-show')

			this.scrollTop = scrollTop
		},

		hideSideNavbar() {
			this.showSideNavbar = false
		},

		resetFields() {
			app.$emit('resetFields')
		},

		resetAvatarUploader() {
			app.$emit('resetAvatarUploader')
		},

		toggleModal(check) {
			let modal = {
				then: this,

				show: function() {
					document.body.style.cssText = 'overflow-y: hidden; margin-right: '+ modal.then.scrollbarWidth +'px;'
					modal.then.$refs.fixedHeader.style.marginRight = modal.then.scrollbarWidth +'px'
				},

				hide: function() {
					document.body.removeAttribute('style')
					modal.then.$refs.fixedHeader.removeAttribute('style')
				},

				calcScrollbarWidth() {
					let div = document.createElement('div')

					Object.assign(div.style, {
						overflowY: 'scroll',
						width: '100%',
						visibility: 'hidden'
					})

					document.body.appendChild(div)
					let scrollWidth = div.offsetWidth - div.clientWidth
					document.body.removeChild(div)

					modal.then.scrollbarWidth = scrollWidth
				}
			}

			if (check)
				this.showModal += 1
			else
				this.showModal -= 1

			if (!this.showModal) {
				modal.hide()
				return
			}

			if (this.scrollbarWidth === undefined)
				modal.calcScrollbarWidth()

			modal.show()
		}
	},
});

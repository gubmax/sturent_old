let Field = {
  bind(el, binding, vnode) {
		 const handler = () => {
			 if (!el.value || el.hasAttribute('disabled')) {
				 el.classList.remove('is-active')
				 return
			 }

			 if (!el.classList.contains('is-active'))
				 el.classList.add('is-active')
		}

		el.__vueField__ = handler
		el.addEventListener('blur', handler)
		vnode.context.$on('resetFields', handler)
  },

	inserted(el) {
		el.__vueField__()
	},

  update(el) {
    if (el.value != el.oldValue)
      el.__vueField__()
  },

  unbind(el) {
	  el.removeEventListener('blur', el.__vueField__)
  },
}

export default Field;

<template lang="html">
	<form role="form" class="page__narrow-block" :id="id" :method="method" :action="action" @submit.prevent="onSubmit">
		<slot></slot>
	</form>
</template>

<script>
export default {
	props: {
		id: String,
		method: String,
		action: String,
		page: String
	},

	methods: {
		onSubmit(e) {
			let formData = new FormData(e.target)

			Vue.http.headers.common['X-CSRF-TOKEN'] = document.getElementsByName('_token')[0].getAttribute('value');

			this.$http.post(this.action, formData).then(response => {
				Array.from(document.getElementsByClassName('msg-error')).forEach((msg) => {
					msg.parentNode.removeChild(msg);
				})

				if (response.data.success) {
					this.$root.$emit('selectTab', 'step-3')
					this.$root.$emit('selectStep', 3)
					this.$root.$emit('selectAdIdForImgUploader', {'page': this.page, 'id': response.data.ad_id})
					return true
				}

				let errors = response.data.errors

				for (let key in errors) {
					let input = document.getElementsByName(key)

					if (!input.length)
						input = document.getElementsByName(key+'[]')

					if (input) {
						let error = document.createElement('span')

						error.className += 'msg-error'
						error.innerHTML = errors[key]

						input[input.length - 1].parentNode.appendChild(error)
					}
				}
			}, error => {
				console.error(error)
			})
		},
	},
}
</script>

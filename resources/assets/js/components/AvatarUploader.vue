<template lang="html">
	<div class="user-info">
		<div class="user-info__img" ref="img">
			<div class="avatar-uploader__loader loader" v-show="loading">
				<div class="loader__dot_one"></div>
				<div class="loader__dot_two"></div>
				<div class="loader__dot_three"></div>
			</div>
		</div>

		<input class="avatar-uploader__input" type="file"  id="avatar" name="avatar" @change="syncImg" @reset="syncImg"/>
		<label class="btn btn_default" for="avatar" v-ripple>Изменить аватар</label>

		<span class="msg-error" v-show="msgError">{{ msgError }}</span>
	</div>
</template>

<script>
export default {
	props: {
		error: {
			type: String,
		},
		src: {
			type: String,
		},
	},

	data() {
		return {
			img: '',
			loading: false,
			msgError: ''
		}
	},

	created() {
		this.msgError = this.error

		this.$root.$on('resetAvatarUploader', this.resetImg)
	},

	mounted() {
		this.insertImg(this.src)
	},

	methods: {
		insertImg(src) {
			let img = new Image()

			img.onload = () => {
				this.loading = false
				this.$refs.img.style.background = 'url('+src+') center center / cover no-repeat'
			}

			img.src = src
		},

		syncImg(e) {
			if (!e.target.files[0])
				return

			if (e.target.files[0].size > 1e+7) {
				this.msgError = 'Изображение должно весить не более 10 МБ'
				e.target.value = ''

				return
			}

			let file = e.target.files[0],
					imageType = /image.*/

			if (!file.type.match(imageType)) {
				this.msgError = 'Формат выбранного файла не поддерживается'

				return
			}

			this.msgError = ''
			this.loading = true

			let reader = new FileReader()

			reader.onload = (e) => {
				this.insertImg(e.target.result)
			}
			reader.readAsDataURL(file)
		},

		resetImg() {
			this.insertImg(this.src)
		},
	},
}
</script>

<style lang="scss">
@import 'resources/assets/sass/_variables.scss';

.avatar-uploader__input {
  display: none;
}
.avatar-uploader__loader {
	height: 100%;
}
</style>

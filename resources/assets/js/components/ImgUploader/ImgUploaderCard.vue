<template lang="html">
	<div class="image-uploader__card">
		<div class="image-uploader__uload-image" ref="img"></div>

		<div class="image-uploader__footer btns-group">
			<span class="btn btn_default" v-ripple @click="removeImg">Удалить</span>
			<span class="image-uploader__img-size">{{ bytesToSize(size) }}</span>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		id: Number,
		src: String,
		size: Number,
	},

	mounted() {
		this.insertImg(this.src)
	},

	methods: {
		insertImg(src) {
			let img = new Image()

			img.onload = () => {
				this.$refs.img.style.background = 'url('+src+') center center / cover no-repeat'
			}

			img.src = src
		},

		removeImg(e) {
			this.$parent.$parent.$emit('removeImg', this.id)
		},

		bytesToSize(bytes) {
			if (bytes == 0) return 'n/a'

			let sizes = ['Б', 'КБ', 'МБ', 'ГБ', 'ТБ'],
					i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)))

			if (i == 0) return bytes + ' ' + sizes[i]

			return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i]
		},
	},
}
</script>

<style lang="scss">
@import 'resources/assets/sass/_variables.scss';

.image-uploader__card {
	box-sizing: border-box;
	display: block;
	background: $white;
	margin: 0 20px 20px 0;
	border-radius: 4px;
	overflow: hidden;
  box-shadow: 0 1px 4px 0 $shadow;
}
.image-uploader__uload-image {
	display: block;
	height: 120px;
}
.image-uploader__footer {
	padding: 8px 16px;
}
.image-uploader__img-size {
	margin: 0 16px 0 8px;
}
</style>

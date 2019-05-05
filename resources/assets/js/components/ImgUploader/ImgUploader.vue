<template lang="html">
	<div class="image-uploader">
		<label class="image-uploader__zone"
			for="image-uploader__input"
			@drop.prevent="syncImgs"
			@dragover.prevent
			@dragenter="hovering = true"
			@dragleave="hovering = false"
			:class="{'is-active': hovering}">
			<i class="image-uploader__icon">
				<svg viewBox="0 0 24 24" class="sr-icon sr-icon_image-uploader">
					<path d="M22 16V4c0-1.1-.9-2-2-2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2zm-11-4l2.03 2.71L16 11l4 5H8l3-4zM2 6v14c0 1.1.9 2 2 2h14v-2H4V6H2z"/>
				</svg>
			</i>
			<input id="image-uploader__input" type="file" name="images[]" multiple ref="input" @change="syncImgs"/>
			<p class="image-uploader__text"></p>
		</label>

		<span class="msg-error" v-for="error in msgError">{{ error }}</span>

		<div class="btns-group">
			<span class="btn btn_default" v-ripple @click="removeImgs()">Удалить все</span>
			<span class="image-uploader__count">Прикреплено: {{ imgCount }} из 6</span>
		</div>

		<transition-group class="image-uploader__cards" name="image-uploader__cards" tag="div">
			<img-uploader-card v-for="img in imgs"
				:key="img.id"
				:id="img.id"
				:src="img.src"
				:size.sync="img.size">
			</img-uploader-card>
		</transition-group>

		<div class="btns-group">
			<transition name="show-upload-bar">
				<div class="image-uploader__upload-bar" v-show="uploadPercent >= 0">
					<div class="image-uploader__loading" ref="loading"></div>
					<div class="image-uploader__loading-percent">{{ uploadPercent }}%</div>
				</div>
			</transition>
			<span type="submit" class="btn btn_primary" v-show="uploadPercent == undefined" v-ripple @click.once="uploadImgs">Загрузить</span>
			<span class="btn btn_link" v-show="uploadPercent == undefined" v-ripple @click="switchAdRelevance">Не загружать</span>
		</div>
	</div>
</template>

<script>
import ImgUploaderCard from './ImgUploaderCard.vue'

export default {
	props: {
		error: String
	},

	data() {
		return {
			page: '',
			hovering: false,
			imgCount: 0,
			imgs: [],
			msgError: [],
			ad_id: undefined,
			uploadPercent: undefined
		}
	},

	components: { 'img-uploader-card': ImgUploaderCard },

	created() {
		this.msgError[0] = this.error

		this.$on('removeImg', this.removeImgs)
		this.$root.$on('selectAdIdForImgUploader', this.selectAdId);
	},

	mounted() {
		this.data = this.$children
	},
	methods: {
		insertImg(src, size) {
			this.imgs.push({
				id: this.imgs[this.imgs.length - 1] ? this.imgs[this.imgs.length - 1].id + 1 : 0,
				src: src,
				size: size
			})
		},

		syncImgs(e) {
			this.hovering = false
			this.msgError = []

			let files = e.target.files || e.dataTransfer.files,
					key = this.imgCount,
					imageType = /image.*/

			if (!files)
				return

			Array.from(files).forEach(file => {
				if (key >= 6) {
					if (!this.msgError[1])
						this.msgError[1] = 'Можно прикрепить не больше 6 фотографий'
					return
				}

				if (!file.type.match(imageType)) {
					if (!this.msgError[2])
						this.msgError[2] = 'Один или несколько файлов не прикреплены, т.к. их формат не поддерживается'
					return
				}

				if (file.size > 1e+7) {
					if (!this.msgError[3])
						this.msgError[3] = 'Одна или несколько фотографий не прикреплены, т.к их размер более 10 МБ'
					return
				}

				let reader = new FileReader()

				reader.onload = (e) => {
					let index = this.imgs.push({
						id: this.imgs[this.imgs.length - 1] ? this.imgs[this.imgs.length - 1].id + 1 : 0,
						src: e.target.result,
						size: e.loaded
					})
				}
				reader.readAsDataURL(file)

				key += 1
			})

			this.imgCount = key
			this.$refs.input.value = ''
    },

		removeImgs(id = -1) {
			if (id >= 0) {
				let index = 0

				this.imgs.forEach((img, i) => {
					if (img.id == id)
						return index = i
				})

				this.imgs.splice(index, 1)
				this.imgCount -= 1
			}
			else {
				this.imgs = []
				this.imgCount = 0
			}

			this.msgError = []
		},

		selectAdId(data) {
			this.page = data.page
			this.ad_id = data.id
		},

		switchAdRelevance() {
			if (!this.ad_id) return

			let resourceUrl = '/api/'+ this.page +'/relevance/'+this.ad_id

			this.$http.get(resourceUrl).then(response => {
				window.location.replace('/profile')
			},
			error => {
				console.error(error)
			})
		},

		uploadImgs(e) {
			if (!this.ad_id) return

			if (this.imgs.length == 0) {
				this.msgError = 'Не прикреплена ни одна картинка.'
				return
			}

			this.uploadPercent = 0

			let totalPercent = 100 / this.imgs.length;

			this.imgs.forEach((img, i) => {
				img.ad_id = this.ad_id

				this.$http.post('/api/'+ this.page +'/upload-img', img).then(response => {
					let count = i + 1
					this.uploadPercent = Math.round(totalPercent * count)
					this.$refs.loading.style.width = this.uploadPercent + '%'

					if(totalPercent * count == 100) {
						setTimeout(() => {
							this.switchAdRelevance()
						}, 400)
					}
				}, error => {
					console.error(error)
				})
			})
		},
	},
}
</script>

<style lang="scss">
@import 'resources/assets/sass/_variables.scss';

#image-uploader__input {
	display: none;
}
.image-uploader__zone {
	display: flex;
	flex-flow: column wrap;
	justify-content: center;
	margin-bottom: 24px;
	padding: 24px 16px;
	border: 2px dashed $border;
	border-radius: 4px;
	cursor: pointer;
	transition: background .15s, border-style .15s, box-shadow .15s;
}
.image-uploader__zone.is-active {
	background: $light_2;
	border-style: solid;
}
.image-uploader__icon {
	display: block;
	margin: 0 auto 16px;
	width: 100px;
	height: 100px;
	pointer-events: none;
}
.sr-icon_image-uploader {
  fill: #dbe1e7;
}
.image-uploader__cards {
	display: flex;
	flex-direction: row;
	flex-wrap: wrap;
	margin: 24px 0 8px;
}
.image-uploader__count {
	color: $primary;
	font-size: 14px;
	font-weight: 500;
}
.image-uploader__text {
  color: $secondary;
  text-align: center;
  line-height: 1em;
	font-size: 16px;
  cursor: pointer;
	pointer-events: none;
}
.image-uploader__text::before {
  content: 'Нажмите на данную область или перетащите в неё фотографии';
}
.image-uploader__zone.is-active .image-uploader__text::before {
  content: 'Отпустите клавишу мыши, чтобы загрузить фотографии';
}
.image-uploader__cards-enter-active, .image-uploader__cards-leave-active {
  transition: all .25s;
}
.image-uploader__cards-enter, .image-uploader__cards-leave-to {
  opacity: 0;
  transform: translateY(32px);
}
.image-uploader__cards-move {
  transition: transform 1s;
}
.image-uploader__upload-bar {
  position: relative;
  flex: 1;
  height: 16px;
  margin: 7px 48px 7px 0;
  padding: 4px;
  border: 1px solid $border;
  border-radius: 20px;
	animation: load .5s linear infinite;
	background-image: linear-gradient(-45deg, $light_2 25%, $white 25% , $white 50%, $light_2 50%, $light_2 75%, $white 75%);
	background-size: 40px 40px;
}
.image-uploader__loading {
  width: 0%;
  height: 16px;
  background: $accent;
  border-radius: 20px;
	transition: width .2s ease-out;
}
@keyframes load {
	0% {
    background-position: 0px 0px;
	}
	100% {
    background-position: 40px 0px;
	}
}
.image-uploader__loading-percent {
  position: absolute;
  top: 0;
  right: -48px;
  width: 44px;
  line-height: 24px;
  text-align: center;
}
.show-upload-bar-enter-active, .show-upload-bar-leave-active {
  transition: opacity .5s;
	transition-delay: .5s;
}
.show-upload-bar-leave-active {
	position: absolute;
}
.show-upload-bar-move{
  transition: transform .6s;
}
.show-upload-bar-enter, .show-upload-bar-leave-to {
  opacity: 0;
}
</style>

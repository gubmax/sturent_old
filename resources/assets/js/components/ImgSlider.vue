<template lang="html">
	<figure class="img-slider">
		<a :href="url+'thumbnail/'+activeImg.img_name+'.jpg'" class="img-slider__img-thumb replace"
			@click.prevent="viewImg"
			v-html="lazyLoadImg"
			ref="thumb">
		</a>

		<div class="loader img-slider__loader" v-show="loading">
			<div class="loader__dot_one"></div>
			<div class="loader__dot_two"></div>
			<div class="loader__dot_three"></div>
		</div>

		<div class="img-slider__counter" v-if="imgs.length > 1">{{ activeImgId+' / '+imgs.length }}</div>

		<i class="img-slider__btn prev" v-if="imgs.length > 1" @click="slideImg('prev')">
			<svg class="sr-icon sr-icon_slider-btn" viewBox="0 0 24 24">
				<path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
			</svg>
		</i>

		<i class="img-slider__btn next" v-if="imgs.length > 1" @click="slideImg('next')">
			<svg class="sr-icon sr-icon_slider-btn" viewBox="0 0 24 24">
				<path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
			</svg>
		</i>

		<modal-block v-if="showModal">
			<div class="img-slider__header">
				<a class="img-slider__link" :href="url+activeImg.img_name+'.jpg'" target="_blank">
					<i class="btn__icon">
						<svg viewBox="0 0 24 24" class="sr-icon sr-icon_light">
							<path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/>
						</svg>
					</i>
					<span>Ссылка</span>
				</a>

				<span class="img-slider__link" @click="scaleImg">
					<i class="btn__icon">
						<svg viewBox="0 0 24 24" class="sr-icon sr-icon_light">
							<path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
						</svg>
					</i>
					<span>{{ Math.round(imgScale * 100) + '%' }}</span>
				</span>

				<span class="img-slider__link" @click="zoom(-0.24)">
					<i class="icon">
						<svg viewBox="0 0 24 24" class="sr-icon sr-icon_light">
							<path d="M19 13H5v-2h14v2z"/>
						</svg>
					</i>
				</span>

				<span class="img-slider__link" @click="zoom(0.24)">
					<i class="icon">
						<svg viewBox="0 0 24 24" class="sr-icon sr-icon_light">
						<path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
					</i>
				</span>

				<span class="img-slider__link img-slider__link_close" @click="closeView">
					<i class="icon">
						<svg viewBox="0 0 24 24" class="sr-icon sr-icon_light">
							<path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
						</svg>
					</i>
				</span>
			</div>

			<div class="img-slider__bg" @mousewheel.sync="wheelZoom">
				<transition name="slide-img">
					<img class="img-slider__img"
						:src="url+activeImg.img_name+'.jpg'"
						:key="activeImgId"
						ref="img"/>
				</transition>

				<div class="loader img-slider__loader" v-show="loading">
					<div class="loader__dot_one"></div>
					<div class="loader__dot_two"></div>
					<div class="loader__dot_three"></div>
				</div>

				<div class="img-slider__counter">{{ activeImgId+' / '+imgs.length }}</div>

				<i class="img-slider__btn prev" @click="slideImg('prev')" v-if="imgs.length > 1">
					<svg class="sr-icon sr-icon_slider-btn" viewBox="0 0 24 24">
						<path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
					</svg>
				</i>

				<i class="img-slider__btn next" @click="slideImg('next')" v-if="imgs.length > 1">
					<svg class="sr-icon sr-icon_slider-btn" viewBox="0 0 24 24">
						<path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
					</svg>
				</i>
			</div>
		</modal-block>
	</figure>
</template>

<script>
export default {
	props: {
		url: {
			type: String,
			requied: true,
		},
		imgs: {
			type: Array
		},
		lazyLoad: {
			type: Boolean,
			default: true
		}
	},

	data() {
		return {
			lazyLoadImg: '',
			activeImgId: 1,
			activeImg: [],
			loading: false,
			showModal: false,
			imgScale: 1
		}
	},

	created() {
		let activeImg,
				activeImgId = this.activeImgId

		this.imgs.forEach((img, i) => {
			if (i + 1 == activeImgId) return activeImg = img
		})

		this.activeImg = activeImg

		if (this.lazyLoad)
			this.lazyLoadImg = '<img class="preview" src="'+this.url+'lazy/'+this.activeImg.img_name+'.jpg" alt="photo">'
		else
			this.lazyLoadImg = '<img src="'+this.url+'thumbnail/'+this.activeImg.img_name+'.jpg" alt="photo">'
	},

	methods: {
		slideImg(direction) {
			let activeImg = this.activeImg,
					activeImgId = this.activeImgId,
					arrayLength = this.imgs.length

			if (direction == 'prev')
				activeImgId = activeImgId - 1
			else if (direction == 'next')
				activeImgId = activeImgId + 1

			if (activeImgId > arrayLength) activeImgId = 1
			else if (activeImgId < 1) activeImgId = arrayLength

			this.loading = true
			this.activeImgId = activeImgId

			this.imgs.forEach((img, i) => {
				if (i + 1 == activeImgId) return activeImg = img
			})

			this.activeImg = activeImg

			this.$nextTick(() => {
				if (this.$refs.img)
					this.loadImg(false)
				else
					this.loadImg(true)
			})
		},

		loadImg(loadThumb) {
			let img = new Image(),
					url,
					el = this,
					item = el.$refs.thumb
					//child = item.firstChild

			if (loadThumb)
				url = this.url+ 'thumbnail/' + this.activeImg.img_name + '.jpg'
			else
				url = this.url + this.activeImg.img_name + '.jpg'

			//if (child.src.split('/').pop() == url.split('/').pop()) return

			img.src = url
			img.className = 'reveal'

			function replaceImg() {
				item.appendChild(img).addEventListener('animationend', function(e) {
					let pImg = item.querySelector && item.querySelector('img')

					if (pImg) {
						e.target.alt = pImg.alt || ''
						item.removeChild(pImg)
						e.target.classList.remove('replace')
					}
				});
			}

			// Если изобображение уже загружено
			if (img.complete) {
				el.loading = false

				if (!loadThumb) el.scaleImg()
				else replaceImg()

				return
			}

			// Если изобображение надо загрузить
			img.onload = () => {
				el.loading = false

				if (!loadThumb) {
					this.scaleImg(img)
					return
				}

				replaceImg()
			}

		},

		viewImg() {
			this.showModal = true
			this.loading = true

			let img = new Image()
			img.src = this.url + this.activeImg.img_name + '.jpg'

			img.onload = () => {
				this.loading = false
				this.scaleImg(img)

				window.addEventListener('resize', this.scaleImg)
			}
		},

		closeView() {
			this.showModal = false
			this.loadImg(true)

			window.removeEventListener('resize', this.scaleImg)
		},

		scaleImg() {
			let clientWidth = document.body.clientWidth + this.$root.scrollbarWidth,
					clientHeight = document.body.clientHeight,
					img = this.$refs.img,
					scale = 1

			if (!img) return

			if (img.width > clientWidth || img.height > clientHeight) {
				let scaleWidth = 1 - clientWidth / img.width,
						scaleHeight = 1 - clientHeight / img.height

				scale -= Math.max(scaleWidth, scaleHeight)
			}

			Object.assign(img.style, {
				transition: '',
				top: -img.height / 2 + 'px',
				left: -img.width / 2 + 'px',
				transform: 'translate3d('+ clientWidth / 2 +'px, '+  clientHeight / 2  +'px, 0px) scale3d(' + scale + ',' + scale + ', 1)'
			})

			this.imgScale = scale
		},

		zoom(value) {
			let clientWidth = document.body.clientWidth + this.$root.scrollbarWidth,
					clientHeight = document.body.clientHeight,
					minWidth = 325,
					minHeight = 285,
					img = this.$refs.img,
					scale = this.imgScale

			if (scale + value > 0.12)
				scale += value

			if (img) {
				img.style.transition = 'transform .2s'
				img.style.transform = 'translate3d('+ clientWidth / 2 +'px, '+  clientHeight / 2  +'px, 0px) scale3d(' + scale + ',' + scale + ', 1)'
			}

			this.imgScale = scale
		},

		wheelZoom (e) {
			if (e.deltaY < 0)
				this.zoom(0.12)
			else
				this.zoom(-0.12)
		},
	},
}
</script>

<style lang="scss">
@import 'resources/assets/sass/_variables.scss';

.img-slider {
	position: relative;
	width: 100%;
	margin-bottom: 16px;
	background: $light_3;
	overflow: hidden;
}
.img-slider__bg {
	width: 100%;
	height: 100%;
	overflow: hidden;
}
.img-slider__img-thumb {
	display: block;
	height: inherit;
	cursor: pointer;
}
.img-slider__img-thumb > img {
	position: absolute;
	top: 50%;
	left: 50%;
	display: block;
	min-width: 100%;
	min-height: 100%;
	transform: translate(-50%, -50%);
	transform-origin: left;
}
.img-slider__img-thumb > img.preview  {
	transform: scale(1.05) translate(-50%, -50%);
	filter: blur(8px);
}
.img-slider__img-thumb > img.reveal {
	will-change: transform, opacity;
	animation: reveal .4s ease;
}
@keyframes reveal {
  from {
  	opacity: 0;
		transform: scale(1.05) translate(-50%, -50%);
  }
	to {
		opacity: 1;
		transform: scale(1) translate(-50%, -50%);
	}
}
.img-slider__img {
	position: relative;
	top: 50%;
	left: 50%;
	max-height: 100%;
	transform: translate3d(-50%, -50%, 0);
}
.img-slider__loader {
	position: absolute;
	left: 50%;
	top: 50%;
	transform: translate3d(-50%, -50%, 0);
}
.img-slider__btn {
	position: absolute;
	top: 50%;
	width: 44px;
	height: 44px;
	cursor: pointer;
	border-radius: 50%;
	transform: translateY(-50%);
	user-select: none;
}
.img-slider__btn:hover {
	background: rgba($accent, .9);
	transition: background .2s;
}
.img-slider__btn.next {
	right: 8px;
}
.img-slider__btn.prev {
	left: 8px;
}
.sr-icon_slider-btn {
	fill: $white;
  filter: drop-shadow(2px 2px 2px $primary);
}
.img-slider__counter {
	position: absolute;
	bottom: 12px;
	left: 50%;
	transform: translateX(-50%);
	color: $white;
	font-size: 14px;
	line-height: 1em;
	font-weight: 500;
	text-align: center;
	text-shadow: 1px 0 1px $primary,
							 0 1px 1px $primary,
							 -1px 0 1px $primary,
							 0 -1px 1px $primary;
	user-select: none;
	cursor: default;
}
/*
 * Img-viewer
 */
.img-slider {
	 height: 264px;
 }
.img-slider__header {
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	height: $header-height;
	background: rgba($black, .4);
	z-index: 2;
}
.img-slider__link {
	display: inline-flex;
  align-items: center;
	height: inherit;
	padding: 0 6px;
	color: $white;
	font-size: 12px;
	font-weight: 500;
  text-transform: uppercase;
	text-decoration: none;
	transition: background .2s;
	user-select: none;
	cursor: pointer;
}
.img-slider__link_close {
	padding: 0 10px 0 16px;
	float: right;
}
.img-slider__link:hover,
.img-slider__link_close:hover {
	background: rgba($accent, .8);
}
/*
 * Transition
 */
.slide-img-enter-active, .slide-img-leave-active {
	position: absolute;
	transition: opacity .4s ease;
}
.slide-img-enter,
.slide-img-leave-to {
	opacity: 0;
}

@media (min-width: 776px) {
	.img-slider__link {
		padding: 0 12px;
	}
	.img-slider__btn,
	.img-slider__counter {
		opacity: 0;
		transition: opacity .2s;
	}
	.img-slider:hover .img-slider__btn,
	.img-slider:hover .img-slider__counter {
		opacity: 1;
	}
}
</style>

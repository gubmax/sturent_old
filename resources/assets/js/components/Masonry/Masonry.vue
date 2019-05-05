<template lang="html">
	<div class="masonry" v-if="items.length || loading">
		<div class="form-group btns-group masonry__group page__block_with-padding" v-if="clearing && items.length">
			<span class="btn btn_default btn_with-icon" v-ripple @click.stop="showModal = true">
				<i class="btn__icon">
					<svg viewBox="0 0 24 24" class="sr-icon sr-icon_secondary">
						<path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"></path>
					</svg>
				</i>
				<span>Очистить</span>
			</span>
		</div>
		<transition-group :name="pressButton ? 'masonry__column' : ''" tag="div" class="masonry__body">
			<masonry-item
				v-for="(item, index) in items"
				:key="index"
				:item="item"
				:page="page"
				:clearing="clearing"
				:location="location"
				:user-id="userId"
				class="masonry__item"
				ref="masonryItems">
			</masonry-item>
		</transition-group>

		<button type="button" class="masonry__btn btn btn_link" v-show="!loading && resourceUrl && !pressButton" v-ripple @click.once="removeButton">Показать ещё</button>

		<div class="msg-collection" v-show="lastItems">
			<span>Объявления закончились</span>
		</div>

		<div class="masonry__loader loader" v-show="loading">
			<div class="loader__dot_one"></div>
			<div class="loader__dot_two"></div>
			<div class="loader__dot_three"></div>
		</div>

		<modal-block v-if="showModal">
			<div class="modal-block" v-click-outside="hideModal">
				<div class="modal-block__header">Очистить избранное?</div>
				<div class="modal-block__content">Все объявления из активной вкладки будут удалены из избранного.</div>
				<div class="modal-block__btns">
					<span class="btn btn_default" v-ripple @click="hideModal">Закрыть</span>
					<span class="btn btn_link" v-ripple @click="clearFavorites">Очистить</span>
				</div>
			</div>
		</modal-block>
	</div>
	<span class="msg-collection" v-else>Нет ни одного обьявления</span>
</template>

<script>
export default {
	props: {
		userId: {
			type: Number,
			requied: true,
		},
		url: {
			type: String,
			requied: true,
		},
		page: {
			type: String,
			requied: true,
		},
		favorites: {
			type: Array,
			default: Array[0],
		},
		load: {
			type: Boolean,
			default: true,
		},
		clearing: {
			type: Boolean,
			default: false,
		},
		location: {
			type: Boolean,
			default: true,
		},
	},

	data() {
		return {
			items: [],
			images: [],
			timer: '',
			lastItems: false,
			resourceUrl: '',
			pressButton: false,
			loading: true,
			showModal: false,
			shuffle: true
		}
	},

	created() {
		this.resourceUrl = !this.load ? this.url + '/' + this.page : this.url

		if (this.load) this.loadMasonry()
		else this.$root.$on('selectTab', this.loadInTab)

		this.countItems()
	},

	mounted() {
		this.$root.$on('removeMasonryItem', this.removeItem)
		this.$on('swithOffOtherRelevance', this.swithOffOtherRelevance)
		this.$root.$on('selectTab', this.checkActiveTab)

		window.addEventListener('scroll', this.onScroll, false)
		window.addEventListener('scroll', this.scroller, false)
		window.addEventListener('resize', this.shuffleMasonry, false)

		this.images = document.getElementsByClassName('img-slider__img-thumb replace')
	},

	watch: {
		items: function() {
			this.$nextTick(() => {
				this.shuffleMasonry()
				this.scroller()
			})
		}
	},

	methods: {
		scroller(e) {
			let then = this
		  then.timer = then.timer || setTimeout(function() {
		    then.timer = null
		    requestAnimationFrame(then.inView)
		  }, 200);
		},

		inView() {
			let wT = window.pageYOffset,
					wB = wT + window.innerHeight,
					pItem = this.images,
					cRect, pT, pB, i = 0

			while (i < pItem.length) {
				cRect = pItem[i].getBoundingClientRect()
		    pT = wT + cRect.top
		    pB = pT + cRect.height

		    if (wT < pB && wB > pT) {
					this.loadThumb(pItem[i])
					pItem[i].classList.remove('replace');
		    }
				else i++
			}
		},

		loadThumb(item) {
			if (!item || !item.href) return;

		  let img = new Image()

		  if (item.dataset)
		    img.src = item.dataset.src || ''
		  img.src = item.href
		  img.className = 'reveal'

		  if (img.complete) addImg()
		  else img.onload = addImg

			function addImg() {
				item.appendChild(img).addEventListener('animationend', function(e) {
					let pImg = item.querySelector && item.querySelector('img.preview')

					if (pImg) {
						e.target.alt = pImg.alt || ''
						item.removeChild(pImg)
						e.target.classList.remove('reveal')
					}
				});
			}
		},

 		removeButton() {
			this.pressButton = true
			this.loadMasonry()
		},

		loadInTab(href) {
			if (this.page == href) {
				this.loadMasonry()
				this.$root.$off('selectTab', this.loadInTab)
			}
		},

		loadMasonry() {
			this.loading = true

			this.$http.get(this.resourceUrl).then(response => {
				let json = response.data,
						items = json.data

				if (items) {
					if (this.favorites) {
						items.forEach((item, i) => {
							item.in_favorites = ( this.favorites.indexOf(item.ad_id) != -1 )
						})
					}

					this.items = this.items.concat(items)
				}

				if (json.next_page_url == null && this.pressButton) {
					this.lastItems = true
					window.removeEventListener('scroll', this.onScroll)
				}

				this.resourceUrl = json.next_page_url ? json.next_page_url+ '&' + this.page + '&' + window.location.search.replace('?','') : null

				this.loading = false
			}, error => {
				console.error(error)
			})
		},

		countItems() {
			let url = this.resourceUrl,
					pos = url.indexOf('?')

			if (pos > -1) {
				url += '&count'
			}
			else
				url += '?count'

			this.$http.get(url).then(response => {
				this.$root.$emit('setMasonryItemsCount', {'tab_name': this.page, 'count': Number(response.data)})
			}, error => {
				console.error(error)
			})
		},

		onScroll() {
			let clientHeight = document.body.clientHeight,
					masonryBottom = this.$el.getBoundingClientRect().bottom

			if (masonryBottom <= clientHeight && !this.loading && this.pressButton)
				this.loadMasonry()
		},

		checkActiveTab(href) {
			this.shuffle = (this.page == href) ? true : false
			if (this.shuffle) {
				this.$nextTick(() => {
					this.shuffleMasonry()
				})
			}
		},

		shuffleMasonry() {
			if (!this.shuffle) return

			let masonry = {
				items: this.$refs.masonryItems,
				body: this.$refs.masonryItems && this.$refs.masonryItems.length ? this.$refs.masonryItems[0].$el.parentNode : null,
				oneColumn: false,

				shuffle: () => {
					if (!masonry.body) {
						masonry.destroy()
						return
					}

					let leftColHeight = 0,
							rightColHeight = 0,
							topPos = 0,
							leftPos = 0,
							vMargin = 20,
							itemsHeight = [],
							columnWidth = masonry.oneColumn ? '100%' : 'calc(50% - '+ vMargin / 2 +'px)'

					masonry.items.forEach((item, i) => {
						item.$el.style.width = columnWidth
						itemsHeight[i] = item.$el.offsetHeight

						if (i == masonry.items.length - 1) {
							masonry.items.forEach((item, i) => {
								if (rightColHeight < leftColHeight && !masonry.oneColumn) {
									topPos = rightColHeight
									rightColHeight += (itemsHeight[i] + vMargin)
									leftPos = columnWidth
								}
								else {
									topPos = leftColHeight
									leftColHeight += (itemsHeight[i] + vMargin)
									leftPos = 0
								}
								let margin = leftPos ? '0 0 0 20px' : '0 20px 0 0'

								item.$el.style.cssText += '; top: ' + topPos + 'px; margin: ' + margin + '; left:' + leftPos
							})

							masonry.body.style.height = Math.max(leftColHeight, rightColHeight) + 'px'
						}
					})
				},

				destroy: () => {
					window.removeEventListener('resize', this.shuffleMasonry)
				}
			}

			if (document.body.clientWidth >= 776)
				masonry.oneColumn = false
			else
				masonry.oneColumn = true

			if (this.items.length)
				masonry.shuffle()
		},

		removeItem(ad_id) {
			let itemKey

			this.items.forEach(function(item, i) {
				if (item.ad_id == ad_id)
					itemKey = i
			})

			console.log(itemKey,	this.items);

			this.items.splice(itemKey, 1);
		},

		swithOffOtherRelevance(ad_id, user_id) {
			this.items.forEach((item, index) => {
				if (item.ad_id != ad_id && item.user_id == user_id)
					item.relevance = false
			})
		},

		hideModal() {
			this.showModal = false
		},

		clearFavorites() {
			this.$http.get('/api/favorites/clear' + '?' + this.page).then(response => {
				let navbar = this.$root.$refs.navbarFavCounter,
						fixedNavbar = this.$root.$refs.fixedNavbarFavCounter

				navbar.innerHTML = 0
				navbar.classList.add('is-hide')
				fixedNavbar.innerHTML = 0
				fixedNavbar.classList.add('is-hide')

				this.$root.$emit('setMasonryItemsCount', {'tab_name': this.page, 'count': -this.items.length})
				this.items = []
			}, error => {
				console.error(error)
			})
		},
	},
}
</script>

<style lang="css">
.masonry {
	margin-bottom: 24px;
}
.masonry__item {
	position: absolute;
}
.masonry__body {
	position: relative;
	display: flex;
	flex-flow: row wrap;
	height: inherit;
	margin-bottom: 20px;
}
.masonry__group {
	padding-top: 20px;
}
.masonry__first-column,
.masonry__second-column {
	display: inline-block;
	box-sizing: border-box;
	width: 100%;
}
@media (min-width: 776px) {
	.masonry__group {
		padding-top: 0;
	}
	.masonry__first-column,
	.masonry__second-column {
		display: inline-block;
		box-sizing: border-box;
		vertical-align: top;
		width: 50%;
	}
	.masonry__first-column {
		padding-right: 10px
	}
	.masonry__second-column {
		padding-left: 10px
	}
}
.masonry__btn {
	left: 50%;
	transform: translateX(-50%);
}
.masonry__loader {
	margin: 0 auto;
}
.masonry__column-enter-active, .masonry__column-leave-active {
  transition: opacity .4s, transform .4s ease-in;
}
.masonry__column-leave-active {
	position: absolute;
}
.masonry__column-enter {
  opacity: 0;
  transform: translate3d(0, 50%, 0);
}
.masonry__column-leave-to {
  opacity: 0;
	transform: scale(0);
}
.masonry__column-move {
  transition: transform .4s;
}
</style>

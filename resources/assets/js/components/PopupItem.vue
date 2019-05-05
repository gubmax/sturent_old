<template lang="html">
	<transition name="show-popup-item" @before-enter="beforeEnter" @after-leave="afterLeave" appear>
		<div class="popup-item__bg" ref="bg">
			<transition name="slide-popup-item" appear>
				<div class="popup-item__body" ref="item" v-click-outside="hidePopup">
					<slot></slot>
				</div>
			</transition>
		</div>
	</transition>
</template>

<script>
export default {
	props: {
		height: {
			type: Number,
			default: 0,
		},
		el: {
			requied: true,
		}
	},

	mounted() {
		this.showPopup()
	},

	destroyed() {
		window.removeEventListener('resize', this.setCoords)
	},

	methods: {
		beforeEnter: function(el) {
			this.$root.$emit('toggleModal', true)
		},

		 afterLeave: function(el) {
			this.$root.$emit('toggleModal', false)
		},

		showPopup() {
			this.setCoords()

			window.addEventListener('resize', this.setCoords)
		},

		hidePopup() {
			this.$parent.$emit('hidePopup')

			window.removeEventListener('resize', this.setCoords)
		},

		setCoords() {
			let item = this.$refs.item,
					clientWidth = document.body.clientWidth

			if (clientWidth < 776) {
				Object.assign(item.style, {
					top: '',
					bottom: 0,
					left: 0,
					right: 0,
					width: 'auto',
					marginRight: this.$root.scrollbarWidth + 'px',
					maxHeight: '50%'
				})

				return
			}

			let elCoords = this.el.getBoundingClientRect(),
					bgCoords = this.$refs.bg.getBoundingClientRect(),
					clientHeight = document.body.clientHeight,
					style = {}

			style.left = elCoords.left - bgCoords.left + 'px'
			style.width = elCoords.right - elCoords.left + 'px'

			if (this.height)
				style.maxHeight = this.height + 'px'

			if (elCoords.top < clientHeight * 0.5) {
				style.top = elCoords.top - bgCoords.top + 'px'
				style.bottom = 'auto'
			}
			else {
				let toBottom = (clientHeight - elCoords.bottom) - (clientHeight - bgCoords.bottom)
				if (clientHeight < elCoords.bottom) {
					document.documentElement.scrollTop += -toBottom + 40
					elCoords = this.el.getBoundingClientRect()
					toBottom = clientHeight - elCoords.bottom
				}

				style.top = 'auto'
				style.bottom = toBottom + 'px'
			}

			Object.assign(item.style, style)
		},
	},
}
</script>

<style lang="scss">
@import 'resources/assets/sass/_variables.scss';

.popup-item {
	position: relative;
}
.popup-item__body {
	display: flex;
	flex-flow: column nowrap;
	position: absolute;
	background: $white;
  list-style-type: none;
	box-shadow: 0 4px 16px 2px $shadow;
	overflow-y: auto;
  z-index: 101;
}
@media (min-width: 776px) {
	.popup-item__body {
		border-radius: 4px;
	}
}
.popup-item__bg {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	z-index: 2;
	background: transparent;
}
.popup-item__footer {
	padding: 0 24px 16px;
}

.slide-popup-item-enter-active,
.slide-popup-item-leave-active {
	transform: scaleY(1);
	transition: transform .15s ease-out;
}
.slide-popup-item-enter,
.slide-popup-item-leave-to {
	transform: scaleY(0);
}
.show-popup-item-enter-active,
.show-popup-item-leave-active {
	opacity: 1;
	transition: opacity .15s ease-out;
}
.show-popup-item-enter,
.show-popup-item-leave-to {
	opacity: 0;
}
</style>

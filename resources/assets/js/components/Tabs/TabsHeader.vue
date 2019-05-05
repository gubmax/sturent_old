<template lang="html">
	<div class="tabs-header">
		<ul class="tabs-header__nav">
			<slot></slot>
		</ul>
		<div class="tabs-header__pointer" :class="{'with-transition': transition }" ref="pointer"><div></div></div>
	</div>
</template>

<script>
export default {
	data() {
		return {
			tabs: [],
			selectedTabIndex: 0,
			transition: false,
		}
	},

	created() {
		this.tabs = this.$children
	},

	mounted() {
		this.setPointer()
		this.$root.$on('selectTab', this.selectTab)
		this.$root.$on('setMasonryItemsCount', this.setMasonryItemsCount)

		window.addEventListener('resize', this.setPointer)
	},

	methods: {
		selectTab(tab_href) {
			let activeTabIndex = 0

			this.tabs.forEach((tab, i) => {
				tab.isActive = ( tab.href == tab_href )

				if (tab.isActive)
					activeTabIndex = i
			});

			this.setPointer()
			this.selectedTabIndex = activeTabIndex
		},

		setPointer(animation = false) {
			let marginLeft = 0,
					marginRight = 0,
					activeTabIndex = -1,
					pointer = this.$refs.pointer.firstChild

			this.tabs.forEach((tab, i) => {
				if (tab.isActive)
					activeTabIndex = i
				else if (activeTabIndex < 0)
					marginLeft += tab.$el.offsetWidth
				else
					marginRight += tab.$el.offsetWidth
			});

			if (this.selectedTabIndex != activeTabIndex) {
				this.transition = true

				Vue.nextTick(() => {
					if (pointer.style.marginRight < pointer.style.marginLeft )
						pointer.style.marginLeft = 0 + 'px'
					else
						pointer.style.marginRight = 0 + 'px'

					setTimeout(() => {
						pointer.style.marginRight = marginRight + 'px'
						pointer.style.marginLeft = marginLeft + 'px'
					}, 200);

					setTimeout(() => {
						this.transition = false
					}, 400);
				})
			}
			else {
				pointer.style.marginRight = marginRight + 'px'
				pointer.style.marginLeft = marginLeft + 'px'
			}
		},

		setMasonryItemsCount(data) {
			if (data.count >= 0) {
				this.tabs.forEach((tab) => {
					if (tab.href == data.tab_name )
						tab.counter = data.count
				})
			}
			else {
				this.tabs.forEach((tab) => {
					if (tab.href == data.tab_name )
						tab.counter += data.count
				})
			}

			this.$nextTick(function() {
				this.setPointer()
			})
		},
	},
}
</script>

<style lang="scss">
@import 'resources/assets/sass/_variables.scss';
.tabs-header {
	position: relative;
	background: rgba($primary, .2);
}
.tabs-header__nav {
	display: flex;
}
.tabs-header__pointer {
	position: absolute;
	left: 0;
	right: 0;
	bottom: 0;
  width: 100%;
}
.tabs-header__nav,
.tabs-header__pointer {
	box-sizing: border-box;
	max-width: 1000px;
	margin: 0 auto;
}
.tabs-header__pointer > div {
	height: 3px;
	background: $white;
}
.tabs-header__pointer.with-transition > div {
	transition: margin .2s ease-in-out;
}
@media (min-width: 776px) {
	.tabs-header__nav {
		margin-top: 32px;
	}
	.tabs-header__nav,
	.tabs-header__pointer {
		padding: 0 $v-margin_desktop;
	}
}
</style>

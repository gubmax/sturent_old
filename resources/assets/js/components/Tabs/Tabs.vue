<template>
	<div class="tabs">
		<slot></slot>
	</div>
</template>

<script>
export default {
	data() {
		return {
			tabs: [],
			selectedTabIndex: 0,
		};
	},

	created() {
		this.tabs = this.$children
	},

	beforeMount() {
		this.$root.$on('selectTab', this.selectTab)
	},

	methods: {
		selectTab(tab_href) {
			let activeTabIndex = 0,
					transitionReverse = false

			this.tabs.forEach((tab, i) => {
				tab.isActive = ( tab.href == tab_href )

				if (tab.isActive)
					activeTabIndex = i
			});

			if (activeTabIndex < this.selectedTabIndex)
				transitionReverse = true

			this.tabs[activeTabIndex].transitionReverse = transitionReverse
			this.tabs[this.selectedTabIndex].transitionReverse = transitionReverse

			this.selectedTabIndex = activeTabIndex
		},
	},
}
</script>

<style lang="css">
	.tabs {
		position: relative;
	}
</style>

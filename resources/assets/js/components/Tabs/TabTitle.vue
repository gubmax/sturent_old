<template lang="html">
	<li class="tabs-header__title" :class="{ 'is-active': isActive }" v-ripple @click="selectTab(href)">
		<span>{{ name }}</span>
		<span class="round-counter round-counter_in-tab " v-show="counter > 0">{{ counter }}</span>
	</li>
</template>

<script>
export default {
	props: {
		name: {
			required: true
		},
		selected: {
			type: Boolean,
			default: false,
		},
		href: {
			required: true
		},
	},

	data() {
		return {
			isActive: false,
			counter: {
				type: Number,
				default: 0
			}
		}
	},

	created() {
		let hash = window.location.hash.substr(1)

		if (hash == this.href || hash == '' && this.selected)
			this.isActive = true
	},

	methods: {
		selectTab(href) {
			this.$root.$emit('selectTab', href);
		}
	},
}
</script>

<style lang="scss">
@import 'resources/assets/sass/_variables.scss';

.tabs-header__title {
	display: inline-block;
	flex-grow: 1;
	text-align: center;
	cursor: pointer;
}
.tabs-header__title {
	display: block;
	color: $white;
	text-decoration: none;
	font-size: 13px;
	font-weight: 500;
	line-height: 48px;
	text-transform: uppercase;
	transition: color .2s, border-color .2s;
}
.round-counter_in-tab {
	background: $white;
	color: $accent;
}
</style>

<template>
	<transition
		name="switch-tab"
		:enter-class="transitionReverse ? 'switch-tab-reverse-enter': 'switch-tab-enter'"
		:leave-to-class="transitionReverse ? 'switch-tab-reverse-leave-to': 'switch-tab-leave-to'">
		<div class="tab" v-show="isActive">
			<slot></slot>
		</div>
	</transition>
</template>

<script>
export default {
	props: {
		href: {
			type: String,
			required: true
		},
		selected: {
			type: Boolean,
			default: false,
		},
	},

	data() {
		return {
			isActive: false,
			transitionReverse: false,
		}
	},

	beforeMount() {
		let hash = window.location.hash.substr(1)

		if (hash == this.href || hash == '' && this.selected) {
			this.isActive = true
			this.$nextTick(() => {
			  this.$root.$emit('selectTab', this.href)
			})
		}
	},
}
</script>

<style lang="scss">
@import 'resources/assets/sass/_variables.scss';

.tab {
	background: inherit;
	z-index: 1;
}
.switch-tab-enter-active, .switch-tab-leave-active {
	width: 100%;
	transition: transform .4s ease-out;
}
.switch-tab-leave-active  {
	position: absolute;
	top: 0;
 }
.switch-tab-enter {
	transform: translate3d(150%, 0, 0);
}
.switch-tab-leave-to {
	transform: translate3d(-150%, 0, 0);
}
.switch-tab-reverse-enter {
	transform: translate3d(-150%, 0, 0);
}
.switch-tab-reverse-leave-to {
	transform: translate3d(150%, 0, 0);
}
</style>

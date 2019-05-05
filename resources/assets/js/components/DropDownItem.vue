<template lang="html">
	<div class="drop-down-item" v-click-outside="hideDropDown">
		<div class="drop-down-item__btn" @click="showDropDownItem = !showDropDownItem">
			<slot name="activator"></slot>
		</div>

		<transition name="slide-drop-down-item">
			<div class="drop-down-item__body" v-show="showDropDownItem">
				<slot></slot>
			</div>
		</transition>
	</div>
</template>

<script>
export default {
	data() {
		return {
			showDropDownItem: false
		}
	},

	methods: {
		hideDropDown() {
			this.showDropDownItem = false
		}
	},
}
</script>

<style lang="scss">
@import 'resources/assets/sass/_variables.scss';

.drop-down-item {
	position: relative;
	white-space: nowrap;
}
.drop-down-item__body {
	position: absolute;
	top: 100%;
	right: 0;
  background: #fff;
  box-shadow: 0 4px 16px 2px $shadow;
	border-radius: 4px;
	transform-origin: 100% 0;
	z-index: 2;
}
.slide-drop-down-item-enter-active,
.slide-drop-down-item-leave-active {
	transform: scale(1);
	transition: transform .25s ease-out, opacity .25s ease-out;
}
.slide-drop-down-item-enter,
.slide-drop-down-item-leave-to {
	opacity: 0;
	transform: scale(0);
}
</style>

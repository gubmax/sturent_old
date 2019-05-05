<template lang="html">
	<transition name="modal" @before-enter="beforeEnter" @after-leave="afterLeave">
		<div class="modal-block__bg" >
			<slot></slot>
		</div>
	</transition>
</template>

<script>
export default {
	methods: {
		beforeEnter: function(el) {
			this.$root.$emit('toggleModal', true)
	  },
		 afterLeave: function(el) {
			this.$root.$emit('toggleModal', false)
		},
	}
}
</script>

<style lang="scss">
@import 'resources/assets/sass/_variables.scss';

.modal-block__bg {
	position: fixed;
	display: flex;
	flex-flow: column nowrap;
	top: 0;
	left: 0;
	right: 0;
	height: 100%;
	z-index: 100;
	background: rgba($black, .7);
  overflow-y: scroll;
  overflow-x: hidden;
}
.modal-block {
	box-sizing: border-box;
	width: 100%;
	max-width: $page-width_narrow;
	margin: auto;
	background: $white;
	text-align: left;
	border-radius: 4px;
	transition: transform .25s ease-out;
}
.modal-block__header,
.modal-block__header_full-window {
	display: flex;
	flex-flow: row nowrap;
	align-items: center;
	color: $primary;
	font-size: 22px;
	font-weight: 500;
}
.modal-block__header {
	min-height: 36px;
	padding: 12px 20px 0;
}
.modal-block__header_full-window {
  min-height: 56px;
  padding: 0 20px;
	background: $white;
}
.modal-block__header-text {
	flex-grow: 1;
}
.modal-block__content {
	margin: 12px 24px;
}
.modal-block__content > .form-group_field:first-of-type {
	margin-top: 28px;
}
.modal-block__btns {
	display: flex;
	flex-direction: row;
	justify-content: flex-end;
	margin: 12px 0 12px 20px;
}
.modal-block__btns > .btn {
	margin-right: 20px;
}
.modal-block__btn {
	display: inline-block;
	width: 24px;
	height: 24px;
	min-width: 24px;
	padding: 8px;
	color: $secondary;
	vertical-align: middle;
	border-radius: 50%;
	cursor: pointer;
  transition: background .4s;
}
.modal-block__btn_absolute {
	position: absolute;
	top: 12px;
	right: 16px;
	z-index: 1;
}
/*
 * Transition
 */
.modal-enter,
.modal-leave-to {
  opacity: 0;
}
.modal-enter .modal-block,
.modal-leave-to .modal-block {
	transform-origin: bottom;
  transform: translate3d(0, 50%, 0);
}
.modal-leave-active,
.modal-enter-active {
	transition: opacity .4s;
}
</style>

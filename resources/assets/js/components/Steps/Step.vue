<template lang="html">
	<div class="steps__step">
		<span class="steps__step-num" :class="{'steps__step-num_is-active': active}">
			{{ number }}
			<i class="steps__step-icon" v-show="!number">
				<svg viewBox="0 0 24 24" class="sr-icon sr-icon_light">
					<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"></path>
				</svg>
			</i>
		</span>
		<span class="steps__step-text">
			<slot></slot>
		</span>
	</div>
</template>

<script>
export default {
	data() {
		return {
			number: undefined,
			active: false,
		}
	},
}
</script>

<style lang="scss">
@import 'resources/assets/sass/_variables.scss';

.steps__step {
	position: relative;
	width: 100%;
}
.steps__step-num {
	position: absolute;
	top: 25%;
	left: 50%;
	display: block;
	width: 28px;
	background: $secondary;
	color: $white;
	line-height: 28px;
	text-align: center;
	border-radius: 50%;
	border: 12px solid $white;
	transform: translate(-50%, -50%);
	z-index: 1;
}
.steps__step-num_is-active {
	background: linear-gradient(120deg, $gradient_1, $gradient_2);
}
.steps__step:not(:first-of-type):after {
  content: "";
  position: absolute;
  width: 100%;
  height: 1px;
  background-color: $border;
  top: 25%;
  right: 50%;
	z-index: 0;
}
.steps__step-text {
  display: block;
	padding-top: 48px;
	color: $primary;
	font-size: 15px;
	text-align: center;
	font-weight: 500;
	line-height: 1;
}
.steps__step-icon {
	display: block;
	width: 24px;
	height: 28px;
	margin: 0 auto;
}
</style>

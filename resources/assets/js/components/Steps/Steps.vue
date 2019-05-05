<template lang="html">
	<div class="steps">
		<slot></slot>
	</div>
</template>

<script>
export default {
	props: {
		current: {
			type: Number,
			default: 1,
		},
	},

	data() {
		return {
			steps: [],
		}
	},

	created() {
		this.steps = this.$children

		this.$root.$on('selectStep', this.selectStep)
	},

	mounted() {
		this.selectStep(this.current)
	},

	methods: {
		selectStep(num) {
			this.steps.forEach((step, i) => {
				if (i + 1 < num) {
					step.active = true
					step.number = undefined
				}
				else if (i + 1 == num) {
					step.active = true
					step.number = i + 1
				}
				else
					step.number = i + 1
			})
		},
	},
}
</script>

<style lang="css">
.steps {
	display: flex;
	justify-content: space-between;
}
</style>

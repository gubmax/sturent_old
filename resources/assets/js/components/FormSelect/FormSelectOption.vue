<template lang="html">
	<option :value="value" v-if="!selected"></option>
	<option :value="value" selected v-else ></option>
</template>

<script>
export default {
	props: {
		name:  {
			type: String,
			requied: true,
		},
		value:  {
			requied: true,
		},
	},

	data() {
		return {
			selected: false,
		}
	},
}
</script>

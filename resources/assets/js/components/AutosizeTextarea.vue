<template lang="html">
	<div class="autosize-textarea">
		<textarea v-model="count" ref="textarea" :id="id" :name="name" :required="required"></textarea>
		<label class="autosize-textarea__label" :for="id">Текст</label>
		<span class="autosize-textarea__count" :class="{ 'has-error': count.length > max }">{{ max ? count.length + " / " + max : count.length}}</span>
	</div>
</template>

<script>
export default {
	props: {
		id: String,
		name: String,
		value: String,
		required: Boolean,
		max: Number,
	},

	data() {
		return {
			count: '',
		}
	},

	mounted() {
		this.count = this.value

		this.$nextTick(() => {
			autosize(this.$refs.textarea)
		})
	},
}
</script>

<style lang="scss">
 @import 'resources/assets/sass/_variables.scss';

.autosize-textarea {
	position: relative;
}
.autosize-textarea__count {
	position: absolute;
	bottom: -24px;
	right: 0;
	color: $secondary;
  font-size: 13px;
}
.autosize-textarea__count.has-error {
	color: $red;
}
</style>

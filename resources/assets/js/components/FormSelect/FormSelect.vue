<template lang="html">
	<div class="select">
		<div class="select__field" @click.stop="showOptions = true">{{  selected ? selected.join(', ') : selected }}</div>
		<span class="select__label" :class="{ 'is-active': selected && selected.length }">{{ text }}</span>
		<span class="select__icon"></span>

		<popup-item v-if="showOptions" :el="this.$el" :height="256">
			<ul class="select__options drop-down_list">
				<li class="drop-down_list__item select__options-item_reset" v-if="cleaner" :class="{ 'with-checkboxes': multiple }" @click="selectedOption(false)">
					<span>Очистить поле</span>
				</li>
				<li v-for="option in options" class="drop-down_list__item select__options-item" :class="{ 'selected': option.selected }" @click="selectedOption(option)">
					<span><div class="checkbox" :class="{ 'checked': option.selected }" v-if="multiple"></div>{{ option.name }}</span>
				</li>
			</ul>
		</popup-item>

		<select class="select__input" :name="name" :multiple="multiple">
			<option disabled selected value ref="default"></option>
			<slot></slot>
		</select>
	 </div>
</template>

<script>
export default {
	props: {
		name: {
			type: String,
			requied: true,
		},
		text: {
			type: String,
			requied: true,
		},
		multiple: {
			type: Boolean,
			default: false,
		},
		value: {
			requied: false,
		},
		changer: {
			type: Boolean,
			default: false,
		},
		cleaner: {
			type: Boolean,
			default: true,
		},
	},

	data() {
		return {
			options: [],
			selected: [],
			showOptions: false
		}
	},

	created() {
		if (this.changer && !this.multiple)
			this.$root.selectedInChanger = this.value
		else
			this.$root.$on('resetFields', this.resetSelect)

		this.$on('hidePopup', this.hideOptions)
	},

	mounted() {
		this.options = this.$children.filter(comp => comp.$options._componentTag === 'select-option')
		this.resetSelect()
	},

	methods: {
		hideOptions() {
			this.showOptions = false
		},

		singleSelect(selected) {
			this.options.forEach(option => {
				if (option == selected)
					option.selected = true
				else
					option.selected = false
			})

			this.selected[0] = selected.name
			this.showOptions = false
		},

		multipleSelect(selected) {
			selected.selected = !selected.selected

			if (selected.selected)
				this.selected[this.selected.length] = selected.name
			else {
				this.selected.splice(this.selected.indexOf(selected.value), 1)
			}
		},

		clearSelect() {
			this.selected = []

			this.options.forEach(option => {
				option.selected = false
			})

			if (this.$refs.default && !this.selected.length)
				this.$refs.default.selected = true

			this.showOptions = false
		},

		resetSelect() {
			this.selected = []

			this.options.forEach(option => {
				if (this.value && this.value.indexOf(option.value) >= 0) {
					option.selected = true
					this.selected[this.selected.length] = option.name
					return
				}
			})
		},

		selectedOption(selected) {
			if (!selected)
				this.clearSelect()
			else if (this.multiple)
				this.multipleSelect(selected)
			else
				this.singleSelect(selected)

			if (this.changer)
				this.$root.selectedInChanger = selected.value
		},
	},
}
</script>

<style lang="scss">
@import 'resources/assets/sass/_variables.scss';

.select__icon {
	position: absolute;
	top: 16px;
	right: 4px;
	border-style: solid;
	border-width: 6px 5px 0 5px;
	border-color: $secondary transparent transparent transparent;
	pointer-events: none;
}
.select__input {
	display: none;
}
.select__field {
	height: 36px;
	cursor: pointer;
	overflow: hidden;
	min-width: 144px;
}
.select__label {
	pointer-events: none;
}
.select__options-item.selected {
	color: $accent;
	font-weight: 500;
}
.select__options-item_reset {
	color: $secondary !important;
}
.select__options-item_reset.with-checkboxes {
	padding-left: 60px;
}
</style>

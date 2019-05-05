<template lang="html">
	<div class="address-suggest" :class="{'has-error': error}">
		<input type="text" :name="oneString ? 'location' : ''" required autocomplete="off" :placeholder="placeholder" v-model.trim='str' v-field  @input="findKladr"></input>
		<label>Местоположение</label>
		<div class="msg-error" v-show="error">{{ error }}</div>

		<input type="hidden" v-for="(val, i) in arr" v-if="!oneString && val != null && i != 'value'" :name="i" :value="val"></input>

		<ul class="address-suggest__list drop-down_list" v-show="suggest && suggest.length" v-click-outside="hide">
			<li class="drop-down_list__item" v-for="(item, index) in suggest" @click.stop="selectValue(index, 1)">{{ item.value }}</li>
		</ul>
	</div>
</template>

<script>
export default {
	props: {
		value: [Object, String],
		placeholder: String,
		oneString: {
			type: Boolean,
			default: false
		}
	},

	data() {
		return {
			resourceUrl: '//suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address',
			suggest: [],
			str: '',
			arr: {
				region: null,
				city: null,
				setlement: null,
				district: null,
				street: null
			},
			error: null,
		}
	},

	created() {
		let loc = this.value
		this.str = loc.value
		if (loc && !this.oneString) {
			Object.assign(this.arr, loc)
		}
	},

	methods: {
		hide() {
		 this.selectValue(0, 0)
			this.$emit('input', this.str)
		},

		setAddress(arr) {
			if (!arr) {
				this.error = 'Адрес не найден'
				Object.assign(this.arr, {
					region: null,
					city: null,
					setlement: null,
					district: null,
					street: null
				})
			}
			else {
				this.error = null
				Object.assign(this.arr, {
					region: arr.region_with_type,
					city: arr.city,
					setlement: arr.setlement,
					district: arr.city_district,
					street: arr.street_with_type
				})

				let prev = null,
						obj = this.arr

				for (let key in obj) {
					if (obj[key] == prev || 'г ' + obj[key] == prev) obj[key] = null
					if (obj[key] != null) prev = obj[key]
				}
			}
		},

		findKladr() {
			if (!this.str) return

			let request = {
						query: this.str,
						count: 4,
						bounds: 'region-city',
						country: 'Россия'
					},
					data = JSON.stringify(request),
					token = '541f72d92dd4ccea6f654f5fad08a42b602ca87f',
					params = {
						contentType: 'application/json',
						headers: { 'Authorization': 'Token ' + token },
						bounds: 'region-city'
					}

			this.$http.post(this.resourceUrl, data, params).then(response => {
				let suggestions = response.data.suggestions
				if (suggestions.length) {
					this.suggest = suggestions
					this.setAddress(suggestions[0].data)
				}
				else {
					this.suggest = []
					this.setAddress(null)
				}
			}, error => {
				console.error(error)
			})
		},

		selectValue(i, find) {
			if(!this.suggest.length) return

			let val = this.suggest[i].value
			this.str = val

			if (find == 1) this.findKladr()
			else this.suggest = []
		}
	}
}
</script>

<style lang="scss">
@import 'resources/assets/sass/_variables.scss';

.address-suggest__list {
	position: absolute;
	top: 37px;
	width: 100%;
	background: $white;
	border-radius: 4px;
	box-shadow: 0 4px 16px 2px $shadow;
	z-index: 1;
}
</style>

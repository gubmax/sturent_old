<template lang="html">
	<div class="masonry-filters btns-group">
		<div class="masonry-filters__btns">
			<a class="btn btn_link btn_with-icon" :href="'/map?'+viewProps" v-if="mapLink &&  location.length" v-ripple>
				<i class="btn__icon">
					<svg viewBox="0 0 24 24" class="sr-icon sr-icon_is-active">
						<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"></path>
					</svg>
				</i>
				<span>На карте</span>
			</a>

			<a class="btn btn_link btn_with-icon" :href="'/share?'+viewProps" v-else-if="mapLink" v-ripple>
				<i class="btn__icon">
					<svg viewBox="0 0 24 24" class="sr-icon sr-icon_is-active">
						<path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
					</svg>
				</i>
				<span>Списком</span>
			</a>

			<div class="masonry-filters__btns-separator" v-if="mapLink"></div>

			<span class="btn btn_default btn_with-icon" @click.stop="showFilters = true" v-ripple>
				<i class="btn__icon">
					<svg viewBox="0 0 24 24" class="sr-icon sr-icon_secondary">
						<path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z"/>
					</svg>
				</i>
				<span>Фильтры</span>
				<span class="round-counter round-counter_primary" v-show="filtersCount">{{ filtersCount }}</span>
			</span>
		</div>
		<div class="masonry-filters__region-box" v-if="location.length">
			<span class="masonry-filters__region">{{ location }}</span>
			<span class="masonry-filters__counter" v-show="adsCount">найдено объявлений: {{ adsCount }}</span>
		</div>

		<modal-block v-if="showFilters">
			<div class="modal-block" v-click-outside="hideForm">
				<div class="modal-block__header">
					<span class="modal-block__header-text">Фильтры</span>
					<i class="modal-block__btn" v-ripple @click="hideForm">
						<svg viewBox="0 0 24 24" class="sr-icon sr-icon_primary">
							<path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path>
						</svg>
					</i>
				</div>
				<form role="form"
							name="masonry-filters"
							method="GET"
							:action="this.$root.activeNavLink"
							ref="form">
					<div class="modal-block__content">
						<slot></slot>
					</div>
					<div class="modal-block__btns">
						<button class="btn btn_default" @click.prevent="resetForm" v-ripple>Сбросить фильтры</button>
						<button type="submit" class="btn btn_link" v-ripple>Применить</button>
					</div>
				</form>
			</div>
		</modal-block>
	</div>
</template>

<script>
export default {
	props: {
		location: {
			type: String,
			default: ''
		},
		mapLink: {
			type: Boolean,
			default: false
		}
	},

	data() {
		return {
			showFilters: false,
			showRegion: false,
			filtersCount: 0,
			viewProps: '',
			adsCount: 0
		}
	},

	created() {
		this.setFiltersCounter()
		this.viewProps = window.location.search.replace('?','')

		this.$root.$on('setMasonryItemsCount', this.setMasonryItemsCount);
	},

	methods: {
		setFiltersCounter() {
			let params = window
			 .location
			 .search
			 .replace('?','')
			 .split('&')
			 .reduce(
				 function(prev, curr, i) {
					 let a = curr.split('=')
					 prev[a[0]] = a[1] ? decodeURIComponent(a[1]) : ''
					 return prev
				 }, {}
			 );

		 let count = 0,
				 keys = Object.keys(params)

		 for (let i = 0; i < keys.length; i++) {
			 if (params[keys[i]] && (keys[i] != 'region' && keys[i] != 'city' && keys[i] != 'settlement' && keys[i] != 'district' && keys[i] != 'street')) count += 1
		 }

		 this.filtersCount = count
		},

		hideForm(count) {
			this.showFilters = false
		},

		resetForm() {
			this.$root.$emit('resetFields')

			var frm_elements = this.$refs.form.elements

			for (var i = 0; i < frm_elements.length; i++) {
		    var field_type = frm_elements[i].type.toLowerCase()
		    switch (field_type) {
			    case "text":
			    case "password":
			    case "textarea":
		        frm_elements[i].value = ""
		        break
			    case "radio":
			    case "checkbox":
		        if (frm_elements[i].checked)
	            frm_elements[i].checked = false
		        break
			    case "select-one":
			    case "select-multi":
		        frm_elements[i].selectedIndex = -1
		        break
			    default:
		        break
		    }
			}
		},

		setMasonryItemsCount(data) {
			this.adsCount = data.count
		}
	},
}
</script>

<style lang="scss">
@import 'resources/assets/sass/_variables.scss';
.masonry-filters {
	position: relative;
}
.masonry-filters__region {
	margin-left: 12px;
	line-height: 24px;
	font-size: 20px;
	font-weight: 500;
	color: $primary;
}
.masonry-filters__counter {
	display: inline-block;
	margin-left: 12px;
	font-size: 16px;
	color: $secondary;
}
.masonry-filters__btns {
  display: flex;
	width: 100%;
	margin-bottom: 20px;
	background: $white;
	padding: 6px;
	box-shadow: 0 1px 2px 0 $shadow;
}
.masonry-filters__btns-separator {
	margin: 0 6px;
	border-left: 1px solid $border;
}
.masonry-filters__btns > .btn {
	flex: 1;
}
.masonry-filters__region-box {
	flex-grow: 1;
}
@media (min-width: 776px) {
	.masonry-filters {
		background: $white;
		padding: 8px;
		box-shadow: 0 1px 2px 0 $shadow;
		border-radius: 4px;
	}
	.masonry-filters__btns {
		width: auto;
		margin-bottom: 0;
		background: transparent;
		padding: 0;
		box-shadow: none;
	}
	.masonry-filters__btns > .btn {
		width: auto;
	}
	.masonry-filters__region {
		line-height: 40px;
	}
}
</style>

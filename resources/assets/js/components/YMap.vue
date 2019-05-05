<template lang="html">
	<div id="y-map">
		<div class="y-map__loader loader" v-show="loading">
			<div class="loader__dot_one"></div>
			<div class="loader__dot_two"></div>
			<div class="loader__dot_three"></div>
		</div>

		<div class="y-map__header">
			<masonry-filters class="masonry-filters_on-map" :map-link="true">
				<slot></slot>
			</masonry-filters>

			<span class="y-map__btn" v-ripple @click="setZoom(1)">
				<i>
					<svg viewBox="0 0 24 24" class="sr-icon sr-icon_primary">
						<path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
					</svg>
				</i>
			</span>
			<span class="y-map__btn" v-ripple @click="setZoom(-1)">
				<i>
					<svg viewBox="0 0 24 24" class="sr-icon sr-icon_primary">
						<path d="M19 13H5v-2h14v2z"/>
					</svg>
				</i>
			</span>
			<span class="y-map__btn" v-ripple @click="setGeolocation()">
			<i>
				<svg viewBox="0 0 24 24" class="sr-icon sr-icon_primary">
					<path d="M21 3L3 10.53v.98l6.84 2.65L12.48 21h.98L21 3z"/>
				</svg>
			</i>
			</span>
		</div>

		<modal-block v-if="ad.ad_id">
			<masonry-item
				:item="ad"
				:user-id="userId"
				:location="false"
				page="share"
				class="modal-block"
				v-click-outside="hideAd">
			</masonry-item>
		</modal-block>
	</div>
</template>

<script>
export default {
	props: {
		userId: {
			type: Number,
			requied: true
		},
		location: String
	},

	data() {
		return {
			myMap: {},
			ad: [],
			loading: false,
			timer: ''
		}
	},

	created() {
		ymaps.ready(this.initYMap)

		this.$root.$on('hideModalUsingBtn', this.hideAd)
	},


	methods: {
		initYMap() {
			let then = this,
					mapContainer = document.getElementById('y-map')

			ymaps.geocode(then.location, { results: 1 }).then(res => {
				let firstGeoObject = res.geoObjects.get(0),
						coords = firstGeoObject.geometry.getCoordinates(),
						bounds = firstGeoObject.properties.get('boundedBy'),
						mapState = ymaps.util.bounds.getCenterAndZoom(
							bounds,
							[mapContainer.offsetWidth, mapContainer.offsetHeight]
						)

				then.createMap(mapState)
				then.getItems(bounds)
			})

			// Создание карты с местоположением пользователя
			/*ymaps.geolocation.get().then(function(res) {
				let mapContainer = document.getElementById('y-map'),
						bounds = res.geoObjects.get(0).properties.get('boundedBy'),
						// Рассчитываем видимую область для текущей положения пользователя.
						mapState = ymaps.util.bounds.getCenterAndZoom(
							bounds,
							[mapContainer.offsetWidth, mapContainer.offsetHeight]
						);

				then.createMap(mapState);
				then.getItems(bounds);

			}, function(e) {
				// Если местоположение невозможно получить, то просто создаем карту.
			});*/
		},

		createMap(state) {
			let then = this

			state.zoom = 11
			state.controls = []

			this.myMap = new ymaps.Map('y-map', state)

			this.myMap.events.add('boundschange', e => { then.updateItems(e) });
		},

		getItems(bounds) {
			this.loading = true

			let then = this,
					url = '/api/share/coords?coords[]=' + bounds[0][0]
					+'&coords[]='+ bounds[0][1]
					+'&coords[]='+ bounds[1][0]
					+'&coords[]='+ bounds[1][1]
					+ '&' + window.location.search.replace('?','')

			this.$http.get(url).then(response => {
				let data = response.data,
						myCollection = new ymaps.GeoObjectCollection({}, {
							preset: 'islands#blackDotIconWithCaption',
							iconCaptionMaxWidth: '140',
							iconColor: '#3f51b5'
						});

				data.forEach((item, i) => {
					let test = new ymaps.Placemark([item.latitude, item.longitude], {
						adId: item.ad_id,
						iconCaption: item.pay + ' руб.'
					})

					test.events.add('click', (e) => {
						then.getAd(e.get('target').properties.get('adId'))
					});

					myCollection.add(test)
				})

				this.myMap.geoObjects.removeAll()
				this.myMap.geoObjects.add(myCollection)
				this.loading = false
				// Устанавливаем карте центр и масштаб так, чтобы охватить коллекцию целиком.
				// myMap.setBounds(myCollection.getBounds());
			}, error => {
				console.error(error)
			})
		},

		getAd(adId) {
			if (!adId) return
			this.loading = true

			this.$http.get('/api/share/'+adId).then(response => {
				this.ad = response.data
				this.loading = false
			}, error => {
				console.error(error)
			})
		},

		updateItems(e) {
			let then = this
			then.timer = then.timer || setTimeout(function() {
				then.timer = null
				if (e.get('newBounds') != e.get('oldBounds')) {
					then.getItems(e.get('newBounds'));
				}
			}, 500);
		},

		setZoom(val) {
			let zoom = this.myMap.getZoom()
			this.myMap.setZoom(zoom + val, {checkZoomRange: true, duration: 250})
		},

		setGeolocation() {
			let then = this
			ymaps.geolocation.get({
			    // Выставляем опцию для определения положения по ip
			    provider: 'auto',
	    		// Карта автоматически отцентрируется по положению пользователя.
			    mapStateAutoApply: true
			}).then(function (result) {
			    then.myMap.geoObjects.add(result.geoObjects);
			});
		},

		hideAd() {
			this.ad = []
		}
	},
}
</script>

<style lang="scss">
@import 'resources/assets/sass/_variables.scss';

.masonry-filters_on-map {
	margin-bottom: 20px;
	border-radius: 4px;
	pointer-events: auto;
}
</style>

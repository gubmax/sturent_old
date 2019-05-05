<template lang="html">
	<div class="form-group_field" ref='field'>
		<address-suggest ref='address' v-model="address" :one-string="true"></address-suggest>

		<span class="msg-error">{{ error }}</span>

		<div class="btns-group">
			<span class="btn btn_default btn_with-icon" v-ripple @click="showYMap">
				<i class="btn__icon">
					<svg viewBox="0 0 24 24" class="sr-icon sr-icon_secondary">
						<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"></path>
					</svg>
				</i>
				<span>Указать на карте</span>
			</span>
		</div>

		<modal-block v-show="show">
			<div class="modal-block__header_full-window">
				<span class="modal-block__header-text">Адрес на карте</span>
				<i class="modal-block__btn" v-ripple @click="hideYMap">
					<svg viewBox="0 0 24 24" class="sr-icon sr-icon_primary">
						<path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path>
					</svg>
				</i>
			</div>
			<div id="y-map">
				<div class="y-map__header">
					<div class="msg msg-info y-map__msg">
						<i class="msg-info__icon">
							<svg viewBox="0 0 24 24" class="sr-icon sr-icon_info">
								<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path>
							</svg>
						</i>
						<span>Укажите местоположение с помощью метки на карте.</span>
					</div>
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
			</div>
		</modal-block>
	</div>
</template>

<script>
export default {
	data() {
		return {
			show: false,
			myMap: '',
			address: '',
			error: ''
		}
	},

	watch: {
		address: function() {
			this.checkAddress()
		}
	},
	methods: {
		checkAddress() {
			if(!this.address) return

			let field = this.$refs.field,
					address = this.address,
					then = this

			// Геокодируем введённые данные.
			ymaps.geocode(address, ['jsonp']).then(function(res) {
				let obj = res.geoObjects.get(0),
						error

				if (obj) {
					switch (obj.properties.get('metaDataProperty.GeocoderMetaData.precision')) {
						case 'exact':
							break;
						case 'number':
						case 'near':
						case 'range':
							error = 'Неточный адрес, yточните номер дома';
						break;
						case 'street':
							error = 'Неполный адрес, yточните номер дома';
							break;
						case 'other':
						default:
							error = 'Неточный адрес, требуется уточнение';
					}
				}
				else
					error = 'Адрес не найден';

				// Если геокодер возвращает пустой массив или неточный результат, то показываем ошибку.
				if (error) showError(error)
				else showResult(obj)
			},
			function(e) {
				console.log(e)
			})

			function showError(msg) {
				field.classList.add('has-error')
				then.error = msg
			}

			function showResult(obj) {
				let coords = obj.geometry._coordinates

				field.classList.remove('has-error')
				then.error = ''
			}
		},

		showYMap() {
			this.show = true
			ymaps.ready(this.initYMap)
		},

		hideYMap() {
			this.show = false

			this.checkAddress()

			if (this.myMap) {
				this.myMap.destroy()
				this.myMap = null
			}
		},

		initYMap() {
			let then = this,
					myPlacemark,
				 	mapContainer = document.getElementById('y-map')

			// Создание метки.
			function createPlacemark(coords) {
				return new ymaps.Placemark(coords, {
					iconCaption: 'Поиск...'
				}, {
					preset: 'islands#blackDotIconWithCaption',
					iconColor: '#3f51b5',
					draggable: true
				});
			}

			// Центрируем карту.
			if (this.address) {
				// Определяем координаты по адресу (прямое геокодирование).
				ymaps.geocode(this.address, {
					results: 1
				}).then(function(res) {
					// Выбираем первый результат геокодирования.
					let firstGeoObject = res.geoObjects.get(0),
							// Координаты геообъекта.
							coords = firstGeoObject.geometry.getCoordinates(),
							// Область видимости геообъекта.
							bounds = firstGeoObject.properties.get('boundedBy'),
							// Масштабируем карту на область видимости геообъекта.
							mapState = ymaps.util.bounds.getCenterAndZoom(
								bounds,
								[mapContainer.offsetWidth, mapContainer.offsetHeight]
							);

					createMap(mapState);
					setPlacemark(coords);
				})
			}
			else {
				// Создание карты с местоположением пользователя
				ymaps.geolocation.get().then(function(res) {
					let bounds = res.geoObjects.get(0).properties.get('boundedBy'),
							// Рассчитываем видимую область для текущей положения пользователя.
							mapState = ymaps.util.bounds.getCenterAndZoom(
								bounds,
								[mapContainer.offsetWidth, mapContainer.offsetHeight]
							);

					createMap(mapState);
				}, function(e) {
					// Если местоположение невозможно получить, то просто создаем карту.
					createMap({
						center: [55.751574, 37.573856],
						zoom: 6
					});
				});
			}

			function createMap(state) {
				state.controls = []

				then.myMap = new ymaps.Map('y-map', state);

				// Слушаем клик на карте.
				then.myMap.events.add('click', function(e) {
					let coords = e.get('coords');

					setPlacemark(coords);
				});
			}

			function setPlacemark(coords) {
				// Если метка уже создана – просто передвигаем ее.
				if (myPlacemark) {
					myPlacemark.geometry.setCoordinates(coords);
				}
				// Если нет – создаем.
				else {
					myPlacemark = createPlacemark(coords);
					then.myMap.geoObjects.add(myPlacemark);
					// Слушаем событие окончания перетаскивания на метке.
					myPlacemark.events.add('dragend', function () {
						getAddress(myPlacemark.geometry.getCoordinates());
					});
				}
				getAddress(coords);
			}

			// Определяем адрес по координатам (обратное геокодирование).
			function getAddress(coords) {
				myPlacemark.properties.set('iconCaption', 'поиск...');
				ymaps.geocode(coords).then(function(res) {
					let firstGeoObject = res.geoObjects.get(0)

					then.$refs.address.str = firstGeoObject.getAddressLine()

					myPlacemark.properties
						.set({
							// Формируем строку с данными об объекте.
							iconCaption: [
								// Название населенного пункта или вышестоящее административно-территориальное образование.
								firstGeoObject.getLocalities().length ? firstGeoObject.getLocalities() : firstGeoObject.getAdministrativeAreas(),
								// Получаем путь до топонима, если метод вернул null, запрашиваем наименование здания.
								firstGeoObject.getThoroughfare() || firstGeoObject.getPremise()
							].filter(Boolean).join(', '),
						});
				});
			}
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
		}
	}
}
</script>

<style lang="scss">
@import 'resources/assets/sass/_variables.scss';

.y-map__msg {
	margin: 0 0 20px 20px;
	box-shadow: 0 1px 2px 0 $shadow;
}
</style>

<template lang="html">
	<div class="send-complaint" v-if="!send">
		<div class="modal-block__header">
			<i class="send-complaint__back-btn modal-block__btn" v-show="depth" v-ripple @click="prevItem">
				<svg viewBox="0 0 24 24" class="sr-icon sr-icon_primary">
					<path d="M21 11H6.83l3.58-3.59L9 6l-6 6 6 6 1.41-1.41L6.83 13H21z"/>
				</svg>
			</i>
			<span class="modal-block__header-text">{{ currentData.title }}</span>
			<i class="modal-block__btn" v-ripple @click="hidePopup">
				<svg viewBox="0 0 24 24" class="sr-icon sr-icon_primary">
					<path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path>
				</svg>
			</i>
		</div>
		<ul class="drop-down_list">
			<li v-for="item in currentData.items">
				<span class="drop-down_list__item" @click.stop="selectItem(item, currentData.items.indexOf(item))">{{ item.title }}</span>
			</li>
		</ul>
	</div>

	<div class="send-complaint__complete" v-else>
		<div class="modal-block__btn_absolute">
			<i class="modal-block__btn" v-ripple @click="hidePopup">
				<svg viewBox="0 0 24 24" class="sr-icon sr-icon_primary">
					<path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path>
				</svg>
			</i>
		</div>
		<i class="send-complaint__icon">
			<svg viewBox="0 0 24 24" class="sr-icon sr-icon_is-active">
				<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"></path>
			</svg>
		</i>
		<span class="send-complaint__complete-title">Спасибо, проверим!</span>
		<span class="send-complaint__complete-text">Cообщение о нарушении отправлено.</span>
	</div>
</template>

<script>
export default {
	data() {
		return {
			data: {
				'title': 'О чём хотите сообщить?',
				'items': [
					{
						'title': 'Фотографии',
						'items': [
							{ 'title': 'На фотографиях видны чужие водяные знаки или следы их удаления' },
							{ 'title': 'На фотографиях дополнительная реклама' },
							{ 'title': 'Посторонние картинки вместо фотографий' },
							{ 'title': 'На фотографиях ссылки, телефоны, e-mail, надписи' },
							{ 'title': 'В объявлении использованы фотографии другого объекта из Интернета' },
							{ 'title': 'Такие же фотографии использованы в объекте с другим адресом или другим количеством комнат и т.п.' },
							{ 'title': 'Фотографии используются без согласия автора' },
						]
					},
					{
						'title': 'Адрес',
						'items': [
							{ 'title': 'Город, область или регион неверные' },
							{ 'title': 'Улица, дом или район неверные' },
						]
					},
					{ 'title': 'Некорректное описание' },
					{ 'title': 'Объект не существует или объявление неактуально' },
					{ 'title': 'Невозможно связаться' },
					{ 'title': 'Объявление-дубль' },
				],
			},
			currentData: {},
			previousData: {},
			depth: 0,
			send: false,
		}
	},

	created() {
		this.resetComplaint()
	},

	methods: {
		selectItem(item, index) {
			if (item.items) {
				this.depth += 1
				this.previousData = this.currentData
				this.currentData = item
			}
			else
				this.send = true
		},

		prevItem() {
			this.depth -= 1
			this.currentData = this.previousData
		},

		resetComplaint() {
			if (!this.send)
				this.currentData = this.data
		},

		hidePopup() {
			this.$parent.$parent.$emit('hideModal')
		}
	},
}
</script>

<style lang="scss">
@import 'resources/assets/sass/_variables.scss';

.send-complaint__complete {
	position: relative;
	box-sizing: border-box;
	display: flex;
	flex-flow: column nowrap;
	align-items: center;
	justify-content: center;
	width: 100%;
	height: 100%;
	padding: 40px 16px;
	text-align: center;
}
.send-complaint__title {
	color: $primary;
	line-height: 1em;
	font-size: 16px;
	font-weight: 500;
}
.send-complaint__complete-title {
	display: block;
	margin-bottom: 12px;
	color: $accent;
	font-size: 24px;
}
.send-complaint__complete-text {
	color: $primary;
	font-size: 16px;
	font-weight: 500;
}
.send-complaint__icon {
  display: block;
  width: 100px;
  height: 100px;
}
.send-complaint__back-btn {
	margin-right: 16px;
}
</style>

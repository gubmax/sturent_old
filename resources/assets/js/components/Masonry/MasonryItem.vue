<template lang="html">
	<div :class="{ 'not-relevant': !item.relevance }">
		<div class="ad__header">
			<span class="ad__header__avatar" :class="{ 'is-link': userId != item.user_id }" @click.stop="showProfile">
				<img :src="'/images/avatar/'+item.user.avatar+'_small.jpg'" v-if="item.user.avatar">
				<img src="/images/avatar/avatar_small.jpg" v-else>
			</span>

			<div class="ad__header__info">
				<span class="ad__header__name" :class="{ 'is-link': userId != item.user_id }" @click.stop="showProfile">{{ item.user.name }}</span>
				<span class="ad__header__date">{{ formatDate(new Date(item.created_at)) }}</span>
			</div>

			<div class="ad__header__right-container">
				<span class="tooltip" description="В избранное" v-if="userId != item.user.id">
					<div class="ad__header__favorites-btn" :class="{ 'in-favorites': inFavorites }" v-ripple @click="switchFavorites">
						<i>
							<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
								<path d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z"/>
							</svg>
						</i>
					</div>
					<div class="ad__header__favorites-btn-bg">
						<i class="ad__header__favorites-btn-icon">
							<svg class="sr-icon sr-icon_light" viewBox="0 0 24 24">
								<path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path>
							</svg>
						</i>
					</div>
				</span>
				<span class="tooltip" description="Ссылка" v-if="!location && page == 'share'">
					<a class="ad__header__btn" :href="'/ad?'+page+'&id='+item.ad_id" target="_blank" rel="noopener" v-ripple>
						<i class="btn__icon">
							<svg viewBox="0 0 24 24" class="sr-icon sr-icon_primary">
								<path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"></path>
							</svg>
						</i>
					</a>
				</span>

				<span class="ad__header__btn" v-if="!location && page == 'share'" v-ripple @click="hideModalUsingBtn">
					<i class="btn__icon">
						<svg viewBox="0 0 24 24" class="sr-icon sr-icon_primary">
							<path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path>
						</svg>
					</i>
				</span>

				<drop-down-item v-else>
					<i class="ad__header__btn" slot="activator" v-ripple>
						<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
							<path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
						</svg>
					</i>

					<ul class="drop-down_list">
						<li v-if="userId == item.user.id">
							<span class="drop-down_list__item" @click="switchRelevance">
								<i class="list-icon">
									<svg class="sr-icon sr-icon_is-active" viewBox="0 0 24 24" v-if="item.relevance">
										<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
									</svg>
								</i>
								<span v-if="item.relevance">Актуально</span>
								<span v-else>Не актуально</span>
							</span>
						</li>
						<li v-if="userId != item.user.id">
							<span class="drop-down_list__item" @click.stop="showProfile">
								<i class="list-icon">
									<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
										<path d="M20 0H4v2h16V0zM4 24h16v-2H4v2zM20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm-8 2.75c1.24 0 2.25 1.01 2.25 2.25s-1.01 2.25-2.25 2.25S9.75 10.24 9.75 9 10.76 6.75 12 6.75zM17 17H7v-1.5c0-1.67 3.33-2.5 5-2.5s5 .83 5 2.5V17z"/>
									</svg>
								</i>
								<span>Контактные данные</span>
							</span>
						</li>
						<li v-if="shareSupport && userId != item.user.id" @click="share">
							<span class="drop-down_list__item">
								<i class="list-icon">
									<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
										<path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/>
									</svg>
								</i>
								<span>Поделиться</span>
							</span>
						</li>
						<li class="drop-down_list__separator" v-if="userId == item.user.id"></li>
						<li>
							<a :href="'/ad?'+page+'&id='+item.ad_id" target="_blank" rel="noopener" class="drop-down_list__item">
								<i class="list-icon">
									<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
										<path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"></path>
									</svg>
								</i>
								<span>Ссылка</span>
							</a>
						</li>
						<li v-if="userId == item.user.id">
							<a class="drop-down_list__item" :href="'/' + page + '/edit/' + item.ad_id">
								<i class="list-icon">
									<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
										<path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"></path>
									</svg>
								</i>
								<span>Редактировать</span>
							</a>
						</li>
						<li v-if="userId == item.user.id">
							<span class="drop-down_list__item" @click.stop="showModal = 'remove'">
								<i class="list-icon">
									<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
										<path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
									</svg>
								</i>
								<span>Удалить</span>
							</span>
						</li>
						<li v-if="userId != item.user.id">
							<span class="drop-down_list__item" @click.stop="showModal = 'complaint'">
								<i class="list-icon">
									<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
										<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
									</svg>
								</i>
								<span>Сообщить о нарушении</span>
							</span>
						</li>
					</ul>
				</drop-down-item>
			</div>
		</div>

		<img-slider
			v-if="item.imgs.length > 0"
			:url="'../images/'+ page +'/'"
			:lazy-load="inMasonry"
			:imgs="item.imgs">
		</img-slider>

		<span class="ad__location">
			<span class="ad__location-string" v-html="formatLocation()"></span>
			<span class="tooltip" description="На карте" v-if="location">
				<i class="ad__location-btn" @click.stop="showMap" v-ripple>
					<svg class="sr-icon sr-icon_is-active" viewBox="0 0 24 24">
						<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"></path>
					</svg>
				</i>
			</span>
		</span>

		<div class="ad__info-wrapper">
			<div class="ad__info_post">
				<span class="ad__info-title">Кого ищут</span>
				<span class="ad__info-text" v-if="item.looking == 0">Женщину</span>
				<span class="ad__info-text" v-else-if="item.looking == 1">Мужчину</span>
				<span class="ad__info-text" v-else-if="item.looking == 2">Пару</span>
				<span class="ad__info-text" v-else-if="item.looking == 3">Компанию</span>
				<span class="ad__info-text" v-else>Кого-нибудь</span>
			</div>
			<div class="ad__info_post">
				<span class="ad__info-title">Хотят арендовать</span>
				<span class="ad__info-text">{{ formatRent(item.rent) }}</span>
			</div>
			<div class="ad__info_post">
				<span class="ad__info-title">Арендная плата</span>
				<span class="ad__info-text">{{ item.pay + ' руб.' }}</span>
			</div>
			<div class="ad__info_post">
				<span class="ad__info-title">Залог</span>
				<span class="ad__info-text" v-if="item.pledge == null">Без залога</span>
				<span class="ad__info-text" v-else>{{ item.pledge }} руб.</span>
			</div>
			<div class="ad__info_post">
				<span class="ad__info-title">Жильцов в кв.</span>
				<span class="ad__info-text">{{ item.tenants }}</span>
			</div>
			<div class="ad__info_post" v-if="item.tags.length">
				<span class="ad__info-title">Особенности</span>
				<span class="ad__tag tooltip" v-for="tag in item.tags" :description="tag.name">
					<i class="btn__icon">
						<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24" v-if="tag.tag_id == 1">
							<path d="M13 2v8h8c0-4.42-3.58-8-8-8zm6.32 13.89C20.37 14.54 21 12.84 21 11H6.44l-.95-2H2v2h2.22s1.89 4.07 2.12 4.42c-1.1.59-1.84 1.75-1.84 3.08C4.5 20.43 6.07 22 8 22c1.76 0 3.22-1.3 3.46-3h2.08c.24 1.7 1.7 3 3.46 3 1.93 0 3.5-1.57 3.5-3.5 0-1.04-.46-1.97-1.18-2.61zM8 20c-.83 0-1.5-.67-1.5-1.5S7.17 17 8 17s1.5.67 1.5 1.5S8.83 20 8 20zm9 0c-.83 0-1.5-.67-1.5-1.5S16.17 17 17 17s1.5.67 1.5 1.5S17.83 20 17 20z"/>
						</svg>
						<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24" v-else-if="tag.tag_id == 2">
							<circle cx="4.5" cy="9.5" r="2.5"/>
							<circle cx="9" cy="5.5" r="2.5"/>
							<circle cx="15" cy="5.5" r="2.5"/>
							<circle cx="19.5" cy="9.5" r="2.5"/>
							<path d="M17.34 14.86c-.87-1.02-1.6-1.89-2.48-2.91-.46-.54-1.05-1.08-1.75-1.32-.11-.04-.22-.07-.33-.09-.25-.04-.52-.04-.78-.04s-.53 0-.79.05c-.11.02-.22.05-.33.09-.7.24-1.28.78-1.75 1.32-.87 1.02-1.6 1.89-2.48 2.91-1.31 1.31-2.92 2.76-2.62 4.79.29 1.02 1.02 2.03 2.33 2.32.73.15 3.06-.44 5.54-.44h.18c2.48 0 4.81.58 5.54.44 1.31-.29 2.04-1.31 2.33-2.32.31-2.04-1.3-3.49-2.61-4.8z"/>
						</svg>
						<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24" v-else-if="tag.tag_id == 3">
							<path d="M2 16h15v3H2zm18.5 0H22v3h-1.5zM18 16h1.5v3H18zm.85-8.27c.62-.61 1-1.45 1-2.38C19.85 3.5 18.35 2 16.5 2v1.5c1.02 0 1.85.83 1.85 1.85S17.52 7.2 16.5 7.2v1.5c2.24 0 4 1.83 4 4.07V15H22v-2.24c0-2.22-1.28-4.14-3.15-5.03zm-2.82 2.47H14.5c-1.02 0-1.85-.98-1.85-2s.83-1.75 1.85-1.75v-1.5c-1.85 0-3.35 1.5-3.35 3.35s1.5 3.35 3.35 3.35h1.53c1.05 0 1.97.74 1.97 2.05V15h1.5v-1.64c0-1.81-1.6-3.16-3.47-3.16z"/>
						</svg>
						<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24" v-else-if="tag.tag_id == 4">
							<path d="M21 5V3H3v2l8 9v5H6v2h12v-2h-5v-5l8-9zM7.43 7L5.66 5h12.69l-1.78 2H7.43z"/>
						</svg>
					</i>
				</span>
			</div>
		</div>

		<div class="masonry__item-text">
			<p>{{ item.text }}</p>
		</div>

		<modal-block v-if="showModal == 'remove'">
			<div class="modal-block" v-click-outside="hideModal">
				<div class="modal-block__header">Удалить выбранное объявление?</div>
				<div class="modal-block__content">Выбранное объявление будет безвозвратно удалено.</div>
				<div class="modal-block__btns">
					<span class="btn btn_default" v-ripple @click="hideModal">Закрыть</span>
					<span class="btn btn_link" v-ripple @click="removeItem">Удалить</span>
				</div>
			</div>
		</modal-block>

		<modal-block v-if="showModal == 'map'">
			<div class="modal-block__header_full-window">
				<span class="modal-block__header-text">Адрес на карте</span>
				<i class="modal-block__btn" v-ripple @click="hideMap">
					<svg viewBox="0 0 24 24" class="sr-icon sr-icon_primary">
						<path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path>
					</svg>
				</i>
			</div>
			<div id="y-map">
				<div class="loader ad__y-map-loader" v-if="loading">
					<div class="loader__dot_one"></div>
					<div class="loader__dot_two"></div>
					<div class="loader__dot_three"></div>
				</div>

				<div class="y-map__header" v-else>
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

		<modal-block v-if="showModal == 'complaint'">
			<div class="modal-block" v-click-outside="hideModal">
				<send-complaint></send-complaint>
			</div>
		</modal-block>

		<modal-block v-if="showModal == 'profile'">
			<div class="modal-block" v-click-outside="hideModal">
				<div class="user-info user-info_header">
					<div class="modal-block__btn_absolute">
						<i class="modal-block__btn" v-ripple @click="hideModal">
							<svg viewBox="0 0 24 24" class="sr-icon sr-icon_light">
								<path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path>
							</svg>
						</i>
					</div>
					<img class="user-info_header-bg" :src="'/images/avatar/'+item.user.avatar+'.jpg'" v-if="item.user.avatar">
					<img class="user-info_header-bg" src="/images/avatar/avatar.jpg" v-else>
					<a class="user-info__img" :href="'/profile/'+item.user.id">
						<img :src="'/images/avatar/'+item.user.avatar+'.jpg'" v-if="item.user.avatar">
						<img src="/images/avatar/avatar.jpg" v-else>
					</a>
					<a class="user-info__name" :href="'/profile/'+item.user.id">{{ item.user.name }}</a>
				</div>

				<span class="ad__contact-info">Контактные данные:</span>

				<ul class="ad__contact-data">
					<div class="loader" v-show="loading">
						<div class="loader__dot_one"></div>
						<div class="loader__dot_two"></div>
						<div class="loader__dot_three"></div>
					</div>
					<li v-for="contact in contactData">
						<a class="ad__info-link" :href="contact.contact_data.link+contact.value" v-ripple>
							<i class="list-icon" v-if="contact.contact_data_id == 0">
								<svg class="sr-icon sr-icon_secondary" viewBox="0 0 24 24">
									<path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
								</svg>
							</i>
							<i class="list-icon" v-else-if="contact.contact_data_id == 1">
								<svg class="sr-icon sr-icon_secondary" viewBox="0 0 24 24">
									<path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
								</svg>
							</i>
							<i class="list-icon" v-else-if="contact.contact_data_id == 2">
								<svg class="sr-icon sr-icon_secondary" viewBox="0 0 24 24">
									<path d="m0.082828,5.354315c-0.163359,-0.996839 4.655725,-0.379655 4.655725,-0.379655c0,0 2.797519,7.025341 3.900191,7.025341c1.102672,0 0.571756,-5.957299 0.571756,-5.957299c0,0 -1.36813,-0.356014 -1.36813,-0.901902c0,-0.545888 1.36813,-0.925636 1.36813,-0.925636c0,0 3.540627,0 4.02271,0c0.482083,0 0.673934,0.783231 0.673935,1.305384c0,0.522154 0,6.384516 0,6.384516l1.102672,0c0,0 0.981556,-1.577636 1.756027,-3.180391c0.774471,-1.602755 1.715267,-3.750013 1.715267,-3.750013l4.676145,0c0,0 0.857634,0.023549 0.755534,0.735669c-0.138897,0.968781 -3.249906,5.569356 -3.900191,7.144012c-0.490076,1.186713 2.058765,2.792936 3.144656,4.509509c0.704759,1.114079 0.755534,2.420987 0.755534,2.420987l-5.554119,0l-2.777179,-3.227952c0,0 -0.571676,-0.23725 -1.020992,0.094937c-0.449316,0.332187 -0.469657,0.996839 -0.469657,0.996839l0,2.136176l-4.349427,0c0,0 -3.328435,-1.139244 -6.146374,-6.123439c-2.817939,-4.984194 -3.348855,-7.310245 -3.512214,-8.307084z"></path>
								</svg>
							</i>
							<div class="ad__contact-data__item">
								<span class="ad__info-title">{{ contact.contact_data.name }}</span>
								<span>{{ contact.value }}</span>
							</div>
						</a>
					</li>
				</ul>

				<p class="ad__note">Пожалуйста, сообщите, что нашли это объявление на STURENT</p>
			</div>
		</modal-block>
	</div>
</template>

<script>
export default {
	props: {
		item: {
			type: Object,
			requied: true
		},
		page : {
			type: String,
			requied: true
		},
		userId: {
			type: Number,
			requied: true
		},
		clearing: {
			type: Boolean,
			default: false
		},
		location: {
			type: Boolean,
			default: true
		}
	},

	data() {
		return {
			inFavorites: false,
			showModal: '',
			shareSupport: true,
			contactData: [],
			loading: false,
			inMasonry: false,
			myMap: {}
		}
	},

	created() {
		this.inFavorites = this.item.in_favorites

		if (this.$parent.$parent && this.$parent.$parent.$options._componentTag == 'masonry')
			this.inMasonry = true

		this.$on('hideModal', this.hideModal)

		if (!navigator.share)
  		this.shareSupport = false
	},

	methods: {
		formatDate(date) {
		  let monthNames = [
		    "января", "февраля", "марта", "апреля", "мая", "июня",
				"июля", "августа", "сентября", "октября","ноября", "декабря"
		  ]

		  let day = date.getDate(),
		  		monthIndex = date.getMonth(),
					hours = date.getHours(),
					minutes = date.getMinutes()

			 if (minutes < 10)
			 	minutes = "0"+minutes

		  return day+' '+monthNames[monthIndex]+' в '+hours+':'+minutes;
		},

		formatLocation() {
			let item = this.item,
					loc = {
						'region': item.region,
						'city': item.city,
						'settlement': item.settlement,
						'district': item.district,
						'street': item.street,
						'house': item.house
					},
					str = '',
					depth = ''

			for(let prop in loc) {
				if (!loc[prop]) continue

				if (depth.length) depth += '&'
				depth += prop + '=' + loc[prop]

				if (prop != 'house')
				  str += '<a class="ad__location-link" href="/'+ this.page +'?'+depth+'">'+loc[prop]+',</a>'
				else
					str += '<span>'+loc[prop]+'</span>'
			}

			if (!loc.house) str = str.slice(0, -5) + '</a>'

			return str
		},

		formatRent(rent) {
			let rentNames = [
				'Комнату', 1, 2, 3, 4, 'Часть дома', 'Дом',
			]

			if (!Array.isArray(rent)) {
				if (Number.isInteger(rentNames[rent]))
					return rentNames[rent] + '-комн. кв.'

				return rentNames[rent]
			}

			let str = [],
					roomsKey

			rent.forEach((val, i) => {
				str[i] = rentNames[val.rent_id]
				if (Number.isInteger(str[i])) roomsKey = i
			})

			str[roomsKey] += '-комн. кв.'

			return str.join(', ')
		},

		switchFavorites() {
			let ad_id = this.item.ad_id,
					resourceUrl = '/api/favorites/switch/' + ad_id + '?' + this.page

			this.$http.get(resourceUrl).then(function(response) {
				let favorites_count = response.data.favorites_count,
						navbar = this.$root.$refs.navbarFavCounter,
						fixedNavbar = this.$root.$refs.fixedNavbarFavCounter

				if (favorites_count > 0) {
					navbar.innerHTML = favorites_count
					fixedNavbar.innerHTML = favorites_count
					navbar.classList.remove('is-hide')
					fixedNavbar.classList.remove('is-hide')
				}
				else {
					navbar.classList.add('is-hide')
					fixedNavbar.classList.add('is-hide')
				}

				if (this.clearing) {
					this.$root.$emit('removeMasonryItem', ad_id)
					this.$root.$emit('setMasonryItemsCount', {'tab_name': this.page, 'count': -1})
				}
				else
					this.inFavorites = !this.inFavorites
			}, function(error) {
				console.error(error)
			})
		},

		switchRelevance() {
			let ad_id = this.item.ad_id,
			 		resourceUrl = '/api/'+ this.page +'/relevance/'+ad_id

			this.$http.get(resourceUrl).then(function(response) {
				let json = response.data,
						currentItemId = ad_id,
						currentUserId = this.userId

				this.item.relevance = !this.item.relevance

				if (this.inMasonry)
					this.$parent.$parent.$emit('swithOffOtherRelevance', ad_id, this.item.user_id)
			}, function(error) {
				console.error(error)
			})
		},

		hideModal() {
			this.showModal = false
		},

		hideModalUsingBtn () {
			this.$root.$emit('hideModalUsingBtn')
		},

		removeItem() {
			Vue.http.headers.common['X-CSRF-TOKEN'] = document.getElementsByName('_token')[0].getAttribute('value');

			let id = this.item.ad_id

			this.$http.get('/api/'+ this.page +'/del/'+id).then(response => {
				this.showModal = false

				if (this.inMasonry) {
					this.$root.$emit('removeMasonryItem', id)
					this.$root.$emit('setMasonryItemsCount', {'tab_name': this.page, 'count': -1})
				}
			},error => {
				console.error(error)
			})
		},

		showProfile() {
			if (this.userId == this.item.user_id) {
				return
			}

			this.showModal = 'profile'
			if (this.contactData.length) return

			this.loading= true

			this.$http.get('/api/profile/'+this.item.user_id+'/contact-data').then(response => {
				this.contactData = response.data
				this.loading= false
			}, error => {
				console.error(error)
			})
		},

		showMap() {
			let then = this
			this.loading = true
			this.showModal = 'map'

			if (typeof ymaps == 'undefined') {
				let script = document.createElement('script')
				script.type = 'text/javascript'
				script.setAttribute('src', '//api-maps.yandex.ru/2.1/?lang=ru_RU')
				document.body.appendChild(script)

				script.onload = () => {
					ymaps.ready(init);
				}
			}
			else
				ymaps.ready(init);

			function init() {
				let lat = then.item.latitude,
						long = then.item.longitude

				then.myMap = new ymaps.Map('y-map', {
					center: [lat, long],
					zoom: 16,
					controls: []
				})

				then.myMap.geoObjects.add(new ymaps.Placemark([lat, long], {
						iconCaption: then.item.pay + ' руб.'
					}, {
						preset: 'islands#blackDotIconWithCaption',
						iconColor: '#3f51b5'
				}))

				then.loading = false
			}
		},

		hideMap() {
			this.myMap.destroy()
			this.myMap = null
			this.showModal = false
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
			}).then(result => {
			    then.myMap.geoObjects.add(result.geoObjects);
			});
		},

		share() {
			if (navigator.share) {
			  navigator.share({
		      title: 'Web Fundamentals',
		      text: 'Check out Web Fundamentals — it rocks!',
		      url: 'https://developers.google.com/web',
			  })
		    .then(() => console.log('Successful share'))
		    .catch((error) => console.log('Error sharing', error));
			}
		},
	},
}
</script>

<style lang="scss">
@import 'resources/assets/sass/_variables.scss';

.ad__header {
	position: relative;
	display: flex;
	flex-direction: row;
	align-items: center;
	padding: 12px 16px 12px;
}
.ad__header__avatar {
	display: block;
	width: 52px;
	height: 52px;
  min-width: 52px;
	margin-right: 16px;
	border-radius: 50%;
	overflow: hidden;
  box-shadow: 0 1px 4px $shadow;
}
.ad__header__info {
	flex: 1;
}
.ad__header__name {
	display: block;
  margin-right: 16px;
	font-size: 16px;
	font-weight: 500;
  text-decoration: none;
	color: $primary;
}
.ad__header__avatar.is-link,
.ad__header__name.is-link {
  color: $accent;
	cursor: pointer;
}
.ad__header__date {
	display: inline-block;
	color: $secondary;
}
.ad__header__right-container {
	display: flex;
	box-sizing: border-box;
}
.ad__header__favorites-btn,
.ad__header__btn,
.ad__location-btn {
	display: block;
	vertical-align: middle;
	width: 24px;
	height: 24px;
	margin-left: 8px;
	padding: 8px;
	color: $secondary;
	border-radius: 50%;
	transition: background .4s;
	cursor: pointer;
}
.ad__header__favorites-btn-bg {
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	height: 100%;
	margin-left: 8px;
	padding: 8px;
	box-sizing: border-box;
	pointer-events: none;
}
.ad__header__favorites-btn-bg {
	background: linear-gradient(120deg, $red, $pink);
	border-radius: 50%;
	transform: scale(0);
	transition: transform .2s ease-in;
}
.ad__header__favorites-btn.in-favorites ~ .ad__header__favorites-btn-bg {
	animation: scale .4s;
	transform: scale(1);
	opacity: 1;
}
.ad__header__favorites-btn-icon {
	opacity: 0;
	transition: opacity .4s ease-in;
}
.ad__header__favorites-btn.in-favorites ~ .ad__header__favorites-btn-bg > .ad__header__favorites-btn-icon {
	opacity: 1;
}
@keyframes scale {
  0% {
    transform: scale(0);
  }
  60% {
    transform: scale(1.25);
  }
	100% {
		transform: scale(1);
	}
}
.ad__tag {
	margin-right: 4px;
}
.ad__location {
	display: flex;
	margin: 0 16px;
	align-items: center;
	line-height: 1em;
	font-weight: 500;
	font-size: 16px;
	& > .tooltip { top: -10px; }
}
.ad__location-btn {
	margin-right: 4px;
}
.ad__location-string {
	margin-bottom: 16px;
}
.ad__location-link {
	margin-right: 8px;
	color: $black;
	text-decoration: none;
	transition: color .2s;
}
.ad__location-link:hover {
	color: $accent;
	cursor: pointer;
}
.ad__info-wrapper {
	display: flex;
	flex-flow: row wrap;
	padding: 0 16px;
}
.ad__info_post {
	box-sizing: border-box;
	flex-basis: 33.3%;
	padding-right: 12px;
	margin-bottom: 12px;
}
.ad__info_profile {
	flex-basis: 50%;
	margin-bottom: 16px;
}
.ad__info-title {
	display: block;
	color: $secondary;
	font-size: 11px;
	font-weight: 500;
	line-height: 1.5;
	text-transform: uppercase;
}
.ad__info-text,
.ad__info-link {
	font-size: 16px;
	font-weight: 500;
	line-height: 24px;
}
.ad__info-text {
  color: $primary;
}
.ad__info-link {
	display: flex;
	flex-flow: row nowrap;
	align-items: center;
	padding-left: 16px;
  color: $accent;
	text-decoration: none;
}
.ad__note {
	color: $secondary;
	padding: 16px;
	font-size: 13px;
	line-height: 1em;
}
.ad__contact-info {
	color: $primary;
	font-size: 16px;
	line-height: 2.5em;
	font-weight: 500;
	margin: 0 16px 16px;
}
.ad__contact-data {
	position: relative;
	border-top: 1px solid $border;
	border-bottom: 1px solid $border;
}
.ad__contact-data > li {
	list-style: none;
}
.ad__contact-data__item {
	flex: 1;
	padding: 10px 0;
	border-bottom: 1px solid $border;
}
.ad__contact-data > li:last-of-type .ad__contact-data__item {
	border-bottom: none;
}
.ad__y-map-loader {
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
}
</style>

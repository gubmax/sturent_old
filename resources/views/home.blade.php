@extends('layouts.app')

@section('main-header')
	<div class="home-page__header">
		<div class="home-page__section-block_left">
			<h1 class="home-page__mission page__block_with-padding">Помогаем искать соседей и недвижимость для совместной аренды</h1>
			<p class="home-page__text page__block_with-padding">У нас большой выбор объявлений по поиску соседей. Вы можете найти соседа или соседку, чтобы вместе снять комнату, арендовать квартиру или другое жилое помещение. Надеемся, что здесь вы найдете для себя подходящий вариант.</p>
		</div>

		<form class="home-page__form form" id="add-ad" method="GET" :action="'/' + (selectedInChanger == 0 ? 'neighbors' : selectedInChanger == 1 ? 'share' : 'map')">
			<div class="form-group_field">
				<form-select text="Хочу" :changer="true" :cleaner="false" :value="[0]">
					<select-option name="Найти соседей" :value="0"></select-option>
					<select-option name="Подселиться" :value="1"></select-option>
				</form-select>
			</div>

			<address-suggest class="form-group_field" :value="{{ json_encode($location) }}"
				placeholder="населенный пункт, улица, стр.">
				<div class="form-group_field">
					<input type="text"></input>
				</div>
				<div class="form-group_field">
					<input type="text"></input>
				</div>
			</address-suggest>

			<div class="form-group_field{{ $errors->has('rent') ? ' has-error' : '' }}">
				<form-select name="rent[]" text="Хочу арендовать" :multiple="true">
					<input type="text"></input>
					<select-option name="Комнату" value="0"></select-option>
					<select-option name="1-комн. кв." value="1"></select-option>
					<select-option name="2-комн. кв." value="2"></select-option>
					<select-option name="3-комн. кв." value="3"></select-option>
					<select-option name="4-комн. и более кв." value="4"></select-option>
					<select-option name="Часть дома" value="5"></select-option>
					<select-option name="Дом" value="6"></select-option>
				</form-select>
				@if ($errors->has('rent'))
					<div class="msg-error">{{ $errors->first('rent') }}</div>
				@endif
			</div>

			<div class="form__pay">
				<div class="form-group_field form__pay_from">
					<input type="text" id="pay_from" name="pay_from" placeholder="руб." v-field @if (isset($_GET['pay_from'])) {{ 'value='.$_GET['pay_from'] }} @endif>
					<label for="pay_from">Арендная плата, от</label>
				</div>

				<div class="form-group_field form__pay_to">
					<input type="text" id="pay_to" name="pay_to" placeholder="руб." v-field @if (isset($_GET['pay_to'])) {{ 'value='.$_GET['pay_to'] }} @endif>
					<label for="pay_to">Арендная плата, до</label>
				</div>
			</div>

			<div class="btns-group">
				<button type="submit" class="btn btn_primary" v-ripple>Показать</button>
				<a class="btn btn_link btn_with-icon" href="/map" v-show="selectedInChanger == 1" v-ripple>
					<i class="btn__icon">
						<svg viewBox="0 0 24 24" class="sr-icon sr-icon_is-active">
							<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"></path>
						</svg>
					</i>
					<span>На карте</span>
				</a>
			</div>
		<form>
	</div>
@endsection

@section('content')
	<h2 class="home-page__title page__block_with-padding">Что мы предлагаем:</h2>
  <div class="home-page__section">
		<div class="home-page__section-block_left page__block_with-padding">
			<div class="home-page__title-box">
				<h3 class="home-page__title">Удобный просмотр</h3>
			</div>
    	<p>На сайте вы найдёте обьявления по поиску соседей для совместной аренды жилых помещений. Информация в обьявлении, как правило, с фотографиями и контактной информацией хозяина.</p>
		</div>

		<div class="home-page__section-block">
			<div class="home-page__dummy">
				<span class="home-page__dummy__avatar">
					<img src="/images/avatar/avatar_small.jpg">
				</span>

				<div class="home-page__dummy__info">
					<span class="home-page__dummy__name"></span>
					<span class="home-page__dummy__date"></span>
				</div>

				<div class="home-page__dummy__right-container">
					<i class="home-page__dummy__btn">
						<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
							<path d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z"/>
						</svg>
					</i>
					<i class="home-page__dummy__btn">
						<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
							<path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path>
						</svg>
					</i>
				</div>
			</div>

			<div class="home-page__img-dummy">
				<i>
					<svg viewBox="0 0 24 24" class="sr-icon sr-icon_is-active">
						<path d="M22 16V4c0-1.1-.9-2-2-2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2zm-11-4l2.03 2.71L16 11l4 5H8l3-4zM2 6v14c0 1.1.9 2 2 2h14v-2H4V6H2z"></path>
					</svg>
				</i>
			</div>

			<div class="home-page__info-wrapper">
				<div class="home-page__info_post">
					<span class="home-page__info-title"></span>
					<span class="home-page__info-text"></span>
				</div>
				<div class="home-page__info_post">
					<span class="home-page__info-title"></span>
					<span class="home-page__info-text"></span>
				</div>
				<div class="home-page__info_post">
					<span class="home-page__info-title"></span>
					<span class="home-page__info-text"></span>
				</div>
				<span class="home-page__text-line"></span>
				<span class="home-page__text-line"></span>
			</div>
		</div>
  </div>
	<div class="home-page__section">
		<div class="home-page__section-block_right page__block_with-padding">
			<div class="home-page__title-box">
				<h3 class="home-page__title">Быстрая регистрация</h3>
			</div>
			<p>Объявления можно подавать бесплатно, а регистрация происходит в один клик с помощью кнопок социальных сетей или стандартным способом - заполнив форму.</p>
		</div>
		<div class="home-page__section-block">
			<p class="form-text_center">Войдите через социальные сети</p>
			<div class="social-block">
				<a class="social-block__btn btn_with-icon btn_social_vk" href="{{ url('auth/vk') }}" title="Вконтакте" v-ripple>
					<i class="icon">
						<svg class="sr-icon sr-icon_light" viewBox="0 0 24 24">
							<path d="m0.082828,5.354315c-0.163359,-0.996839 4.655725,-0.379655 4.655725,-0.379655c0,0 2.797519,7.025341 3.900191,7.025341c1.102672,0 0.571756,-5.957299 0.571756,-5.957299c0,0 -1.36813,-0.356014 -1.36813,-0.901902c0,-0.545888 1.36813,-0.925636 1.36813,-0.925636c0,0 3.540627,0 4.02271,0c0.482083,0 0.673934,0.783231 0.673935,1.305384c0,0.522154 0,6.384516 0,6.384516l1.102672,0c0,0 0.981556,-1.577636 1.756027,-3.180391c0.774471,-1.602755 1.715267,-3.750013 1.715267,-3.750013l4.676145,0c0,0 0.857634,0.023549 0.755534,0.735669c-0.138897,0.968781 -3.249906,5.569356 -3.900191,7.144012c-0.490076,1.186713 2.058765,2.792936 3.144656,4.509509c0.704759,1.114079 0.755534,2.420987 0.755534,2.420987l-5.554119,0l-2.777179,-3.227952c0,0 -0.571676,-0.23725 -1.020992,0.094937c-0.449316,0.332187 -0.469657,0.996839 -0.469657,0.996839l0,2.136176l-4.349427,0c0,0 -3.328435,-1.139244 -6.146374,-6.123439c-2.817939,-4.984194 -3.348855,-7.310245 -3.512214,-8.307084z"/>
						</svg>
					</i>
				</a>
				<a class="social-block__btn btn_with-icon btn_social_facebook" href="{{ url('auth/facebook') }}" title="Facebook" v-ripple>
					<i class="icon">
						<svg class="sr-icon sr-icon_light" viewBox="0 0 24 24">
							<path d="m17.584148,7.840448l-3.735876,0l0,-2.450187c0,-0.920164 0.609859,-1.13469 1.039399,-1.13469c0.428563,0 2.63637,0 2.63637,0l0,-4.045203l-3.630812,-0.014171c-4.030543,0 -4.947775,3.017043 -4.947775,4.947775l0,2.696476l-2.330952,0l0,4.168348l2.330952,0c0,5.349461 0,11.795007 0,11.795007l4.902818,0c0,0 0,-6.509073 0,-11.795007l3.30829,0l0.427586,-4.168348z"/>
						</svg>
					</i>
				</a>
				<a class="social-block__btn btn_with-icon btn_social_google" href="{{ url('auth/google') }}" title="Google+" v-ripple>
					<i class="icon">
						<svg class="sr-icon sr-icon_light" viewBox="0 0 24 24">
							<path d="m8.109334,19.979622c-4.480065,0 -8.124668,-3.5792 -8.124668,-7.979094c0,-4.399895 3.644602,-7.980149 8.124668,-7.980149c1.958908,0 3.852413,0.695164 5.329242,1.956798l-2.066505,2.332334c-0.90403,-0.77217 -2.063341,-1.198341 -3.263791,-1.198341c-2.744792,0 -4.977968,2.193091 -4.977968,4.888303c0,2.695212 2.233176,4.888303 4.977968,4.888303c2.321786,0 3.841864,-1.112896 4.335547,-3.108724l-4.297572,0l0,-3.090791l7.619381,0l0,1.545396c0.001055,4.631968 -3.076023,7.745967 -7.656302,7.745967z"/>
							<polygon points="24.076580047607422,10.731418240815401 21.6633358001709,10.731418240815401 21.6633358001709,8.31925355270505 19.73404312133789,8.31925355270505 19.73404312133789,10.731418240815401 17.32187271118164,10.731418240815401 17.32187271118164,12.660717595368624 19.73404312133789,12.660717595368624 19.73404312133789,15.072879422456026 21.6633358001709,15.072879422456026 21.6633358001709,12.660717595368624 24.076580047607422,12.660717595368624"/>
						</svg>
					</i>
				</a>
			</div>
			<p class="form-text_center">Или зарегистрируйтесь на сайте</p>
			<a class="btn btn_primary btn_wide" href="{{ url('register') }}" v-ripple>Регистрация</a>
		</div>
	</div>
  <div class="home-page__section">
		<div class="home-page__section-block_left page__block_with-padding">
			<div class="home-page__title-box">
				<h3 class="home-page__title">Интерактивная карта</h3>
			</div>
			<p>Интерактивная карта, на которой можно найти все наши обьявление в интересуещем вас месте.</p>
		</div>
		<a href="{{ url('map') }}" class="home-page__map" v-ripple></a>
	</div>
  <div class="home-page__section">
		<div class="home-page__section-block_right page__block_with-padding">
			<div class="home-page__title-box">
				<h3 class="home-page__title">Избранное</h3>
			</div>
			<p>Сохранение понравившихся объявлений в "избранное", чтобы они были доступны через продолжительное время или при работе на другом устройстве.</p>
		</div>
		<div class="home-page__section-block">
			<div class="home-page__dummy">
				<span class="home-page__dummy__avatar">
					<img src="/images/avatar/avatar_small.jpg">
				</span>

				<div class="home-page__dummy__info">
					<span class="home-page__dummy__name"></span>
					<span class="home-page__dummy__date"></span>
				</div>

				<div class="home-page__dummy__right-container">
					<i class="home-page__dummy__btn in-favorites">
						<svg class="sr-icon sr-icon_light" viewBox="0 0 24 24">
							<path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path>
						</svg>
					</i>
					<i class="home-page__dummy__btn">
						<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
							<path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path>
						</svg>
					</i>
				</div>
			</div>

			<div class="home-page__img-dummy">
				<i>
					<svg viewBox="0 0 24 24" class="sr-icon sr-icon_is-active">
						<path d="M22 16V4c0-1.1-.9-2-2-2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2zm-11-4l2.03 2.71L16 11l4 5H8l3-4zM2 6v14c0 1.1.9 2 2 2h14v-2H4V6H2z"></path>
					</svg>
				</i>
			</div>

			<div class="home-page__info-wrapper">
				<div class="home-page__info_post">
					<span class="home-page__info-title"></span>
					<span class="home-page__info-text"></span>
				</div>
				<div class="home-page__info_post">
					<span class="home-page__info-title"></span>
					<span class="home-page__info-text"></span>
				</div>
				<div class="home-page__info_post">
					<span class="home-page__info-title"></span>
					<span class="home-page__info-text"></span>
				</div>
				<span class="home-page__text-line"></span>
				<span class="home-page__text-line"></span>
			</div>
		</div>
	</div>
  <div class="home-page__section">
		<div class="home-page__section-block_left page__block_with-padding">
			<div class="home-page__title-box">
				<h3 class="home-page__title">Адаптивный дизайн</h3>
			</div>
    	<p>Удобный и понятный дизайн на смартфоне, планшете, ноутбуке или домашнем компьютере, что позволяет пользоваться нашим сервисом где вам угодно.</p>
		</div>
		<img class="home-page__img" src="/images/devices.png">
  </div>
	<div class="home-page__section">
		<div class="home-page__section-block_right page__block_with-padding">
			<div class="home-page__title-box">
				<h3 class="home-page__title">Сайт как приложение</h3>
			</div>
			<p>Добавьте сайт на главный экран мобильного устройства в виде приложения. Оно быстро загружается, не требует установки и занимает минимум места (около 200КБ).</p>
		</div>
		<img class="home-page__img" src="/images/app-on-device.png">
	</div>
@endsection

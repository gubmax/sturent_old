<!doctype html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
		<meta name="theme-color" content="#3f51b5">
		<!-- Apple -->
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="#3f51b5">

    <title>
			@hasSection ('title')
				@yield('title') | STURENT
			@else
				STURENT
			@endif
		</title>

 		<!-- Manifest -->
		<link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
		<!-- Apple Icons -->
		<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
		<link rel="mask-icon" href="{{ asset('images/safari-pinned-tab.svg') }}" color="#3f51b5">
	  <!-- Styles -->
		<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
		<link rel="stylesheet" type="text/css" media="screen and (min-width: 776px)" href="{{ asset('css/min-776.css') }}"/>
	</head>
  <body>
		<!-- App -->
		<div id="app">
			<nav class="nav_fixed" ref="fixedHeader">
				<div class="nav_fixed__wrapper">
					<span class="header-btns__menu" v-ripple @click.stop="showSideNavbar = true">
						<i class="icon">
							<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
								<path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
							</svg>
						</i>
					</span>

					<a class="logo logo_colored" href="{{ url('/') }}"><span class="logo-color">STU</span>RENT</a>

					<div class="navbar">
						<a class="navbar__link navbar__link_colored" :class="{'is-active': activeNavLink == 'neighbors'}" href="{{ url('neighbors') }}" v-ripple>Найти соседей</a>
						<a class="navbar__link navbar__link_colored" :class="{'is-active': activeNavLink == 'share'}" href="{{ url('share') }}" v-ripple>Подселиться</a>
						<a class="navbar__link navbar__link_colored" :class="{'is-active': activeNavLink == 'map'}" href="{{ url('add') }}" v-ripple>Добавить объявление</a>
					</div>

					<div class="header-btns">
						<a class="header-btns__favorites" href="{{ url('/favorites') }}" v-ripple>
							<i class="icon">
								<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
									<path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
								</svg>
							</i>
              @php
                $favorites_count = 0;
                if ( Session::has('favorites_count')) {
                  $favorites_count += Session::get('favorites_count') ? Session::get('favorites_count') : 0;
                }
              @endphp
							<span class="header-btns__favorites-counter round-counter round-counter_favorites {{ ($favorites_count == 0) ? 'is-hide' : '' }}" ref="navbarFavCounter">{{ $favorites_count }}</span>
						</a>
						@if (Auth::guest())
							<a class="header-btns__login" href="{{ url('/login') }}" v-ripple>
								<i class="icon">
									<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
										<path xmlns="http://www.w3.org/2000/svg" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
									</svg>
								</i>
							</a>
						@else
							<div class="header-btns__profile">
								<drop-down-item>
									@if (!is_null(Auth::user()->avatar))
										<img class="header-btns__profile-img" src="{{ url('/images/avatar/'.Auth::user()->avatar.'_small.jpg') }}" slot="activator">
									@else
										<img class="header-btns__profile-img" src="{{ url('/images/avatar/avatar_small.jpg') }}" slot="activator">
									@endif

									<div class="drop-down_list-header" v-cloak>
										@if (!is_null(Auth::user()->avatar))
											<img class="drop-down_list-header__img" src="{{ url('/images/avatar/'.Auth::user()->avatar.'_small.jpg') }}">
										@else
											<img class="drop-down_list-header__img" src="{{ url('/images/avatar/avatar_small.jpg') }}">
										@endif
										<div class="drop-down_list-header__container">
											<span class="drop-down_list-header__name">{{ Auth::user()->name }}</span>
											<span class="drop-down_list-header__auth-type">{{ Auth::user()->email ?? 'Вконтакте' }}</span>
										</div>
									</div>
									<ul class="drop-down_list" v-cloak>
										<li>
											<a class="drop-down_list__item" href="{{ url('/profile') }}">
												<i class="list-icon">
													<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
														<path d="M2,3H22C23.05,3 24,3.95 24,5V19C24,20.05 23.05,21 22,21H2C0.95,21 0,20.05 0,19V5C0,3.95 0.95,3 2,3M14,6V7H22V6H14M14,8V9H21.5L22,9V8H14M14,10V11H21V10H14M8,13.91C6,13.91 2,15 2,17V18H14V17C14,15 10,13.91 8,13.91M8,6A3,3 0 0,0 5,9A3,3 0 0,0 8,12A3,3 0 0,0 11,9A3,3 0 0,0 8,6Z" />
													</svg>
												</i>
												<span>Мои объявления</span>
											</a>
										</li>
										<li>
											<a class="drop-down_list__item" href="{{ url('profile/edit') }}">
												<i class="list-icon">
													<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
														<path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
													</svg>
												</i>
												<span>Редактировать профиль</span>
											</a>
										</li>
                    <li>
                      <a class="drop-down_list__item" href="{{ url('info') }}">
                        <i class="list-icon">
                          <svg class="sr-icon sr-icon_secondary" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                          </svg>
                        </i>
                        <span>Справка</span>
                      </a>
                    </li>
										<li>
											<form action="{{ url('logout') }}" method="post">
												{{ csrf_field() }}
												<button class="drop-down_list__item" type="submit">
													<i class="list-icon">
														<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
															<path d="M17,17.25V14H10V10H17V6.75L22.25,12L17,17.25M13,2A2,2 0 0,1 15,4V8H13V4H4V20H13V16H15V20A2,2 0 0,1 13,22H4A2,2 0 0,1 2,20V4A2,2 0 0,1 4,2H13Z" />
														</svg>
													</i>
													<span>Выйти</span>
												</button>
											</form>
										</li>
									</ul>
								</drop-down-item>
							</div>
						@endif
					</div>
				</div>
			</nav>

			<header>
			 	@if(View::hasSection('tabs') || View::hasSection('map'))
					<div class="header__wrapper without-margin">
				@else
					<div class="header__wrapper">
				@endif
					<nav class="nav">
						<span class="header-btns__menu" v-ripple @click.stop="showSideNavbar = true">
							<i class="icon">
								<svg class="sr-icon sr-icon_light" viewBox="0 0 24 24">
									<path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
								</svg>
							</i>
						</span>

						<a class="logo logo_light" href="{{ url('/') }}">STURENT</a>

						<div class="navbar">
							<a class="navbar__link navbar__link_light" href="{{ url('neighbors') }}" v-ripple>Найти соседей</a>
							<a class="navbar__link navbar__link_light" href="{{ url('share') }}" v-ripple>Подселиться</a>
							<a class="navbar__link navbar__link_light" href="{{ url('add') }}" v-ripple>Добавить объявление</a>
						</div>

						<div class="header-btns">
							<a class="header-btns__favorites" href="{{ url('/favorites') }}" v-ripple>
								<i class="icon">
									<svg class="sr-icon sr-icon_light" viewBox="0 0 24 24">
										<path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
									</svg>
								</i>
								<span class="header-btns__favorites-counter round-counter round-counter_favorites {{ ($favorites_count == 0) ? 'is-hide' : '' }}" ref="fixedNavbarFavCounter">{{ $favorites_count }}</span>
							</a>
							@if (Auth::guest())
								<a class="header-btns__login" href="{{ url('/login') }}" v-ripple>
									<i class="icon">
										<svg class="sr-icon sr-icon_light" viewBox="0 0 24 24">
											<path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
										</svg>
									</i>
								</a>
							@else
								<div class="header-btns__profile">
									<drop-down-item>
										@if (!is_null(Auth::user()->avatar))
											<img class="header-btns__profile-img" src="{{ url('/images/avatar/'.Auth::user()->avatar.'_small.jpg') }}" slot="activator">
										@else
											<img class="header-btns__profile-img" src="{{ url('/images/avatar/avatar_small.jpg') }}" slot="activator">
										@endif

										<div class="drop-down_list-header" v-cloak>
											@if (!is_null(Auth::user()->avatar))
												<img class="drop-down_list-header__img" src="{{ url('/images/avatar/'.Auth::user()->avatar.'_small.jpg') }}">
											@else
												<img class="drop-down_list-header__img" src="{{ url('/images/avatar/avatar_small.jpg') }}">
											@endif
											<div class="drop-down_list-header__container">
												<span class="drop-down_list-header__name">{{ Auth::user()->name }}</span>
												<span class="drop-down_list-header__auth-type">{{ Auth::user()->email ?? 'Вконтакте' }}</span>
											</div>
										</div>
										<ul class="drop-down_list" v-cloak>
											<li>
												<a class="drop-down_list__item" href="{{ url('/profile') }}">
													<i class="list-icon">
														<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
															<path d="M2,3H22C23.05,3 24,3.95 24,5V19C24,20.05 23.05,21 22,21H2C0.95,21 0,20.05 0,19V5C0,3.95 0.95,3 2,3M14,6V7H22V6H14M14,8V9H21.5L22,9V8H14M14,10V11H21V10H14M8,13.91C6,13.91 2,15 2,17V18H14V17C14,15 10,13.91 8,13.91M8,6A3,3 0 0,0 5,9A3,3 0 0,0 8,12A3,3 0 0,0 11,9A3,3 0 0,0 8,6Z" />
														</svg>
													</i>
													<span>Мои объявления</span>
												</a>
											</li>
											<li>
												<a class="drop-down_list__item" href="{{ url('profile/edit') }}">
													<i class="list-icon">
														<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
															<path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
														</svg>
													</i>
													<span>Редактировать профиль</span>
												</a>
											</li>
                      <li>
                        <a class="drop-down_list__item" href="{{ url('info') }}">
                          <i class="list-icon">
                            <svg class="sr-icon sr-icon_secondary" viewBox="0 0 24 24">
                              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                            </svg>
                          </i>
                          <span>Справка</span>
                        </a>
                      </li>
											<li>
												<form action="{{ url('logout') }}" method="post">
													{{ csrf_field() }}
													<button class="drop-down_list__item" type="submit">
														<i class="list-icon">
															<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
																<path d="M17,17.25V14H10V10H17V6.75L22.25,12L17,17.25M13,2A2,2 0 0,1 15,4V8H13V4H4V20H13V16H15V20A2,2 0 0,1 13,22H4A2,2 0 0,1 2,20V4A2,2 0 0,1 4,2H13Z" />
															</svg>
														</i>
														<span>Выйти</span>
													</button>
												</form>
											</li>
										</ul>
									</drop-down-item>
								</div>
							@endif
						</div>
					</nav>
					@yield('main-header')
				</div>
				@yield('tabs')
			</header>

			@hasSection('content')
				@if(View::hasSection('tabs') || View::hasSection('map'))
					<main class="without-margin">
				@else
					<main>
				@endif
					<div class="main__wrapper">
						@yield('content')
					</div>
				</main>
				<footer>
					<div class="footer__wrapper">
						<span class="footer__copyright">{{ date('Y') }} &#xa9; STURENT</span>
						<a class="footer__link btn btn_with-icon" href="//vk.com" target="_blank">
							<i class="btn__icon">
								<svg viewBox="0 0 24 24" class="sr-icon sr-icon_secondary">
									<path d="m0.082828,5.354315c-0.163359,-0.996839 4.655725,-0.379655 4.655725,-0.379655c0,0 2.797519,7.025341 3.900191,7.025341c1.102672,0 0.571756,-5.957299 0.571756,-5.957299c0,0 -1.36813,-0.356014 -1.36813,-0.901902c0,-0.545888 1.36813,-0.925636 1.36813,-0.925636c0,0 3.540627,0 4.02271,0c0.482083,0 0.673934,0.783231 0.673935,1.305384c0,0.522154 0,6.384516 0,6.384516l1.102672,0c0,0 0.981556,-1.577636 1.756027,-3.180391c0.774471,-1.602755 1.715267,-3.750013 1.715267,-3.750013l4.676145,0c0,0 0.857634,0.023549 0.755534,0.735669c-0.138897,0.968781 -3.249906,5.569356 -3.900191,7.144012c-0.490076,1.186713 2.058765,2.792936 3.144656,4.509509c0.704759,1.114079 0.755534,2.420987 0.755534,2.420987l-5.554119,0l-2.777179,-3.227952c0,0 -0.571676,-0.23725 -1.020992,0.094937c-0.449316,0.332187 -0.469657,0.996839 -0.469657,0.996839l0,2.136176l-4.349427,0c0,0 -3.328435,-1.139244 -6.146374,-6.123439c-2.817939,-4.984194 -3.348855,-7.310245 -3.512214,-8.307084z"></path>
								</svg>
							</i>
							<span>Группа Вконтакте</span>
						</a>
						<a class="footer__link btn" href="{{ url('neighbors') }}">Поиск сожителей</a>
						<a class="footer__link btn" href="{{ url('share') }}">Подселение</a>
						<a class="footer__link btn" href="{{ url('info') }}">О проекте</a>
						<a class="footer__link btn" href="{{ url('info') }}">Ответы на вопросы</a>
						<a class="footer__link btn" href="{{ url('info') }}">Правила пользования</a>
						<a class="footer__link btn" href="{{ url('contacts') }}">Обратная связь</a>
					</div>
				</footer>
			@endif

			@yield('map')

			<modal-block v-show="showSideNavbar" v-cloak>
				<transition name="slide-side-navbar" mode="out-in">
					<div class="side-navbar" v-show="showSideNavbar" v-click-outside="hideSideNavbar">
            <div class="side-navbar__wrapper"></div>
						<div class="side-navbar__header">
							<span class="header-btns__menu" v-ripple @click.stop="showSideNavbar = false">
								<i class="icon">
									<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
										<path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
									</svg>
								</i>
							</span>
							<a href="{{ url('/') }}" class="logo logo_colored"><span class="logo-color">STU</span>RENT</a>
						</div>
						<ul class="drop-down_list">
							<li>
								<a class="drop-down_list__item" :class="{'is-active': activeNavLink == 'neighbors'}" href="{{ url('neighbors') }}">
									<i class="list-icon">
										<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
											<path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
										</svg>
									</i>
									<span>Найти соседей</span>
								</a>
							</li>
							<li>
								<a class="drop-down_list__item" :class="{'is-active': activeNavLink == 'share'}" href="{{ url('share') }}">
									<i class="list-icon">
										<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
											<path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/>
										</svg>
									</i>
									<span>Подселиться</span>
								</a>
							</li>
							<li>
								<a class="drop-down_list__item":class="{'is-active': activeNavLink == 'map'}" href="{{ url('map') }}" >
									<i class="list-icon">
										<svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
											<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"></path>
										</svg>
									</i>
									<span>Подселение на карте</span>
								</a>
							</li>
              <li>
                <a class="drop-down_list__item":class="{'is-active': activeNavLink == 'add'}" href="{{ url('add') }}" >
                  <i class="list-icon">
                    <svg class="sr-icon sr-icon_primary" viewBox="0 0 24 24">
                      <path d="M14 10H2v2h12v-2zm0-4H2v2h12V6zm4 8v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zM2 16h8v-2H2v2z"/>
                    </svg>
                  </i>
                  <span>Добавить объявление</span>
                </a>
              </li>
							<li class="drop-down_list__separator"></li>
							<a class="drop-down_list__item_secondary" href="https://vk.com" target="_blank">
								<i class="list-icon">
									<svg class="sr-icon sr-icon_secondary" viewBox="0 0 24 24">
										<path d="m0.082828,5.354315c-0.163359,-0.996839 4.655725,-0.379655 4.655725,-0.379655c0,0 2.797519,7.025341 3.900191,7.025341c1.102672,0 0.571756,-5.957299 0.571756,-5.957299c0,0 -1.36813,-0.356014 -1.36813,-0.901902c0,-0.545888 1.36813,-0.925636 1.36813,-0.925636c0,0 3.540627,0 4.02271,0c0.482083,0 0.673934,0.783231 0.673935,1.305384c0,0.522154 0,6.384516 0,6.384516l1.102672,0c0,0 0.981556,-1.577636 1.756027,-3.180391c0.774471,-1.602755 1.715267,-3.750013 1.715267,-3.750013l4.676145,0c0,0 0.857634,0.023549 0.755534,0.735669c-0.138897,0.968781 -3.249906,5.569356 -3.900191,7.144012c-0.490076,1.186713 2.058765,2.792936 3.144656,4.509509c0.704759,1.114079 0.755534,2.420987 0.755534,2.420987l-5.554119,0l-2.777179,-3.227952c0,0 -0.571676,-0.23725 -1.020992,0.094937c-0.449316,0.332187 -0.469657,0.996839 -0.469657,0.996839l0,2.136176l-4.349427,0c0,0 -3.328435,-1.139244 -6.146374,-6.123439c-2.817939,-4.984194 -3.348855,-7.310245 -3.512214,-8.307084z"/>
									</svg>
								</i>
								<span>Группа Вконтакте</span>
							</a>
							<a class="drop-down_list__item_secondary" :class="{'is-active': activeNavLink == 'info'}" href="{{ url('/info') }}">
								<i class="list-icon">
									<svg class="sr-icon sr-icon_secondary" viewBox="0 0 24 24">
										<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
									</svg>
								</i>
								<span>Справка</span>
							</a>
							<a class="drop-down_list__item_secondary" :class="{'is-active': activeNavLink == 'contacts'}" href="{{ url('/contacts') }}">
								<i class="list-icon">
									<svg class="sr-icon sr-icon_secondary" viewBox="0 0 24 24">
										<path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
									</svg>
								</i>
								<span>Обратная связь</span>
							</a>
						</ul>
            <span class="side-navbar__copyright">{{ date('Y') }} &#xa9; STURENT</span>
					</div>
				</transition>
			</modal-block>

			<notifications :info="['{{ Session::has('info') ? Session::get('info') : 'null' }}']"></notifications>
		</div>

		<!-- JavaScripts -->
		@stack('js')
		<script src="{{ asset('js/app.js') }}" async></script>
  </body>
</html>

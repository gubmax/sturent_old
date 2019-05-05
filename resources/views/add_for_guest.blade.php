@extends('layouts.app')

@section('title')
	Размещение объявления
@endsection

@section('main-header')
	<h1 class="page-title">Размещение объявления</h1>
@endsection

@section('content')
	<steps class="page__block">
		<step>Авторизуйтесь на сайте</step>
		<step>Заполните форму</step>
		<step>Загрузите фотографии</step>
	</steps>
	<div class="page__columns">
		<div class="page__column">
			<div class="msg-info form-group">
				<i class="msg-info__icon">
					<svg viewBox="0 0 24 24" class="sr-icon sr-icon_info">
						<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path>
					</svg>
				</i>
				<p>Если вы ещё не зарегистированы, можете сделать это за пару кликов с помощью кнопок социальных сетей или стандартным способом - заполнив <a class="form-text_link" href="{{ url('register') }}">форму</a>.</p>
			</div>
		</div>

		<div class="page__column">
				@include('layouts.login_form')
		</div>
	</div>
@endsection

@extends('layouts.app')

@section('title')
	Регистрация
@endsection

@section('main-header')
	<h1 class="page-title">Регистрация</h1>
@endsection

@section('content')
  <form class="page__block page__block_narrow form" role="form" action="{{ route('register') }}" method="post">
		{{ csrf_field() }}

		<div class="msg-info">
			<i class="msg-info__icon">
				<svg class="sr-icon sr-icon_info" viewBox="0 0 24 24">
					<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path>
				</svg>
			</i>
			<p>Все поля обязательны для заполнения.</p>
		</div>

    <div class="form-group_field form-group_first{{ $errors->has('name') ? ' has-error' : '' }}">
			<input id="name" type="text" name="name" value="{{ old('name') }}" required v-field>
      <label for="name">Имя</label>
        @if ($errors->has('name'))
          <span class="msg-error">{{ $errors->first('name') }}</span>
        @endif
    </div>

    <div class="form-group_field{{ $errors->has('email') ? ' has-error' : '' }}">
			<input id="email" type="email" name="email" value="{{ old('email') }}" required v-field>
			<label for="email">E-Mail адрес</label>
      @if ($errors->has('email'))
        <span class="msg-error">{{ $errors->first('email') }}</span>
      @endif
    </div>

    <div class="form-group_field{{ $errors->has('password') ? ' has-error' : '' }}">
			<input id="password" type="password" name="password" required v-field>
			<label for="password">Пароль</label>
      @if ($errors->has('password'))
        <span class="msg-error">{{ $errors->first('password') }}</span>
      @endif
    </div>

    <div class="form-group_field">
			<input id="password-confirm" type="password" name="password_confirmation" required v-field>
      <label for="password-confirm">Подтверждение пароля</label>
    </div>

    <div class="btns-group">
			<button type="submit" class="btn btn_primary" v-ripple>Зарегистрироваться</button>
			<span class="form-text">Уже есть аккаунт? <a class="form-text_link" href="{{ route('login') }}">Войти</a></span>
    </div>
  </form>
@endsection

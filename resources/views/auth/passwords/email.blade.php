@extends('layouts.app')

@section('title')
	Сброс пароля
@endsection

@section('main-header')
	<h1 class="page-title">Сброс пароля</h1>
@endsection

@section('content')
  <form class="page__block page__block_narrow form" role="form" action="{{ route('password.email') }}" method="post">
		{{ csrf_field() }}

    @if (session('status'))
      <span class="msg-error">{{ session('status') }}</span >
    @endif

		<div class="msg-info">
			<i class="msg-info__icon">
				<svg class="sr-icon sr-icon_info" viewBox="0 0 24 24">
					<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path>
				</svg>
			</i>
			<p>Для смены пароля укажите E-Mail, который вы использовали при регистрации, на него будет выслана инструкция по сбросу пароля.</p>
		</div>

    <div class="form-group_field form-group_first{{ $errors->has('email') ? ' has-error' : '' }}">
      <input id="email" type="email" name="email" value="{{ old('email') }}" required v-field>
      <label for="email">E-Mail адрес</label>
      @if ($errors->has('email'))
        <span class="msg-error">{{ $errors->first('email') }}</span>
      @endif
    </div>

    <div class="btns-group">
      <button type="submit" class="btn btn_primary" v-ripple>Отправить</button>
    </div>
  </form>
@endsection

@extends('layouts.app')

@section('title')
	Сброс пароля
@endsection

@section('main-header')
	<h1 class="page-title">Сброс пароля</h1>
@endsection

@section('content')
  <form class="page__block page__block_narrow form" role="form" method="POST" action="{{ route('password.request') }}">
		{{ csrf_field() }}

    @if (session('status'))
      <span class="msg-error">{{ session('status') }}</span >
    @endif
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="form-group_field form-group_first{{ $errors->has('email') ? ' has-error' : '' }}">
      <input id="email" type="email" name="email" value="{{ $email or old('email') }}" required v-field>
      <label for="email" >E-Mail адрес</label>
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

    <div class="form-group_field{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
      <input id="password-confirm" type="password" name="password_confirmation" required v-field>
      <label for="password-confirm">Подтверждение пароля</label>
      @if ($errors->has('password_confirmation'))
        <span class="msg-error">{{ $errors->first('password_confirmation') }}</span>
      @endif
    </div>

    <div class="btns-group">
      <button type="submit" class="btn btn_primary" v-ripple>Сбросить пароль</button>
    </div>
  </form>
@endsection

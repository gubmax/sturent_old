@extends('layouts.app')

@section('title')
	Редактирование профиля
@endsection

@section('main-header')
	<h1 class="page-title">Редактирование профиля</h1>
@endsection

@section('content')
  <form role="form" method="POST" action="{{ url('profile/save') }}" enctype="multipart/form-data">
    {!! csrf_field() !!}

		<div class="page__block page__block_narrow form">
			<h2 class="page-title">Мой профиль</h2>

			<avatar-uploader
				src="{{ Auth::user()->avatar ? '/images/avatar/'.Auth::user()->avatar.'.jpg' : '/images/avatar/avatar.jpg' }}"
				error="{{  $errors->has('avatar') ? $errors->first('avatar') : '' }}">
			</avatar-uploader>

			<div class="form-group_field{{ $errors->has('name') ? ' has-error' : '' }}">
				<input type="text" id="name" name="name" value="{{ $user->name }}" required v-field>
				<label for="name">Имя</label>
					@if ($errors->has('name'))
						<span class="msg-error">{{ $errors->first('name') }}</span>
					@endif
			</div>

			<div class="form-group_field{{ $errors->has('age') ? ' has-error' : '' }}">
				<input type="text" id="age" name="age" value="{{ $user->age }}" v-field>
				<label for="age">Возраст</label>
					@if ($errors->has('age'))
						<span class="msg-error">{{ $errors->first('age') }}</span>
					@endif
			</div>

			<h2 class="page-title">Контактные данные</h2>

			<div class="msg-info">
				<i class="msg-info__icon">
					<svg viewBox="0 0 24 24" class="sr-icon sr-icon_info">
						<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path>
					</svg>
				</i>
				<p>При изменении контакных данных, данные для входа на сайт не меняются.</p>
			</div>

			@foreach ($contact_data_list as $key => $item)
				@php
					$contact_data_name = '';
					foreach ($user->contactData as $value) {
						if ($value->contact_data_id == $key)
							$contact_data_name = $value->value;
					}
				@endphp
				@if ($key == 0)
					<div class="form-group_field form-group_first{{ $errors->has('contact_data') ? ' has-error' : '' }}">
				@else
					<div class="form-group_field{{ $errors->has('contact_data') ? ' has-error' : '' }}">
				@endif
					<input type="text" id="{{ 'contact-data-'.$key }}" name="{{ 'contact-data[]' }}" value="{{ $contact_data_name }}" v-field>
					<label for="{{ 'contact-data-'.$key }}">{{ $item->name }}</label>
					@if ($errors->has('phone'))
						<span class="msg-error">{{ $errors->first('phone') }}</span>
					@endif
				</div>
			@endforeach

			<div class="btns-group">
				<button type="submit" class="btn btn_primary" v-ripple>Сохранить</button>
				<button type="reset" class="btn btn_default" v-ripple @click="resetAvatarUploader">Сбросить</button>
			</div>
		</div>
  </form>
@endsection

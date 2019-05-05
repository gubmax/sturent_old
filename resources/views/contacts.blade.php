@extends('layouts.app')

@section('title')
	Обратная связь
@endsection

@section('main-header')
  <h1 class="page-title">Обратная связь</h1>
@endsection

@push('js')
	<script src="{{ asset('js/autosize.min.js') }}" type="text/javascript"></script>
@endpush

@section('content')
	<form class="page__block page__block_narrow form" role="form" method="POST" action="{{ url('contacts') }}" enctype="multipart/form-data">
		{!! csrf_field() !!}

		<div class="msg-info">
			<i class="msg-info__icon">
				<svg viewBox="0 0 24 24" class="sr-icon sr-icon_info">
					<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path>
				</svg>
			</i>
			<p>Если у Вас есть пожелания, предложения или вопросы, напишите нам c помощью этой формы.</p>
		</div>

		<div class="form-group_field form-group_first{{ $errors->has('name') ? ' has-error' : '' }}">
			<input type="text" id="name" name="name" value="{{ Auth::check() ? Auth::user()->name : '' }}" required v-field>
			<label for="name">Имя</label>
				@if ($errors->has('name'))
					<span class="msg-error">{{ $errors->first('name') }}</span>
				@endif
		</div>

		<div class="form-group_field{{ $errors->has('email') ? ' has-error' : '' }}">
			<input id="email" type="email" name="email" value="{{ Auth::check() ? Auth::user()->email : '' }}" placeholder="на этот адрес придёт ответ" required v-field>
			<label for="email">E-Mail адрес</label>
			@if ($errors->has('email'))
				<span class="msg-error">
					<strong>{{ $errors->first('email') }}</strong>
				</span>
			@endif
		</div>

		<div class="form-group_field{{ $errors->has('theme') ? ' has-error' : '' }}">
			<input type="text" id="theme" name="theme" required v-field>
			<label for="theme">Тема</label>
				@if ($errors->has('theme'))
					<span class="msg-error">{{ $errors->first('theme') }}</span>
				@endif
		</div>

		<div class="form-group_field{{ $errors->has('text') ? ' has-error' : '' }}">
			<autosize-textarea
				id="text"
				name="text"
				value="{{ old('text') }}"
				:required="true">
			</autosize-textarea>
			@if ($errors->has('text'))
				<div class="msg-error">{{ $errors->first('text') }}</div>
			@endif
		</div>

		<div class="btns-group">
			<button type="submit" class="btn btn_primary" v-ripple>Отправить</button>
			<button type="reset" class="btn btn_default" @click="resetFields" v-ripple>Сбросить</button>
		</div>
	</form>
@endsection

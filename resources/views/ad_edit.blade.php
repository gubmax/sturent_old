@extends('layouts.app')

@section('title')
	Редактирование объявления
@endsection

@section('main-header')
  <h1 class="page-title">Редактирование объявления №{{ $ad->ad_id }}</h1>
@endsection

@push('js')
	<script src="{{ asset('js/autosize.min.js') }}" type="text/javascript"></script>
@endpush

@section('content')
  <form class="page__block page__block_narrow form" role="form" method="POST" action="{{ url('/'.$page.'/save/'.$ad->ad_id) }}">
		{!! csrf_field() !!}

		<div class="form-group_field{{ $errors->has('text') ? ' has-error' : '' }}">
			<autosize-textarea
				id="text"
				name="text"
				value="{{ $ad->text }}"
				:required="true"
				:max="1000">
			</autosize-textarea>
			@if ($errors->has('text'))
				<div class="msg-error">{{ $errors->first('text') }}</div>
			@endif
		</div>

		<div class="btns-group">
			<button type="submit" class="btn btn_primary" v-ripple>Сохранить</button>
			<button type="reset" class="btn btn_default" v-ripple>Сбросить</button>
		</div>
  </form>
@endsection

@extends('layouts.app')

@section('title')
	Поиск соседей
@endsection

@section('main-header')
	<h1 class="page-title">Поиск соседей</h1>
@endsection

@section('content')
	<masonry-filters class="form-group" location="{{ $location->value }}" v-cloak>
		<div class="form-group_field">
			<address-suggest :value="{{ json_encode($location) }}"
				placeholder="регион, город, район">
			</address-suggest>
			@if ($errors->has('location'))
				<div class="msg-error">{{ $errors->first('location') }}</div>
			@endif
		</div>

		<div class="form-group_field{{ $errors->has('looking') ? ' has-error' : '' }}">
			<form-select name="looking" text="Ищут"  @if (isset($_GET['looking'])) {{ ':value='.json_encode($_GET['looking']) }} @endif>
				<select-option name="Женщину" value="0"></select-option>
				<select-option name="Мужчину" value="1"></select-option>
				<select-option name="Пару" value="2"></select-option>
				<select-option name="Компанию" value="3"></select-option>
			</form-select>
			@if ($errors->has('looking'))
				<div class="msg-error">{{ $errors->first('looking') }}</div>
			@endif
		</div>

		<div class="form-group_field{{ $errors->has('rent') ? ' has-error' : '' }}">
			<form-select name="rent[]" text="Хотят арендовать" :multiple="true" @if (isset($_GET['rent'])) {{ ':value='.json_encode($_GET['rent']) }} @endif>
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

		<div class="form-group_field{{ $errors->has('tenants') ? ' has-error' : '' }}">
			<form-select name="tenants" text="Максимум жильцов"  @if (isset($_GET['tenants'])) {{ ':value='.json_encode($_GET['tenants']) }} @endif>
				<select-option name="2" value="2"></select-option>
				<select-option name="3" value="3"></select-option>
				<select-option name="4" value="4"></select-option>
			</form-select>
			@if ($errors->has('tenants'))
				<div class="msg-error">{{ $errors->first('tenants') }}</div>
			@endif
		</div>

		<div class="form-group_field{{ $errors->has('tags') ? ' has-error' : '' }}">
			<form-select name="tags[]" text="Особенности" :multiple="true" @if (isset($_GET['tags'])) {{ ':value='.json_encode($_GET['tags']) }} @endif>
				<select-option name="Без детей" value="1"></select-option>
				<select-option name="Без животных" value="2"></select-option>
				<select-option name="Не курят" value="3"></select-option>
				<select-option name="Не выпивают" value="4"></select-option>
			</form-select>
			@if ($errors->has('tags'))
				<div class="msg-error">{{ $errors->first('tags') }}</div>
			@endif
		</div>

		<div class="form-group">
			<input id="photo" name="photo" value="y" type="checkbox" @if (isset($_GET['photo'])) {{ 'checked' }} @endif>
			<label for="photo">С фото</label>
		</div>
	</masonry-filters>

	<masonry url={{ "/api/neighbors?".Request::getQueryString() }}
		page="neighbors"
		:user-id="{{ Auth::id() ?? 'null' }}"
		:favorites="[{{ Session::get('favorites_id.neighbors') ? implode(",", Session::get('favorites_id.neighbors')) : 'null' }}]"
		:location="false">
	</masonry>
@endsection

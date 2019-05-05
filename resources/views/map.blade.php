@extends('layouts.app')

@section('title')
	Поиск на карте
@endsection

@push('js')
	<script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
@endpush

@section('map')
	<y-map :user-id="{{ Auth::id() ?? 'null' }}" location="{{ $location->value }}" v-cloak>
		<div class="form-group_field{{ $errors->has('location') ? ' has-error' : '' }}">
			<address-suggest :value="{{ json_encode($location) }}"
				placeholder="регион, город, район">
			</address-suggest>
			@if ($errors->has('location'))
				<div class="msg-error">{{ $errors->first('location') }}</div>
			@endif
		</div>

		<div class="form-group_field{{ $errors->has('looking') ? ' has-error' : '' }}">
			<form-select name="looking" text="Ищут" @if (isset($_GET['looking'])) {{ ':value='.json_encode($_GET['looking']) }} @endif>
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

		<div class="form-group">
			<input id="pledge" name="pledge" value="n" type="checkbox" @if (isset($_GET['pledge'])) {{ 'checked' }} @endif>
			<label for="pledge">Без залога</label>
		</div>

		<div class="form-group">
			<input id="photo" name="photo" value="y" type="checkbox" @if (isset($_GET['photo'])) {{ 'checked' }} @endif>
			<label for="photo">С фото</label>
		</div>
	</y-map>
@endsection

@extends('layouts.app')

@section('title')
	Размещение объявления
@endsection

@section('main-header')
	<h1 class="page-title">Размещение объявления</h1>
@endsection

@push('js')
	<script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
	<script src="{{ asset('js/autosize.min.js') }}" type="text/javascript"></script>
@endpush

@section('content')
	<steps class="page__block" :current="2" v-cloak>
		<step>Авторизуйтесь на сайте</step>
		<step>Заполните форму</step>
		<step>Загрузите фотографии</step>
	</steps>

	<tabs v-cloak>
		<tab href="step-2" :selected="true">
			<div class="page__columns">
				<div class="page__column">
					<div class="msg-info form-group">
						<i class="msg-info__icon">
							<svg viewBox="0 0 24 24" class="sr-icon sr-icon_info">
								<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path>
							</svg>
						</i>
						<p>Необходимо выбрать Тип объявления. После выбора одгного из типов появится соответствующая форма для заполнения.</p>
					</div>

					<div class="page__block page__block_narrow">
						<h2 class="page-title">Тип объявления</h2>

						<div class="form-group_field form-group_first">
							<form-select name="want" text="Хочу" :changer="true">
								<select-option name="Найти соседей" value="0"></select-option>
								<select-option name="Подселить к себе" value="1"></select-option>
							</form-select>
						</div>
					</div>
				</div>

				<div class="page__column">
					<transition name="page__show-form">
						<ajax-form class="page__block form" id="add-ad_1" key="1" method="POST" action="{{ url('add/neighbors') }}" page="neighbors" v-if="selectedInChanger == 0">
							<h2 class="page-title">Форма для размещения объявления</h2>

							{!! csrf_field() !!}

							<div class="form-group_field form-group_first{{ $errors->has('location') ? ' has-error' : '' }}">
								<address-suggest value="{{ Session::has('current_location.value') ? Session::get('current_location.value') : '' }}"
									placeholder="регион, город, район"
									:one-string="true">
								</address-suggest>
								@if ($errors->has('location'))
									<div class="msg-error">{{ $errors->first('location') }}</div>
								@endif
							</div>

							<div class="form-group_field{{ $errors->has('looking') ? ' has-error' : '' }}">
								<form-select name="looking" text="Ищу">
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
								<form-select name="rent[]" text="Хочу арендовать" :multiple="true" @if (isset($_GET['rent'])) {{ ':value='.json_encode($_GET['rent']) }} @endif>
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

							<div class="form-group_field{{ $errors->has('pay') ? ' has-error' : '' }}">
								<input type="text" id="pay" name="pay" placeholder="руб." required v-field>
								<label  for="pay">Арендная плата, не более</label>
								@if ($errors->has('pay'))
									<div class="msg-error">{{ $errors->first('pay') }}</div>
								@endif
							</div>

							<div class="form-group_field{{ $errors->has('tenants') ? ' has-error' : '' }}">
								<input type="text" id="tenants" name="tenants" placeholder="шт." required v-field>
								<label  for="tenants">Максимум жильцов</label>
								@if ($errors->has('tenants'))
									<div class="msg-error">{{ $errors->first('tenants') }}</div>
								@endif
							</div>

							<div class="form-group_field{{ $errors->has('text') ? ' has-error' : '' }}">
								<autosize-textarea
									id="text"
									name="text"
									value="{{ old('text') }}"
									:required="true"
									:max="1000">
								</autosize-textarea>
								@if ($errors->has('text'))
									<div class="msg-error">{{ $errors->first('text') }}</div>
								@endif
							</div>

							<div class="form-group_field{{ $errors->has('tags') ? ' has-error' : '' }}">
								<form-select name="tags[]" text="Особенности" :multiple="true">
									@foreach ($tags as $tag)
										<select-option name="{{ $tag->name }}" value="{{ $tag->tag_id }}"></select-option>
									@endforeach
								</form-select>
								@if ($errors->has('tags'))
									<div class="msg-error">{{ $errors->first('tags') }}</div>
								@endif
							</div>

							<div class="btns-group">
								<button type="submit" class="btn btn_primary" v-ripple>Отправить</button>
								<button type="reset" class="btn btn_default" v-ripple @click="resetFields">Сбросить</button>
							</div>
						</ajax-form>

						<ajax-form class="page__block form" id="add-ad_2" key="2" method="POST" action="{{ url('add/share') }}" page="share" v-if="selectedInChanger == 1">
							<h2 class="page-title">Форма для размещения объявления</h2>

							{!! csrf_field() !!}

							<div class="form-group_field{{ $errors->has('looking') ? ' has-error' : '' }}">
								<form-select name="looking" text="Ищу">
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
								<form-select name="rent" text="Хочу арендовать">
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

							<y-map-select class="form-group_first"></y-map-select>

							<div class="form-group_field{{ $errors->has('pay') ? ' has-error' : '' }}">
								<input type="text" id="pay" name="pay" placeholder="руб." required v-field>
								<label  for="pay">Арендная плата</label>
								@if ($errors->has('pay'))
									<div class="msg-error">{{ $errors->first('pay') }}</div>
								@endif
							</div>

							<div class="form-group_field{{ $errors->has('pledge') ? ' has-error' : '' }}">
								<input type="text" id="pledge" name="pledge" placeholder="руб." required :disabled="switchPledge" v-field>
								<label  for="pledge">Залог</label>
								<div class="btns-group">
									<input type="checkbox" id="no-pledge" name="no-pledge" checked v-model="switchPledge">
									<label for="no-pledge">Без залога</label>
								</div>
								@if ($errors->has('pledge'))
									<div class="msg-error">{{ $errors->first('pledge') }}</div>
								@endif
							</div>

							<div class="form-group_field{{ $errors->has('tenants') ? ' has-error' : '' }}">
								<input type="text" id="tenants" name="tenants" placeholder="шт." required v-field>
								<label  for="tenants">Всего жильцов</label>
								@if ($errors->has('tenants'))
									<div class="msg-error">{{ $errors->first('tenants') }}</div>
								@endif
							</div>

							<div class="form-group_field{{ $errors->has('text') ? ' has-error' : '' }}">
								<autosize-textarea
									id="text"
									name="text"
									value="{{ old('text') }}"
									:required="true"
									:max="1000">
								</autosize-textarea>
								@if ($errors->has('text'))
									<div class="msg-error">{{ $errors->first('text') }}</div>
								@endif
							</div>

							<div class="form-group_field{{ $errors->has('tags') ? ' has-error' : '' }}">
								<form-select name="tags[]" text="Особенности" :multiple="true">
									@foreach ($tags as $tag)
										<select-option name="{{ $tag->name }}" value="{{ $tag->tag_id }}"></select-option>
									@endforeach
								</form-select>
								@if ($errors->has('tags'))
									<div class="msg-error">{{ $errors->first('tags') }}</div>
								@endif
							</div>

							<div class="btns-group">
								<button type="submit" class="btn btn_primary" v-ripple>Отправить</button>
								<button type="reset" class="btn btn_default" v-ripple @click="resetFields">Сбросить</button>
							</div>
						</ajax-form>
					</transition>
				</div>
			</div>

		</tab>
		<tab href="step-3">
			<div class="page__block">
				<div class="form-group">
					<h2 class="page-title">Осталось загрузить фотографии</h2>
					<ul class="page__list">
						<li>
							<p>Не допускаются фотографии с водяными знаками, чужими объектами и рекламными баннерами</p>
						</li>
						<li>
							<p>Форматы: JPG, PNG или GIF</p>
						</li>
						<li>
							<p>Размер не менее 350x285 пкс. и не более 10 МБ</p>
						</li>
						<li>
							<p>Можно прикрепить не больше 6 фотографий</p>
						</li>
					</ul>
				</div>

				<img-uploader error="{{ $errors->has('upload-imgs') ? $errors->first('upload-imgs') : '' }}"></img-uploader>
			</div>
		</tab>
	</tabs>
@endsection

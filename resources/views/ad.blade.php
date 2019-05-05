@extends('layouts.app')

@section('title')
	Размещение объявления
@endsection

@section('main-header')
	<h1 class="page-title">Объявление №{{ $ad->ad_id }}</h1>
@endsection

@section('content')
	<div class="page__block_centered">
		<div class="form-group page__block">
			<h3 class="page-title">
				<span>Тип объявления:</span>
				@if ($page == 'neighbors')
					<a class="form-text_link" href="{{ url($page) }}">Поиск соседей</a></h3>
				@elseif ($page == 'share')
					<a class="form-text_link" href="{{ url($page) }}">Подселение</a></h3>
				@endif
		</div>

		<masonry-item
			:item="{{ $ad }}"
			:user-id="{{ Auth::check() ? Auth::id() : 'null' }}"
			page="{{ $page }}"
			:location="{{ $page == 'share' ? 'true' : 'false' }}"
			class="masonry__item">
		</masonry-item>
	</div>
@endsection

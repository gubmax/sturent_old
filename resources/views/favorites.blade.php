@extends('layouts.app')

@section('title')
	Избранные объявления
@endsection

@section('main-header')
  <h1 class="page-title">Избранные объявления</h1>
@endsection

@section('tabs')
	<tabs-header>
		<tab-title name="Поиск соседей" href="neighbors" :selected="true"></tab-title>
		<tab-title name="Подселение" href="share"></tab-title>
	</tabs-header>
@endsection

@section('content')
	@if (Auth::guest())
		<div class="msg-info">
			<i class="msg-info__icon">
				<svg class="sr-icon sr-icon_info" viewBox="0 0 24 24">
					<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path>
				</svg>
			</i>
			<p>Выполните вход на сайт, чтобы Ваши избранные объявления сохранились и были доступны через продолжительное время или при работе на другом устройстве.</p>
		</div>
	@endif

	<tabs>
		<tab href="neighbors" :selected="true">
			<masonry
				url="/api/favorites"
				page="neighbors"
				:load="false"
				:user-id="{{ Auth::id() ?? 'null' }}"
				:favorites="[{{ Session::get('favorites_id.neighbors') ? implode(",", Session::get('favorites_id.neighbors')) : 'null' }}]"
				:clearing="true">
			</masonry>
		</tab>
		<tab href="share">
			<masonry
				url="/api/favorites"
				page="share"
				:load="false"
				:user-id="{{ Auth::id() ?? 'null' }}"
				:favorites="[{{ Session::get('favorites_id.share') ? implode(",", Session::get('favorites_id.share')) : 'null' }}]"
				:clearing="true">
			</masonry>
		</tab>
	</tabs>
@endsection

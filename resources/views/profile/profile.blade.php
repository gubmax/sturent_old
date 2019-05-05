@extends('layouts.app')

@section('title')
	@if (Auth::id() == $user->id)
		Мои объявления
	@else
		Профиль пользователя
	@endif
@endsection

@section('main-header')
	@if (Auth::id() == $user->id)
    <h1 class="page-title">Мои объявления</h1>
  @else
    <div class="user-info">
      @if (!is_null($user->avatar))
      	<img class="user-info__img" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="{{ url('/images/avatar/'.$user->avatar.'.jpg') }}">
      @else
        <img class="user-info__img" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="{{ url('/images/avatar/avatar.jpg') }}">
      @endif
			<div class="user-info__name">{{ $user->name }}</div>
    </div>
  @endif
@endsection

@section('tabs')
	<tabs-header>
		<tab-title name="Поиск соседей" href="neighbors" :selected="true"></tab-title>
		<tab-title name="Подселение" href="share"></tab-title>
	</tabs-header>
@endsection

@section('content')
	<tabs>
		<tab href="neighbors" :selected="true">
			<masonry :url="'/api/profile/'+{{ $user->id }}"
				page="neighbors"
				:load="false"
				:location="false"
				:user-id="{{ Auth::id() ?? 'null' }}">
			</masonry>
		</tab>
		<tab href="share">
			<masonry :url="'/api/profile/'+{{ $user->id }}"
				page="share"
				:load="false"
				:user-id="{{ Auth::id() ?? 'null' }}">
			</masonry>
		</tab>
	</tabs>
@endsection

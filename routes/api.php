<?php

use Illuminate\Support\Facades\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Получить объявления для пользователя
Route::get('profile/{id?}/neighbors', 'ProfileController@getProfileNeighborsAds')
    ->where('id', '[0-9]+');
Route::get('profile/{id?}/share', 'ProfileController@getProfileShareAds')
    ->where('id', '[0-9]+');
Route::get('profile/{id?}/contact-data', 'ProfileController@getContactData')
    ->where('id', '[0-9]+');

// Получить объявления в Избранном
Route::get('favorites/neighbors', 'FavoritesController@getNeighborsFavorites');
// Получить объявления в Избранном
Route::get('favorites/share', 'FavoritesController@getShareFavorites');
// Добавить или удалить объявление в Избранное
Route::get('favorites/switch/{id}', 'FavoritesController@switchFavorites');
// Очистить Избранное
Route::get('favorites/clear', 'FavoritesController@clearFavorites');


// Получить объявления по аренде недвижимости
Route::get('neighbors', 'NeighborsController@getAds');
// Сделать объявление о поиске соседей актуальным или не актуальным
Route::get('neighbors/relevance/{ad_id}', 'NeighborsController@swithOnRelevance')
    ->where('ad_id', '[0-9]+')
    ->middleware('auth');
// Удалить  объявление о поиске соседей
Route::get('/neighbors/del/{ad_id}', 'NeighborsController@delAd')
    ->where('ad_id', '[0-9]+')
    ->middleware('auth');
// Загрузить изображения к объявлению о поиске соседей
Route::post('/neighbors/upload-img', 'NeighborsController@uploadImage')
    ->middleware('auth');
// Загрузить координаты для меток на карте
Route::get('/neighbors/coords', 'NeighborsController@getCoords');


// Получить объявления по аренде недвижимости
Route::get('share', 'ShareController@getAds');
// Получить конретное объявление
Route::get('/share/{ad_id}', 'ShareController@getAd')
    ->where('ad_id', '[0-9]+');
// Сделать объявление о поиске соседей актуальным или не актуальным
Route::get('share/relevance/{ad_id}', 'ShareController@swithOnRelevance')
    ->where('ad_id', '[0-9]+')
    ->middleware('auth');
// Удалить  объявление о поиске соседей
Route::get('/share/del/{ad_id}', 'ShareController@delAd')
    ->where('ad_id', '[0-9]+')
    ->middleware('auth');
// Загрузить изображения к объявлению о поиске соседей
Route::post('/share/upload-img', 'ShareController@uploadImage')
    ->middleware('auth');
// Загрузить координаты для меток на карте
Route::get('/share/coords', 'ShareController@getCoords');

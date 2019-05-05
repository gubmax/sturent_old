<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Домашняя страница
Route::get('/', 'HomeController@index');

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')
  ->middleware('guest')
	->name('login');
$this->post('login', 'Auth\LoginController@login')
  ->middleware('guest');
$this->post('logout', 'Auth\LoginController@logout')
	->name('logout');

// Registration Routes...
$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
$this->post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');

// Авторизация через Вконтакте
Route::get('auth/vk', 'Auth\LoginController@redirectToProvider');
Route::get('auth/vk/callback', 'Auth\LoginController@handleProviderCallback');

// Показать личный кабинет пользователя
Route::get('profile/{id?}', 'ProfileController@showProfile')
    ->where('id', '[0-9]+');
// Показать страницу редактирования профиля
Route::get('profile/edit', 'ProfileController@editProfile')
    ->middleware('auth');
// Сохранить изменения в профиле
Route::post('profile/save', 'ProfileController@saveProfile')
    ->middleware('auth');


// Показать страницу с объявлениями о поиске соседей
Route::get('/neighbors', 'NeighborsController@showNeighborsPage');
// Показать страницу редактирования объявления о поиске соседей
Route::get('/neighbors/edit/{ad_id}', 'AdController@editAdNeighbors')
    ->where('ad_id', '[0-9]+')
    ->middleware('auth');
// Сохарнить отредактированное объявление о поиске соседей
Route::post('/neighbors/save/{ad_id}', 'AdController@saveAdNeighbors')
    ->where('ad_id', '[0-9]+')
    ->middleware('auth');
// Получить изображения для объявления о поиске соседа
Route::get('/neighbors/get-images/{ad_id}', 'NeighborsController@getImages')
    ->where('ad_id', '[0-9]+');


// Показать страницу с объявлениями о поиске соседей
Route::get('/share', 'ShareController@showSharePage');
// Показать страницу редактирования объявления о поиске соседей
Route::get('/share/edit/{ad_id}', 'AdController@editAdShare')
    ->where('ad_id', '[0-9]+')
    ->middleware('auth');
// Сохарнить отредактированное объявление о поиске соседей
Route::post('/share/save/{ad_id}', 'AdController@saveAdShare')
    ->where('ad_id', '[0-9]+')
    ->middleware('auth');
// Получить изображения для объявления о поиске соседа
Route::get('/share/get-images/{ad_id}', 'ShareController@getImages')
    ->where('ad_id', '[0-9]+');
// Показать страницу с интерактивной картой
Route::get('map', 'MapController@showMap');


// Показать форму добавления объявдения.
Route::get('/add', 'AddController@showAdAdd');
// Добавить объявление о поиске соседей
Route::post('/add/neighbors', 'NeighborsController@addAd')
    ->middleware('auth');
// Добавить объявление о подселении
Route::post('/add/share', 'ShareController@addAd')
    ->middleware('auth');


// Показать конкретное объявление
Route::get('ad', 'AdController@showAd');

// Показать избранные объявления
Route::get('favorites', function() {
    return view('favorites');
});

// Показать страницу аренды недвижимости
Route::get('share', 'ShareController@showSharePage');

// Показать страницу ответов на частые вопросы
Route::get('info', function() {
    return view('info');
});

// Показать страницу обратной связи
Route::get('contacts', function() {
    return view('contacts');
});
Route::post('contacts', function() {
    return 'done';
});

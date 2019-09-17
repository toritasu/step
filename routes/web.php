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


// TOPページ表示
Route::get('/', 'HomeController@index')->name('home');
// STEP一覧ページの表示
Route::get('/steps/list', 'StepController@list')->name('steps.list');
// STEP詳細ページの表示
Route::get('/steps/{id}/detail', 'StepController@detail')->name('steps.detail');
// Ajax通信によるstepsテーブルの取得
Route::get('/ajax/steps', 'Ajax\StepController@index');

// |--------------
// | ログイン中のみ
// |--------------
Route::group(['middleware' => 'auth'], function() {
  // STEP投稿ページの表示
  Route::get('/steps/create', 'StepController@showCreateForm')->name('steps.create');
  // STEP投稿処理の実行
  Route::post('/steps/create', 'StepController@create');
  // STEP編集ページの表示
  Route::get('/steps/{id}/edit', 'StepController@showEditForm')->name('steps.edit');
  // STEP編集処理の実行
  Route::post('/steps/{id}/edit', 'StepController@edit');
  // マイページの表示
  Route::get('/steps/users/{id}', 'HomeController@mypage')->name('mypage');
  // プロフィール編集の表示
  Route::get('/steps/users/{id}/edit', 'HomeController@showProfileEditForm')->name('profile.edit');
  // プロフィール編集処理の実行
  Route::post('/steps/users/{id}/edit', 'HomeController@profileEdit');
  // Ajax通信によるuserテーブルの取得
  Route::get('/ajax/user', 'Ajax\StepController@user');
  // Ajax通信によるstep情報の取得
  Route::get('/ajax/{id}/detail', 'Ajax\StepController@detail');
  // Ajax通信によるchallengesテーブルへの挿入
  Route::get('/ajax/{id}/challenge/start', 'Ajax\StepController@goChallenge');
  // Ajax通信によるchallengesテーブルの削除
  Route::get('/ajax/{id}/challenge/abort', 'Ajax\StepController@abortChallenge');
  // Ajax通信によるsubstepのクリア処理
  Route::get('/ajax/{id}/substep/{substep_id}/clear', 'Ajax\StepController@clearSubstep');
});


// ユーザー認証機能
Auth::routes();
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

<?php

use Illuminate\Http\Request;

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

// Installation
Route::post('ping', '\Platform\Controllers\InstallationController@postPing');
Route::get('install', '\Platform\Controllers\InstallationController@getInstall');
Route::post('install', '\Platform\Controllers\InstallationController@postInstall');

// Public routes
Route::get('page', '\Platform\Controllers\Pages\PageController@getPage');
Route::get('i18n/languages', '\Platform\Controllers\Core\Content@getAvailableLanguages');
Route::get('i18n/{lang}', '\Platform\Controllers\Core\Content@getTranslation');

Route::group(['prefix' => 'localization'], function() {
    Route::get('locales', '\Platform\Controllers\Core\Localization@getLocales');
    Route::get('timezones', '\Platform\Controllers\Core\Localization@getTimezones');
    Route::get('currencies', '\Platform\Controllers\Core\Localization@getCurrencies');
});

// Secured app routes
Route::group(['middleware' => 'auth:api'], function() {

    // Admin related routes
    Route::group(['prefix' => 'admin',  'middleware' => 'role:1'], function () {
        Route::get('stats', '\Platform\Controllers\App\AdminController@getStats');
    });
    
    // User related routes
    Route::group(['prefix' => 'user',  'middleware' => 'role:2'], function () {
        // Resume
        Route::get('download-resume', '\Platform\Controllers\Resume\ResumeController@getDownloadResumePdf');
        Route::get('resume', '\Platform\Controllers\Resume\ResumeController@getUserResume');
        Route::get('tags', '\Platform\Controllers\Resume\ResumeController@getTags');
    });
});

// App authorization routes
Route::prefix('auth')->group(function () {
    Route::post('register', '\Platform\Controllers\App\AuthController@register');
    Route::post('login', '\Platform\Controllers\App\AuthController@login');
    Route::post('impersonate', '\Platform\Controllers\App\AuthController@impersonate');
    Route::get('refresh', '\Platform\Controllers\App\AuthController@refresh');
    Route::post('password/reset', '\Platform\Controllers\App\AuthController@passwordReset');
    Route::post('password/reset/validate-token', '\Platform\Controllers\App\AuthController@passwordResetValidateToken');
    Route::post('password/update', '\Platform\Controllers\App\AuthController@passwordUpdate');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('user', '\Platform\Controllers\App\AuthController@user');
        Route::post('logout', '\Platform\Controllers\App\AuthController@logout');
        Route::post('profile', '\Platform\Controllers\App\AuthController@postUpdateProfile');
    });
});

// Secured app routes
Route::group(['prefix' => 'app', 'middleware' => 'auth:api'], function() {

    // DataTable
    Route::get('data-table', '\Platform\Controllers\App\DataTableController@getDataList');
    Route::post('data-table/delete', '\Platform\Controllers\App\DataTableController@postDeleteRecords');
    Route::get('data-table/export', '\Platform\Controllers\App\DataTableController@getExport');

    // DataForm
    Route::get('data-form', '\Platform\Controllers\App\DataFormController@getDataForm');
    Route::post('data-form/relation', '\Platform\Controllers\App\DataFormController@postGetRelation');
    Route::post('data-form/save', '\Platform\Controllers\App\DataFormController@postSaveRecord');
});
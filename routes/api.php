<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
/*
|--------------------------------------------------------------------------
| API Routes
|-------------------------------------------------------------------------- 
*/
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Register and Login User 
Route::group([
    'namespace' => 'App\Http\Controllers',
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('/register', 'AuthController@register');
    Route::post('/login', 'AuthController@login');
});

// Register and Login Admin
Route::group([
    'namespace' => 'App\Http\Controllers',
    'middleware' => 'api',
    'prefix' => 'adminauth'
], function () {
    Route::post('/admin_register', 'AdminAuthController@register');
    Route::post('/admin_login', 'AdminAuthController@login');
});

//Admin Activities On registered users 
Route::group([
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'admin'
], function () {
        Route::get('/all_users', 'UsersController@get_all_users');
        Route::get('/get_user/{id}', 'UsersController@get_user_by_id');
        Route::get('/pending_request', 'UsersController@view_all_pending_request');
        Route::post('/edit_user/{id}', 'UsersController@update_user');
        Route::get('/get_admin/{id}', 'UsersController@get_admin_info');
        Route::post('/approve_user/{id}', 'UsersController@approve_user');
        Route::post('/decline_user/{id}', 'UsersController@decline_user');
        Route::post('/logout', 'AdminAuthController@logout');



});


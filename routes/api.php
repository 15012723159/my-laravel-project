<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IndexController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\MoiveController;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Api\ArrController;
use App\Http\Controllers\Api\StrController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('index')->group(function () {
    Route::get('index', [IndexController::class, 'index']);
    Route::get('phpinfo', [IndexController::class, 'phpinfo']);
});

Route::middleware('profile')->group(function () {
    Route::prefix('profile')->group(function () {
        Route::get('index', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('edit', [ProfileController::class, 'edit']);
        Route::get('create', [ProfileController::class, 'create']);
        Route::get('batchAll', [ProfileController::class, 'batchAll']);
        Route::get('curl', [ProfileController::class, 'curl']);
        Route::get('log', [ProfileController::class, 'log']);
    });

});

Route::get('/greeting', function () {
    return 'Hello World';
});


Route::get('/user/{id}/{action}', [ProfileController::class, 'user']);

/*Route::fallback(function () {
    return "未定义路由";
});*/

/*Route::resources([
    'moives' => MovieController::class,
]);*/

Route::resource('photos', PhotoController::class)->missing(function (Request $request) {

    return Redirect::route('photos.index');
});


Route::prefix('arr')->group(function () {
    Route::get('head', [ArrController::class, 'head']);
});

Route::prefix('str')->group(function () {
    Route::get('after', [StrController::class, 'after']);
    Route::get('camel', [StrController::class, 'camel']);

});





<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\newsController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\videosController;
use App\Http\Controllers\slideShowController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::resource('videos', videosController::class);
Route::get('/index-dashboard', [videosController::class, 'indexDashboard']);

Route::post('/news-update/{id}', [newsController::class, 'updateNews']);
Route::resource('news', newsController::class);
Route::get('/views-dashboard', [newsController::class, 'viewsDashboard']);

Route::resource('announcement', AnnouncementController::class);
Route::get('/index-announcement', [AnnouncementController::class, 'indexAnnouncement']);

Route::post('/pelatihan-update/{id}', [PelatihanController::class, 'updatePelatihan']);
Route::resource('pelatihan', PelatihanController::class);
Route::get('/views-dashboard-pelatihan', [PelatihanController::class, 'viewsDashboard']);

Route::post('slideshow-update/{id}', [slideShowController::class, 'updateSlideShow']);
Route::resource('slideshow', slideShowController::class);
Route::get('/views-dashboard-slideshow', [slideShowController::class, 'viewsDashboard']);
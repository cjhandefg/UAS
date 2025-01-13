<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MapTugasController;

Route::get('/tugas1', [MapTugasController::class, 'tugas1']);

Route::get('/', function () {
  return view('landing');
});

use App\Http\Controllers\LandingController;
Route::get('/', [LandingController::class, 'index']);

use App\Http\Controllers\MapDataController;
use App\Http\Controllers\MarkerController;
Route::get('/interactive', [MapDataController::class, 'index'])->name('map.index');

Route::post('/api/markers', [MarkerController::class, 'storeMarker']);
Route::get('/api/markers', [MarkerController::class, 'getMarkers']);
Route::put('/api/markers/{id}', [MarkerController::class, 'updateMarker']);
Route::delete('/api/markers/{id}', [MarkerController::class, 'deleteMarker']);

Route::get('/markers/{id}/directions', [MarkerController::class, 'showDirections'])->name('markers.directions');



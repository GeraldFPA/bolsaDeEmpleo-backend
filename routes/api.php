<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\PostulacionController;


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::middleware('auth:sanctum')->post('/logout', [AuthenticatedSessionController::class, 'destroy']);
Route::middleware('auth:sanctum')->post('/oferta', [OfertaController::class, 'store']);
Route::middleware('auth:sanctum')->get('/ofertas', [OfertaController::class, 'index']);
Route::middleware('auth:sanctum')->post('/postular', [PostulacionController::class, 'store']);
Route::middleware('auth:sanctum')->get('/postulaciones/recibidas', [PostulacionController::class, 'recibidas']);
Route::get('/cv/{id}', [PostulacionController::class, 'descargarCV']);
Route::middleware('auth:sanctum')->get('/postulacion/existe/{oferta}', [PostulacionController::class, 'verificarPostulacion']);
Route::middleware('auth:sanctum')->get('/ofertas/{id}', [OfertaController::class, 'showByUserId']); 
Route::middleware('auth:sanctum')->get('/postulaciones/hechas/{id}', [PostulacionController::class, 'showByUserId']);
Route::middleware('auth:sanctum')->get('/postulaciones/recibidas/{id}', [PostulacionController::class, 'showByOfertaId']);

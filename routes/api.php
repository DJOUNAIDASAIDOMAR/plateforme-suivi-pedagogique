<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CoursController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| TEST DE L'API
|--------------------------------------------------------------------------
*/

Route::get('/test', function () {
    return response()->json([
        'message' => 'API Plateforme de Suivi Pédagogique OK'
    ]);
});


/*
|--------------------------------------------------------------------------
| INSCRIPTION MOBILE
|--------------------------------------------------------------------------
*/

// Liste des filières pour le formulaire étudiant
Route::get('/filieres', [AuthController::class, 'filieres']);

// Création d'un compte étudiant ou formateur
Route::post('/register', [AuthController::class, 'register']);


/*
|--------------------------------------------------------------------------
| CONNEXION MOBILE
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);


/*
|--------------------------------------------------------------------------
| COURS
|--------------------------------------------------------------------------
*/

Route::get('/cours', [CoursController::class, 'index']);
Route::get('/cours/{id}', [CoursController::class, 'show']);


/*
|--------------------------------------------------------------------------
| ROUTES PROTÉGÉES PAR SANCTUM
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Utilisateur actuellement connecté
    Route::get('/me', [AuthController::class, 'me']);

    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout']);
});
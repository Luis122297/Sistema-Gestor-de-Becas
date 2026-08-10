<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ScholarshipController;
use App\Http\Controllers\Api\StudentDocumentController;
use App\Http\Controllers\Api\TwoFactorController;
use App\Http\Controllers\ProfessorController;

Route::middleware('auth:sanctum')->get('/scholarships/my-status', [ScholarshipController::class, 'myStatus']);

Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/2fa/verify', [TwoFactorController::class, 'verifyChallenge']);
});

Route::middleware(['auth:sanctum', 'throttle:60,1', 'ability:*'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/2fa/generate', [TwoFactorController::class, 'generateSecret']);

    Route::get('/profesores', [ProfessorController::class, 'index']);
    Route::post('/profesores', [ProfessorController::class, 'store']);
    Route::put('/profesores/{id}', [ProfessorController::class, 'update']);
    Route::delete('/profesores/{id}', [ProfessorController::class, 'destroy']);

    Route::get('/scholarships/export', [ScholarshipController::class, 'export']);
    Route::post('/scholarships/my-request', [ScholarshipController::class, 'submitRequest']);
    Route::put('/scholarships/{id}/assign-percentage', [ScholarshipController::class, 'assignPercentage']);
    Route::put('/scholarships/{id}/recommend', [ScholarshipController::class, 'recommend']);
    Route::apiResource('scholarships', ScholarshipController::class);

    Route::post('/students/my-documents', [StudentDocumentController::class, 'uploadMyDocuments']);
    Route::post('/students/{id}/documents', [StudentDocumentController::class, 'uploadDocuments']);
    Route::get('/documents/{type}/{filename}', [StudentDocumentController::class, 'serveDocument'])->name('documents.show');
});
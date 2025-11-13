<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\SubTaskController;

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

// ==============================
// 🔐 PUBLIC AUTH ROUTES
// ==============================
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// ==============================
// 🔒 PROTECTED ROUTES (Require Authentication)
// ==============================
Route::middleware('auth:sanctum')->group(function () {

    // 👤 Auth Routes
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/profile', [AuthController::class, 'profile']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // ==============================
    // 📁 PROJECTS
    // ==============================
    Route::prefix('projects')->group(function () {
        Route::get('/', [ProjectController::class, 'index']); // Get all projects
        Route::post('/', [ProjectController::class, 'store']); // Create project
        Route::get('/{project_id}', [ProjectController::class, 'show']); // Get project detail
        Route::get('/{project_id}/members', [ProjectController::class, 'members']); // Get project members
    });

    // ==============================
    // 📦 CARDS
    // ==============================
    Route::prefix('cards')->group(function () {
        Route::get('/project/{project_id}', [CardController::class, 'index']); // Get cards by project
        Route::get('/project/{project_id}/status/{status}', [CardController::class, 'byStatus']); // Get cards by status
        Route::get('/{card_id}', [CardController::class, 'show']); // Get card detail
        Route::post('/project/{project_id}', [CardController::class, 'store']); // Create card
        Route::patch('/{card_id}/status', [CardController::class, 'updateStatus']); // Update card status
    });

    // ==============================
    // 📝 SUBTASKS
    // ==============================
    Route::prefix('subtasks')->group(function () {
        Route::get('/card/{card_id}', [SubTaskController::class, 'index']); // Get subtasks by card
        Route::get('/card/{card_id}/status/{status}', [SubTaskController::class, 'byStatus']); // Get subtasks by status
        Route::get('/{sub_task_id}', [SubTaskController::class, 'show']); // Get subtask detail
        Route::post('/card/{card_id}', [SubTaskController::class, 'store']); // Create subtask
        Route::patch('/{sub_task_id}/status', [SubTaskController::class, 'updateStatus']); // Update subtask status
        Route::patch('/{sub_task_id}/toggle', [SubTaskController::class, 'toggle']); // Toggle subtask completion
    });
});


<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Task\TaskAttachmentController;
use App\Http\Controllers\Task\TaskCommentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\User\ChangePasswordController;
use App\Http\Controllers\User\ProfileController;
use Illuminate\Support\Facades\Route;


// Public Routes
Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);

// Protected Routes
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::post('/logout', LogoutController::class);

    // User Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']); // Using POST for file uploads
    Route::post('/change-password', ChangePasswordController::class);

    // Groups
    Route::apiResource('groups', GroupController::class);
    Route::prefix('groups/{group}')->group(function () {
        Route::get('/members', [GroupController::class, 'members']);
        Route::post('/members', [GroupController::class, 'addMember']);

        // Tasks
        Route::get('/tasks', [TaskController::class, 'index']);
        Route::post('/tasks', [TaskController::class, 'store']);
    });

    // Task Comments & Attachments
    Route::post('tasks/{task}/comments', TaskCommentController::class);
    Route::post('tasks/{task}/attachments', TaskAttachmentController::class);

    // Dashboard & Analytics
    Route::get('/dashboard/general', [DashboardController::class, 'index']);
    Route::get('/dashboard/groups/{group}', [DashboardController::class, 'groupStats']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread', [NotificationController::class, 'unread']);
    Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::put('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
});

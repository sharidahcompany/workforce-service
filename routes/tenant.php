<?php

declare(strict_types=1);

use App\Http\Controllers\BranchController;
use App\Http\Controllers\BuffetController;
use App\Http\Controllers\BuffetItemController;
use App\Http\Controllers\BuffetOrderController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectUserController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SprintController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisionController;
use App\Http\Middleware\InitializeTenantFromHeader;
use App\Http\Middleware\SetLocaleFromHeader;
use App\Models\Tenant\SprintStage;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1')->middleware([InitializeTenantFromHeader::class, SetLocaleFromHeader::class])->group(function () {

    Route::middleware('tenant.jwt')->group(function () {
        // Organization Sturcture
        Route::delete('visions', [VisionController::class, 'destroy']);
        Route::apiResource('visions', VisionController::class);

        Route::delete('goals', [GoalController::class, 'destroy']);
        Route::apiResource('goals', GoalController::class);

        Route::delete('branches', [BranchController::class, 'destroy']);
        Route::apiResource('branches', BranchController::class);

        Route::delete('departments', [DepartmentController::class, 'destroy']);
        Route::apiResource('departments', DepartmentController::class);

        // HR
        Route::delete('employees', [UserController::class, 'destroy']);
        Route::apiResource('employees', UserController::class);

        // Project Management
        Route::delete('projects', [ProjectController::class, 'destroy']);
        Route::post('projects/{project}/users', [ProjectUserController::class, 'store']);
        Route::put('projects/{project}/users', [ProjectUserController::class, 'update']);
        Route::delete('projects/{project}/users', [ProjectUserController::class, 'destroy']);
        Route::post('projects/{project}/files', [ProjectController::class, 'uploadFiles']);
        Route::delete('projects/{project}/files/{mediaId}', [ProjectController::class, 'deleteFile']);
        Route::get('projects/{projectId}/files/{mediaId}', [ProjectController::class, 'downloadFile']);
        Route::apiResource('projects', ProjectController::class);

        Route::post('sprints/{sprint}/stages/reorder', [SprintController::class, 'reorder']);

        Route::delete('sprints', [SprintController::class, 'destroy']);
        Route::apiResource('sprints', SprintController::class);

        Route::delete('stages', [SprintStage::class, 'destroy']);
        Route::apiResource('stages', SprintStage::class);


        Route::delete('tasks', [TaskController::class, 'destroy']);
        Route::post('tasks/{task}/move', [TaskController::class, 'move']);
        Route::apiResource('tasks', TaskController::class);

        Route::prefix('tasks/{task}')->group(function () {
            Route::get('comments', [CommentController::class, 'indexTask']);
            Route::post('comments', [CommentController::class, 'storeTask']);
        });

        Route::prefix('tasks/{task}')->group(function () {
            Route::get('rates', [RatingController::class, 'indexTask']);
            Route::post('rates', [RatingController::class, 'storeTask']);
        });

        Route::apiResource('comments', CommentController::class);

        Route::delete('ratings', [RatingController::class, 'destroy']);
        Route::apiResource('ratings', RatingController::class);

        // Buffet
        Route::delete('buffets', [BuffetController::class, 'destroy']);
        Route::apiResource('buffets', BuffetController::class);

        Route::delete('buffet-items', [BuffetItemController::class, 'destroy']);
        Route::apiResource('buffet-items', BuffetItemController::class);

        Route::delete('buffet-orders', [BuffetOrderController::class, 'destroy']);
        Route::apiResource('buffet-orders', BuffetOrderController::class);
    });
});

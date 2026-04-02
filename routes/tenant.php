<?php

declare(strict_types=1);

use App\Http\Controllers\BranchController;
use App\Http\Controllers\BuffetController;
use App\Http\Controllers\BuffetItemController;
use App\Http\Controllers\BuffetOrderController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisionController;
use App\Http\Middleware\InitializeTenantFromHeader;
use App\Http\Middleware\SetLocaleFromHeader;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1')->middleware([InitializeTenantFromHeader::class, SetLocaleFromHeader::class])->group(function () {
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

    // Buffet
    Route::delete('buffets', [BuffetController::class, 'destroy']);
    Route::apiResource('buffets', BuffetController::class);

    Route::delete('buffet-items', [BuffetItemController::class, 'destroy']);
    Route::apiResource('buffet-items', BuffetItemController::class);

    Route::delete('buffet-orders', [BuffetOrderController::class, 'destroy']);
    Route::apiResource('buffet-orders', BuffetOrderController::class);
});

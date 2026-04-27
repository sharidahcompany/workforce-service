<?php

declare(strict_types=1);

use App\Http\Controllers\AnnualVacationController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendancePermissionController;
use App\Http\Controllers\BenefitController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BuffetController;
use App\Http\Controllers\BuffetItemController;
use App\Http\Controllers\BuffetOrderController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobInterviewController;
use App\Http\Controllers\CareerPostController;
use App\Http\Controllers\JobOfferController;
use App\Http\Controllers\MissionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectUserController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ScholarshipController;
use App\Http\Controllers\ScholarshipRequestController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SprintController;
use App\Http\Controllers\SprintStageController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisionController;
use App\Http\Middleware\InitializeTenantFromHeader;
use App\Http\Middleware\SetLocaleFromHeader;
use App\Models\Tenant\SprintStage;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1')->middleware([InitializeTenantFromHeader::class, SetLocaleFromHeader::class])->group(function () {

    Route::middleware('tenant.jwt')->group(function () {
        
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Organization Sturcture
        Route::delete('visions', [VisionController::class, 'destroy']);
        Route::apiResource('visions', VisionController::class);

        Route::delete('goals', [GoalController::class, 'destroy']);
        Route::apiResource('goals', GoalController::class);

        Route::delete('branches', [BranchController::class, 'destroy']);
        Route::apiResource('branches', BranchController::class);

        Route::delete('departments', [DepartmentController::class, 'destroy']);
        Route::apiResource('departments', DepartmentController::class);
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HR
        Route::delete('users', [UserController::class, 'destroy']);
        Route::post('users/update/{employee}', [UserController::class, 'update']);
        Route::apiResource('users', UserController::class);

        Route::delete('careers', [CareerController::class, 'destroy']);
        Route::post('careers/update/{job}', [CareerController::class, 'update']);
        Route::apiResource('careers', CareerController::class);

        Route::delete('benefits', [BenefitController::class, 'destroy']);
        Route::apiResource('benefits', BenefitController::class);

        Route::delete('career-posts', [CareerPostController::class, 'destroy']);
        Route::apiResource('career-posts', CareerPostController::class);

        Route::put('job-applications/accept/{jobapplication}',[JobApplicationController::class,'jobApplicationAccepted']);
        Route::put('job-applications/confirme/{jobapplication}',[JobApplicationController::class,'jobApplicationConfirmed']);
        Route::delete('job-applications', [JobApplicationController::class, 'destroy']);
        Route::apiResource('job-applications', JobApplicationController::class);

        Route::delete('job-interviews', [JobInterviewController::class, 'destroy']);
        Route::post('job-interviews/reschedule/{interview}', [JobInterviewController::class, 'interviewReschedule']);
        Route::apiResource('job-interviews', JobInterviewController::class);


        Route::delete('job-offers', [JobOfferController::class, 'destroy']);
        Route::apiResource('job-offers', JobOfferController::class);


        Route::delete('shifts', [ShiftController::class, 'destroy']);
        Route::post('shifts/{id}/assign-users', [ShiftController::class, 'assignUsers']);
        Route::put('shifts/{id}/sync-users', [ShiftController::class, 'syncUsers']);
        Route::delete('shifts/{id}/remove-users', [ShiftController::class, 'removeUsers']);
        Route::apiResource('shifts', ShiftController::class);

        Route::delete('annual-vacations', [AnnualVacationController::class, 'destroy']);
        Route::apiResource('annual-vacations', AnnualVacationController::class);

        Route::delete('holidays', [HolidayController::class, 'destroy']);
        Route::apiResource('holidays', HolidayController::class);

        Route::delete('missions', [MissionController::class, 'destroy']);
        Route::apiResource('missions', MissionController::class);

        Route::post('attendances/{id}/check-in', [AttendanceController::class, 'checkIn']);
        Route::post('attendances/{id}/check-out', [AttendanceController::class, 'checkOut']);
        Route::delete('attendances', [AttendanceController::class, 'destroy']);
        Route::apiResource('attendances', AttendanceController::class);

        Route::delete('attendance-permissions', [AttendancePermissionController::class, 'destroy']);
        Route::apiResource('attendance-permissions', AttendancePermissionController::class);

        Route::delete('scholarships', [ScholarshipController::class, 'destroy']);
        Route::apiResource('scholarships', ScholarshipController::class);

        Route::delete('scholarship-requests', [ScholarshipRequestController::class, 'destroy']);
        Route::apiResource('scholarship-requests', ScholarshipRequestController::class);

        Route::delete('experiences', [ExperienceController::class, 'destroy']);
        Route::apiResource('experiences', ExperienceController::class);
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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

        Route::delete('stages', [SprintStageController::class, 'destroy']);
        Route::apiResource('stages', SprintStageController::class);


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

        Route::delete('comments', [CommentController::class, 'destroy']);
        Route::post('comments/{comment}', [CommentController::class, 'update']);
        Route::apiResource('comments', CommentController::class);

        Route::delete('ratings', [RatingController::class, 'destroy']);
        Route::apiResource('ratings', RatingController::class);
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Buffet
        Route::delete('buffets', [BuffetController::class, 'destroy']);
        Route::apiResource('buffets', BuffetController::class);

        Route::delete('buffet-items', [BuffetItemController::class, 'destroy']);
        Route::apiResource('buffet-items', BuffetItemController::class);

        Route::delete('buffet-orders', [BuffetOrderController::class, 'destroy']);
        Route::apiResource('buffet-orders', BuffetOrderController::class);
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    });
});

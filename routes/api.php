<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\CodingArenaController;
use App\Http\Controllers\AiTutorController;
use App\Http\Controllers\JobPostingController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\MockInterviewController;
use App\Http\Controllers\GamificationController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\FacultyDashboardController;
use App\Http\Controllers\LiveClassController;
use App\Http\Controllers\StudyMaterialController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AssignmentSubmissionController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{slug}', [CourseController::class, 'show']);

Route::get('/assessments', [AssessmentController::class, 'index']);
Route::get('/assessments/{id}', [AssessmentController::class, 'show']);

Route::get('/jobs', [JobPostingController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/user/profile', [UserProfileController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/assessments/{id}/submit', [AssessmentController::class, 'submit']);
    Route::post('/coding/submit', [CodingArenaController::class, 'submit']);
    Route::post('/tutor/chat', [AiTutorController::class, 'chat'])->middleware('throttle:10,1');
    Route::post('/jobs/{id}/apply', [JobPostingController::class, 'apply']);
    Route::put('/applications/{id}/status', [JobApplicationController::class, 'updateStatus']);
    Route::post('/interview/generate', [MockInterviewController::class, 'generate']);
    Route::post('/student/progress', [GamificationController::class, 'syncProgress']);
    
    Route::get('/faculty/dashboard', [FacultyDashboardController::class, 'index']);

    Route::get('/live-classes', [LiveClassController::class, 'index']);
    Route::post('/live-classes', [LiveClassController::class, 'store']);
    Route::post('/live-classes/{id}/join', [LiveClassController::class, 'join']);

    Route::get('/materials', [StudyMaterialController::class, 'index']);
    Route::post('/materials/upload', [StudyMaterialController::class, 'upload']);
    Route::get('/materials/{id}/download', [StudyMaterialController::class, 'download']);

    Route::get('/assignments', [AssignmentController::class, 'index']);
    Route::post('/assignments', [AssignmentController::class, 'store']);
    Route::post('/assignments/{id}/submit', [AssignmentSubmissionController::class, 'submit']);
    
    Route::get('/faculty/assignments/pending', [AssignmentSubmissionController::class, 'pendingReviews']);
    Route::post('/faculty/assignments/{id}/grade', [AssignmentSubmissionController::class, 'grade']);
});

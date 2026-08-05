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
use App\Http\Controllers\BatchController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\SettingController;

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

    // Admin & Coordinator CRUD routes
    Route::post('/courses', [CourseController::class, 'store']);
    Route::put('/courses/{id}', [CourseController::class, 'update']);
    Route::delete('/courses/{id}', [CourseController::class, 'destroy']);

    Route::get('/modules', [CourseController::class, 'indexModules']);
    Route::post('/modules', [CourseController::class, 'storeModule']);
    Route::put('/modules/{id}', [CourseController::class, 'updateModule']);
    Route::delete('/modules/{id}', [CourseController::class, 'destroyModule']);

    Route::get('/batches', [BatchController::class, 'index']);
    Route::post('/batches', [BatchController::class, 'store']);
    Route::put('/batches/{id}', [BatchController::class, 'update']);
    Route::delete('/batches/{id}', [BatchController::class, 'destroy']);
    Route::post('/batches/{id}/assign-student', [BatchController::class, 'assignStudent']);
    Route::post('/batches/{id}/remove-student', [BatchController::class, 'removeStudent']);
    Route::post('/batches/{id}/transfer-student', [BatchController::class, 'transferStudent']);
    Route::post('/batches/{id}/assign-faculty', [BatchController::class, 'assignFaculty']);

    Route::get('/students', [StudentController::class, 'index']);
    Route::post('/students', [StudentController::class, 'store']);
    Route::put('/students/{id}', [StudentController::class, 'update']);
    Route::delete('/students/{id}', [StudentController::class, 'destroy']);
    Route::post('/students/{id}/admission-status', [StudentController::class, 'updateAdmissionStatus']);

    Route::get('/faculty', [FacultyController::class, 'index']);
    Route::post('/faculty', [FacultyController::class, 'store']);

    Route::get('/counseling', [\App\Http\Controllers\CounselingController::class, 'index']);
    Route::post('/counseling', [\App\Http\Controllers\CounselingController::class, 'store']);
    Route::put('/counseling/{id}', [\App\Http\Controllers\CounselingController::class, 'update']);
    Route::delete('/counseling/{id}', [\App\Http\Controllers\CounselingController::class, 'destroy']);

    Route::get('/attendances', [\App\Http\Controllers\AttendanceController::class, 'index']);
    Route::put('/attendances/{id}', [\App\Http\Controllers\AttendanceController::class, 'update']);

    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index']);
    Route::put('/faculty/{id}', [FacultyController::class, 'update']);

    Route::get('/inactive-students', [StudentController::class, 'inactiveIndex']);
    Route::post('/inactive-students/follow-up', [StudentController::class, 'followUp']);

    Route::post('/jobs', [JobPostingController::class, 'store']);
    Route::get('/packages', [JobPostingController::class, 'packages']);
    Route::post('/packages', [JobPostingController::class, 'storePackage']);
    Route::put('/packages/{id}/status', [JobPostingController::class, 'updatePackageStatus']);

    Route::get('/settings', [SettingController::class, 'index']);
    Route::post('/settings', [SettingController::class, 'update']);

    Route::get('/coordinator/dashboard', [CoordinatorController::class, 'dashboard']);

    // AI & Career Tool Endpoints
    Route::post('/assessment/skill-eval', [AssessmentController::class, 'skillEval']);
    Route::post('/linkedin/optimize', [UserProfileController::class, 'optimizeLinkedIn']);

    // Corporate Hiring & Company Portal Endpoints
    Route::get('/company/candidates', [\App\Http\Controllers\CompanyController::class, 'candidates']);
    Route::post('/company/schedule-interview', [\App\Http\Controllers\CompanyController::class, 'scheduleInterview']);
    Route::post('/company/offer-letter', [\App\Http\Controllers\CompanyController::class, 'offerLetter']);
});

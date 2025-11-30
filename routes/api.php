<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Calamity\CalamityIncidentController;
use App\Http\Controllers\Calamity\CalamityAffectedHouseholdController;
use App\Http\Controllers\Calamity\EvacuationCenterController;
use App\Http\Controllers\Calamity\ReliefItemController;
use App\Http\Controllers\Calamity\ReliefDistributionController;
use App\Http\Controllers\Calamity\DamageAssessmentController;
use App\Http\Controllers\Calamity\NotificationController;
use App\Http\Controllers\Calamity\ResponseTeamMemberController;
use App\Http\Controllers\Calamity\CalamityReportController;
use App\Http\Controllers\Calamity\RescueOperationController;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('calamities', CalamityIncidentController::class);
    // Alternate endpoints under calamity-incidents for photo operations and compatibility
    Route::post('calamity-incidents', [CalamityIncidentController::class, 'store']);
    Route::put('calamity-incidents/{calamity}', [CalamityIncidentController::class, 'update']);
    Route::post('calamity-incidents/{calamity}/photos', [CalamityIncidentController::class, 'uploadPhotos']);
    Route::delete('calamity-incidents/{calamity}/photos/{photoName}', [CalamityIncidentController::class, 'deletePhoto']);
    Route::apiResource('calamity-affected-households', CalamityAffectedHouseholdController::class);
    Route::apiResource('evacuation-centers', EvacuationCenterController::class);
    Route::apiResource('relief-items', ReliefItemController::class);
    Route::apiResource('relief-distributions', ReliefDistributionController::class);
    Route::apiResource('damage-assessments', DamageAssessmentController::class);
    Route::apiResource('notifications', NotificationController::class);
    Route::apiResource('response-team-members', ResponseTeamMemberController::class);
    Route::apiResource('calamity-reports', CalamityReportController::class);
    Route::apiResource('rescue-operations', RescueOperationController::class);
});

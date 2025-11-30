<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\SubFamilyController;
use App\Http\Controllers\CensusController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\PurokController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CalamityController;
use App\Http\Controllers\ResidentTransferController;
use App\Http\Controllers\HouseholdEventController;

//

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', function () {
    return view('welcome-enhanced');
})->name('landing');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes (Require Authentication)
    Route::middleware(['auth','user_access'])->group(function () {
        Route::get('/apps/profiling-only', function () {
            return view('apps.profiling-only.index');
        })->name('apps.profiling_only');
        // Staff-readable residents routes
        Route::get('/staff/residents', [\App\Http\Controllers\ResidentController::class, 'index'])
            ->name('staff.residents.index');
        Route::get('/staff/residents/{resident}', [\App\Http\Controllers\ResidentController::class, 'show'])
            ->name('staff.residents.show');
        
        

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Non-Calamity Modules (Super Admin only)
    Route::middleware('super_admin')->group(function () {
        // Residents Routes
        Route::resource('residents', ResidentController::class)->except(['create', 'store']);
        
        // Households Routes
        Route::resource('households', HouseholdController::class);
        // Add members
        Route::get('/households/{household}/add-member', [HouseholdController::class, 'addMember'])
            ->name('households.add-member');
        Route::post('/households/{household}/add-member', [HouseholdController::class, 'storeMember'])
            ->name('households.store-member');
        
        // Sub-Family Routes
        Route::get('/sub-families/create', [SubFamilyController::class, 'create'])->name('sub-families.create');
        Route::post('/sub-families', [SubFamilyController::class, 'store'])->name('sub-families.store');
        // Secretary-only sub-family routes (kept within super admin)
        Route::get('/sub-families', [SubFamilyController::class, 'index'])->name('sub-families.index');
        Route::post('/sub-families/{subFamily}/approve', [SubFamilyController::class, 'approve'])->name('sub-families.approve');
        Route::post('/sub-families/{subFamily}/reject', [SubFamilyController::class, 'reject'])->name('sub-families.reject');
        Route::delete('/sub-families/{subFamily}', [SubFamilyController::class, 'destroy'])->name('sub-families.destroy');
        
        // Census Routes
        Route::get('/census', [CensusController::class, 'index'])->name('census.index');
        
        // Resident Transfer Routes
        Route::resource('resident-transfers', ResidentTransferController::class);
        Route::get('/resident-transfers-pending', [ResidentTransferController::class, 'pending'])
            ->name('resident-transfers.pending')
            ->middleware('secretary');
        Route::post('/resident-transfers/{residentTransfer}/approve', [ResidentTransferController::class, 'approve'])
            ->name('resident-transfers.approve')
            ->middleware('secretary');
        Route::post('/resident-transfers/{residentTransfer}/reject', [ResidentTransferController::class, 'reject'])
            ->name('resident-transfers.reject')
            ->middleware('secretary');
        
        // Household Events Routes (View only)
        Route::get('/household-events', [HouseholdEventController::class, 'index'])->name('household-events.index');
        Route::get('/household-events/{householdEvent}', [HouseholdEventController::class, 'show'])->name('household-events.show');
        Route::get('/households/{household}/events', [HouseholdEventController::class, 'byHousehold'])->name('household-events.by-household');
    });

    Route::middleware('calamity_access')->name('web.')->group(function () {
        Route::get('/calamity-affected-households', [\App\Http\Controllers\Calamity\AffectedHouseholdPageController::class, 'index'])->name('calamity-affected-households.index');
        Route::view('/calamity-affected-households/create', 'calamity.affected.create')->name('calamity-affected-households.create');
        Route::get('/calamity-affected-households/{calamity_affected_household}', [\App\Http\Controllers\Calamity\AffectedHouseholdPageController::class, 'show'])->name('calamity-affected-households.show');
        Route::post('/calamity-affected-households', [\App\Http\Controllers\Calamity\AffectedHouseholdPageController::class, 'store'])->name('calamity-affected-households.store');
        Route::post('/rescue-operations', [\App\Http\Controllers\Calamity\RescueOperationController::class, 'store'])->name('rescue-operations.store');
    });
    
    // Export Routes (Super Admin only) and Non-Calamity Admin
    Route::middleware('super_admin')->group(function () {
        // Census Exports
        Route::get('/census/export/pdf', [CensusController::class, 'exportPdf'])->name('census.export.pdf');
        Route::get('/census/export/excel', [CensusController::class, 'exportExcel'])->name('census.export.excel');
        
        // Residents Exports
        Route::get('/residents/export/pdf', [CensusController::class, 'exportResidentsPdf'])->name('residents.export.pdf');
        Route::get('/residents/export/excel', [CensusController::class, 'exportResidentsExcel'])->name('residents.export.excel');
        
        // Households Exports
        Route::get('/households/export/pdf', [CensusController::class, 'exportHouseholdsPdf'])->name('households.export.pdf');
        Route::get('/households/export/excel', [CensusController::class, 'exportHouseholdsExcel'])->name('households.export.excel');
        
        // Approval & Status Management Routes (Secretary Only)
        Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');
        Route::post('/approvals/residents/{resident}/approve', [ApprovalController::class, 'approveResident'])->name('approvals.resident.approve');
        Route::post('/approvals/residents/{resident}/reject', [ApprovalController::class, 'rejectResident'])->name('approvals.resident.reject');
        Route::post('/approvals/households/{household}/approve', [ApprovalController::class, 'approveHousehold'])->name('approvals.household.approve');
        Route::post('/approvals/households/{household}/reject', [ApprovalController::class, 'rejectHousehold'])->name('approvals.household.reject');
        
        // Status Change Routes
        Route::post('/residents/{resident}/change-status', [ApprovalController::class, 'changeResidentStatus'])->name('residents.change-status');
        
        // Archive Routes
        Route::post('/residents/{resident}/archive', [ApprovalController::class, 'archiveResident'])->name('residents.archive');
        Route::post('/households/{household}/archive', [ApprovalController::class, 'archiveHousehold'])->name('households.archive');
        
        // Archived Records
        Route::get('/archived', [ApprovalController::class, 'archived'])->name('archived.index');
        Route::post('/archived/residents/{id}/restore', [ApprovalController::class, 'restoreResident'])->name('archived.resident.restore');
        Route::delete('/archived/residents/{id}/delete', [ApprovalController::class, 'deleteResident'])->name('archived.resident.delete');
        Route::post('/archived/households/{id}/restore', [ApprovalController::class, 'restoreHousehold'])->name('archived.household.restore');
        Route::delete('/archived/households/{id}/delete', [ApprovalController::class, 'deleteHousehold'])->name('archived.household.delete');
        
        // Purok Management Routes (Secretary Only)
        Route::resource('puroks', PurokController::class);
        Route::post('/puroks/{purok}/update-counts', [PurokController::class, 'updateCounts'])->name('puroks.update-counts');
        
        // Certificates Routes (Secretary Only)
        Route::resource('certificates', CertificateController::class);
        Route::post('/certificates/{certificate}/update-status', [CertificateController::class, 'updateStatus'])->name('certificates.update-status');
        Route::get('/certificates/{certificate}/pdf', [CertificateController::class, 'generatePdf'])->name('certificates.pdf');
        Route::get('/certificates/{certificate}/print', [CertificateController::class, 'print'])->name('certificates.print');
        
        
        
        // Calamity Management Routes moved to calamity_access group below
        // Place static routes BEFORE resource to avoid collision with /calamities/{calamity}
        Route::get('/calamities/archived', [CalamityController::class, 'archived'])->name('calamities.archived');
        Route::post('/calamities/{id}/restore', [CalamityController::class, 'restore'])->name('calamities.restore');
        Route::delete('/calamities/{id}/delete', [CalamityController::class, 'forceDelete'])->name('calamities.delete');
        Route::get('/calamities/export/pdf', [CalamityController::class, 'exportPdf'])->name('calamities.export.pdf');
        Route::get('/calamities/export/excel', [CalamityController::class, 'exportExcel'])->name('calamities.export.excel');
        Route::post('/calamities/seed-samples', [CalamityController::class, 'seedSamples'])->name('calamities.seed-samples');
        Route::resource('calamities', CalamityController::class);
        Route::get('/calamities/{calamity}/add-households', [CalamityController::class, 'showAddHouseholds'])->name('calamities.add-households');
        Route::post('/calamities/{calamity}/add-household', [CalamityController::class, 'addAffectedHousehold'])->name('calamities.add-household');

        // (Calamity views moved to calamity_access group)

        // Announcements
        Route::resource('announcements', \App\Http\Controllers\AnnouncementController::class)->only(['index','create','store','show']);
        Route::get('/announcements/bell', [\App\Http\Controllers\AnnouncementController::class, 'bell'])->name('announcements.bell');

        // (Calamity submissions moved to calamity_access group)
    
    
    
    
    
    
    });

    // Calamity Management Routes (Calamity access)
    Route::middleware('calamity_access')->group(function () {
        // Place static routes BEFORE resource to avoid collision with /calamities/{calamity}
        Route::get('/calamities/archived', [CalamityController::class, 'archived'])->name('calamities.archived');
        Route::post('/calamities/{id}/restore', [CalamityController::class, 'restore'])->name('calamities.restore');
        Route::delete('/calamities/{id}/delete', [CalamityController::class, 'forceDelete'])->name('calamities.delete');
        Route::get('/calamities/export/pdf', [CalamityController::class, 'exportPdf'])->name('calamities.export.pdf');
        Route::get('/calamities/export/excel', [CalamityController::class, 'exportExcel'])->name('calamities.export.excel');
        Route::post('/calamities/seed-samples', [CalamityController::class, 'seedSamples'])->name('calamities.seed-samples');
        Route::resource('calamities', CalamityController::class);
        Route::get('/calamities/{calamity}/add-households', [CalamityController::class, 'showAddHouseholds'])->name('calamities.add-households');
        Route::post('/calamities/{calamity}/add-household', [CalamityController::class, 'addAffectedHousehold'])->name('calamities.add-household');

        // Calamity Submodule Views (Web-only)
        Route::name('web.')->group(function () {
            Route::view('/evacuation-centers', 'calamity.evacuation_centers.index')->name('evacuation-centers.index');
            Route::view('/evacuation-centers/create', 'calamity.evacuation_centers.create')->name('evacuation-centers.create');
            Route::get('/relief-items', [\App\Http\Controllers\Calamity\ReliefItemController::class, 'index'])->name('relief-items.index');
            Route::get('/relief-items/create', [\App\Http\Controllers\Calamity\ReliefItemController::class, 'create'])->name('relief-items.create');
            Route::get('/relief-items/{relief_item}', [\App\Http\Controllers\Calamity\ReliefItemController::class, 'show'])->name('relief-items.show');
            Route::get('/relief-items/{relief_item}/edit', [\App\Http\Controllers\Calamity\ReliefItemController::class, 'edit'])->name('relief-items.edit');
            Route::view('/relief-distributions', 'calamity.distributions.index')->name('relief-distributions.index');
            Route::view('/relief-distributions/create', 'calamity.distributions.create')->name('relief-distributions.create');
            Route::get('/damage-assessments', [\App\Http\Controllers\Calamity\DamageAssessmentController::class, 'indexBlade'])->name('damage-assessments.index');
            Route::get('/damage-assessments/create', [\App\Http\Controllers\Calamity\DamageAssessmentController::class, 'createBlade'])->name('damage-assessments.create');
            Route::get('/damage-assessments/{damage_assessment}', [\App\Http\Controllers\Calamity\DamageAssessmentController::class, 'showBlade'])->name('damage-assessments.show');
            Route::get('/damage-assessments/{damage_assessment}/edit', [\App\Http\Controllers\Calamity\DamageAssessmentController::class, 'editBlade'])->name('damage-assessments.edit');
            Route::get('/notifications', [\App\Http\Controllers\Calamity\NotificationPageController::class, 'index'])->name('notifications.index');
            Route::get('/notifications/create', [\App\Http\Controllers\Calamity\NotificationPageController::class, 'create'])->name('notifications.create');
            Route::get('/notifications/{notification}', [\App\Http\Controllers\Calamity\NotificationPageController::class, 'show'])->name('notifications.show');
            Route::get('/notifications/{notification}/edit', [\App\Http\Controllers\Calamity\NotificationPageController::class, 'edit'])->name('notifications.edit');
            Route::view('/response-team-members', 'calamity.response_team.index')->name('response-team-members.index');
            Route::view('/response-team-members/create', 'calamity.response_team.create')->name('response-team-members.create');
            Route::get('/calamity-reports', [\App\Http\Controllers\Calamity\CalamityReportController::class, 'index'])->name('calamity-reports.index');
            Route::get('/calamity-reports/{calamity}', [\App\Http\Controllers\Calamity\CalamityReportController::class, 'show'])->name('calamity-reports.show');
            Route::get('/calamity-reports/{calamity}/pdf', [\App\Http\Controllers\Calamity\CalamityReportController::class, 'pdf'])->name('calamity-reports.pdf');
        });

        // Calamity Submissions
        Route::post('/damage-assessments', [\App\Http\Controllers\Calamity\DamageAssessmentController::class, 'storeWeb'])
            ->name('web.damage-assessments.store');
        Route::put('/damage-assessments/{damage_assessment}', [\App\Http\Controllers\Calamity\DamageAssessmentController::class, 'updateWeb'])
            ->name('web.damage-assessments.update');
        Route::delete('/damage-assessments/{damage_assessment}', [\App\Http\Controllers\Calamity\DamageAssessmentController::class, 'destroyWeb'])
            ->name('web.damage-assessments.destroy');
        Route::post('/notifications', [\App\Http\Controllers\Calamity\NotificationController::class, 'store'])
            ->name('web.notifications.store');
        Route::put('/notifications/{notification}', [\App\Http\Controllers\Calamity\NotificationController::class, 'update'])
            ->name('web.notifications.update');
        Route::delete('/notifications/{notification}', [\App\Http\Controllers\Calamity\NotificationController::class, 'destroy'])
            ->name('web.notifications.destroy');
        Route::post('/response-team-members', [\App\Http\Controllers\Calamity\ResponseTeamMemberController::class, 'store'])
            ->name('web.response-team-members.store');
        Route::post('/relief-items', [\App\Http\Controllers\Calamity\ReliefItemController::class, 'store'])
            ->name('web.relief-items.store');
        Route::put('/relief-items/{relief_item}', [\App\Http\Controllers\Calamity\ReliefItemController::class, 'update'])
            ->name('web.relief-items.update');
        Route::delete('/relief-items/{relief_item}', [\App\Http\Controllers\Calamity\ReliefItemController::class, 'destroy'])
            ->name('web.relief-items.destroy');
    });
    
    
    
    
});
        // User Management (Secretary Settings)
        Route::get('/settings/users', [\App\Http\Controllers\UserManagementController::class, 'index'])->name('settings.users.index');
        Route::post('/settings/users', [\App\Http\Controllers\UserManagementController::class, 'store'])
            ->name('settings.users.store')->middleware('secretary');
        Route::post('/settings/users/{user}/status', [\App\Http\Controllers\UserManagementController::class, 'updateStatus'])
            ->name('settings.users.update-status')->middleware('secretary');
        Route::post('/settings/users/{user}/assignment', [\App\Http\Controllers\UserManagementController::class, 'updateAssignment'])
            ->name('settings.users.update-assignment')->middleware('secretary');

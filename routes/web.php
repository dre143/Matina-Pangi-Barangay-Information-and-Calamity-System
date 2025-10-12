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
use App\Http\Controllers\HealthRecordController;
use App\Http\Controllers\CalamityController;
use App\Http\Controllers\PwdSupportController;
use App\Http\Controllers\GovernmentAssistanceController;
use App\Http\Controllers\SeniorHealthController;

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
Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Residents Routes (View only - Add residents through Households)
    Route::resource('residents', ResidentController::class)->except(['create', 'store']);
    
    // Households Routes
    Route::resource('households', HouseholdController::class);
    // Staff and Secretary can add members
    Route::get('/households/{household}/add-member', [HouseholdController::class, 'addMember'])
        ->name('households.add-member');
    Route::post('/households/{household}/add-member', [HouseholdController::class, 'storeMember'])
        ->name('households.store-member');
    
    // Sub-Family Routes
    Route::get('/sub-families/create', [SubFamilyController::class, 'create'])->name('sub-families.create');
    Route::post('/sub-families', [SubFamilyController::class, 'store'])->name('sub-families.store');
    
    // Secretary-only sub-family routes
    Route::middleware('secretary')->group(function () {
        Route::get('/sub-families', [SubFamilyController::class, 'index'])->name('sub-families.index');
        Route::post('/sub-families/{subFamily}/approve', [SubFamilyController::class, 'approve'])->name('sub-families.approve');
        Route::post('/sub-families/{subFamily}/reject', [SubFamilyController::class, 'reject'])->name('sub-families.reject');
        Route::delete('/sub-families/{subFamily}', [SubFamilyController::class, 'destroy'])->name('sub-families.destroy');
    });
    
    // Census Routes
    Route::get('/census', [CensusController::class, 'index'])->name('census.index');
    
    // Export Routes (Secretary Only)
    Route::middleware('secretary')->group(function () {
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
        
        // Health Records Routes (Secretary Only)
        Route::resource('health-records', HealthRecordController::class);
        
        // Calamity Management Routes (Secretary Only)
        Route::resource('calamities', CalamityController::class);
        Route::get('/calamities/{calamity}/add-households', [CalamityController::class, 'showAddHouseholds'])->name('calamities.add-households');
        Route::post('/calamities/{calamity}/add-household', [CalamityController::class, 'addAffectedHousehold'])->name('calamities.add-household');
        
        // PWD Support Routes (Secretary Only)
        Route::resource('pwd-support', PwdSupportController::class);
        
        // Government Assistance Routes (Secretary Only)
        Route::resource('government-assistance', GovernmentAssistanceController::class);
        
        // Senior Health Routes (Secretary Only)
        Route::resource('senior-health', SeniorHealthController::class);
    });
});

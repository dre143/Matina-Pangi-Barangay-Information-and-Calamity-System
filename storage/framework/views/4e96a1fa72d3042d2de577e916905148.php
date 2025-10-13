<?php $__env->startSection('title', 'Resident Details'); ?>

<?php $__env->startSection('content'); ?>
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('residents.index')); ?>">Residents</a></li>
        <li class="breadcrumb-item active"><?php echo e($resident->resident_id); ?></li>
    </ol>
</nav>

<!-- Profile Header Card -->
<div class="card border-0 mb-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start">
            <div class="d-flex align-items-center gap-4">
                <!-- Avatar -->
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); border: 4px solid rgba(255, 255, 255, 0.3); font-size: 2.5rem; font-weight: 700;">
                    <?php echo e(strtoupper(substr($resident->first_name, 0, 1))); ?><?php echo e(strtoupper(substr($resident->last_name, 0, 1))); ?>

                </div>
                <!-- Info -->
                <div>
                    <h2 class="mb-2 fw-bold"><?php echo e($resident->full_name); ?></h2>
                    <div class="d-flex gap-2 mb-2">
                        <span class="badge" style="background: rgba(255, 255, 255, 0.25); backdrop-filter: blur(10px); padding: 6px 14px; font-size: 0.9rem;">
                            <i class="bi bi-hash"></i> <?php echo e($resident->resident_id); ?>

                        </span>
                        <?php if($resident->status == 'active'): ?>
                            <span class="badge bg-success" style="padding: 6px 14px; font-size: 0.9rem;">
                                <i class="bi bi-check-circle-fill"></i> <?php echo e(ucfirst($resident->status)); ?>

                            </span>
                        <?php elseif($resident->status == 'deceased'): ?>
                            <span class="badge bg-dark" style="padding: 6px 14px; font-size: 0.9rem;">
                                <i class="bi bi-x-circle-fill"></i> <?php echo e(ucfirst($resident->status)); ?>

                            </span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark" style="padding: 6px 14px; font-size: 0.9rem;">
                                <i class="bi bi-arrow-repeat"></i> <?php echo e(ucfirst($resident->status)); ?>

                            </span>
                        <?php endif; ?>
                        <?php if($resident->isPending()): ?>
                            <span class="badge bg-warning text-dark" style="padding: 6px 14px; font-size: 0.9rem;">
                                <i class="bi bi-clock-fill"></i> Pending Approval
                            </span>
                        <?php endif; ?>
                    </div>
                    <p class="mb-0 opacity-75">
                        <i class="bi bi-cake2"></i> <?php echo e($resident->age); ?> years old • 
                        <i class="bi bi-gender-<?php echo e($resident->sex == 'male' ? 'male' : 'female'); ?>"></i> <?php echo e(ucfirst($resident->sex)); ?> • 
                        <i class="bi bi-house-fill"></i> <?php echo e($resident->household->household_id); ?>

                    </p>
                </div>
            </div>
            <!-- Actions -->
            <div class="d-flex gap-2">
                <a href="<?php echo e(route('resident-transfers.create', ['resident_id' => $resident->id])); ?>" class="btn btn-info">
                    <i class="bi bi-arrow-left-right"></i> Request Transfer
                </a>
                <?php if(auth()->user()->isSecretary()): ?>
                    <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#changeStatusModal">
                        <i class="bi bi-arrow-repeat"></i> Change Status
                    </button>
                    <a href="<?php echo e(route('residents.edit', $resident)); ?>" class="btn btn-warning">
                        <i class="bi bi-pencil-fill"></i> Edit
                    </a>
                <?php endif; ?>
                <a href="<?php echo e(route('residents.index')); ?>" class="btn btn-outline-light">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Change Status Modal -->
<?php if(auth()->user()->isSecretary()): ?>
<div class="modal fade" id="changeStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('residents.change-status', $resident)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Change Resident Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Current Status</label>
                        <p><span class="badge bg-<?php echo e($resident->status_badge_color); ?>"><?php echo e(ucfirst($resident->status)); ?></span></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Status <span class="text-danger">*</span></label>
                        <select class="form-select" name="status" required>
                            <option value="">Select Status</option>
                            <option value="active" <?php echo e($resident->status == 'active' ? 'selected' : ''); ?>>Active</option>
                            <option value="reallocated" <?php echo e($resident->status == 'reallocated' ? 'selected' : ''); ?>>Reallocated</option>
                            <option value="deceased" <?php echo e($resident->status == 'deceased' ? 'selected' : ''); ?>>Deceased</option>
                        </select>
                        <small class="text-muted">
                            <strong>Active:</strong> Currently living in household<br>
                            <strong>Reallocated:</strong> Moved to another barangay/household<br>
                            <strong>Deceased:</strong> Marked as deceased
                        </small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="status_notes" rows="3" placeholder="Optional notes about this status change"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row g-3">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Personal Information -->
        <div class="card border-0 mb-4">
            <div class="card-header bg-gradient">
                <h5 class="mb-0"><i class="bi bi-person-badge-fill"></i> Personal Information</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #10b981, #059669); flex-shrink: 0;">
                                <i class="bi bi-hash text-white"></i>
                            </div>
                            <div>
                                <label class="text-muted small mb-1 d-block">Resident ID</label>
                                <p class="mb-0 fw-bold"><?php echo e($resident->resident_id); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #3b82f6, #2563eb); flex-shrink: 0;">
                                <i class="bi bi-person-fill text-white"></i>
                            </div>
                            <div>
                                <label class="text-muted small mb-1 d-block">Full Name</label>
                                <p class="mb-0 fw-bold"><?php echo e($resident->full_name); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #f59e0b, #d97706); flex-shrink: 0;">
                                <i class="bi bi-cake2-fill text-white"></i>
                            </div>
                            <div>
                                <label class="text-muted small mb-1 d-block">Birthdate</label>
                                <p class="mb-0"><?php echo e($resident->birthdate->format('F d, Y')); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); flex-shrink: 0;">
                                <i class="bi bi-calendar-heart-fill text-white"></i>
                            </div>
                            <div>
                                <label class="text-muted small mb-1 d-block">Age</label>
                                <p class="mb-0"><?php echo e($resident->age); ?> years old</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #06b6d4, #0891b2); flex-shrink: 0;">
                                <i class="bi bi-people-fill text-white"></i>
                            </div>
                            <div>
                                <label class="text-muted small mb-1 d-block">Age Category</label>
                                <p class="mb-0">
                                    <span class="badge" style="background: rgba(6, 182, 212, 0.1); color: #0891b2;"><?php echo e($resident->age_category); ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, <?php echo e($resident->sex == 'male' ? '#3b82f6, #2563eb' : '#ec4899, #db2777'); ?>); flex-shrink: 0;">
                                <i class="bi bi-gender-<?php echo e($resident->sex == 'male' ? 'male' : 'female'); ?> text-white"></i>
                            </div>
                            <div>
                                <label class="text-muted small mb-1 d-block">Sex</label>
                                <p class="mb-0"><?php echo e(ucfirst($resident->sex)); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #ef4444, #dc2626); flex-shrink: 0;">
                                <i class="bi bi-heart-fill text-white"></i>
                            </div>
                            <div>
                                <label class="text-muted small mb-1 d-block">Civil Status</label>
                                <p class="mb-0"><?php echo e(ucfirst($resident->civil_status)); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #14b8a6, #0d9488); flex-shrink: 0;">
                                <i class="bi bi-flag-fill text-white"></i>
                            </div>
                            <div>
                                <label class="text-muted small mb-1 d-block">Nationality</label>
                                <p class="mb-0"><?php echo e($resident->nationality); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php if($resident->place_of_birth): ?>
                        <div class="col-md-6">
                            <label class="text-muted small">Place of Birth</label>
                            <p class="mb-0"><?php echo e($resident->place_of_birth); ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if($resident->religion): ?>
                        <div class="col-md-6">
                            <label class="text-muted small">Religion</label>
                            <p class="mb-0"><?php echo e($resident->religion); ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if($resident->blood_type): ?>
                        <div class="col-md-6">
                            <label class="text-muted small">Blood Type</label>
                            <p class="mb-0"><?php echo e($resident->blood_type); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="card border-0 mb-4">
            <div class="card-header bg-gradient">
                <h5 class="mb-0"><i class="bi bi-telephone-fill"></i> Contact Information</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Contact Number</label>
                        <p class="mb-0"><?php echo e($resident->contact_number ?: 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Email Address</label>
                        <p class="mb-0"><?php echo e($resident->email ?: 'N/A'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Household Information -->
        <div class="card border-0 mb-4">
            <div class="card-header bg-gradient">
                <h5 class="mb-0"><i class="bi bi-house-fill"></i> Household Information</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Household ID</label>
                        <p class="mb-0">
                            <a href="<?php echo e(route('households.show', $resident->household)); ?>">
                                <?php echo e($resident->household->household_id); ?>

                            </a>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Household Role</label>
                        <p class="mb-0">
                            <?php echo e(ucfirst($resident->household_role)); ?>

                            <?php if($resident->is_household_head): ?>
                                <span class="badge bg-success">Household Head</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-md-12">
                        <label class="text-muted small">Address</label>
                        <p class="mb-0"><?php echo e($resident->household->full_address); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employment & Income -->
        <div class="card border-0 mb-4">
            <div class="card-header bg-gradient">
                <h5 class="mb-0"><i class="bi bi-briefcase-fill"></i> Employment & Income</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Occupation</label>
                        <p class="mb-0"><?php echo e($resident->occupation ?: 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Employment Status</label>
                        <p class="mb-0"><?php echo e($resident->employment_status ? ucfirst($resident->employment_status) : 'N/A'); ?></p>
                    </div>
                    <?php if($resident->employer_name): ?>
                        <div class="col-md-6">
                            <label class="text-muted small">Employer</label>
                            <p class="mb-0"><?php echo e($resident->employer_name); ?></p>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-6">
                        <label class="text-muted small">Monthly Income</label>
                        <p class="mb-0 fw-bold">₱<?php echo e(number_format($resident->monthly_income ?? 0, 2)); ?></p>
                    </div>
                    <?php if($resident->educational_attainment): ?>
                        <div class="col-md-12">
                            <label class="text-muted small">Educational Attainment</label>
                            <p class="mb-0"><?php echo e(ucfirst($resident->educational_attainment)); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <?php if($resident->medical_conditions || $resident->remarks): ?>
        <div class="card border-0">
            <div class="card-header bg-gradient">
                <h5 class="mb-0"><i class="bi bi-clipboard-fill"></i> Additional Information</h5>
            </div>
            <div class="card-body p-4">
                <?php if($resident->medical_conditions): ?>
                    <div class="mb-3">
                        <label class="text-muted small">Medical Conditions</label>
                        <p class="mb-0"><?php echo e($resident->medical_conditions); ?></p>
                    </div>
                <?php endif; ?>
                <?php if($resident->remarks): ?>
                    <div>
                        <label class="text-muted small">Remarks</label>
                        <p class="mb-0"><?php echo e($resident->remarks); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Special Categories -->
        <div class="card border-0 mb-4">
            <div class="card-header bg-gradient">
                <h5 class="mb-0"><i class="bi bi-star-fill"></i> Special Categories</h5>
            </div>
            <div class="card-body p-4">
                <?php if($resident->is_pwd): ?>
                    <div class="p-3 rounded mb-3" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05)); border-left: 4px solid #3b82f6;">
                        <div class="d-flex align-items-start gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: linear-gradient(135deg, #3b82f6, #2563eb); flex-shrink: 0;">
                                <i class="bi bi-universal-access text-white"></i>
                            </div>
                            <div>
                                <strong class="d-block">PWD</strong>
                                <?php if($resident->pwd_id): ?>
                                    <small class="text-muted">ID: <?php echo e($resident->pwd_id); ?></small><br>
                                <?php endif; ?>
                                <?php if($resident->disability_type): ?>
                                    <small class="text-muted">Type: <?php echo e($resident->disability_type); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if($resident->is_senior_citizen): ?>
                    <div class="p-3 rounded mb-3" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.05)); border-left: 4px solid #f59e0b;">
                        <div class="d-flex align-items-start gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: linear-gradient(135deg, #f59e0b, #d97706); flex-shrink: 0;">
                                <i class="bi bi-person-cane text-white"></i>
                            </div>
                            <div>
                                <strong class="d-block">Senior Citizen</strong>
                                <?php if($resident->senior_id): ?>
                                    <small class="text-muted">ID: <?php echo e($resident->senior_id); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if($resident->is_teen): ?>
                    <div class="p-3 rounded mb-3" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(139, 92, 246, 0.05)); border-left: 4px solid #8b5cf6;">
                        <div class="d-flex align-items-start gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); flex-shrink: 0;">
                                <i class="bi bi-person-standing text-white"></i>
                            </div>
                            <div>
                                <strong class="d-block">Teen (13-19)</strong>
                                <small class="text-muted">Youth Category</small>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if($resident->is_voter): ?>
                    <div class="p-3 rounded mb-3" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05)); border-left: 4px solid #10b981;">
                        <div class="d-flex align-items-start gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: linear-gradient(135deg, #10b981, #059669); flex-shrink: 0;">
                                <i class="bi bi-check-circle-fill text-white"></i>
                            </div>
                            <div>
                                <strong class="d-block">Registered Voter</strong>
                                <?php if($resident->precinct_number): ?>
                                    <small class="text-muted">Precinct: <?php echo e($resident->precinct_number); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if($resident->is_4ps_beneficiary): ?>
                    <div class="p-3 rounded mb-3" style="background: linear-gradient(135deg, rgba(249, 115, 22, 0.1), rgba(249, 115, 22, 0.05)); border-left: 4px solid #f97316;">
                        <div class="d-flex align-items-start gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: linear-gradient(135deg, #f97316, #ea580c); flex-shrink: 0;">
                                <i class="bi bi-cash-coin text-white"></i>
                            </div>
                            <div>
                                <strong class="d-block">4Ps Beneficiary</strong>
                                <?php if($resident['4ps_id']): ?>
                                    <small class="text-muted">ID: <?php echo e($resident['4ps_id']); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(!$resident->is_pwd && !$resident->is_senior_citizen && !$resident->is_teen && !$resident->is_voter && !$resident->is_4ps_beneficiary): ?>
                    <div class="text-center py-4">
                        <i class="bi bi-inbox text-muted" style="font-size: 2.5rem;"></i>
                        <p class="text-muted mb-0 mt-2">No special categories</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Status Information -->
        <?php if($resident->status_notes || $resident->status_changed_at): ?>
        <div class="card border-0 mb-4">
            <div class="card-header bg-gradient">
                <h5 class="mb-0"><i class="bi bi-info-circle-fill"></i> Status Information</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="text-muted small">Current Status</label>
                    <p class="mb-0">
                        <span class="badge bg-<?php echo e($resident->status_badge_color); ?>">
                            <?php echo e(ucfirst($resident->status)); ?>

                        </span>
                    </p>
                </div>
                <?php if($resident->status_changed_at): ?>
                    <div class="mb-3">
                        <label class="text-muted small">Status Changed On</label>
                        <p class="mb-0"><?php echo e($resident->status_changed_at->format('F d, Y h:i A')); ?></p>
                    </div>
                <?php endif; ?>
                <?php if($resident->statusChanger): ?>
                    <div class="mb-3">
                        <label class="text-muted small">Changed By</label>
                        <p class="mb-0"><?php echo e($resident->statusChanger->name); ?></p>
                    </div>
                <?php endif; ?>
                <?php if($resident->status_notes): ?>
                    <div>
                        <label class="text-muted small">Notes</label>
                        <p class="mb-0"><?php echo e($resident->status_notes); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Audit Information -->
        <div class="card border-0">
            <div class="card-header bg-gradient">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Record Information</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="text-muted small">Registered By</label>
                    <p class="mb-0"><?php echo e($resident->creator ? $resident->creator->name : 'System'); ?></p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Registered On</label>
                    <p class="mb-0"><?php echo e($resident->created_at->format('F d, Y h:i A')); ?></p>
                </div>
                <?php if($resident->isPending()): ?>
                    <div class="alert alert-warning mb-3">
                        <strong><i class="bi bi-exclamation-triangle"></i> Pending Approval</strong>
                        <p class="mb-0 small">This resident is awaiting secretary approval.</p>
                    </div>
                <?php endif; ?>
                <?php if($resident->approver): ?>
                    <div class="mb-3">
                        <label class="text-muted small">Approved By</label>
                        <p class="mb-0"><?php echo e($resident->approver->name); ?></p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Approved On</label>
                        <p class="mb-0"><?php echo e($resident->approved_at->format('F d, Y h:i A')); ?></p>
                    </div>
                <?php endif; ?>
                <?php if($resident->updater): ?>
                    <div class="mb-3">
                        <label class="text-muted small">Last Updated By</label>
                        <p class="mb-0"><?php echo e($resident->updater->name); ?></p>
                    </div>
                <?php endif; ?>
                <div>
                    <label class="text-muted small">Last Updated</label>
                    <p class="mb-0"><?php echo e($resident->updated_at->format('F d, Y h:i A')); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/residents/show.blade.php ENDPATH**/ ?>
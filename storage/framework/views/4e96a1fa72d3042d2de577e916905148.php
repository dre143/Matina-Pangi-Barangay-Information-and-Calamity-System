<?php $__env->startSection('title', 'Resident Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('residents.index')); ?>">Residents</a></li>
            <li class="breadcrumb-item active"><?php echo e($resident->resident_id); ?></li>
        </ol>
    </nav>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="bi bi-person"></i> Resident Profile</h2>
        <div class="mt-2">
            <span class="badge bg-<?php echo e($resident->status_badge_color); ?> fs-6">
                <?php echo e(ucfirst($resident->status)); ?>

            </span>
            <?php if($resident->isPending()): ?>
                <span class="badge bg-<?php echo e($resident->approval_badge_color); ?> fs-6">
                    Pending Approval
                </span>
            <?php endif; ?>
        </div>
    </div>
    <div>
        <?php if(auth()->user()->isSecretary()): ?>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changeStatusModal">
                <i class="bi bi-arrow-repeat"></i> Change Status
            </button>
            <a href="<?php echo e(route('residents.edit', $resident)); ?>" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
        <?php endif; ?>
        <a href="<?php echo e(route('residents.index')); ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
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
    <!-- Personal Information -->
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person-badge"></i> Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Resident ID</label>
                        <p class="mb-0 fw-bold"><?php echo e($resident->resident_id); ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Full Name</label>
                        <p class="mb-0 fw-bold"><?php echo e($resident->full_name); ?></p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Birthdate</label>
                        <p class="mb-0"><?php echo e($resident->birthdate->format('F d, Y')); ?></p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Age</label>
                        <p class="mb-0"><?php echo e($resident->age); ?> years old</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Age Category</label>
                        <p class="mb-0">
                            <span class="badge bg-secondary"><?php echo e($resident->age_category); ?></span>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Sex</label>
                        <p class="mb-0"><?php echo e(ucfirst($resident->sex)); ?></p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Civil Status</label>
                        <p class="mb-0"><?php echo e(ucfirst($resident->civil_status)); ?></p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Nationality</label>
                        <p class="mb-0"><?php echo e($resident->nationality); ?></p>
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
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-telephone"></i> Contact Information</h5>
            </div>
            <div class="card-body">
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
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-house"></i> Household Information</h5>
            </div>
            <div class="card-body">
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
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-briefcase"></i> Employment & Income</h5>
            </div>
            <div class="card-body">
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
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clipboard"></i> Additional Information</h5>
            </div>
            <div class="card-body">
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
    <div class="col-md-4">
        <!-- Special Categories -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-star"></i> Special Categories</h5>
            </div>
            <div class="card-body">
                <?php if($resident->is_pwd): ?>
                    <div class="alert alert-info mb-2">
                        <strong><i class="bi bi-universal-access"></i> PWD</strong>
                        <?php if($resident->pwd_id): ?>
                            <br><small>ID: <?php echo e($resident->pwd_id); ?></small>
                        <?php endif; ?>
                        <?php if($resident->disability_type): ?>
                            <br><small>Type: <?php echo e($resident->disability_type); ?></small>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if($resident->is_senior_citizen): ?>
                    <div class="alert alert-warning mb-2">
                        <strong><i class="bi bi-person-walking"></i> Senior Citizen</strong>
                        <?php if($resident->senior_id): ?>
                            <br><small>ID: <?php echo e($resident->senior_id); ?></small>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if($resident->is_teen): ?>
                    <div class="alert alert-secondary mb-2">
                        <strong><i class="bi bi-person"></i> Teen (13-19)</strong>
                    </div>
                <?php endif; ?>

                <?php if($resident->is_voter): ?>
                    <div class="alert alert-success mb-2">
                        <strong><i class="bi bi-check-circle"></i> Registered Voter</strong>
                        <?php if($resident->precinct_number): ?>
                            <br><small>Precinct: <?php echo e($resident->precinct_number); ?></small>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if($resident->is_4ps_beneficiary): ?>
                    <div class="alert alert-primary mb-2">
                        <strong><i class="bi bi-cash-coin"></i> 4Ps Beneficiary</strong>
                        <?php if($resident['4ps_id']): ?>
                            <br><small>ID: <?php echo e($resident['4ps_id']); ?></small>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if(!$resident->is_pwd && !$resident->is_senior_citizen && !$resident->is_teen && !$resident->is_voter && !$resident->is_4ps_beneficiary): ?>
                    <p class="text-muted mb-0">No special categories</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Status Information -->
        <?php if($resident->status_notes || $resident->status_changed_at): ?>
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Status Information</h5>
            </div>
            <div class="card-body">
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
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Record Information</h5>
            </div>
            <div class="card-body">
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
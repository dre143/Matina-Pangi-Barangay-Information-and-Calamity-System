<?php $__env->startSection('title', 'Issue Certificate - Barangay Matina Pangi'); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="mb-1">
            <i class="bi bi-file-earmark-text-fill"></i> Issue New Certificate
        </h2>
        <p class="text-muted mb-0">Create and issue official barangay certificates for residents</p>
    </div>
    <a href="<?php echo e(route('certificates.index')); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Certificates
    </a>
</div>

<!-- Form Card -->
<div class="card border-0">
    <div class="card-header bg-gradient">
        <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Certificate Information</h5>
    </div>
    <div class="card-body p-4">
        <form action="<?php echo e(route('certificates.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <!-- Resident & Certificate Type Section -->
            <div class="mb-4">
                <h6 class="text-uppercase fw-semibold text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.1em;">
                    <i class="bi bi-person-badge"></i> Resident & Certificate Details
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="resident_id" class="form-label">
                            <i class="bi bi-person-circle text-primary"></i> Resident <span class="text-danger">*</span>
                        </label>
                        <select name="resident_id" id="resident_id" class="form-select <?php $__errorArgs = ['resident_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">🔍 Search and select resident...</option>
                            <?php $__currentLoopData = $residents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($resident->id); ?>" <?php echo e(old('resident_id') == $resident->id ? 'selected' : ''); ?>>
                                    <?php echo e($resident->full_name); ?> - <?php echo e($resident->household->household_number); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['resident_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6">
                        <label for="certificate_type" class="form-label">
                            <i class="bi bi-file-earmark-check text-success"></i> Certificate Type <span class="text-danger">*</span>
                        </label>
                        <select name="certificate_type" id="certificate_type" class="form-select <?php $__errorArgs = ['certificate_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">📄 Select certificate type...</option>
                            <option value="barangay_clearance" <?php echo e(old('certificate_type') == 'barangay_clearance' ? 'selected' : ''); ?>>🏘️ Barangay Clearance</option>
                            <option value="certificate_of_indigency" <?php echo e(old('certificate_type') == 'certificate_of_indigency' ? 'selected' : ''); ?>>💰 Certificate of Indigency</option>
                            <option value="certificate_of_residency" <?php echo e(old('certificate_type') == 'certificate_of_residency' ? 'selected' : ''); ?>>🏠 Certificate of Residency</option>
                            <option value="business_clearance" <?php echo e(old('certificate_type') == 'business_clearance' ? 'selected' : ''); ?>>💼 Business Clearance</option>
                            <option value="good_moral" <?php echo e(old('certificate_type') == 'good_moral' ? 'selected' : ''); ?>>⭐ Certificate of Good Moral</option>
                            <option value="travel_permit" <?php echo e(old('certificate_type') == 'travel_permit' ? 'selected' : ''); ?>>✈️ Travel Permit</option>
                        </select>
                        <?php $__errorArgs = ['certificate_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <!-- Purpose Section -->
            <div class="mb-4">
                <h6 class="text-uppercase fw-semibold text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.1em;">
                    <i class="bi bi-chat-left-text"></i> Purpose & Details
                </h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label for="purpose" class="form-label">
                            <i class="bi bi-pencil text-info"></i> Purpose <span class="text-danger">*</span>
                        </label>
                        <textarea name="purpose" id="purpose" rows="4" class="form-control <?php $__errorArgs = ['purpose'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Enter the purpose for requesting this certificate..." required><?php echo e(old('purpose')); ?></textarea>
                        <?php $__errorArgs = ['purpose'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-muted">Be specific about why this certificate is needed</small>
                    </div>
                </div>
            </div>

            <!-- Payment & Validity Section -->
            <div class="mb-4">
                <h6 class="text-uppercase fw-semibold text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.1em;">
                    <i class="bi bi-cash-coin"></i> Payment & Validity
                </h6>
                <div class="row g-3">

                    <div class="col-md-4">
                        <label for="or_number" class="form-label">
                            <i class="bi bi-receipt text-warning"></i> OR Number
                        </label>
                        <input type="text" name="or_number" id="or_number" class="form-control <?php $__errorArgs = ['or_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('or_number')); ?>" placeholder="e.g., OR-2025-001">
                        <?php $__errorArgs = ['or_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-muted">Official Receipt number</small>
                    </div>

                    <div class="col-md-4">
                        <label for="amount_paid" class="form-label">
                            <i class="bi bi-currency-dollar text-success"></i> Amount Paid <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" name="amount_paid" id="amount_paid" step="0.01" class="form-control <?php $__errorArgs = ['amount_paid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('amount_paid', '0.00')); ?>" placeholder="0.00" required>
                        </div>
                        <?php $__errorArgs = ['amount_paid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-4">
                        <label for="valid_until" class="form-label">
                            <i class="bi bi-calendar-check text-info"></i> Valid Until
                        </label>
                        <input type="date" name="valid_until" id="valid_until" class="form-control <?php $__errorArgs = ['valid_until'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('valid_until')); ?>">
                        <?php $__errorArgs = ['valid_until'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-muted">Leave blank for no expiry</small>
                    </div>
                </div>
            </div>

            <!-- Additional Notes Section -->
            <div class="mb-4">
                <h6 class="text-uppercase fw-semibold text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.1em;">
                    <i class="bi bi-sticky"></i> Additional Notes
                </h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label for="remarks" class="form-label">
                            <i class="bi bi-chat-square-text text-secondary"></i> Remarks (Optional)
                        </label>
                        <textarea name="remarks" id="remarks" rows="3" class="form-control <?php $__errorArgs = ['remarks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Add any additional notes or special instructions..."><?php echo e(old('remarks')); ?></textarea>
                        <?php $__errorArgs = ['remarks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex justify-content-between align-items-center pt-4 border-top">
                <a href="<?php echo e(route('certificates.index')); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
                <button type="submit" class="btn btn-gradient px-5">
                    <i class="bi bi-check-circle-fill"></i> Issue Certificate
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/certificates/create.blade.php ENDPATH**/ ?>
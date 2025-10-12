<?php $__env->startSection('title', 'Certificate Details - Barangay Matina Pangi'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-file-earmark-text"></i> Certificate Details</h2>
    <div class="btn-group">
        <a href="<?php echo e(route('certificates.pdf', $certificate)); ?>" class="btn btn-danger">
            <i class="bi bi-file-pdf"></i> Download PDF
        </a>
        <a href="<?php echo e(route('certificates.print', $certificate)); ?>" class="btn btn-secondary" target="_blank">
            <i class="bi bi-printer"></i> Print
        </a>
        <a href="<?php echo e(route('certificates.index')); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Certificate Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Certificate Number:</strong>
                        <p class="text-primary"><?php echo e($certificate->certificate_number); ?></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Certificate Type:</strong>
                        <p><?php echo e($certificate->type_label); ?></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Resident:</strong>
                        <p><?php echo e($certificate->resident->full_name); ?></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Status:</strong>
                        <p>
                            <?php if($certificate->status == 'issued'): ?>
                                <span class="badge bg-info">Issued</span>
                            <?php elseif($certificate->status == 'claimed'): ?>
                                <span class="badge bg-success">Claimed</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Cancelled</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <div class="mb-3">
                    <strong>Purpose:</strong>
                    <p><?php echo e($certificate->purpose); ?></p>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>OR Number:</strong>
                        <p><?php echo e($certificate->or_number ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-4">
                        <strong>Amount Paid:</strong>
                        <p>₱<?php echo e(number_format($certificate->amount_paid, 2)); ?></p>
                    </div>
                    <div class="col-md-4">
                        <strong>Issued Date:</strong>
                        <p><?php echo e($certificate->issued_date->format('F d, Y')); ?></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Valid Until:</strong>
                        <p><?php echo e($certificate->valid_until ? $certificate->valid_until->format('F d, Y') : 'No Expiry'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Issued By:</strong>
                        <p><?php echo e($certificate->issuer->name); ?></p>
                    </div>
                </div>

                <?php if($certificate->remarks): ?>
                    <div class="mb-3">
                        <strong>Remarks:</strong>
                        <p><?php echo e($certificate->remarks); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-gear"></i> Actions</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('certificates.update-status', $certificate)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="status" class="form-label">Update Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="issued" <?php echo e($certificate->status == 'issued' ? 'selected' : ''); ?>>Issued</option>
                            <option value="claimed" <?php echo e($certificate->status == 'claimed' ? 'selected' : ''); ?>>Claimed</option>
                            <option value="cancelled" <?php echo e($certificate->status == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea name="remarks" id="remarks" rows="3" class="form-control"><?php echo e($certificate->remarks); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle"></i> Update Status
                    </button>
                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person"></i> Resident Info</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong><br><?php echo e($certificate->resident->full_name); ?></p>
                <p><strong>Address:</strong><br><?php echo e($certificate->resident->household->address); ?></p>
                <?php if($certificate->resident->household->purok): ?>
                    <p><strong>Purok:</strong><br>
                        <?php echo e(is_object($certificate->resident->household->purok) ? $certificate->resident->household->purok->purok_name : 'Purok ' . $certificate->resident->household->purok); ?>

                    </p>
                <?php endif; ?>
                <a href="<?php echo e(route('residents.show', $certificate->resident)); ?>" class="btn btn-sm btn-outline-primary w-100">
                    <i class="bi bi-eye"></i> View Resident
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/certificates/show.blade.php ENDPATH**/ ?>
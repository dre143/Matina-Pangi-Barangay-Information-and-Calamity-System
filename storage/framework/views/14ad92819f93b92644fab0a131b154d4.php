<?php $__env->startSection('title', 'Pending Transfer Approvals'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-clock-history"></i> Pending Transfer Approvals</h2>
    <a href="<?php echo e(route('resident-transfers.index')); ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> All Transfers
    </a>
</div>

<?php if($pendingTransfers->count() > 0): ?>
<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle"></i>
    <strong><?php echo e($pendingTransfers->total()); ?></strong> transfer request(s) waiting for your approval.
</div>
<?php endif; ?>

<!-- Pending Transfers -->
<div class="row">
    <?php $__empty_1 = true; $__currentLoopData = $pendingTransfers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transfer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="col-md-6 mb-4">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="bi bi-person"></i> <?php echo e($transfer->resident->full_name); ?>

                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">From:</small><br>
                        <strong><?php echo e($transfer->oldHousehold->household_id ?? 'N/A'); ?></strong><br>
                        <small><?php echo e($transfer->old_purok); ?></small>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">To:</small><br>
                        <?php if($transfer->transfer_type === 'internal'): ?>
                            <strong><?php echo e($transfer->newHousehold->household_id ?? 'N/A'); ?></strong><br>
                            <small><?php echo e($transfer->new_purok ?? $transfer->newHousehold->purok ?? ''); ?></small>
                        <?php else: ?>
                            <strong class="text-warning">External Transfer</strong><br>
                            <small><?php echo e($transfer->destination_barangay); ?></small>
                        <?php endif; ?>
                    </div>
                </div>

                <p class="mb-2">
                    <strong>Type:</strong>
                    <?php if($transfer->transfer_type === 'internal'): ?>
                        <span class="badge bg-info">Internal</span>
                    <?php else: ?>
                        <span class="badge bg-warning">External</span>
                    <?php endif; ?>
                </p>

                <p class="mb-2"><strong>Transfer Date:</strong> <?php echo e($transfer->transfer_date->format('M d, Y')); ?></p>
                <p class="mb-2"><strong>Reason:</strong> <?php echo e(ucfirst($transfer->reason)); ?></p>
                
                <div class="bg-light p-2 rounded mb-3">
                    <small><strong>Details:</strong></small><br>
                    <small><?php echo e(Str::limit($transfer->reason_for_transfer ?? $transfer->reason_details ?? 'No details', 100)); ?></small>
                </div>

                <p class="mb-2">
                    <small class="text-muted">
                        <i class="bi bi-person"></i> Requested by: <?php echo e($transfer->creator->name ?? 'N/A'); ?><br>
                        <i class="bi bi-clock"></i> <?php echo e($transfer->created_at->diffForHumans()); ?>

                    </small>
                </p>

                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('resident-transfers.show', $transfer)); ?>" class="btn btn-sm btn-info flex-fill">
                        <i class="bi bi-eye"></i> View Details
                    </a>
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal<?php echo e($transfer->id); ?>">
                        <i class="bi bi-check"></i> Approve
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal<?php echo e($transfer->id); ?>">
                        <i class="bi bi-x"></i> Reject
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal for this transfer -->
    <div class="modal fade" id="approveModal<?php echo e($transfer->id); ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Approve Transfer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('resident-transfers.approve', $transfer)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <p>Approve transfer for <strong><?php echo e($transfer->resident->full_name); ?></strong>?</p>
                        <div class="alert alert-info">
                            <small>
                                <?php if($transfer->transfer_type === 'internal'): ?>
                                    This will move the resident to <?php echo e($transfer->newHousehold->household_id ?? 'the new household'); ?>.
                                <?php else: ?>
                                    This will mark the resident as relocated and archive their record.
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal for this transfer -->
    <div class="modal fade" id="rejectModal<?php echo e($transfer->id); ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Reject Transfer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('resident-transfers.reject', $transfer)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <p>Reject transfer for <strong><?php echo e($transfer->resident->full_name); ?></strong>?</p>
                        <label class="form-label">Reason for rejection:</label>
                        <textarea name="remarks" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                <h4 class="mt-3">No Pending Transfers</h4>
                <p class="text-muted">All transfer requests have been processed.</p>
                <a href="<?php echo e(route('resident-transfers.index')); ?>" class="btn btn-primary">
                    <i class="bi bi-list"></i> View All Transfers
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php if($pendingTransfers->hasPages()): ?>
<div class="mt-4">
    <?php echo e($pendingTransfers->links()); ?>

</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/resident-transfers/pending.blade.php ENDPATH**/ ?>
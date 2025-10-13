<?php $__env->startSection('title', 'Transfer Request Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-arrow-left-right"></i> Transfer Request Details</h2>
    <div class="btn-group">
        <?php if(auth()->user()->isSecretary() && $residentTransfer->status === 'pending'): ?>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
            <i class="bi bi-check-circle"></i> Approve
        </button>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
            <i class="bi bi-x-circle"></i> Reject
        </button>
        <?php endif; ?>
        <a href="<?php echo e(route('resident-transfers.index')); ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<!-- Status Badge -->
<div class="alert 
    <?php if($residentTransfer->status === 'pending'): ?> alert-warning
    <?php elseif($residentTransfer->status === 'approved'): ?> alert-info
    <?php elseif($residentTransfer->status === 'completed'): ?> alert-success
    <?php else: ?> alert-danger
    <?php endif; ?>">
    <h5 class="mb-0">
        <i class="bi 
            <?php if($residentTransfer->status === 'pending'): ?> bi-clock-history
            <?php elseif($residentTransfer->status === 'approved'): ?> bi-check-circle
            <?php elseif($residentTransfer->status === 'completed'): ?> bi-check-circle-fill
            <?php else: ?> bi-x-circle
            <?php endif; ?>"></i>
        Status: <strong><?php echo e(ucfirst($residentTransfer->status)); ?></strong>
    </h5>
</div>

<div class="row">
    <!-- Resident Information -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person"></i> Resident Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> <?php echo e($residentTransfer->resident->full_name); ?></p>
                <p><strong>Resident ID:</strong> <?php echo e($residentTransfer->resident->resident_id); ?></p>
                <p><strong>Age:</strong> <?php echo e($residentTransfer->resident->age); ?> years old</p>
                <p><strong>Sex:</strong> <?php echo e(ucfirst($residentTransfer->resident->sex)); ?></p>
                <a href="<?php echo e(route('residents.show', $residentTransfer->resident)); ?>" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye"></i> View Full Profile
                </a>
            </div>
        </div>
    </div>

    <!-- Transfer Details -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Transfer Details</h5>
            </div>
            <div class="card-body">
                <p><strong>Transfer Type:</strong> 
                    <?php if($residentTransfer->transfer_type === 'transfer_in'): ?>
                        <span class="badge bg-info">Internal Transfer (Within Matina Pangi)</span>
                    <?php elseif($residentTransfer->transfer_type === 'transfer_out'): ?>
                        <span class="badge bg-warning">External Transfer (Outside Matina Pangi)</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Unknown</span>
                    <?php endif; ?>
                </p>
                <p><strong>Transfer Date:</strong> <?php echo e($residentTransfer->transfer_date->format('F d, Y')); ?></p>
                <p><strong>Requested By:</strong> <?php echo e($residentTransfer->creator->name ?? 'N/A'); ?></p>
                <p><strong>Requested On:</strong> <?php echo e($residentTransfer->created_at->format('F d, Y h:i A')); ?></p>
            </div>
        </div>
    </div>

    <!-- From/To Information -->
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-arrow-left-right"></i> Transfer Route</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <h6 class="text-danger">From (Current Location)</h6>
                        <?php if($residentTransfer->oldHousehold): ?>
                            <p><strong>Household:</strong> <?php echo e($residentTransfer->oldHousehold->household_id); ?></p>
                            <p><strong>Purok:</strong> <?php echo e($residentTransfer->old_purok); ?></p>
                            <p><strong>Address:</strong> <?php echo e($residentTransfer->oldHousehold->address); ?></p>
                            <a href="<?php echo e(route('households.show', $residentTransfer->oldHousehold)); ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-house"></i> View Household
                            </a>
                        <?php else: ?>
                            <p class="text-muted">No household information</p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-2 text-center d-flex align-items-center justify-content-center">
                        <i class="bi bi-arrow-right" style="font-size: 3rem; color: #6c757d;"></i>
                    </div>
                    <div class="col-md-5">
                        <h6 class="text-success">To (New Location)</h6>
                        <?php if($residentTransfer->transfer_type === 'transfer_in'): ?>
                            <?php if($residentTransfer->newHousehold): ?>
                                <p><strong>Household:</strong> <?php echo e($residentTransfer->newHousehold->household_id); ?></p>
                                <p><strong>Purok:</strong> <?php echo e($residentTransfer->new_purok ?? $residentTransfer->newHousehold->purok); ?></p>
                                <p><strong>Address:</strong> <?php echo e($residentTransfer->newHousehold->address); ?></p>
                                <a href="<?php echo e(route('households.show', $residentTransfer->newHousehold)); ?>" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-house"></i> View Household
                                </a>
                            <?php else: ?>
                                <p class="text-muted">Pending assignment</p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p><strong>Location:</strong> Outside Matina Pangi</p>
                            <?php if($residentTransfer->destination_address): ?>
                                <p><strong>Address:</strong> <?php echo e($residentTransfer->destination_address); ?></p>
                            <?php endif; ?>
                            <?php if($residentTransfer->destination_barangay): ?>
                                <p><strong>Barangay:</strong> <?php echo e($residentTransfer->destination_barangay); ?></p>
                            <?php endif; ?>
                            <?php if($residentTransfer->destination_municipality): ?>
                                <p><strong>Municipality:</strong> <?php echo e($residentTransfer->destination_municipality); ?></p>
                            <?php endif; ?>
                            <?php if($residentTransfer->destination_province): ?>
                                <p><strong>Province:</strong> <?php echo e($residentTransfer->destination_province); ?></p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reason -->
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-chat-square-text"></i> Reason for Transfer</h5>
            </div>
            <div class="card-body">
                <p><strong>Category:</strong> <?php echo e(ucfirst($residentTransfer->reason)); ?></p>
                <p><strong>Detailed Reason:</strong></p>
                <p class="bg-light p-3 rounded"><?php echo e($residentTransfer->reason_for_transfer ?? $residentTransfer->reason_details ?? 'No details provided'); ?></p>
            </div>
        </div>
    </div>

    <!-- Approval Information -->
    <?php if($residentTransfer->status !== 'pending'): ?>
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person-check"></i> Approval Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Processed By:</strong> <?php echo e($residentTransfer->approver->name ?? 'N/A'); ?></p>
                <p><strong>Processed On:</strong> <?php echo e($residentTransfer->approved_at ? $residentTransfer->approved_at->format('F d, Y h:i A') : 'N/A'); ?></p>
                <?php if($residentTransfer->remarks): ?>
                    <p><strong>Remarks:</strong></p>
                    <p class="bg-light p-3 rounded"><?php echo e($residentTransfer->remarks); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Approve Modal -->
<?php if(auth()->user()->isSecretary() && $residentTransfer->status === 'pending'): ?>
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Approve Transfer Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('resident-transfers.approve', $residentTransfer)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <p>Are you sure you want to approve this transfer request?</p>
                    <div class="alert alert-info">
                        <strong>Note:</strong> Approving this request will:
                        <ul class="mb-0">
                            <?php if($residentTransfer->transfer_type === 'internal'): ?>
                                <li>Move the resident to the new household</li>
                                <li>Update household member counts</li>
                                <li>Create household events for both households</li>
                            <?php else: ?>
                                <li>Mark the resident as "Relocated"</li>
                                <li>Archive the resident record</li>
                                <li>Create a household event</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Approve Transfer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Reject Transfer Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('resident-transfers.reject', $residentTransfer)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <p>Please provide a reason for rejecting this transfer request:</p>
                    <textarea name="remarks" class="form-control" rows="4" required placeholder="Enter rejection reason..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle"></i> Reject Transfer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/resident-transfers/show.blade.php ENDPATH**/ ?>
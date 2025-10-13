<?php $__env->startSection('title', 'Resident Transfers'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-arrow-left-right"></i> Resident Transfers</h2>
    <div class="btn-group">
        <?php if(auth()->user()->isSecretary()): ?>
        <a href="<?php echo e(route('resident-transfers.pending')); ?>" class="btn btn-warning">
            <i class="bi bi-clock-history"></i> Pending Approvals
        </a>
        <?php endif; ?>
        <a href="<?php echo e(route('resident-transfers.create')); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Request Transfer
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('resident-transfers.index')); ?>" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by resident name..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                    <option value="approved" <?php echo e(request('status') == 'approved' ? 'selected' : ''); ?>>Approved</option>
                    <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                    <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>Rejected</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="internal" <?php echo e(request('type') == 'internal' ? 'selected' : ''); ?>>Internal Transfer</option>
                    <option value="external" <?php echo e(request('type') == 'external' ? 'selected' : ''); ?>>External Transfer</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Transfers Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table transfer-table">
                <thead>
                    <tr>
                        <th><i class="bi bi-person"></i> Resident</th>
                        <th><i class="bi bi-house-door"></i> From</th>
                        <th><i class="bi bi-house-check"></i> To</th>
                        <th><i class="bi bi-tag"></i> Type</th>
                        <th><i class="bi bi-calendar"></i> Date</th>
                        <th><i class="bi bi-info-circle"></i> Status</th>
                        <th><i class="bi bi-gear"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $transfers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transfer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                        <i class="bi bi-person-fill text-primary"></i>
                                    </div>
                                    <div>
                                        <strong class="d-block"><?php echo e($transfer->resident->full_name); ?></strong>
                                        <small class="text-muted"><?php echo e($transfer->resident->resident_id); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if($transfer->oldHousehold): ?>
                                    <strong class="text-danger"><?php echo e($transfer->oldHousehold->household_id); ?></strong><br>
                                    <small class="text-muted"><i class="bi bi-geo-alt"></i> <?php echo e($transfer->old_purok); ?></small>
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($transfer->transfer_type === 'transfer_in' && $transfer->newHousehold): ?>
                                    <strong class="text-success"><?php echo e($transfer->newHousehold->household_id); ?></strong><br>
                                    <small class="text-muted"><i class="bi bi-geo-alt"></i> <?php echo e($transfer->new_purok ?? $transfer->newHousehold->purok); ?></small>
                                <?php elseif($transfer->transfer_type === 'transfer_out'): ?>
                                    <span class="badge bg-warning">External Location</span><br>
                                    <small class="text-muted"><?php echo e($transfer->destination_barangay ?? 'Outside Barangay'); ?></small>
                                <?php else: ?>
                                    <span class="text-muted">Pending Assignment</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($transfer->transfer_type === 'transfer_in'): ?>
                                    <span class="badge bg-info"><i class="bi bi-arrow-left-right"></i> Internal</span>
                                <?php elseif($transfer->transfer_type === 'transfer_out'): ?>
                                    <span class="badge bg-warning"><i class="bi bi-box-arrow-right"></i> External</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Unknown</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <i class="bi bi-calendar-event"></i>
                                <?php echo e($transfer->transfer_date->format('M d, Y')); ?><br>
                                <small class="text-muted"><?php echo e($transfer->transfer_date->diffForHumans()); ?></small>
                            </td>
                            <td>
                                <?php if($transfer->status === 'pending'): ?>
                                    <span class="status-badge pending">
                                        <i class="bi bi-clock-history"></i> Pending
                                    </span>
                                <?php elseif($transfer->status === 'approved'): ?>
                                    <span class="status-badge approved">
                                        <i class="bi bi-check-circle"></i> Approved
                                    </span>
                                <?php elseif($transfer->status === 'completed'): ?>
                                    <span class="status-badge completed">
                                        <i class="bi bi-check-circle-fill"></i> Completed
                                    </span>
                                <?php else: ?>
                                    <span class="status-badge rejected">
                                        <i class="bi bi-x-circle"></i> Rejected
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-btn-group">
                                    <a href="<?php echo e(route('resident-transfers.show', $transfer)); ?>" class="btn btn-sm btn-primary" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <h5>No Transfer Records Found</h5>
                                    <p class="text-muted">There are no resident transfer records matching your criteria.</p>
                                    <a href="<?php echo e(route('resident-transfers.create')); ?>" class="btn btn-primary mt-3">
                                        <i class="bi bi-plus-circle"></i> Create New Transfer Request
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($transfers->hasPages()): ?>
        <div class="mt-3">
            <?php echo e($transfers->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/resident-transfers/index.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Pending Approvals'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-clock-history"></i> Pending Approvals</h2>
    <a href="<?php echo e(route('archived.index')); ?>" class="btn btn-secondary">
        <i class="bi bi-archive"></i> View Archived Records
    </a>
</div>

<!-- Pending Residents -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-people"></i> Pending Residents (<?php echo e($pendingResidents->total()); ?>)</h5>
    </div>
    <div class="card-body">
        <?php if($pendingResidents->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Resident ID</th>
                            <th>Name</th>
                            <th>Age/Sex</th>
                            <th>Household</th>
                            <th>Registered By</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $pendingResidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($resident->resident_id); ?></strong></td>
                            <td><?php echo e($resident->full_name); ?></td>
                            <td><?php echo e($resident->age); ?> / <?php echo e(ucfirst($resident->sex)); ?></td>
                            <td>
                                <a href="<?php echo e(route('households.show', $resident->household)); ?>">
                                    <?php echo e($resident->household->household_id); ?>

                                </a>
                            </td>
                            <td><?php echo e($resident->creator ? $resident->creator->name : 'N/A'); ?></td>
                            <td><?php echo e($resident->created_at->format('M d, Y')); ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('residents.show', $resident)); ?>" 
                                       class="btn btn-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="<?php echo e(route('approvals.resident.approve', $resident)); ?>" 
                                          method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-success" title="Approve">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#rejectResidentModal<?php echo e($resident->id); ?>"
                                            title="Reject">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </div>
                                
                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectResidentModal<?php echo e($resident->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="<?php echo e(route('approvals.resident.reject', $resident)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Resident</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to reject <strong><?php echo e($resident->full_name); ?></strong>?</p>
                                                    <div class="mb-3">
                                                        <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                                        <textarea class="form-control" name="rejection_reason" rows="3" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Reject & Archive</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                <?php echo e($pendingResidents->links()); ?>

            </div>
        <?php else: ?>
            <p class="text-muted text-center mb-0">No pending residents.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Pending Households -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-house"></i> Pending Households (<?php echo e($pendingHouseholds->total()); ?>)</h5>
    </div>
    <div class="card-body">
        <?php if($pendingHouseholds->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Household ID</th>
                            <th>Address</th>
                            <th>Head</th>
                            <th>Members</th>
                            <th>Registered By</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $pendingHouseholds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $household): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($household->household_id); ?></strong></td>
                            <td><?php echo e($household->full_address); ?></td>
                            <td><?php echo e($household->head ? $household->head->full_name : 'N/A'); ?></td>
                            <td><?php echo e($household->total_members); ?></td>
                            <td><?php echo e($household->head && $household->head->creator ? $household->head->creator->name : 'N/A'); ?></td>
                            <td><?php echo e($household->created_at->format('M d, Y')); ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('households.show', $household)); ?>" 
                                       class="btn btn-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="<?php echo e(route('approvals.household.approve', $household)); ?>" 
                                          method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-success" title="Approve">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#rejectHouseholdModal<?php echo e($household->id); ?>"
                                            title="Reject">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </div>
                                
                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectHouseholdModal<?php echo e($household->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="<?php echo e(route('approvals.household.reject', $household)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Household</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to reject household <strong><?php echo e($household->household_id); ?></strong>?</p>
                                                    <p class="text-danger small">This will also reject all <?php echo e($household->total_members); ?> member(s).</p>
                                                    <div class="mb-3">
                                                        <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                                        <textarea class="form-control" name="rejection_reason" rows="3" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Reject & Archive</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                <?php echo e($pendingHouseholds->links()); ?>

            </div>
        <?php else: ?>
            <p class="text-muted text-center mb-0">No pending households.</p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/approvals/index.blade.php ENDPATH**/ ?>
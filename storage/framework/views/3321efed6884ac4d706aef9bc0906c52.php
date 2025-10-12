<?php $__env->startSection('title', 'Archived Records'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-archive"></i> Archived Records</h2>
    <a href="<?php echo e(route('approvals.index')); ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Approvals
    </a>
</div>

<!-- Archived Residents -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-people"></i> Archived Residents (<?php echo e($archivedResidents->total()); ?>)</h5>
    </div>
    <div class="card-body">
        <?php if($archivedResidents->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Resident ID</th>
                            <th>Name</th>
                            <th>Age/Sex</th>
                            <th>Household</th>
                            <th>Status</th>
                            <th>Archived Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $archivedResidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($resident->resident_id); ?></strong></td>
                            <td><?php echo e($resident->full_name); ?></td>
                            <td><?php echo e($resident->age); ?> / <?php echo e(ucfirst($resident->sex)); ?></td>
                            <td>
                                <?php if($resident->household): ?>
                                    <?php echo e($resident->household->household_id); ?>

                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo e($resident->approval_badge_color); ?>">
                                    <?php echo e(ucfirst($resident->approval_status)); ?>

                                </span>
                                <?php if($resident->rejection_reason): ?>
                                    <br><small class="text-muted"><?php echo e(Str::limit($resident->rejection_reason, 30)); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($resident->deleted_at->format('M d, Y')); ?></td>
                            <td>
                                <form action="<?php echo e(route('archived.resident.restore', $resident->id)); ?>" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to restore this resident?')">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-success" title="Restore">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                    </button>
                                </form>
                                
                                <form action="<?php echo e(route('archived.resident.delete', $resident->id)); ?>" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('⚠️ WARNING: This will PERMANENTLY delete this resident. This action CANNOT be undone. Are you absolutely sure?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger" title="Permanently Delete">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                <?php echo e($archivedResidents->links()); ?>

            </div>
        <?php else: ?>
            <p class="text-muted text-center mb-0">No archived residents.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Archived Households -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-house"></i> Archived Households (<?php echo e($archivedHouseholds->total()); ?>)</h5>
    </div>
    <div class="card-body">
        <?php if($archivedHouseholds->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Household ID</th>
                            <th>Address</th>
                            <th>Head</th>
                            <th>Members</th>
                            <th>Status</th>
                            <th>Archived Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $archivedHouseholds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $household): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($household->household_id); ?></strong></td>
                            <td><?php echo e($household->full_address); ?></td>
                            <td><?php echo e($household->head ? $household->head->full_name : 'N/A'); ?></td>
                            <td><?php echo e($household->total_members); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($household->approval_badge_color); ?>">
                                    <?php echo e(ucfirst($household->approval_status)); ?>

                                </span>
                                <?php if($household->rejection_reason): ?>
                                    <br><small class="text-muted"><?php echo e(Str::limit($household->rejection_reason, 30)); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($household->deleted_at->format('M d, Y')); ?></td>
                            <td>
                                <form action="<?php echo e(route('archived.household.restore', $household->id)); ?>" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to restore this household and all its members?')">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-success" title="Restore">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                    </button>
                                </form>
                                
                                <form action="<?php echo e(route('archived.household.delete', $household->id)); ?>" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('⚠️ WARNING: This will PERMANENTLY delete this household and ALL its members. This action CANNOT be undone. Are you absolutely sure?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger" title="Permanently Delete">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                <?php echo e($archivedHouseholds->links()); ?>

            </div>
        <?php else: ?>
            <p class="text-muted text-center mb-0">No archived households.</p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/approvals/archived.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Calamity Management - Barangay Matina Pangi'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Calamity Management</h2>
    <a href="<?php echo e(route('calamities.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Record Calamity
    </a>
</div>

<!-- Calamities Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Calamity Name</th>
                        <th>Type</th>
                        <th>Date Occurred</th>
                        <th>Severity</th>
                        <th>Affected Households</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $calamities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $calamity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong><?php echo e($calamity->calamity_name); ?></strong></td>
                            <td><span class="badge bg-warning"><?php echo e(ucfirst($calamity->calamity_type)); ?></span></td>
                            <td><?php echo e($calamity->date_occurred->format('M d, Y')); ?></td>
                            <td>
                                <?php if($calamity->severity_level == 'catastrophic'): ?>
                                    <span class="badge bg-danger">Catastrophic</span>
                                <?php elseif($calamity->severity_level == 'severe'): ?>
                                    <span class="badge bg-danger">Severe</span>
                                <?php elseif($calamity->severity_level == 'moderate'): ?>
                                    <span class="badge bg-warning">Moderate</span>
                                <?php else: ?>
                                    <span class="badge bg-info">Minor</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($calamity->affected_households_count); ?> households</td>
                            <td>
                                <?php if($calamity->status == 'ongoing'): ?>
                                    <span class="badge bg-danger">Ongoing</span>
                                <?php elseif($calamity->status == 'monitoring'): ?>
                                    <span class="badge bg-warning">Monitoring</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Resolved</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('calamities.show', $calamity)); ?>" class="btn btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('calamities.edit', $calamity)); ?>" class="btn btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-2">No calamity records found.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <?php echo e($calamities->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/calamities/index.blade.php ENDPATH**/ ?>
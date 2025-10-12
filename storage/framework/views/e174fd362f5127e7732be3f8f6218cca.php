<?php $__env->startSection('title', 'Calamity Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Calamity Details</h2>
    <div class="btn-group">
        <a href="<?php echo e(route('calamities.edit', $calamity)); ?>" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="<?php echo e(route('calamities.add-households', $calamity)); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Affected Household
        </a>
        <a href="<?php echo e(route('calamities.index')); ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Calamity Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Calamity Name:</strong>
                        <p><?php echo e($calamity->calamity_name); ?></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Type:</strong>
                        <p><span class="badge bg-warning"><?php echo e(ucfirst($calamity->calamity_type)); ?></span></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Date Occurred:</strong>
                        <p><?php echo e($calamity->date_occurred->format('F d, Y')); ?></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Severity Level:</strong>
                        <p>
                            <?php if($calamity->severity_level == 'catastrophic'): ?>
                                <span class="badge bg-danger">Catastrophic</span>
                            <?php elseif($calamity->severity_level == 'severe'): ?>
                                <span class="badge bg-danger">Severe</span>
                            <?php elseif($calamity->severity_level == 'moderate'): ?>
                                <span class="badge bg-warning">Moderate</span>
                            <?php else: ?>
                                <span class="badge bg-info">Minor</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Status:</strong>
                        <p>
                            <?php if($calamity->status == 'ongoing'): ?>
                                <span class="badge bg-danger">Ongoing</span>
                            <?php elseif($calamity->status == 'monitoring'): ?>
                                <span class="badge bg-warning">Monitoring</span>
                            <?php else: ?>
                                <span class="badge bg-success">Resolved</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <strong>Reported By:</strong>
                        <p><?php echo e($calamity->reporter ? $calamity->reporter->name : 'N/A'); ?></p>
                    </div>
                </div>

                <?php if($calamity->affected_areas): ?>
                    <div class="mb-3">
                        <strong>Affected Areas:</strong>
                        <p><?php echo e($calamity->affected_areas); ?></p>
                    </div>
                <?php endif; ?>

                <?php if($calamity->description): ?>
                    <div class="mb-3">
                        <strong>Description:</strong>
                        <p><?php echo e($calamity->description); ?></p>
                    </div>
                <?php endif; ?>

                <?php if($calamity->response_actions): ?>
                    <div class="mb-3">
                        <strong>Response Actions:</strong>
                        <p><?php echo e($calamity->response_actions); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-house"></i> Affected Households (<?php echo e($calamity->affectedHouseholds->count()); ?>)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Household</th>
                                <th>Damage Level</th>
                                <th>Estimated Cost</th>
                                <th>Assistance Needed</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $calamity->affectedHouseholds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $affected): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($affected->household->household_number); ?></td>
                                    <td>
                                        <?php if($affected->damage_level == 'total'): ?>
                                            <span class="badge bg-danger">Total</span>
                                        <?php elseif($affected->damage_level == 'severe'): ?>
                                            <span class="badge bg-danger">Severe</span>
                                        <?php elseif($affected->damage_level == 'moderate'): ?>
                                            <span class="badge bg-warning">Moderate</span>
                                        <?php else: ?>
                                            <span class="badge bg-info">Minor</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($affected->estimated_damage_cost ? '₱' . number_format($affected->estimated_damage_cost, 2) : 'N/A'); ?></td>
                                    <td><?php echo e(Str::limit($affected->assistance_needed ?? 'None specified', 30)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No affected households recorded yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Statistics</h5>
            </div>
            <div class="card-body">
                <p><strong>Total Affected Households:</strong><br><?php echo e($calamity->affectedHouseholds->count()); ?></p>
                <p><strong>Total Estimated Damage:</strong><br>₱<?php echo e(number_format($calamity->affectedHouseholds->sum('estimated_damage_cost'), 2)); ?></p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/calamities/show.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Health Records - Barangay Matina Pangi'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-heart-pulse"></i> Health Records</h2>
    <a href="<?php echo e(route('health-records.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add Health Record
    </a>
</div>

<!-- Search -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('health-records.index')); ?>" class="row g-3">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control" placeholder="Search by resident name..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Health Records Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Resident</th>
                        <th>Blood Type</th>
                        <th>Height (cm)</th>
                        <th>Weight (kg)</th>
                        <th>PhilHealth #</th>
                        <th>Emergency Contact</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $healthRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong><?php echo e($record->resident->full_name); ?></strong></td>
                            <td><?php echo e($record->blood_type ?? 'N/A'); ?></td>
                            <td><?php echo e($record->height ?? 'N/A'); ?></td>
                            <td><?php echo e($record->weight ?? 'N/A'); ?></td>
                            <td><?php echo e($record->philhealth_number ?? 'N/A'); ?></td>
                            <td>
                                <?php echo e($record->emergency_contact ?? 'N/A'); ?><br>
                                <small class="text-muted"><?php echo e($record->emergency_contact_number); ?></small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('health-records.show', $record)); ?>" class="btn btn-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('health-records.edit', $record)); ?>" class="btn btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-2">No health records found.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <?php echo e($healthRecords->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/health-records/index.blade.php ENDPATH**/ ?>
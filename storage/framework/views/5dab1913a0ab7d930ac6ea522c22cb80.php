<?php $__env->startSection('title', 'Senior Health - Barangay Matina Pangi'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-person-cane"></i> Senior Health Records</h2>
    <a href="<?php echo e(route('senior-health.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add Senior Health Record
    </a>
</div>

<!-- Search -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control" placeholder="Search by senior citizen name..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Records Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Senior Citizen</th>
                        <th>Age</th>
                        <th>Mobility Status</th>
                        <th>Last Checkup</th>
                        <th>Health Conditions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $seniorHealthRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong><?php echo e($record->resident->full_name); ?></strong></td>
                            <td><?php echo e($record->resident->age); ?> years</td>
                            <td>
                                <?php if($record->mobility_status == 'independent'): ?>
                                    <span class="badge bg-success">Independent</span>
                                <?php elseif($record->mobility_status == 'assisted'): ?>
                                    <span class="badge bg-info">Assisted</span>
                                <?php elseif($record->mobility_status == 'wheelchair'): ?>
                                    <span class="badge bg-warning">Wheelchair</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Bedridden</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($record->last_checkup_date ? $record->last_checkup_date->format('M d, Y') : 'N/A'); ?></td>
                            <td><?php echo e(Str::limit($record->health_conditions ?? 'None specified', 40)); ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('senior-health.show', $record)); ?>" class="btn btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('senior-health.edit', $record)); ?>" class="btn btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-2">No senior health records found.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <?php echo e($seniorHealthRecords->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/senior-health/index.blade.php ENDPATH**/ ?>
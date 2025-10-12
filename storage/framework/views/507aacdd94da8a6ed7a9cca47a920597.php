<?php $__env->startSection('title', 'PWD Support - Barangay Matina Pangi'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-universal-access"></i> PWD Support Registry</h2>
    <a href="<?php echo e(route('pwd-support.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Register PWD
    </a>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Search by PWD ID or name..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-2">
                <select name="disability_type" class="form-select">
                    <option value="">All Types</option>
                    <option value="visual">Visual</option>
                    <option value="hearing">Hearing</option>
                    <option value="mobility">Mobility</option>
                    <option value="mental">Mental</option>
                    <option value="psychosocial">Psychosocial</option>
                    <option value="multiple">Multiple</option>
                    <option value="other">Other</option>
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

<!-- PWD Records Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>PWD ID</th>
                        <th>Name</th>
                        <th>Disability Type</th>
                        <th>Date Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $pwdRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong><?php echo e($record->pwd_id_number); ?></strong></td>
                            <td><?php echo e($record->resident->full_name); ?></td>
                            <td><span class="badge bg-info"><?php echo e(ucfirst($record->disability_type)); ?></span></td>
                            <td><?php echo e($record->date_registered ? $record->date_registered->format('M d, Y') : 'N/A'); ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('pwd-support.show', $record)); ?>" class="btn btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('pwd-support.edit', $record)); ?>" class="btn btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-2">No PWD records found.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <?php echo e($pwdRecords->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/pwd-support/index.blade.php ENDPATH**/ ?>
<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Purok Management</h2>
        <?php if(auth()->user()->isSecretary()): ?>
        <a href="<?php echo e(route('puroks.create')); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Purok
        </a>
        <?php endif; ?>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php $__empty_1 = true; $__currentLoopData = $puroks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purok): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><?php echo e($purok->purok_name); ?></h5>
                    <p class="text-muted mb-3">Code: <strong><?php echo e($purok->purok_code); ?></strong></p>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Households:</span>
                            <strong><?php echo e($purok->households_count ?? 0); ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Population:</span>
                            <strong><?php echo e($purok->residents_count ?? 0); ?></strong>
                        </div>
                    </div>

                    <?php if($purok->purok_leader_name): ?>
                    <div class="mb-3">
                        <small class="text-muted">Purok Leader:</small>
                        <p class="mb-0"><strong><?php echo e($purok->purok_leader_name); ?></strong></p>
                        <?php if($purok->purok_leader_contact): ?>
                        <small><?php echo e($purok->purok_leader_contact); ?></small>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <div class="d-flex gap-2">
                        <a href="<?php echo e(route('puroks.show', $purok)); ?>" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i> View
                        </a>
                        <?php if(auth()->user()->isSecretary()): ?>
                        <a href="<?php echo e(route('puroks.edit', $purok)); ?>" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="<?php echo e(route('puroks.update-counts', $purok)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-secondary" title="Update Counts">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <div class="alert alert-info">
                No puroks found. <a href="<?php echo e(route('puroks.create')); ?>">Create one now</a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/puroks/index.blade.php ENDPATH**/ ?>
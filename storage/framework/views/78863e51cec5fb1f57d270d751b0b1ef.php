<?php $__env->startSection('title', 'PWD Record Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-universal-access"></i> PWD Record Details</h2>
    <div class="btn-group">
        <a href="<?php echo e(route('pwd-support.edit', $pwdSupport)); ?>" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="<?php echo e(route('pwd-support.index')); ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person"></i> PWD Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>PWD ID Number:</strong>
                        <p class="text-primary"><?php echo e($pwdSupport->pwd_id_number); ?></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Resident:</strong>
                        <p><?php echo e($pwdSupport->resident->full_name); ?></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Disability Type:</strong>
                        <p><span class="badge bg-info"><?php echo e(ucfirst($pwdSupport->disability_type)); ?></span></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Date Registered:</strong>
                        <p><?php echo e($pwdSupport->date_registered ? $pwdSupport->date_registered->format('F d, Y') : 'Not recorded'); ?></p>
                    </div>
                </div>

                <?php if($pwdSupport->disability_description): ?>
                    <div class="mb-3">
                        <strong>Disability Description:</strong>
                        <p><?php echo e($pwdSupport->disability_description); ?></p>
                    </div>
                <?php endif; ?>

                <?php if($pwdSupport->assistance_received): ?>
                    <div class="mb-3">
                        <strong>Assistance Received:</strong>
                        <p><?php echo e($pwdSupport->assistance_received); ?></p>
                    </div>
                <?php endif; ?>

                <?php if($pwdSupport->medical_needs): ?>
                    <div class="mb-3">
                        <strong>Medical Needs:</strong>
                        <p><?php echo e($pwdSupport->medical_needs); ?></p>
                    </div>
                <?php endif; ?>

                <?php if($pwdSupport->notes): ?>
                    <div class="mb-3">
                        <strong>Additional Notes:</strong>
                        <p><?php echo e($pwdSupport->notes); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person"></i> Resident Info</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong><br><?php echo e($pwdSupport->resident->full_name); ?></p>
                <p><strong>Age:</strong><br><?php echo e($pwdSupport->resident->age); ?> years</p>
                <p><strong>Address:</strong><br><?php echo e($pwdSupport->resident->household->address); ?></p>
                <a href="<?php echo e(route('residents.show', $pwdSupport->resident)); ?>" class="btn btn-sm btn-outline-primary w-100">
                    <i class="bi bi-eye"></i> View Full Profile
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="<?php echo e(route('pwd-support.destroy', $pwdSupport)); ?>" method="POST" onsubmit="return confirm('Are you sure?');">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash"></i> Delete Record
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/pwd-support/show.blade.php ENDPATH**/ ?>
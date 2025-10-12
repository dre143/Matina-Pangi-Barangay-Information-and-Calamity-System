<?php $__env->startSection('title', 'Government Assistance Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-gift"></i> Government Assistance Details</h2>
    <div class="btn-group">
        <a href="<?php echo e(route('government-assistance.edit', $governmentAssistance)); ?>" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="<?php echo e(route('government-assistance.index')); ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Program Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Program Name:</strong>
                        <p><?php echo e($governmentAssistance->program_name); ?></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Program Type:</strong>
                        <p><span class="badge bg-primary"><?php echo e(strtoupper($governmentAssistance->program_type)); ?></span></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Recipient:</strong>
                        <p><?php echo e($governmentAssistance->resident->full_name); ?></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Status:</strong>
                        <p>
                            <?php if($governmentAssistance->status == 'active'): ?>
                                <span class="badge bg-success">Active</span>
                            <?php elseif($governmentAssistance->status == 'completed'): ?>
                                <span class="badge bg-info">Completed</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Cancelled</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Amount:</strong>
                        <p><?php echo e($governmentAssistance->amount ? '₱' . number_format($governmentAssistance->amount, 2) : 'Not specified'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Date Received:</strong>
                        <p><?php echo e($governmentAssistance->date_received ? $governmentAssistance->date_received->format('F d, Y') : 'Not recorded'); ?></p>
                    </div>
                </div>

                <?php if($governmentAssistance->description): ?>
                    <div class="mb-3">
                        <strong>Description:</strong>
                        <p><?php echo e($governmentAssistance->description); ?></p>
                    </div>
                <?php endif; ?>

                <?php if($governmentAssistance->notes): ?>
                    <div class="mb-3">
                        <strong>Notes:</strong>
                        <p><?php echo e($governmentAssistance->notes); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person"></i> Recipient Info</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong><br><?php echo e($governmentAssistance->resident->full_name); ?></p>
                <p><strong>Address:</strong><br><?php echo e($governmentAssistance->resident->household->address); ?></p>
                <a href="<?php echo e(route('residents.show', $governmentAssistance->resident)); ?>" class="btn btn-sm btn-outline-primary w-100">
                    <i class="bi bi-eye"></i> View Profile
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="<?php echo e(route('government-assistance.destroy', $governmentAssistance)); ?>" method="POST" onsubmit="return confirm('Are you sure?');">
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/government-assistance/show.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Edit Government Assistance'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-gift"></i> Edit Government Assistance</h2>
    <a href="<?php echo e(route('government-assistance.show', $governmentAssistance)); ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?php echo e(route('government-assistance.update', $governmentAssistance)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Recipient</label>
                    <input type="text" class="form-control" value="<?php echo e($governmentAssistance->resident->full_name); ?>" disabled>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="program_name" class="form-label">Program Name <span class="text-danger">*</span></label>
                    <input type="text" name="program_name" id="program_name" class="form-control" value="<?php echo e($governmentAssistance->program_name); ?>" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="program_type" class="form-label">Program Type <span class="text-danger">*</span></label>
                    <select name="program_type" id="program_type" class="form-select" required>
                        <option value="4ps" <?php echo e($governmentAssistance->program_type == '4ps' ? 'selected' : ''); ?>>4Ps</option>
                        <option value="sss" <?php echo e($governmentAssistance->program_type == 'sss' ? 'selected' : ''); ?>>SSS</option>
                        <option value="philhealth" <?php echo e($governmentAssistance->program_type == 'philhealth' ? 'selected' : ''); ?>>PhilHealth</option>
                        <option value="ayuda" <?php echo e($governmentAssistance->program_type == 'ayuda' ? 'selected' : ''); ?>>Ayuda</option>
                        <option value="scholarship" <?php echo e($governmentAssistance->program_type == 'scholarship' ? 'selected' : ''); ?>>Scholarship</option>
                        <option value="livelihood" <?php echo e($governmentAssistance->program_type == 'livelihood' ? 'selected' : ''); ?>>Livelihood</option>
                        <option value="housing" <?php echo e($governmentAssistance->program_type == 'housing' ? 'selected' : ''); ?>>Housing</option>
                        <option value="other" <?php echo e($governmentAssistance->program_type == 'other' ? 'selected' : ''); ?>>Other</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" name="amount" id="amount" step="0.01" class="form-control" value="<?php echo e($governmentAssistance->amount); ?>">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="date_received" class="form-label">Date Received <span class="text-danger">*</span></label>
                    <input type="date" name="date_received" id="date_received" class="form-control" value="<?php echo e($governmentAssistance->date_received ? $governmentAssistance->date_received->format('Y-m-d') : ''); ?>" required>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" rows="3" class="form-control"><?php echo e($governmentAssistance->description); ?></textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="active" <?php echo e($governmentAssistance->status == 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="completed" <?php echo e($governmentAssistance->status == 'completed' ? 'selected' : ''); ?>>Completed</option>
                        <option value="cancelled" <?php echo e($governmentAssistance->status == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                    </select>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea name="notes" id="notes" rows="2" class="form-control"><?php echo e($governmentAssistance->notes); ?></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo e(route('government-assistance.show', $governmentAssistance)); ?>" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Update Record
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/government-assistance/edit.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Edit Senior Health Record'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-person-cane"></i> Edit Senior Health Record</h2>
    <a href="<?php echo e(route('senior-health.show', $seniorHealth)); ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?php echo e(route('senior-health.update', $seniorHealth)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Senior Citizen</label>
                    <input type="text" class="form-control" value="<?php echo e($seniorHealth->resident->full_name); ?>" disabled>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="mobility_status" class="form-label">Mobility Status</label>
                    <select name="mobility_status" id="mobility_status" class="form-select">
                        <option value="">Select Status</option>
                        <option value="independent" <?php echo e($seniorHealth->mobility_status == 'independent' ? 'selected' : ''); ?>>Independent</option>
                        <option value="assisted" <?php echo e($seniorHealth->mobility_status == 'assisted' ? 'selected' : ''); ?>>Assisted</option>
                        <option value="wheelchair" <?php echo e($seniorHealth->mobility_status == 'wheelchair' ? 'selected' : ''); ?>>Wheelchair</option>
                        <option value="bedridden" <?php echo e($seniorHealth->mobility_status == 'bedridden' ? 'selected' : ''); ?>>Bedridden</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="last_checkup_date" class="form-label">Last Checkup Date</label>
                    <input type="date" name="last_checkup_date" id="last_checkup_date" class="form-control" value="<?php echo e($seniorHealth->last_checkup_date?->format('Y-m-d')); ?>">
                </div>

                <div class="col-md-12 mb-3">
                    <label for="health_conditions" class="form-label">Health Conditions</label>
                    <textarea name="health_conditions" id="health_conditions" rows="3" class="form-control"><?php echo e($seniorHealth->health_conditions); ?></textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="medications" class="form-label">Current Medications</label>
                    <textarea name="medications" id="medications" rows="3" class="form-control"><?php echo e($seniorHealth->medications); ?></textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="notes" class="form-label">Additional Notes</label>
                    <textarea name="notes" id="notes" rows="2" class="form-control"><?php echo e($seniorHealth->notes); ?></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo e(route('senior-health.show', $seniorHealth)); ?>" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Update Record
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/senior-health/edit.blade.php ENDPATH**/ ?>
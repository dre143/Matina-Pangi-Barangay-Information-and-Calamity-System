<?php $__env->startSection('title', 'Request Resident Transfer'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2><i class="bi bi-arrow-left-right"></i> Request Resident Transfer</h2>
            <p class="text-muted">Submit a transfer request for a resident</p>
        </div>
    </div>

    <?php if(isset($resident)): ?>
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Selected Resident</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Name:</strong> <?php echo e($resident->full_name); ?></p>
                    <p><strong>Resident ID:</strong> <?php echo e($resident->resident_id); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Current Household:</strong> <?php echo e($resident->household->household_id); ?></p>
                    <p><strong>Current Purok:</strong> <?php echo e($resident->household->purok); ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Transfer Request Form</h5>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('resident-transfers.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <div class="row g-3">
                    <?php if(!isset($resident)): ?>
                    <div class="col-12">
                        <label class="form-label fw-bold">Select Resident <span class="text-danger">*</span></label>
                        <select name="resident_id" id="resident_id" class="form-select" required>
                            <option value="">-- Select Resident --</option>
                            <?php $__currentLoopData = $residents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($r['id']); ?>" <?php echo e(old('resident_id') == $r['id'] ? 'selected' : ''); ?>>
                                    <?php echo e($r['label']); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <?php else: ?>
                    <input type="hidden" name="resident_id" value="<?php echo e($resident->id); ?>">
                    <?php endif; ?>

                    <div class="col-12"><hr><h6 class="text-primary">Transfer Details</h6></div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Transfer Type <span class="text-danger">*</span></label>
                        <select name="transfer_type" id="transfer_type" class="form-select" required>
                            <option value="">-- Select Type --</option>
                            <option value="internal" <?php echo e(old('transfer_type') == 'internal' ? 'selected' : ''); ?>>Internal (Within Matina Pangi)</option>
                            <option value="external" <?php echo e(old('transfer_type') == 'external' ? 'selected' : ''); ?>>External (Moving Out)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Transfer Date <span class="text-danger">*</span></label>
                        <input type="date" name="transfer_date" class="form-control" required value="<?php echo e(old('transfer_date', date('Y-m-d'))); ?>">
                    </div>

                    <!-- Internal Transfer Fields -->
                    <div id="internal-fields" style="display: none;" class="col-12">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">New Household <span class="text-danger">*</span></label>
                                <select name="new_household_id" class="form-select">
                                    <option value="">-- Select Household --</option>
                                    <?php $__currentLoopData = $households; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($h['id']); ?>" <?php echo e(old('new_household_id') == $h['id'] ? 'selected' : ''); ?>>
                                            <?php echo e($h['label']); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- External Transfer Fields -->
                    <div id="external-fields" style="display: none;" class="col-12">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Destination Address <span class="text-danger">*</span></label>
                                <textarea name="destination_address" class="form-control" rows="2"><?php echo e(old('destination_address')); ?></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Barangay</label>
                                <input type="text" name="destination_barangay" class="form-control" value="<?php echo e(old('destination_barangay')); ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Municipality/City</label>
                                <input type="text" name="destination_municipality" class="form-control" value="<?php echo e(old('destination_municipality')); ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Province</label>
                                <input type="text" name="destination_province" class="form-control" value="<?php echo e(old('destination_province')); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-12"><hr><h6 class="text-primary">Reason for Transfer</h6></div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Reason Category <span class="text-danger">*</span></label>
                        <select name="reason" class="form-select" required>
                            <option value="">-- Select Reason --</option>
                            <option value="work" <?php echo e(old('reason') == 'work' ? 'selected' : ''); ?>>Work/Employment</option>
                            <option value="marriage" <?php echo e(old('reason') == 'marriage' ? 'selected' : ''); ?>>Marriage</option>
                            <option value="school" <?php echo e(old('reason') == 'school' ? 'selected' : ''); ?>>Education</option>
                            <option value="family" <?php echo e(old('reason') == 'family' ? 'selected' : ''); ?>>Family Reasons</option>
                            <option value="health" <?php echo e(old('reason') == 'health' ? 'selected' : ''); ?>>Health Reasons</option>
                            <option value="other" <?php echo e(old('reason') == 'other' ? 'selected' : ''); ?>>Other</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold">Detailed Reason <span class="text-danger">*</span></label>
                        <textarea name="reason_for_transfer" class="form-control" rows="3" required placeholder="Please provide a detailed explanation for this transfer request..."><?php echo e(old('reason_for_transfer')); ?></textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send"></i> Submit Transfer Request
                    </button>
                    <a href="<?php echo e(route('resident-transfers.index')); ?>" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const transferType = document.getElementById('transfer_type');
    const internalFields = document.getElementById('internal-fields');
    const externalFields = document.getElementById('external-fields');

    function toggleFields() {
        const type = transferType.value;
        
        if (type === 'internal') {
            internalFields.style.display = 'block';
            externalFields.style.display = 'none';
            internalFields.querySelectorAll('select, input, textarea').forEach(el => el.required = true);
            externalFields.querySelectorAll('select, input, textarea').forEach(el => el.required = false);
        } else if (type === 'external') {
            internalFields.style.display = 'none';
            externalFields.style.display = 'block';
            internalFields.querySelectorAll('select, input, textarea').forEach(el => el.required = false);
            externalFields.querySelectorAll('select, input, textarea').forEach(el => el.required = true);
        } else {
            internalFields.style.display = 'none';
            externalFields.style.display = 'none';
            internalFields.querySelectorAll('select, input, textarea').forEach(el => el.required = false);
            externalFields.querySelectorAll('select, input, textarea').forEach(el => el.required = false);
        }
    }

    transferType.addEventListener('change', toggleFields);
    
    // Initialize on page load
    if (transferType.value) {
        toggleFields();
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/resident-transfers/create.blade.php ENDPATH**/ ?>
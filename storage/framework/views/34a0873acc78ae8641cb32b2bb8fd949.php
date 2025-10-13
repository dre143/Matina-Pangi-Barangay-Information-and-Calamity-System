<?php $__env->startSection('title', 'Add Extended Family'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2><i class="bi bi-people-fill"></i> Add Extended Family (Co-Head)</h2>
            <p class="text-muted">Add an extended family group with a co-head to the household</p>
        </div>
    </div>

    <?php if(isset($household)): ?>
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Selected Household</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Household ID:</strong> <?php echo e($household->household_id); ?></p>
                    <p><strong>Address:</strong> <?php echo e($household->address); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Primary Head:</strong> <?php echo e($household->officialHead ? $household->officialHead->full_name : 'N/A'); ?></p>
                    <p><strong>Current Members:</strong> <?php echo e($household->total_members); ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-person-plus"></i> Extended Family Information</h5>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('sub-families.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <?php if(!isset($household)): ?>
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <label class="form-label fw-bold">Select Household <span class="text-danger">*</span></label>
                        <select name="household_id" class="form-select" required>
                            <option value="">-- Select Household --</option>
                            <?php $__currentLoopData = $households; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($h['id']); ?>" <?php echo e(old('household_id') == $h['id'] ? 'selected' : ''); ?>>
                                    <?php echo e($h['label']); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <?php else: ?>
                <input type="hidden" name="household_id" value="<?php echo e($household->id); ?>">
                <?php endif; ?>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold">Extended Family Name <span class="text-danger">*</span></label>
                        <input type="text" name="sub_family_name" class="form-control" placeholder="e.g., Reyes Extended Family" required value="<?php echo e(old('sub_family_name')); ?>">
                        <small class="text-muted">A descriptive name for this extended family group</small>
                    </div>

                    <div class="col-12"><hr><h6 class="text-primary">Co-Head Information</h6></div>

                    <div class="col-md-4">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="head[first_name]" class="form-control" required value="<?php echo e(old('head.first_name')); ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="head[middle_name]" class="form-control" value="<?php echo e(old('head.middle_name')); ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="head[last_name]" class="form-control" required value="<?php echo e(old('head.last_name')); ?>">
                    </div>

                    <div class="col-md-1">
                        <label class="form-label">Suffix</label>
                        <input type="text" name="head[suffix]" class="form-control" placeholder="Jr." value="<?php echo e(old('head.suffix')); ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Birthdate <span class="text-danger">*</span></label>
                        <input type="date" name="head[birthdate]" id="birthdate" class="form-control" required value="<?php echo e(old('head.birthdate')); ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Sex <span class="text-danger">*</span></label>
                        <select name="head[sex]" class="form-select" required>
                            <option value="">Select</option>
                            <option value="male" <?php echo e(old('head.sex') == 'male' ? 'selected' : ''); ?>>Male</option>
                            <option value="female" <?php echo e(old('head.sex') == 'female' ? 'selected' : ''); ?>>Female</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Civil Status <span class="text-danger">*</span></label>
                        <select name="head[civil_status]" class="form-select" required>
                            <option value="">Select</option>
                            <option value="single" <?php echo e(old('head.civil_status') == 'single' ? 'selected' : ''); ?>>Single</option>
                            <option value="married" <?php echo e(old('head.civil_status') == 'married' ? 'selected' : ''); ?>>Married</option>
                            <option value="widowed" <?php echo e(old('head.civil_status') == 'widowed' ? 'selected' : ''); ?>>Widowed</option>
                            <option value="separated" <?php echo e(old('head.civil_status') == 'separated' ? 'selected' : ''); ?>>Separated</option>
                            <option value="divorced" <?php echo e(old('head.civil_status') == 'divorced' ? 'selected' : ''); ?>>Divorced</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Relationship to Primary Head <span class="text-danger">*</span></label>
                        <select name="head[household_role]" class="form-select" required>
                            <option value="">Select</option>
                            <option value="spouse" <?php echo e(old('head.household_role') == 'spouse' ? 'selected' : ''); ?>>Spouse</option>
                            <option value="child" <?php echo e(old('head.household_role') == 'child' ? 'selected' : ''); ?>>Child</option>
                            <option value="parent" <?php echo e(old('head.household_role') == 'parent' ? 'selected' : ''); ?>>Parent</option>
                            <option value="sibling" <?php echo e(old('head.household_role') == 'sibling' ? 'selected' : ''); ?>>Sibling</option>
                            <option value="relative" <?php echo e(old('head.household_role') == 'relative' ? 'selected' : ''); ?>>Relative</option>
                            <option value="other" <?php echo e(old('head.household_role') == 'other' ? 'selected' : ''); ?>>Other</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="head[contact_number]" class="form-control" value="<?php echo e(old('head.contact_number')); ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="head[email]" class="form-control" value="<?php echo e(old('head.email')); ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Occupation</label>
                        <input type="text" name="head[occupation]" class="form-control" value="<?php echo e(old('head.occupation')); ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Employment Status</label>
                        <select name="head[employment_status]" class="form-select">
                            <option value="">Select</option>
                            <option value="employed" <?php echo e(old('head.employment_status') == 'employed' ? 'selected' : ''); ?>>Employed</option>
                            <option value="unemployed" <?php echo e(old('head.employment_status') == 'unemployed' ? 'selected' : ''); ?>>Unemployed</option>
                            <option value="self-employed" <?php echo e(old('head.employment_status') == 'self-employed' ? 'selected' : ''); ?>>Self-Employed</option>
                            <option value="student" <?php echo e(old('head.employment_status') == 'student' ? 'selected' : ''); ?>>Student</option>
                            <option value="retired" <?php echo e(old('head.employment_status') == 'retired' ? 'selected' : ''); ?>>Retired</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Monthly Income</label>
                        <input type="number" name="head[monthly_income]" class="form-control" step="0.01" min="0" value="<?php echo e(old('head.monthly_income')); ?>">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Add Extended Family
                    </button>
                    <?php if(isset($household)): ?>
                    <a href="<?php echo e(route('households.show', $household)); ?>" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <?php else: ?>
                    <a href="<?php echo e(route('households.index')); ?>" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/sub-families/create.blade.php ENDPATH**/ ?>
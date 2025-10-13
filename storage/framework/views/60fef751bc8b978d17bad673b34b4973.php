<?php $__env->startSection('title', 'Register Household'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <h2><i class="bi bi-house-add"></i> Register New Household</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('households.index')); ?>">Households</a></li>
            <li class="breadcrumb-item active">Register</li>
        </ol>
    </nav>
</div>

<form action="<?php echo e(route('households.store')); ?>" method="POST" id="householdForm">
    <?php echo csrf_field(); ?>
    
    <!-- Household Information -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-house"></i> Household Information</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="household_type" class="form-label">Household Type <span class="text-danger">*</span></label>
                    <select class="form-select" id="household_type" name="household_type" required>
                        <option value="">Select Type</option>
                        <option value="solo" <?php echo e(old('household_type') == 'solo' ? 'selected' : ''); ?>>Solo (Living Alone)</option>
                        <option value="family" <?php echo e(old('household_type') == 'family' ? 'selected' : ''); ?>>Family</option>
                        <option value="extended" <?php echo e(old('household_type') == 'extended' ? 'selected' : ''); ?>>Extended Family</option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label for="total_members" class="form-label">Total Members <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="total_members" name="total_members" 
                           value="<?php echo e(old('total_members', 1)); ?>" min="1" max="20" required>
                    <small class="text-muted">Including household head</small>
                </div>
                
                <div class="col-md-4">
                    <label for="purok" class="form-label">Purok</label>
                    <select class="form-select" id="purok" name="purok">
                        <option value="">Select Purok</option>
                        <?php $__currentLoopData = $puroks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purok): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($purok); ?>" <?php echo e(old('purok') == $purok ? 'selected' : ''); ?>>
                                <?php echo e($purok); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                
                <div class="col-md-12">
                    <label for="address" class="form-label">Complete Address <span class="text-danger">*</span></label>
                    <select class="form-select" id="address" name="address" required>
                        <option value="">Select Address</option>
                        <?php $__currentLoopData = $addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($addr); ?>" <?php echo e(old('address') == $addr ? 'selected' : ''); ?>>
                                <?php echo e($addr); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <option value="__new__">+ Add New Address</option>
                    </select>
                    <input type="text" class="form-control mt-2 d-none" id="new_address" 
                           placeholder="Enter new address (House No., Street Name)">
                    <small class="text-muted">Select from existing addresses or add new</small>
                </div>
                
                <div class="col-md-4">
                    <label for="housing_type" class="form-label">Housing Type <span class="text-danger">*</span></label>
                    <select class="form-select" id="housing_type" name="housing_type" required>
                        <option value="owned" <?php echo e(old('housing_type') == 'owned' ? 'selected' : ''); ?>>Owned</option>
                        <option value="rented" <?php echo e(old('housing_type') == 'rented' ? 'selected' : ''); ?>>Rented</option>
                        <option value="rent-free" <?php echo e(old('housing_type') == 'rent-free' ? 'selected' : ''); ?>>Rent-Free</option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Electricity</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="has_electricity" name="has_electricity" 
                               value="1" <?php echo e(old('has_electricity', true) ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="has_electricity">
                            Has Electricity Connection
                        </label>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <label for="electric_account_number" class="form-label">Electric Account Number</label>
                    <input type="text" class="form-control" id="electric_account_number" 
                           name="electric_account_number" value="<?php echo e(old('electric_account_number')); ?>">
                </div>
                
                <div class="col-md-12">
                    <label for="parent_household_id" class="form-label">Parent Household (For New Family Head)</label>
                    <select class="form-select" id="parent_household_id" name="parent_household_id">
                        <option value="">None - Independent Household</option>
                        <?php $__currentLoopData = $households; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $household): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($household->id); ?>" <?php echo e(old('parent_household_id') == $household->id ? 'selected' : ''); ?>>
                                <?php echo e($household->household_id); ?> - <?php echo e($household->head ? $household->head->full_name : 'N/A'); ?> (<?php echo e($household->full_address); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <small class="text-muted">Select if this is a married child still living in parent's household</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Household Head Information -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-person-badge"></i> Household Head Information</h5>
        </div>
        <div class="card-body">
            <?php echo $__env->make('households.partials.resident-form', ['prefix' => 'head', 'isHead' => true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
    
    <!-- Members Forms Container -->
    <div id="membersContainer"></div>
    
    <!-- Submit Button -->
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <a href="<?php echo e(route('households.index')); ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-save"></i> Register Household
                </button>
            </div>
        </div>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    let memberCount = 0;
    
    // Update member forms when total_members changes
    $('#total_members').on('change', function() {
        updateMemberForms();
    });
    
    // Update on household type change
    $('#household_type').on('change', function() {
        if ($(this).val() === 'solo') {
            $('#total_members').val(1).prop('readonly', true);
            updateMemberForms();
        } else {
            $('#total_members').prop('readonly', false);
        }
    });
    
    function updateMemberForms() {
        const totalMembers = parseInt($('#total_members').val()) || 1;
        const membersNeeded = totalMembers - 1; // Subtract household head
        
        const container = $('#membersContainer');
        
        // Remove excess member forms
        if (memberCount > membersNeeded) {
            for (let i = memberCount; i > membersNeeded; i--) {
                $(`#member-${i}`).remove();
            }
        }
        
        // Add new member forms
        if (memberCount < membersNeeded) {
            for (let i = memberCount + 1; i <= membersNeeded; i++) {
                addMemberForm(i);
            }
        }
        
        memberCount = membersNeeded;
    }
    
    function addMemberForm(index) {
        const formHtml = `
            <div class="card mb-4" id="member-${index}">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-person"></i> Member ${index} Information</h5>
                </div>
                <div class="card-body">
                    ${generateMemberFormFields(index)}
                </div>
            </div>
        `;
        
        $('#membersContainer').append(formHtml);
    }
    
    function generateMemberFormFields(index) {
        return `
            <div class="row g-3">
                <!-- Personal Information -->
                <div class="col-md-3">
                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="members[${index-1}][first_name]" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Middle Name</label>
                    <input type="text" class="form-control" name="members[${index-1}][middle_name]">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="members[${index-1}][last_name]" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Suffix</label>
                    <input type="text" class="form-control" name="members[${index-1}][suffix]" placeholder="Jr., Sr., III">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Birthdate <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="members[${index-1}][birthdate]" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sex <span class="text-danger">*</span></label>
                    <select class="form-select" name="members[${index-1}][sex]" required>
                        <option value="">Select</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Civil Status <span class="text-danger">*</span></label>
                    <select class="form-select" name="members[${index-1}][civil_status]" required>
                        <option value="single">Single</option>
                        <option value="married">Married</option>
                        <option value="widowed">Widowed</option>
                        <option value="separated">Separated</option>
                        <option value="divorced">Divorced</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Household Role <span class="text-danger">*</span></label>
                    <select class="form-select" name="members[${index-1}][household_role]" required>
                        <option value="spouse">Spouse</option>
                        <option value="child">Child</option>
                        <option value="parent">Parent</option>
                        <option value="sibling">Sibling</option>
                        <option value="relative">Relative</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <!-- Contact -->
                <div class="col-md-6">
                    <label class="form-label">Contact Number</label>
                    <input type="text" class="form-control" name="members[${index-1}][contact_number]">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="members[${index-1}][email]">
                </div>
                
                <!-- Special Categories -->
                <div class="col-md-12">
                    <label class="form-label fw-bold">Special Categories</label>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="members[${index-1}][is_pwd]" value="1" id="member_${index}_pwd">
                                <label class="form-check-label" for="member_${index}_pwd">PWD</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="members[${index-1}][is_voter]" value="1" id="member_${index}_voter">
                                <label class="form-check-label" for="member_${index}_voter">Voter</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="members[${index-1}][is_4ps_beneficiary]" value="1" id="member_${index}_4ps">
                                <label class="form-check-label" for="member_${index}_4ps">4Ps Beneficiary</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Employment -->
                <div class="col-md-4">
                    <label class="form-label">Occupation</label>
                    <input type="text" class="form-control" name="members[${index-1}][occupation]">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Employment Status</label>
                    <select class="form-select" name="members[${index-1}][employment_status]">
                        <option value="">Select</option>
                        <option value="employed">Employed</option>
                        <option value="unemployed">Unemployed</option>
                        <option value="self-employed">Self-Employed</option>
                        <option value="student">Student</option>
                        <option value="retired">Retired</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Monthly Income</label>
                    <input type="number" class="form-control" name="members[${index-1}][monthly_income]" step="0.01" min="0">
                </div>
            </div>
        `;
    }
    
    // Initialize on page load
    updateMemberForms();
    
    // Handle "Add New Address" option
    document.getElementById('address').addEventListener('change', function() {
        const newAddressInput = document.getElementById('new_address');
        if (this.value === '__new__') {
            newAddressInput.classList.remove('d-none');
            newAddressInput.required = true;
            newAddressInput.focus();
        } else {
            newAddressInput.classList.add('d-none');
            newAddressInput.required = false;
            newAddressInput.value = '';
        }
    });
    
    // Before form submit, use new address if provided
    document.getElementById('householdForm').addEventListener('submit', function(e) {
        const addressSelect = document.getElementById('address');
        const newAddressInput = document.getElementById('new_address');
        
        if (addressSelect.value === '__new__' && newAddressInput.value.trim()) {
            // Create a hidden input with the new address
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'address';
            hiddenInput.value = newAddressInput.value.trim();
            this.appendChild(hiddenInput);
            
            // Remove name from select to avoid conflict
            addressSelect.removeAttribute('name');
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/households/create.blade.php ENDPATH**/ ?>
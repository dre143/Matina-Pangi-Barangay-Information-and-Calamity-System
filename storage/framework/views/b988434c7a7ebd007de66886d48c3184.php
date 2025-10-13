<?php $__env->startSection('title', 'Add Member to Household'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2><i class="bi bi-person-plus"></i> Add Member to Household <?php echo e($household->household_id); ?></h2>
            <p class="text-muted">Add a new resident to this household</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Household Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Household ID:</strong> <?php echo e($household->household_id); ?></p>
                    <p><strong>Address:</strong> <?php echo e($household->address); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Current Members:</strong> <?php echo e($household->total_members); ?></p>
                    <p><strong>Head:</strong> <?php echo e($household->officialHead ? $household->officialHead->full_name : 'N/A'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-person-plus"></i> New Member Information</h5>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('households.store-member', $household)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> <strong>Important:</strong> Select which head this member belongs to. This determines their family group within the household.
                </div>
                
                <div class="row g-3">
                    <!-- Head Selection -->
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <label class="form-label fw-bold">Select Head <span class="text-danger">*</span></label>
                                <p class="text-muted small mb-3">Choose whether this member belongs to the Primary Head's family or a Co-Head's family</p>
                                
                                <select name="sub_family_id" class="form-select form-select-lg" required>
                                    <option value="">-- Select Head --</option>
                                    
                                    <?php
                                        $primaryFamily = $household->subFamilies()->where('is_primary_family', true)->first();
                                        $extendedFamilies = $household->subFamilies()->where('is_primary_family', false)->get();
                                    ?>
                                    
                                    <?php if($primaryFamily && $primaryFamily->subHead): ?>
                                        <option value="<?php echo e($primaryFamily->id); ?>" class="fw-bold" <?php echo e(old('sub_family_id') == $primaryFamily->id ? 'selected' : ''); ?>>
                                            ⭐ PRIMARY HEAD: <?php echo e($primaryFamily->subHead->full_name); ?>

                                        </option>
                                    <?php endif; ?>
                                    
                                    <?php $__currentLoopData = $extendedFamilies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $family): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($family->subHead): ?>
                                            <option value="<?php echo e($family->id); ?>" <?php echo e(old('sub_family_id') == $family->id ? 'selected' : ''); ?>>
                                                👤 CO-HEAD: <?php echo e($family->subHead->full_name); ?> (<?php echo e($family->sub_family_name); ?>)
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12"><hr></div>
                    
                    <div class="col-md-4">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control" required value="<?php echo e(old('first_name')); ?>">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control" value="<?php echo e(old('middle_name')); ?>">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control" required value="<?php echo e(old('last_name')); ?>">
                    </div>
                    
                    <div class="col-md-1">
                        <label class="form-label">Suffix</label>
                        <input type="text" name="suffix" class="form-control" placeholder="Jr., Sr." value="<?php echo e(old('suffix')); ?>">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Birthdate <span class="text-danger">*</span></label>
                        <input type="date" name="birthdate" id="birthdate" class="form-control" required value="<?php echo e(old('birthdate')); ?>">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Sex <span class="text-danger">*</span></label>
                        <select name="sex" class="form-select" required>
                            <option value="">Select</option>
                            <option value="male" <?php echo e(old('sex') == 'male' ? 'selected' : ''); ?>>Male</option>
                            <option value="female" <?php echo e(old('sex') == 'female' ? 'selected' : ''); ?>>Female</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Civil Status <span class="text-danger">*</span></label>
                        <select name="civil_status" class="form-select" required>
                            <option value="">Select</option>
                            <option value="single" <?php echo e(old('civil_status') == 'single' ? 'selected' : ''); ?>>Single</option>
                            <option value="married" <?php echo e(old('civil_status') == 'married' ? 'selected' : ''); ?>>Married</option>
                            <option value="widowed" <?php echo e(old('civil_status') == 'widowed' ? 'selected' : ''); ?>>Widowed</option>
                            <option value="separated" <?php echo e(old('civil_status') == 'separated' ? 'selected' : ''); ?>>Separated</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Relationship to Head <span class="text-danger">*</span></label>
                        <select name="household_role" class="form-select" required>
                            <option value="">Select</option>
                            <option value="spouse" <?php echo e(old('household_role') == 'spouse' ? 'selected' : ''); ?>>Spouse</option>
                            <option value="child" <?php echo e(old('household_role') == 'child' ? 'selected' : ''); ?>>Child</option>
                            <option value="parent" <?php echo e(old('household_role') == 'parent' ? 'selected' : ''); ?>>Parent</option>
                            <option value="sibling" <?php echo e(old('household_role') == 'sibling' ? 'selected' : ''); ?>>Sibling</option>
                            <option value="relative" <?php echo e(old('household_role') == 'relative' ? 'selected' : ''); ?>>Relative</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control" value="<?php echo e(old('contact_number')); ?>">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>">
                    </div>
                    
                    <!-- Additional Personal Info -->
                    <div class="col-md-4">
                        <label class="form-label">Place of Birth</label>
                        <input type="text" name="place_of_birth" class="form-control" value="<?php echo e(old('place_of_birth')); ?>">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Nationality</label>
                        <input type="text" name="nationality" class="form-control" value="<?php echo e(old('nationality', 'Filipino')); ?>">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Religion</label>
                        <input type="text" name="religion" class="form-control" value="<?php echo e(old('religion')); ?>">
                    </div>
                    
                    <div class="col-12"><hr><h6 class="text-primary"><i class="bi bi-briefcase"></i> Employment & Education</h6></div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Occupation</label>
                        <input type="text" name="occupation" class="form-control" value="<?php echo e(old('occupation')); ?>">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Employment Status</label>
                        <select name="employment_status" class="form-select">
                            <option value="">Select</option>
                            <option value="employed" <?php echo e(old('employment_status') == 'employed' ? 'selected' : ''); ?>>Employed</option>
                            <option value="unemployed" <?php echo e(old('employment_status') == 'unemployed' ? 'selected' : ''); ?>>Unemployed</option>
                            <option value="self-employed" <?php echo e(old('employment_status') == 'self-employed' ? 'selected' : ''); ?>>Self-Employed</option>
                            <option value="student" <?php echo e(old('employment_status') == 'student' ? 'selected' : ''); ?>>Student</option>
                            <option value="retired" <?php echo e(old('employment_status') == 'retired' ? 'selected' : ''); ?>>Retired</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Monthly Income</label>
                        <input type="number" name="monthly_income" class="form-control" step="0.01" min="0" value="<?php echo e(old('monthly_income')); ?>">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Employer Name</label>
                        <input type="text" name="employer_name" class="form-control" value="<?php echo e(old('employer_name')); ?>">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Educational Attainment</label>
                        <select name="educational_attainment" class="form-select">
                            <option value="">Select</option>
                            <option value="No Formal Education" <?php echo e(old('educational_attainment') == 'No Formal Education' ? 'selected' : ''); ?>>No Formal Education</option>
                            <option value="Elementary Undergraduate" <?php echo e(old('educational_attainment') == 'Elementary Undergraduate' ? 'selected' : ''); ?>>Elementary Undergraduate</option>
                            <option value="Elementary Graduate" <?php echo e(old('educational_attainment') == 'Elementary Graduate' ? 'selected' : ''); ?>>Elementary Graduate</option>
                            <option value="High School Undergraduate" <?php echo e(old('educational_attainment') == 'High School Undergraduate' ? 'selected' : ''); ?>>High School Undergraduate</option>
                            <option value="High School Graduate" <?php echo e(old('educational_attainment') == 'High School Graduate' ? 'selected' : ''); ?>>High School Graduate</option>
                            <option value="College Undergraduate" <?php echo e(old('educational_attainment') == 'College Undergraduate' ? 'selected' : ''); ?>>College Undergraduate</option>
                            <option value="College Graduate" <?php echo e(old('educational_attainment') == 'College Graduate' ? 'selected' : ''); ?>>College Graduate</option>
                            <option value="Vocational" <?php echo e(old('educational_attainment') == 'Vocational' ? 'selected' : ''); ?>>Vocational</option>
                            <option value="Post Graduate" <?php echo e(old('educational_attainment') == 'Post Graduate' ? 'selected' : ''); ?>>Post Graduate</option>
                        </select>
                    </div>
                    
                    <div class="col-md-12">
                        <label class="form-label">School Name (if student)</label>
                        <input type="text" name="school_name" class="form-control" value="<?php echo e(old('school_name')); ?>">
                    </div>
                    
                    <div class="col-12"><hr><h6 class="text-success"><i class="bi bi-heart-pulse"></i> Health Information</h6></div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Blood Type</label>
                        <select name="blood_type" class="form-select">
                            <option value="">Select</option>
                            <option value="A+" <?php echo e(old('blood_type') == 'A+' ? 'selected' : ''); ?>>A+</option>
                            <option value="A-" <?php echo e(old('blood_type') == 'A-' ? 'selected' : ''); ?>>A-</option>
                            <option value="B+" <?php echo e(old('blood_type') == 'B+' ? 'selected' : ''); ?>>B+</option>
                            <option value="B-" <?php echo e(old('blood_type') == 'B-' ? 'selected' : ''); ?>>B-</option>
                            <option value="AB+" <?php echo e(old('blood_type') == 'AB+' ? 'selected' : ''); ?>>AB+</option>
                            <option value="AB-" <?php echo e(old('blood_type') == 'AB-' ? 'selected' : ''); ?>>AB-</option>
                            <option value="O+" <?php echo e(old('blood_type') == 'O+' ? 'selected' : ''); ?>>O+</option>
                            <option value="O-" <?php echo e(old('blood_type') == 'O-' ? 'selected' : ''); ?>>O-</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Medical Conditions</label>
                        <textarea name="medical_conditions" class="form-control" rows="2"><?php echo e(old('medical_conditions')); ?></textarea>
                    </div>
                    
                    <div class="col-12"><hr><h6 class="text-warning"><i class="bi bi-star-fill"></i> Special Categories & IDs</h6></div>
                    
                    <div class="col-md-4">
                        <div class="form-check mb-2">
                            <input type="checkbox" name="is_pwd" class="form-check-input" id="is_pwd" value="1" <?php echo e(old('is_pwd') ? 'checked' : ''); ?>>
                            <label class="form-check-label fw-bold" for="is_pwd">Person with Disability (PWD)</label>
                        </div>
                        <input type="text" name="pwd_id" id="pwd_id_input" class="form-control" placeholder="PWD ID Number" value="<?php echo e(old('pwd_id')); ?>" disabled>
                        <input type="text" name="disability_type" id="disability_type_input" class="form-control mt-2" placeholder="Type of Disability" value="<?php echo e(old('disability_type')); ?>" disabled>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-check mb-2">
                            <input type="checkbox" name="is_4ps_beneficiary" class="form-check-input" id="is_4ps" value="1" <?php echo e(old('is_4ps_beneficiary') ? 'checked' : ''); ?>>
                            <label class="form-check-label fw-bold" for="is_4ps">4Ps Beneficiary</label>
                        </div>
                        <input type="text" name="4ps_id" id="4ps_id_input" class="form-control" placeholder="4Ps ID Number" value="<?php echo e(old('4ps_id')); ?>" disabled>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Voting Status</label>
                        <select name="is_voter" id="voting_status_select" class="form-select mb-2" disabled>
                            <option value="">-- Select Age First --</option>
                        </select>
                        <input type="text" name="precinct_number" id="precinct_input" class="form-control" placeholder="Precinct Number (if registered)" value="<?php echo e(old('precinct_number')); ?>" disabled>
                        <small class="text-muted" id="voting_hint">Determined by age</small>
                    </div>
                    
                    <div class="col-12"><hr><h6 class="text-info"><i class="bi bi-chat-square-text"></i> Additional Information</h6></div>
                    
                    <div class="col-md-12">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="2"><?php echo e(old('remarks')); ?></textarea>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Add Member
                    </button>
                    <a href="<?php echo e(route('households.show', $household)); ?>" class="btn btn-secondary">
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
    const birthdateInput = document.getElementById('birthdate');
    const votingStatusInput = document.getElementById('voting_status');
    const isVoterHidden = document.getElementById('is_voter_hidden');
    const precinctInput = document.getElementById('precinct_input');
    
    // PWD checkbox and inputs
    const pwdCheckbox = document.getElementById('is_pwd');
    const pwdIdInput = document.getElementById('pwd_id_input');
    const disabilityTypeInput = document.getElementById('disability_type_input');
    
    // 4Ps checkbox and input
    const fourPsCheckbox = document.getElementById('is_4ps');
    const fourPsIdInput = document.getElementById('4ps_id_input');
    
    function calculateVotingStatus() {
        const birthdate = birthdateInput.value;
        const votingSelect = document.getElementById('voting_status_select');
        const votingHint = document.getElementById('voting_hint');
        
        if (!birthdate) {
            votingSelect.innerHTML = '<option value="">-- Select Age First --</option>';
            votingSelect.disabled = true;
            precinctInput.disabled = true;
            votingHint.textContent = 'Determined by age';
            votingHint.className = 'text-muted';
            return;
        }
        
        // Calculate age
        const today = new Date();
        const birth = new Date(birthdate);
        let age = today.getFullYear() - birth.getFullYear();
        const monthDiff = today.getMonth() - birth.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
            age--;
        }
        
        // Determine voting status based on age
        if (age < 15) {
            // Below 15: Not eligible (disabled, auto-set to 0)
            votingSelect.innerHTML = '<option value="0" selected>Not Eligible (Below 15 years old)</option>';
            votingSelect.disabled = true;
            votingSelect.className = 'form-select mb-2 text-muted';
            precinctInput.disabled = true;
            precinctInput.value = '';
            votingHint.textContent = 'Not eligible to vote';
            votingHint.className = 'text-muted';
        } else if (age >= 15 && age <= 17) {
            // 15-17: SK Voter options
            votingSelect.innerHTML = `
                <option value="">-- Select Status --</option>
                <option value="1">Registered SK Voter</option>
                <option value="0">Not Registered</option>
            `;
            votingSelect.disabled = false;
            votingSelect.className = 'form-select mb-2 text-info fw-bold';
            votingHint.textContent = 'SK Voter eligibility (15-17 years old)';
            votingHint.className = 'text-info fw-bold';
        } else if (age >= 18) {
            // 18+: Regular Voter options
            votingSelect.innerHTML = `
                <option value="">-- Select Status --</option>
                <option value="1">Registered Voter</option>
                <option value="0">Not Registered</option>
            `;
            votingSelect.disabled = false;
            votingSelect.className = 'form-select mb-2 text-success fw-bold';
            votingHint.textContent = 'Regular Voter eligibility (18+ years old)';
            votingHint.className = 'text-success fw-bold';
        }
    }
    
    // Handle voting status selection change
    document.addEventListener('change', function(e) {
        if (e.target.id === 'voting_status_select') {
            const votingSelect = document.getElementById('voting_status_select');
            if (votingSelect.value === '1') {
                // Registered - enable precinct input
                precinctInput.disabled = false;
            } else {
                // Not registered - disable and clear precinct
                precinctInput.disabled = true;
                precinctInput.value = '';
            }
        }
    });
    
    // PWD checkbox handler
    pwdCheckbox.addEventListener('change', function() {
        if (this.checked) {
            pwdIdInput.disabled = false;
            disabilityTypeInput.disabled = false;
        } else {
            pwdIdInput.disabled = true;
            disabilityTypeInput.disabled = true;
            pwdIdInput.value = '';
            disabilityTypeInput.value = '';
        }
    });
    
    // 4Ps checkbox handler
    fourPsCheckbox.addEventListener('change', function() {
        if (this.checked) {
            fourPsIdInput.disabled = false;
        } else {
            fourPsIdInput.disabled = true;
            fourPsIdInput.value = '';
        }
    });
    
    // Calculate on page load if birthdate exists
    if (birthdateInput.value) {
        calculateVotingStatus();
    }
    
    // Calculate when birthdate changes
    birthdateInput.addEventListener('change', calculateVotingStatus);
    birthdateInput.addEventListener('blur', calculateVotingStatus);
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/households/add-member.blade.php ENDPATH**/ ?>
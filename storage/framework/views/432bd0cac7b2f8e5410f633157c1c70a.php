<?php $__env->startSection('title', 'Household Details - ' . $household->household_id); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('households.index')); ?>">Households</a></li>
            <li class="breadcrumb-item active"><?php echo e($household->household_id); ?></li>
        </ol>
    </nav>
</div>

<!-- Header with Actions -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-house-door-fill"></i> Household <?php echo e($household->household_id); ?></h2>
    <div class="btn-group">
        <a href="<?php echo e(route('household-events.by-household', $household)); ?>" class="btn btn-info">
            <i class="bi bi-calendar-event"></i> View Events
        </a>
        <a href="<?php echo e(route('sub-families.create', ['household_id' => $household->id])); ?>" class="btn btn-primary">
            <i class="bi bi-people-fill"></i> Add Extended Family
        </a>
        <?php if(auth()->user()->isSecretary() || auth()->user()->isStaff()): ?>
            <a href="<?php echo e(route('households.add-member', $household)); ?>" class="btn btn-success">
                <i class="bi bi-person-plus-fill"></i> Add Member
            </a>
        <?php endif; ?>
        <?php if(auth()->user()->isSecretary()): ?>
            <a href="<?php echo e(route('households.edit', $household)); ?>" class="btn btn-warning">
                <i class="bi bi-pencil-fill"></i> Edit
            </a>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#archiveModal">
                <i class="bi bi-archive-fill"></i> Archive
            </button>
        <?php endif; ?>
        <a href="<?php echo e(route('households.index')); ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<!-- Household Overview Card -->
<div class="card mb-4 border-primary">
    <div class="card-header bg-primary text-white">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="mb-0">
                    <i class="bi bi-house-fill"></i> 
                    <?php echo e($household->household_id); ?>

                    <?php if($household->officialHead): ?>
                        - <?php echo e($household->officialHead->full_name); ?>

                        <span class="badge bg-warning text-dark ms-2">
                            <i class="bi bi-star-fill"></i> PRIMARY HEAD
                        </span>
                    <?php endif; ?>
                </h4>
            </div>
            <div class="col-md-4 text-end">
                <span class="badge bg-light text-dark fs-6">
                    <i class="bi bi-people-fill"></i> <?php echo e($statistics['total_residents']); ?> Members
                </span>
                <span class="badge bg-light text-dark fs-6 ms-2">
                    <i class="bi bi-diagram-3-fill"></i> <?php echo e($statistics['total_families']); ?> Families
                </span>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <!-- Address Information -->
            <div class="col-md-6">
                <h6 class="text-muted mb-2"><i class="bi bi-geo-alt-fill"></i> Address</h6>
                <p class="mb-0 fw-bold"><?php echo e($household->address); ?></p>
                <?php if($household->purok): ?>
                    <p class="mb-0 text-muted"><?php echo e($household->purok->purok_name ?? $household->purok); ?></p>
                <?php endif; ?>
                <p class="mb-0 text-muted">Barangay Matina Pangi, Davao City</p>
            </div>

            <!-- Housing Details -->
            <div class="col-md-3">
                <h6 class="text-muted mb-2"><i class="bi bi-house-fill"></i> Housing</h6>
                <p class="mb-1">
                    <strong>Type:</strong> 
                    <span class="badge bg-<?php echo e($household->housing_type === 'owned' ? 'success' : ($household->housing_type === 'rented' ? 'warning' : 'info')); ?>">
                        <?php echo e(ucfirst($household->housing_type)); ?>

                    </span>
                </p>
                <p class="mb-0">
                    <strong>Electricity:</strong> 
                    <?php if($household->has_electricity): ?>
                        <span class="text-success"><i class="bi bi-check-circle-fill"></i> Yes</span>
                        <?php if($household->electric_account_number): ?>
                            <br><small class="text-muted">Acct: <?php echo e($household->electric_account_number); ?></small>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="text-danger"><i class="bi bi-x-circle-fill"></i> No</span>
                    <?php endif; ?>
                </p>
            </div>

            <!-- Status -->
            <div class="col-md-3">
                <h6 class="text-muted mb-2"><i class="bi bi-info-circle-fill"></i> Status</h6>
                <p class="mb-1">
                    <span class="badge bg-<?php echo e($household->approval_badge_color); ?>">
                        <?php echo e(ucfirst($household->approval_status)); ?>

                    </span>
                </p>
                <?php if($household->approved_at): ?>
                    <small class="text-muted">
                        Approved: <?php echo e($household->approved_at->format('M d, Y')); ?>

                    </small>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Household Statistics -->
<div class="row g-3 mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-bar-chart-fill"></i> Household Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center g-3">
                    <div class="col-md-2">
                        <div class="p-3 bg-primary bg-opacity-10 rounded">
                            <h3 class="mb-0 text-primary"><?php echo e($statistics['total_residents']); ?></h3>
                            <small class="text-muted">Total Residents</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="p-3 bg-info bg-opacity-10 rounded">
                            <h3 class="mb-0 text-info"><?php echo e($statistics['seniors']); ?></h3>
                            <small class="text-muted">Senior Citizens</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="p-3 bg-warning bg-opacity-10 rounded">
                            <h3 class="mb-0 text-warning"><?php echo e($statistics['teens']); ?></h3>
                            <small class="text-muted">Teens (13-19)</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="p-3 bg-danger bg-opacity-10 rounded">
                            <h3 class="mb-0 text-danger"><?php echo e($statistics['pwd']); ?></h3>
                            <small class="text-muted">PWD</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="p-3 bg-success bg-opacity-10 rounded">
                            <h3 class="mb-0 text-success"><?php echo e($statistics['voters']); ?></h3>
                            <small class="text-muted">Voters</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="p-3 bg-secondary bg-opacity-10 rounded">
                            <h3 class="mb-0 text-secondary"><?php echo e($statistics['four_ps']); ?></h3>
                            <small class="text-muted">4Ps Beneficiaries</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PRIMARY FAMILY -->
<?php if($primaryFamily && $primaryMembers->count() > 0): ?>
<div class="card mb-4 border-warning">
    <div class="card-header bg-warning bg-opacity-10">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="mb-0">
                    <i class="bi bi-house-heart-fill text-warning"></i> 
                    PRIMARY FAMILY
                    <?php if($household->officialHead): ?>
                        <small class="text-muted">(<?php echo e($household->officialHead->full_name); ?>)</small>
                    <?php endif; ?>
                </h4>
            </div>
            <div class="col-md-4 text-end">
                <span class="badge bg-dark"><?php echo e($primaryStats['total']); ?> Members</span>
                <?php if($primaryStats['seniors'] > 0): ?>
                    <span class="badge bg-info"><?php echo e($primaryStats['seniors']); ?> Seniors</span>
                <?php endif; ?>
                <?php if($primaryStats['pwd'] > 0): ?>
                    <span class="badge bg-danger"><?php echo e($primaryStats['pwd']); ?> PWD</span>
                <?php endif; ?>
                <?php if($primaryStats['four_ps'] > 0): ?>
                    <span class="badge bg-primary"><?php echo e($primaryStats['four_ps']); ?> 4Ps</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Age/Sex</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Categories</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $primaryMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="<?php echo e($member->status !== 'active' ? 'table-secondary' : ''); ?>">
                        <td>
                            <strong><?php echo e($member->full_name); ?></strong>
                            <?php if($member->is_primary_head): ?>
                                <span class="badge bg-warning text-dark ms-2">
                                    <i class="bi bi-star-fill"></i> PRIMARY HEAD
                                </span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($member->age); ?> / <?php echo e(ucfirst($member->sex)); ?></td>
                        <td>
                            <span class="badge bg-secondary"><?php echo e(ucfirst($member->household_role)); ?></span>
                        </td>
                        <td>
                            <?php if($member->approval_status === 'pending'): ?>
                                <span class="badge bg-warning">
                                    <i class="bi bi-clock"></i> Pending Approval
                                </span>
                            <?php elseif($member->status === 'active'): ?>
                                <span class="badge bg-success">Active</span>
                            <?php elseif($member->status === 'reallocated'): ?>
                                <span class="badge bg-warning">Reallocated</span>
                            <?php else: ?>
                                <span class="badge bg-dark">Deceased</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($member->is_senior_citizen): ?>
                                <span class="badge bg-info">Senior</span>
                            <?php endif; ?>
                            <?php if($member->is_teen): ?>
                                <span class="badge bg-warning">Teen</span>
                            <?php endif; ?>
                            <?php if($member->is_pwd): ?>
                                <span class="badge bg-danger">PWD</span>
                            <?php endif; ?>
                            <?php if($member->is_voter): ?>
                                <span class="badge bg-success">Voter</span>
                            <?php endif; ?>
                            <?php if($member->is_4ps_beneficiary): ?>
                                <span class="badge bg-primary">4Ps</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo e(route('residents.show', $member)); ?>" class="btn btn-sm btn-info">
                                <i class="bi bi-eye-fill"></i> View
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- EXTENDED FAMILIES (CO-HEADS) -->
<?php if($extendedFamilies->count() > 0): ?>
    <?php $__currentLoopData = $extendedFamilies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $extendedFamily): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="card mb-4 border-primary">
        <div class="card-header bg-primary bg-opacity-10">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-0">
                        <i class="bi bi-people-fill text-primary"></i> 
                        EXTENDED FAMILY
                        <?php if($extendedFamily->subHead): ?>
                            <small class="text-muted">(<?php echo e($extendedFamily->sub_family_name); ?>)</small>
                            <span class="badge bg-primary ms-2">
                                <i class="bi bi-person-badge-fill"></i> CO-HEAD: <?php echo e($extendedFamily->subHead->full_name); ?>

                            </span>
                        <?php endif; ?>
                    </h4>
                </div>
                <div class="col-md-4 text-end">
                    <?php
                        $familyMembers = $extendedFamily->members;
                        $familyStats = [
                            'total' => $familyMembers->count(),
                            'seniors' => $familyMembers->where('is_senior_citizen', true)->count(),
                            'pwd' => $familyMembers->where('is_pwd', true)->count(),
                            'four_ps' => $familyMembers->where('is_4ps_beneficiary', true)->count(),
                        ];
                    ?>
                    <span class="badge bg-dark"><?php echo e($familyStats['total']); ?> Members</span>
                    <?php if($familyStats['seniors'] > 0): ?>
                        <span class="badge bg-info"><?php echo e($familyStats['seniors']); ?> Seniors</span>
                    <?php endif; ?>
                    <?php if($familyStats['pwd'] > 0): ?>
                        <span class="badge bg-danger"><?php echo e($familyStats['pwd']); ?> PWD</span>
                    <?php endif; ?>
                    <?php if($familyStats['four_ps'] > 0): ?>
                        <span class="badge bg-primary"><?php echo e($familyStats['four_ps']); ?> 4Ps</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Age/Sex</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Categories</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $familyMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="<?php echo e($member->status !== 'active' ? 'table-secondary' : ''); ?>">
                            <td>
                                <strong><?php echo e($member->full_name); ?></strong>
                                <?php if($member->is_co_head): ?>
                                    <span class="badge bg-primary ms-2">
                                        <i class="bi bi-person-badge-fill"></i> CO-HEAD
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($member->age); ?> / <?php echo e(ucfirst($member->sex)); ?></td>
                            <td>
                                <span class="badge bg-secondary"><?php echo e(ucfirst($member->household_role)); ?></span>
                            </td>
                            <td>
                                <?php if($member->status === 'active'): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php elseif($member->status === 'reallocated'): ?>
                                    <span class="badge bg-warning">Reallocated</span>
                                <?php else: ?>
                                    <span class="badge bg-dark">Deceased</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($member->is_senior_citizen): ?>
                                    <span class="badge bg-info">Senior</span>
                                <?php endif; ?>
                                <?php if($member->is_teen): ?>
                                    <span class="badge bg-warning">Teen</span>
                                <?php endif; ?>
                                <?php if($member->is_pwd): ?>
                                    <span class="badge bg-danger">PWD</span>
                                <?php endif; ?>
                                <?php if($member->is_voter): ?>
                                    <span class="badge bg-success">Voter</span>
                                <?php endif; ?>
                                <?php if($member->is_4ps_beneficiary): ?>
                                    <span class="badge bg-primary">4Ps</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('residents.show', $member)); ?>" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye-fill"></i> View
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>

<!-- Archive Modal -->
<?php if(auth()->user()->isSecretary()): ?>
<div class="modal fade" id="archiveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-archive-fill"></i> Archive Household</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to archive this household?</p>
                <p class="text-muted">This will archive all members and can be restored later.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="<?php echo e(route('households.archive', $household)); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-archive-fill"></i> Archive
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/households/show.blade.php ENDPATH**/ ?>
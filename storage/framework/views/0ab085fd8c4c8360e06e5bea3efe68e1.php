<?php $__env->startSection('title', 'Residents'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people"></i> Residents</h2>
    <div class="alert alert-info mb-0 py-2 px-3">
        <i class="bi bi-info-circle"></i> To add residents, go to <strong>Households</strong> → Select household → <strong>Add Member</strong>
    </div>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="<?php echo e(route('residents.index')); ?>" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" class="form-control" name="search" 
                       placeholder="Search by name or ID..." 
                       value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <select class="form-select" name="category">
                    <option value="">All Categories</option>
                    <option value="pwd" <?php echo e(request('category') == 'pwd' ? 'selected' : ''); ?>>PWD</option>
                    <option value="senior" <?php echo e(request('category') == 'senior' ? 'selected' : ''); ?>>Senior Citizens</option>
                    <option value="teen" <?php echo e(request('category') == 'teen' ? 'selected' : ''); ?>>Teens</option>
                    <option value="voter" <?php echo e(request('category') == 'voter' ? 'selected' : ''); ?>>Voters</option>
                    <option value="4ps" <?php echo e(request('category') == '4ps' ? 'selected' : ''); ?>>4Ps Beneficiaries</option>
                    <option value="head" <?php echo e(request('category') == 'head' ? 'selected' : ''); ?>>Household Heads</option>
                </select>
            </div>
            <div class="col-md-5">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Search
                </button>
                <a href="<?php echo e(route('residents.index')); ?>" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Clear
                </a>
                <?php if(auth()->user()->isSecretary()): ?>
                    <div class="btn-group">
                        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-download"></i> Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo e(route('residents.export.pdf')); ?>">
                                <i class="bi bi-file-pdf"></i> Export to PDF
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('residents.export.excel')); ?>">
                                <i class="bi bi-file-excel"></i> Export to Excel
                            </a></li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Residents Table -->
<div class="card">
    <div class="card-body">
        <?php if($residents->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Resident ID</th>
                            <th>Name</th>
                            <th>Age/Sex</th>
                            <th>Household</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Categories</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $residents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($resident->resident_id); ?></strong></td>
                            <td>
                                <a href="<?php echo e(route('residents.show', $resident)); ?>">
                                    <?php echo e($resident->full_name); ?>

                                </a>
                                <?php if($resident->is_household_head): ?>
                                    <br><span class="badge bg-success">Household Head</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo e($resident->age); ?> / <?php echo e(ucfirst($resident->sex)); ?>

                                <br><small class="text-muted"><?php echo e($resident->age_category); ?></small>
                            </td>
                            <td>
                                <a href="<?php echo e(route('households.show', $resident->household)); ?>">
                                    <?php echo e($resident->household->household_id); ?>

                                </a>
                            </td>
                            <td>
                                <small><?php echo e(Str::limit($resident->household->full_address, 30)); ?></small>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo e($resident->status_badge_color); ?>">
                                    <?php echo e(ucfirst($resident->status)); ?>

                                </span>
                                <?php if($resident->isPending()): ?>
                                    <br><span class="badge bg-<?php echo e($resident->approval_badge_color); ?>">
                                        Pending Approval
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($resident->is_pwd): ?>
                                    <span class="badge bg-info">PWD</span>
                                <?php endif; ?>
                                <?php if($resident->is_senior_citizen): ?>
                                    <span class="badge bg-warning">Senior</span>
                                <?php endif; ?>
                                <?php if($resident->is_teen): ?>
                                    <span class="badge bg-secondary">Teen</span>
                                <?php endif; ?>
                                <?php if($resident->is_voter): ?>
                                    <span class="badge bg-success">Voter</span>
                                <?php endif; ?>
                                <?php if($resident->is_4ps_beneficiary): ?>
                                    <span class="badge bg-primary">4Ps</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('residents.show', $resident)); ?>" 
                                       class="btn btn-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <?php if(auth()->user()->isSecretary()): ?>
                                        <a href="<?php echo e(route('residents.edit', $resident)); ?>" 
                                           class="btn btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="<?php echo e(route('residents.archive', $resident)); ?>" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to archive this resident?')">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-secondary" title="Archive">
                                                <i class="bi bi-archive"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                <?php echo e($residents->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-people" style="font-size: 64px; color: #ccc;"></i>
                <p class="text-muted mt-3">No residents found.</p>
                <?php if(auth()->user()->isSecretary()): ?>
                    <a href="<?php echo e(route('households.create')); ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Register First Household
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/residents/index.blade.php ENDPATH**/ ?>
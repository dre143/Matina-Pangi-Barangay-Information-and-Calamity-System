<?php $__env->startSection('title', 'Government Assistance - Barangay Matina Pangi'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-gift"></i> Government Assistance Programs</h2>
    <a href="<?php echo e(route('government-assistance.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add Assistance Record
    </a>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search by program name or resident..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <select name="program_type" class="form-select">
                    <option value="">All Programs</option>
                    <option value="4ps">4Ps</option>
                    <option value="sss">SSS</option>
                    <option value="philhealth">PhilHealth</option>
                    <option value="ayuda">Ayuda</option>
                    <option value="scholarship">Scholarship</option>
                    <option value="livelihood">Livelihood</option>
                    <option value="housing">Housing</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="col-md-1">
                <select name="status" class="form-select">
                    <option value="">Status</option>
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Assistance Records Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Program Name</th>
                        <th>Recipient</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Date Received</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $assistanceRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong><?php echo e($record->program_name); ?></strong></td>
                            <td><?php echo e($record->resident->full_name); ?></td>
                            <td><span class="badge bg-primary"><?php echo e(strtoupper($record->program_type)); ?></span></td>
                            <td><?php echo e($record->amount ? '₱' . number_format($record->amount, 2) : 'N/A'); ?></td>
                            <td><?php echo e($record->date_received ? $record->date_received->format('M d, Y') : 'N/A'); ?></td>
                            <td>
                                <?php if($record->status == 'active'): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php elseif($record->status == 'completed'): ?>
                                    <span class="badge bg-info">Completed</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Cancelled</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('government-assistance.show', $record)); ?>" class="btn btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('government-assistance.edit', $record)); ?>" class="btn btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-2">No assistance records found.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <?php echo e($assistanceRecords->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/government-assistance/index.blade.php ENDPATH**/ ?>
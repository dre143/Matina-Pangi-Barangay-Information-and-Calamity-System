<?php $__env->startSection('title', 'Household Events History'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-calendar-event"></i> Household Events History</h2>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('household-events.index')); ?>" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search household ID..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <select name="household_id" class="form-select">
                    <option value="">All Households</option>
                    <?php $__currentLoopData = $households; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($h->id); ?>" <?php echo e(request('household_id') == $h->id ? 'selected' : ''); ?>>
                            <?php echo e($h->household_id); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="event_type" class="form-select">
                    <option value="">All Event Types</option>
                    <option value="head_change" <?php echo e(request('event_type') == 'head_change' ? 'selected' : ''); ?>>Head Change</option>
                    <option value="member_added" <?php echo e(request('event_type') == 'member_added' ? 'selected' : ''); ?>>Member Added</option>
                    <option value="member_removed" <?php echo e(request('event_type') == 'member_removed' ? 'selected' : ''); ?>>Member Removed</option>
                    <option value="household_split" <?php echo e(request('event_type') == 'household_split' ? 'selected' : ''); ?>>Household Split</option>
                    <option value="household_merged" <?php echo e(request('event_type') == 'household_merged' ? 'selected' : ''); ?>>Household Merged</option>
                    <option value="new_family_created" <?php echo e(request('event_type') == 'new_family_created' ? 'selected' : ''); ?>>New Family Created</option>
                    <option value="relocation" <?php echo e(request('event_type') == 'relocation' ? 'selected' : ''); ?>>Relocation</option>
                    <option value="dissolution" <?php echo e(request('event_type') == 'dissolution' ? 'selected' : ''); ?>>Dissolution</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" placeholder="From Date" value="<?php echo e(request('date_from')); ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Events Timeline -->
<div class="card">
    <div class="card-body">
        <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="d-flex mb-4 pb-4 border-bottom">
            <div class="me-3">
                <div class="rounded-circle bg-<?php echo e($event->event_type == 'member_added' ? 'success' : 
                    ($event->event_type == 'member_removed' ? 'danger' : 
                    ($event->event_type == 'relocation' ? 'warning' : 'info'))); ?> text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="bi 
                        <?php if($event->event_type == 'member_added'): ?> bi-person-plus
                        <?php elseif($event->event_type == 'member_removed'): ?> bi-person-dash
                        <?php elseif($event->event_type == 'head_change'): ?> bi-arrow-repeat
                        <?php elseif($event->event_type == 'relocation'): ?> bi-geo-alt
                        <?php elseif($event->event_type == 'household_split'): ?> bi-scissors
                        <?php elseif($event->event_type == 'household_merged'): ?> bi-union
                        <?php elseif($event->event_type == 'new_family_created'): ?> bi-house-add
                        <?php else: ?> bi-calendar-event
                        <?php endif; ?>"></i>
                </div>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="mb-1">
                            <a href="<?php echo e(route('households.show', $event->household)); ?>" class="text-decoration-none">
                                <?php echo e($event->household->household_id); ?>

                            </a>
                            <span class="badge bg-<?php echo e($event->event_type == 'member_added' ? 'success' : 
                                ($event->event_type == 'member_removed' ? 'danger' : 
                                ($event->event_type == 'relocation' ? 'warning' : 'info'))); ?>"><?php echo e(ucwords(str_replace('_', ' ', $event->event_type))); ?></span>
                        </h5>
                        <p class="mb-2"><?php echo e($event->description); ?></p>
                        <small class="text-muted">
                            <i class="bi bi-calendar"></i> <?php echo e($event->event_date->format('F d, Y')); ?> •
                            <i class="bi bi-person"></i> Processed by: <?php echo e($event->processor->name ?? 'System'); ?> •
                            <i class="bi bi-tag"></i> Reason: <?php echo e(ucfirst($event->reason)); ?>

                        </small>
                        <?php if($event->notes): ?>
                            <p class="mt-2 mb-0"><small><strong>Notes:</strong> <?php echo e($event->notes); ?></small></p>
                        <?php endif; ?>
                    </div>
                    <a href="<?php echo e(route('household-events.show', $event)); ?>" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye"></i> Details
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-5">
            <i class="bi bi-calendar-x" style="font-size: 3rem; color: #ccc;"></i>
            <p class="mt-3 text-muted">No household events found.</p>
        </div>
        <?php endif; ?>

        <?php if($events->hasPages()): ?>
        <div class="mt-4">
            <?php echo e($events->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\pangi\resources\views/household-events/index.blade.php ENDPATH**/ ?>
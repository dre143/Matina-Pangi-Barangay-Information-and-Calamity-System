<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Barangay Matina Pangi Information System'); ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons - Updated Version -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('css/custom.css')); ?>?v=<?php echo e(time()); ?>">
    <style>
        /* Additional inline styles for immediate effect */
        body {
            min-height: 100vh;
        }
        
        /* Status Badges - Enhanced with modern colors */
        .badge.bg-success,
        .badge.text-bg-success {
            background-color: #d1fae5 !important;
            color: #065f46 !important;
            font-weight: 600;
        }
        
        .badge.bg-warning,
        .badge.text-bg-warning {
            background-color: #fef3c7 !important;
            color: #92400e !important;
            font-weight: 600;
        }
        
        .badge.bg-danger,
        .badge.text-bg-danger {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
            font-weight: 600;
        }
        
        .badge.bg-dark,
        .badge.text-bg-dark {
            background-color: #e5e7eb !important;
            color: #1f2937 !important;
            font-weight: 600;
        }
        
        .badge.bg-info,
        .badge.text-bg-info {
            background-color: #dbeafe !important;
            color: #1e40af !important;
            font-weight: 600;
        }
        
        .badge.bg-secondary,
        .badge.text-bg-secondary {
            background-color: #f3f4f6 !important;
            color: #4b5563 !important;
            font-weight: 600;
        }
        
        .badge.bg-primary,
        .badge.text-bg-primary {
            background-color: #d1fae5 !important;
            color: #065f46 !important;
            font-weight: 600;
        }
        
        .badge-role-secretary {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 0.75rem;
        }
        
        .badge-role-staff {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 0.75rem;
        }
        
        /* ========================================
           BACK BUTTON FIX - Ensure visibility
           ======================================== */
        
        /* Fix for all back buttons */
        .btn-secondary,
        .btn-outline-secondary,
        .btn-outline-light {
            white-space: nowrap !important;
            overflow: visible !important;
            text-overflow: clip !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
        }
        
        /* Ensure back button text is always visible */
        .btn-secondary i,
        .btn-outline-secondary i,
        .btn-outline-light i {
            flex-shrink: 0;
        }
        
        /* Fix secondary button text color */
        .btn-secondary {
            color: #ffffff !important;
            background-color: #6c757d !important;
            border-color: #6c757d !important;
        }
        
        .btn-secondary:hover {
            color: #ffffff !important;
            background-color: #5a6268 !important;
            border-color: #545b62 !important;
        }
        
        /* Fix outline-light button for dark/colored backgrounds */
        .btn-outline-light,
        .btn-outline-light:link,
        .btn-outline-light:visited {
            color: #ffffff !important;
            border-color: rgba(255, 255, 255, 0.5) !important;
            background-color: rgba(255, 255, 255, 0.1) !important;
            font-weight: 500 !important;
        }
        
        .btn-outline-light:hover,
        .btn-outline-light:focus,
        .btn-outline-light:active {
            color: #212529 !important;
            background-color: #ffffff !important;
            border-color: #ffffff !important;
        }
        
        /* Specific fix for buttons on gradient backgrounds */
        .card .btn-outline-light,
        [style*="gradient"] .btn-outline-light {
            color: #ffffff !important;
            border-color: rgba(255, 255, 255, 0.8) !important;
            background-color: rgba(255, 255, 255, 0.15) !important;
            backdrop-filter: blur(10px);
        }
        
        /* Ensure button group doesn't cause overflow */
        .btn-group {
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        /* Fix for buttons in header sections */
        .d-flex .btn {
            flex-shrink: 0;
        }
        
        /* Ensure all buttons have proper padding */
        .btn {
            padding: 0.5rem 1rem !important;
            min-width: fit-content !important;
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="<?php echo e(route('dashboard')); ?>">
                <img src="<?php echo e(asset('logo.png')); ?>" alt="Logo" style="width: 40px; height: 40px; object-fit: contain; margin-right: 10px;" onerror="this.style.display='none'">
                Barangay Matina Pangi
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if(auth()->guard()->check()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?php echo e(auth()->user()->name); ?>

                                <span class="badge <?php echo e(auth()->user()->isSecretary() ? 'badge-role-secretary' : 'badge-role-staff'); ?>">
                                    <?php echo e(ucfirst(auth()->user()->role)); ?>

                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <?php if(auth()->guard()->check()): ?>
            <!-- Sidebar -->
            <nav class="col-md-2 d-md-block sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">
                                <i class="bi bi-speedometer2-fill"></i>
                                <span>Dashboard</span>
                                <?php if(request()->routeIs('dashboard')): ?>
                                    <i class="bi bi-chevron-right ms-auto"></i>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('residents.*') ? 'active' : ''); ?>" href="<?php echo e(route('residents.index')); ?>">
                                <i class="bi bi-people-fill"></i>
                                <span>Residents</span>
                                <?php if(request()->routeIs('residents.*')): ?>
                                    <i class="bi bi-chevron-right ms-auto"></i>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('households.*') ? 'active' : ''); ?>" href="<?php echo e(route('households.index')); ?>">
                                <i class="bi bi-house-fill"></i>
                                <span>Households</span>
                                <?php if(request()->routeIs('households.*')): ?>
                                    <i class="bi bi-chevron-right ms-auto"></i>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('census.*') ? 'active' : ''); ?>" href="<?php echo e(route('census.index')); ?>">
                                <i class="bi bi-bar-chart-fill"></i>
                                <span>Census</span>
                                <?php if(request()->routeIs('census.*')): ?>
                                    <i class="bi bi-chevron-right ms-auto"></i>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('resident-transfers.*') ? 'active' : ''); ?>" href="<?php echo e(route('resident-transfers.index')); ?>">
                                <i class="bi bi-arrow-left-right"></i>
                                <span>Resident Transfers</span>
                                <?php if(request()->routeIs('resident-transfers.*')): ?>
                                    <i class="bi bi-chevron-right ms-auto"></i>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('household-events.*') ? 'active' : ''); ?>" href="<?php echo e(route('household-events.index')); ?>">
                                <i class="bi bi-calendar-event"></i>
                                <span>Household Events</span>
                                <?php if(request()->routeIs('household-events.*')): ?>
                                    <i class="bi bi-chevron-right ms-auto"></i>
                                <?php endif; ?>
                            </a>
                        </li>
                        <?php if(auth()->user()->isSecretary()): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(request()->routeIs('puroks.*') ? 'active' : ''); ?>" href="<?php echo e(route('puroks.index')); ?>">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <span>Puroks</span>
                                    <?php if(request()->routeIs('puroks.*')): ?>
                                        <i class="bi bi-chevron-right ms-auto"></i>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(request()->routeIs('certificates.*') ? 'active' : ''); ?>" href="<?php echo e(route('certificates.index')); ?>">
                                    <i class="bi bi-file-earmark-text-fill"></i>
                                    <span>Certificates</span>
                                    <?php if(request()->routeIs('certificates.*')): ?>
                                        <i class="bi bi-chevron-right ms-auto"></i>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(request()->routeIs('health-records.*') ? 'active' : ''); ?>" href="<?php echo e(route('health-records.index')); ?>">
                                    <i class="bi bi-heart-pulse-fill"></i>
                                    <span>Health Records</span>
                                    <?php if(request()->routeIs('health-records.*')): ?>
                                        <i class="bi bi-chevron-right ms-auto"></i>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(request()->routeIs('senior-health.*') ? 'active' : ''); ?>" href="<?php echo e(route('senior-health.index')); ?>">
                                    <i class="bi bi-person-cane"></i>
                                    <span>Senior Health</span>
                                    <?php if(request()->routeIs('senior-health.*')): ?>
                                        <i class="bi bi-chevron-right ms-auto"></i>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(request()->routeIs('pwd-support.*') ? 'active' : ''); ?>" href="<?php echo e(route('pwd-support.index')); ?>">
                                    <i class="bi bi-universal-access"></i>
                                    <span>PWD Support</span>
                                    <?php if(request()->routeIs('pwd-support.*')): ?>
                                        <i class="bi bi-chevron-right ms-auto"></i>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(request()->routeIs('government-assistance.*') ? 'active' : ''); ?>" href="<?php echo e(route('government-assistance.index')); ?>">
                                    <i class="bi bi-gift-fill"></i>
                                    <span>Gov't Assistance</span>
                                    <?php if(request()->routeIs('government-assistance.*')): ?>
                                        <i class="bi bi-chevron-right ms-auto"></i>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(request()->routeIs('calamities.*') ? 'active' : ''); ?>" href="<?php echo e(route('calamities.index')); ?>">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    <span>Calamities</span>
                                    <?php if(request()->routeIs('calamities.*')): ?>
                                        <i class="bi bi-chevron-right ms-auto"></i>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(request()->routeIs('approvals.*') ? 'active' : ''); ?>" href="<?php echo e(route('approvals.index')); ?>">
                                    <i class="bi bi-clock-history"></i>
                                    <span>Approvals</span>
                                    <?php
                                        $pendingCount = \App\Models\Resident::pending()->count() + \App\Models\Household::pending()->count();
                                    ?>
                                    <?php if($pendingCount > 0): ?>
                                        <span class="badge bg-danger rounded-pill ms-auto"><?php echo e($pendingCount); ?></span>
                                    <?php elseif(request()->routeIs('approvals.*')): ?>
                                        <i class="bi bi-chevron-right ms-auto"></i>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(request()->routeIs('archived.*') ? 'active' : ''); ?>" href="<?php echo e(route('archived.index')); ?>">
                                    <i class="bi bi-archive-fill"></i>
                                    <span>Archived</span>
                                    <?php if(request()->routeIs('archived.*')): ?>
                                        <i class="bi bi-chevron-right ms-auto"></i>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>
            <?php endif; ?>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4 py-4" style="padding-left: 4rem !important; padding-top: 2.5rem !important;">
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
                
                <!-- Footer -->
                <footer class="footer mt-5">
                    <div class="container-fluid">
                        <p class="footer-text mb-0">
                            <i class="bi bi-heart-fill text-danger"></i> 
                            Building a connected community — one record at a time.
                        </p>
                        <small class="text-muted">© <?php echo e(date('Y')); ?> Barangay Matina Pangi. All rights reserved.</small>
                    </div>
                </footer>
            </main>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div id="successToast" class="toast toast-success" role="alert">
            <div class="toast-header">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                Action completed successfully!
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Toast Notification Script -->
    <script>
        // Show toast on success
        <?php if(session('success')): ?>
            var successToast = new bootstrap.Toast(document.getElementById('successToast'));
            successToast.show();
        <?php endif; ?>
    </script>
    
    <!-- Auto-Capitalize Names Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to capitalize first letter of each word
            function capitalizeWords(str) {
                return str.replace(/\b\w/g, function(char) {
                    return char.toUpperCase();
                });
            }
            
            // Select all name-related input fields
            const nameFields = document.querySelectorAll(
                'input[name*="first_name"], ' +
                'input[name*="middle_name"], ' +
                'input[name*="last_name"], ' +
                'input[name*="suffix"], ' +
                'input[name*="place_of_birth"], ' +
                'input[name*="nationality"], ' +
                'input[name*="religion"], ' +
                'input[name*="purok"], ' +
                'input[name*="barangay"], ' +
                'input[name*="city"], ' +
                'input[name*="province"], ' +
                'input[name*="municipality"], ' +
                'input[name*="caregiver_name"], ' +
                'input[name*="employer_name"], ' +
                'input[name*="school_name"], ' +
                'input[name*="mother_name"], ' +
                'input[name*="father_name"], ' +
                'input[name*="guardian_name"], ' +
                'input[name*="emergency_contact_name"]'
            );
            
            // Add input event listener to each field
            nameFields.forEach(function(field) {
                field.addEventListener('input', function(e) {
                    const cursorPosition = this.selectionStart;
                    const oldValue = this.value;
                    const newValue = capitalizeWords(oldValue);
                    
                    if (oldValue !== newValue) {
                        this.value = newValue;
                        // Restore cursor position
                        this.setSelectionRange(cursorPosition, cursorPosition);
                    }
                });
                
                // Also capitalize on blur (when leaving the field)
                field.addEventListener('blur', function() {
                    this.value = capitalizeWords(this.value);
                });
            });
        });
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\pangi\resources\views/layouts/app.blade.php ENDPATH**/ ?>
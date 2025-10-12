<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Barangay Matina Pangi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-green: #3AB795;
            --secondary-green: #7AE582;
            --light-green: #e8f5f1;
        }
        
        body {
            background: linear-gradient(135deg, #f5f9f7 0%, #e8f5f1 50%, #f0f9f6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
            padding: 20px 0;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(58, 183, 149, 0.1), transparent);
            border-radius: 50%;
        }
        
        body::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(122, 229, 130, 0.1), transparent);
            border-radius: 50%;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            overflow: visible;
            max-width: 480px;
            width: 90%;
            position: relative;
            z-index: 2;
            animation: fadeInUp 0.6s ease;
            margin: auto;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-header {
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }
        
        .login-logo {
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            padding: 5px;
            overflow: hidden;
        }
        
        .login-logo img {
            width: 110%;
            height: 110%;
            object-fit: cover;
            border-radius: 50%;
        }
        
        .login-body {
            padding: 40px;
        }
        
        .btn-login {
            background: linear-gradient(90deg, var(--primary-green), var(--secondary-green));
            color: white;
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1.05rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(58, 183, 149, 0.3);
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(58, 183, 149, 0.4);
            color: white;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 12px 16px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.25rem rgba(58, 183, 149, 0.15), 0 0 20px rgba(58, 183, 149, 0.2);
            transform: scale(1.01);
        }
        
        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .back-link {
            color: #6c757d;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .back-link:hover {
            color: var(--primary-green);
            transform: translateX(-5px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-card">
        <div class="login-header">
            <div class="login-logo">
                <img src="<?php echo e(asset('logo.png')); ?>?v=<?php echo e(time()); ?>" alt="Barangay Logo">
            </div>
            <h4 class="mb-0 fw-bold">System Login</h4>
            <p class="small mb-0 mt-2 opacity-90">Barangay Matina Pangi Information System</p>
        </div>
        
        <div class="login-body">
            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                    <?php echo e($errors->first()); ?>

                </div>
            <?php endif; ?>
            
            <form action="<?php echo e(route('login')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope"></i> Email Address
                    </label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?php echo e(old('email')); ?>" required autofocus>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock"></i> Password
                    </label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>
                
                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </button>
            </form>
            
            <div class="mt-4 text-center">
                <a href="<?php echo e(route('landing')); ?>" class="back-link small">
                    <i class="bi bi-arrow-left"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
    
        <!-- Footer -->
        <div class="text-center mt-4" style="position: relative; z-index: 2;">
            <p class="text-muted small mb-0">
                © <?php echo e(date('Y')); ?> Barangay Matina Pangi. All rights reserved.
            </p>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\pangi\resources\views/auth/login.blade.php ENDPATH**/ ?>
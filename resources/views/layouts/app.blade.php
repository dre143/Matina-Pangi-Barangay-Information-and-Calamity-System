<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Barangay Matina Pangi Information System')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons - Updated Version -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}?v={{ time() }}">
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <meta name="theme-color" content="#3AB795">
    <style>
        /* Additional inline styles for immediate effect */
        body {
            min-height: 100vh;
        }
        
        /* Status Badges */
        .badge.bg-success,
        .badge.text-bg-success {
            font-weight: 600;
        }
        
        .badge.bg-warning,
        .badge.text-bg-warning {
            font-weight: 600;
        }
        
        .badge.bg-danger,
        .badge.text-bg-danger {
            font-weight: 600;
        }
        
        .badge.bg-dark,
        .badge.text-bg-dark {
            font-weight: 600;
        }
        
        .badge.bg-info,
        .badge.text-bg-info {
            font-weight: 600;
        }
        
        .badge.bg-secondary,
        .badge.text-bg-secondary {
            font-weight: 600;
        }
        
        .badge.bg-primary,
        .badge.text-bg-primary {
            font-weight: 600;
        }
        
        .badge-role-secretary,
        .badge-role-staff {
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 0.75rem;
        }
        
        /* ========================================
           NAVBAR STYLING AND LOGO SPACING FIX
           ======================================== */
        
        .navbar-custom {
            background: #FFFFFF !important;
            border-bottom: 1px solid #E5E7EB !important;
            padding: 0.5rem 1rem !important;
            box-shadow: none !important;
        }
        
        .navbar-custom .container-fluid {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
        
        .navbar-custom .navbar-brand {
            color: #1E3A8A !important;
            font-weight: 700 !important;
            font-size: 1.125rem !important;
            display: flex !important;
            align-items: center !important;
            padding: 0.5rem 0 !important;
        }
        
        .navbar-custom .navbar-brand img {
            margin-right: 12px !important;
            border-radius: 50% !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2) !important;
        }
        
        .navbar-custom .navbar-nav {
            margin-right: 1rem !important;
        }
        
        .navbar-custom .nav-link {
            color: #6B7280 !important;
            font-weight: 500 !important;
        }
        
        .navbar-custom .nav-link:hover {
            color: #1E3A8A !important;
        }
        
        .navbar-custom .dropdown-menu {
            border: 1px solid #E5E7EB !important;
            box-shadow: none !important;
        }
        :root { --sidebar-width: 88px; --sidebar-collapsed: 88px; --sidebar-expanded: 256px; --content-gap: 32px; }
        .sidebar { position: fixed; top: 64px; left: 0; height: calc(100vh - 64px); width: var(--sidebar-width); background: #FFFFFF; border-right: 1px solid #E5E7EB; overflow: visible; transition: width .2s ease; z-index: 1020; padding: 0; box-sizing: border-box; scrollbar-gutter: stable both-edges; }
        .sidebar-inner { height: 100%; display: flex; align-items: center; }
        body.sidebar-hover .sidebar-inner { align-items: flex-start; }
        .sidebar-scroll { height: 100%; overflow-y: auto; overflow-x: hidden; padding: 12px 16px 12px 12px; scrollbar-gutter: stable both-edges; }
        body:not(.sidebar-hover) .sidebar-scroll { padding: 10px 20px 10px 0; }
        body.sidebar-hover .sidebar-scroll { padding: 12px 16px 12px 8px; }
        .sidebar-scroll::-webkit-scrollbar { width: 8px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: rgba(0,0,0,0.04); border-radius: 8px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(60,64,67,0.3); border-radius: 8px; }
        .sidebar .nav { gap: 6px; align-items: center; }
        body.sidebar-hover .sidebar .nav { gap: 2px; align-items: stretch; }
        .sidebar .nav-link { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 8px 10px; border-radius: 10px; min-height: 36px; width: 100%; }
        body.sidebar-hover .sidebar .nav-link { padding: 5px 8px; min-height: 32px; }
        body:not(.sidebar-hover) .sidebar .nav-link { padding: 8px 6px 8px 0; justify-content: flex-start; }
        body:not(.sidebar-hover) .sidebar .nav-link i { margin-left: -1px; }
        body.sidebar-hover .sidebar .nav-link { padding: 12px 14px; }
        .sidebar .nav-link i { font-size: 22px; width: 26px; text-align: center; }
        .sidebar .nav-link:hover { background: #F5F7FB; }
        .sidebar .nav-link.active { background: #EEF3FF; border-left: 3px solid #0d6efd; }
        /* moved scrollbars to .sidebar-scroll */
        .sidebar .nav-item { position: relative; }
        .sidebar .nav-link span,
        .sidebar .nav-link .badge,
        .sidebar .nav-link .bi-chevron-right,
        .sidebar .nav-link .bi-chevron-down { opacity: 0; width: 0; margin: 0; overflow: hidden; transition: opacity .2s ease, width .2s ease, margin .2s ease; }
        body.sidebar-hover { --sidebar-width: var(--sidebar-expanded); }
        body.sidebar-hover .sidebar .nav-link span,
        body.sidebar-hover .sidebar .nav-link .badge,
        body.sidebar-hover .sidebar .nav-link .bi-chevron-right,
        body.sidebar-hover .sidebar .nav-link .bi-chevron-down { opacity: 1; width: auto; margin-left: 4px; }
        body.sidebar-hover .sidebar .nav-link { justify-content: flex-start; padding-left: 4px; }
        /* Subsystems hidden by default; show on hover when expanded (Omada-style) */
        /* Collapsed: show subsystems as flyout on hover */
        body:not(.sidebar-hover) .sidebar .collapse { display: none !important; position: absolute; left: var(--sidebar-width); top: 0; background: #FFFFFF; border: 1px solid #E5E7EB; border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,0.08); width: 240px; padding: 8px; z-index: 1030; }
        body:not(.sidebar-hover) .sidebar .nav-item:hover > .collapse { display: block !important; }
        body:not(.sidebar-hover) .sidebar .nav-item:focus-within > .collapse { display: block !important; }
        /* Expanded/Pinned: allow Bootstrap collapse to work normally */
        body.sidebar-hover .sidebar .collapse { position: static; left: auto; top: auto; width: auto; box-shadow: none; border: 0; padding: 0; }
        
        /* ========================================
           HEALTH MANAGEMENT PARENT BUTTON STYLING
           ======================================== */
        .health-management-parent > .nav-link {
            background: #FFFFFF !important;
            color: #374151 !important;
            font-weight: 600 !important;
            padding: 8px 12px !important;
            border-radius: 8px !important;
            margin: 2px 6px !important;
            border: 1px solid #E5E7EB !important;
        }
        .health-management-parent > .nav-link:hover {
            background: #F3F4F6 !important;
            color: #1E3A8A !important;
        }
        .health-management-parent > .nav-link i {
            color: #1E3A8A !important;
        }
        
        /* Ensure submodules use standard styling with better visibility */
        .health-management-parent .collapse .nav-link {
            background: #ffffff !important;
            color: #2c3e50 !important;
            font-weight: 500 !important;
            padding: 8px 14px !important;
            border-radius: 6px !important;
            margin: 2px 6px !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
            transition: all 0.2s ease !important;
            border: 1px solid #e9ecef !important;
        }
        
        .health-management-parent .collapse .nav-link:hover {
            background: rgba(58, 183, 149, 0.1) !important;
            color: #1a5a45 !important;
            transform: translateX(4px) !important;
            border-color: #3AB795 !important;
        }
        
        .health-management-parent .collapse .nav-link.active {
            background: rgba(58, 183, 149, 0.15) !important;
            color: #1a5a45 !important;
            font-weight: 600 !important;
            border-left: 3px solid #3AB795 !important;
        }
        
        .health-management-parent .collapse .nav-link i {
            color: #3AB795 !important;
            margin-right: 8px !important;
        }
        
        .health-management-parent .collapse .nav-link span {
            color: #2c3e50 !important;
        }
        
        /* Force text visibility for all submenu items */
        #healthManagementSubmenu .nav-link {
            background: #ffffff !important;
            color: #2c3e50 !important;
        }
        
        #healthManagementSubmenu .nav-link:hover {
            background: #f8f9fa !important;
            color: #1a5a45 !important;
        }
        
        #healthManagementSubmenu .nav-link span {
            color: #2c3e50 !important;
        }
        
        /* ========================================
           HEALTH MODULE CONTENT SPACING - Universal Fix
           ======================================== */
        
        /* Apply to all health module pages */
        .health-module-content {
            margin-left: 4rem !important;
            padding-right: 2rem !important;
            margin-top: -1rem !important;
            padding-top: 2rem !important;
            padding-left: 1rem !important;
        }
        
        /* Responsive spacing for health modules */
        @media (max-width: 768px) {
            .health-module-content {
                margin-left: 2.5rem !important;
                padding-right: 1rem !important;
                padding-left: 0.5rem !important;
            }
        }
        
        @media (max-width: 576px) {
            .health-module-content {
                margin-left: 1.5rem !important;
                padding-right: 0.5rem !important;
                padding-left: 0.25rem !important;
            }
        }

        /* ========================================
           FIX TRANSPARENT BUTTON ISSUE GLOBALLY
           ======================================== */
        
        /* Override Bootstrap's default button opacity changes */
        .health-module-content .btn:hover,
        .health-module-content .btn:focus,
        .health-module-content .btn:active,
        .health-module-content .btn.active,
        .health-module-content .btn:focus-visible {
            opacity: 1 !important;
        }

        .health-module-content .btn {
            opacity: 1 !important;
        }

        /* Enhanced button interactions without transparency */
        .health-module-content .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .health-module-content .btn:active {
            transform: translateY(0px);
        }

        /* NUCLEAR OPTION - Remove ALL white effects globally */
        .health-module-content .btn:active,
        .health-module-content .btn:focus,
        .health-module-content .btn:hover,
        .health-module-content .btn.active {
            background-image: none !important;
            background-blend-mode: normal !important;
            filter: none !important;
            box-shadow: none !important;
            outline: none !important;
        }

        /* Force specific button colors when active */
        .health-module-content .btn-primary:active,
        .health-module-content .btn-primary:focus {
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
        }

        .health-module-content .btn-warning:active,
        .health-module-content .btn-warning:focus {
            background-color: #ffc107 !important;
            border-color: #ffc107 !important;
        }

        .health-module-content .btn-success:active,
        .health-module-content .btn-success:focus {
            background-color: #198754 !important;
            border-color: #198754 !important;
        }

        .health-module-content .btn-info:active,
        .health-module-content .btn-info:focus {
            background-color: #0dcaf0 !important;
            border-color: #0dcaf0 !important;
        }

        /* Remove any transitions that cause flashing */
        .health-module-content .btn,
        .health-module-content .btn * {
            transition: none !important;
        }
        .table-hover > tbody > tr:hover > * {
            background-color: transparent !important;
        }
        .table tbody tr:hover > * {
            background-color: inherit !important;
        }
        .theme-black-red .table tbody tr:hover > * {
            background-color: transparent !important;
            background: transparent !important;
        }
        .theme-black-red .table tbody tr:hover {
            transform: none !important;
            box-shadow: none !important;
            background: transparent !important;
        }
        .table, .table * {
            transition: none !important;
        }
        .theme-black-red .table, .theme-black-red .table * {
            transition: none !important;
        }
        .table-hover tbody tr:hover,
        .table tbody tr:hover {
            transform: none !important;
            box-shadow: none !important;
            background: transparent !important;
        }
        .content-container { max-width: 1200px; margin-left: auto; margin-right: auto; padding-left: 24px; padding-right: 24px; }
        .content-full { max-width: none; margin: 0; padding-left: 0; padding-right: 0; position: relative; left: -1.5rem; width: calc(100% + 3rem); }
        @media (max-width: 991px){ .content-full { left: -1.5rem; width: calc(100% + 3rem); } }
        @media (max-width: 768px){ .content-full { left: -1.5rem; width: calc(100% + 3rem); } }
        .form-offset-right { padding-left: 0 !important; padding-right: 0 !important; }
        .table tbody tr:active,
        .table tbody tr:focus,
        .table-hover tbody tr:active,
        .table-hover tbody tr:focus {
            transform: none !important;
            box-shadow: none !important;
            background: transparent !important;
            outline: none !important;
        }
        .theme-black-red .table tbody tr:active,
        .theme-black-red .table tbody tr:focus {
            transform: none !important;
            box-shadow: none !important;
            background: transparent !important;
            outline: none !important;
        }
    </style>
    
    <script>
        // Restore scroll position after page load
        window.addEventListener('load', function() {
            const savedPosition = sessionStorage.getItem('scrollPosition');
            if (savedPosition) {
                window.scrollTo(0, parseInt(savedPosition));
                sessionStorage.removeItem('scrollPosition');
            }
            try { sessionStorage.setItem('lastUrl', window.location.pathname + window.location.search); } catch (e) {}
        });
    </script>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/service-worker.js');
            });
        }
    </script>
    <script>
        (function(){
            function hasFileInput(form){ return !!form.querySelector('input[type="file"]'); }
            function formEntries(form){ return Array.from(new FormData(form).entries()); }
            function enqueueForm(form){
                var payload = {
                    url: form.action || window.location.href,
                    method: (form.method || 'POST').toUpperCase(),
                    entries: formEntries(form),
                    enctype: (form.enctype || 'application/x-www-form-urlencoded').toLowerCase()
                };
                if (navigator.serviceWorker && navigator.serviceWorker.controller) {
                    navigator.serviceWorker.controller.postMessage({ type: 'enqueue', payload: payload });
                }
            }
            document.addEventListener('submit', function(e){
                var form = e.target;
                if (!(form instanceof HTMLFormElement)) return;
                var action = form.action || '';
                if (action.endsWith('/logout')) {
                    if (navigator.serviceWorker && navigator.serviceWorker.controller) {
                        navigator.serviceWorker.controller.postMessage({ type: 'clearCaches' });
                    }
                }
            });
            document.addEventListener('submit', function(e){
                var form = e.target;
                if (!(form instanceof HTMLFormElement)) return;
                var method = (form.method || 'GET').toUpperCase();
                if (method !== 'POST') return;
                if (navigator.onLine) return;
                if (hasFileInput(form)) return;
                e.preventDefault();
                enqueueForm(form);
                alert('Saved offline. Will sync when online.');
                try { form.reset(); } catch (e) {}
            });
            window.addEventListener('online', function(){
                if (navigator.serviceWorker && navigator.serviceWorker.controller) {
                    navigator.serviceWorker.controller.postMessage({ type: 'sync' });
                }
            });
        })();
    </script>
    
    <style>
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
            padding: 6px 12px !important;
            border-radius: 8px !important;
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
    @stack('styles')
</head>
<body>
    <!-- Loading Screen -->
    <div id="loading-screen" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: #ffffff; z-index: 9999; display: flex; align-items: center; justify-content: center; transition: opacity 0.3s ease;">
        <div style="text-align: center;">
            <div style="font-size: 2rem; color: #1E3A8A; margin-bottom: 1rem;">
                <i class="bi bi-house-heart"></i>
            </div>
            <div style="font-weight: 600; color: #374151;">Barangay Matina Pangi</div>
        </div>
    </div>
    
    <script>
        (function(){
            var el = document.getElementById('loading-screen');
            function hide(){ if (!el) return; el.style.opacity='0'; setTimeout(function(){ el.style.display='none'; }, 300); }
            window.addEventListener('load', function(){ setTimeout(hide, 200); });
            document.addEventListener('DOMContentLoaded', function(){ setTimeout(hide, 600); });
            setTimeout(hide, 2000);
        })();
    </script>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                <img src="{{ asset('logo.png') }}" alt="Logo" style="width: 40px; height: 40px; object-fit: contain; margin-right: 10px;" onerror="this.style.display='none'">
                Barangay Matina Pangi
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        @if(auth()->user()->isSecretary())
                        @php
                            $annCount = \App\Models\Announcement::where('status','sent')
                                ->whereNotNull('sent_at')
                                ->count();
                            $latestAnnouncements = \App\Models\Announcement::where('status','sent')
                                ->whereNotNull('sent_at')
                                ->latest('sent_at')
                                ->take(5)
                                ->get();
                        @endphp
                        <li class="nav-item dropdown me-2" id="announcementBell">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell"></i>
                                <span class="badge bg-danger rounded-pill" id="announcementBellCount" style="display: {{ $annCount>0 ? 'inline-block' : 'none' }};">{{ $annCount }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" id="announcementBellMenu">
                                <li class="dropdown-header">Announcements</li>
                                @forelse($latestAnnouncements as $a)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('announcements.show', $a) }}">
                                            <div class="d-flex align-items-start">
                                                <div class="me-2"><i class="bi bi-megaphone"></i></div>
                                                <div>
                                                    <div class="fw-semibold">{{ \Illuminate\Support\Str::limit($a->title, 40) }}</div>
                                                    <small class="text-muted">{{ optional($a->sent_at)->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @empty
                                    <li><span class="dropdown-item text-muted">No recent announcements</span></li>
                                @endforelse
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('announcements.index') }}">View all</a></li>
                            </ul>
                        </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                                <span class="badge {{ auth()->user()->isSecretary() ? 'badge-role-secretary' : 'badge-role-staff' }}">
                                    {{ ucfirst(auth()->user()->role) }}
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(auth()->user()->isSecretary())
                                <li>
                                    <a class="dropdown-item" href="{{ route('settings.users.index') }}">
                                        <i class="bi bi-gear"></i> Settings
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                @endif
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <script>
        (function(){
            const bellCountEl = document.getElementById('announcementBellCount');
            const bellMenuEl = document.getElementById('announcementBellMenu');
            const bellWrapEl = document.getElementById('announcementBell');
            async function refreshAnnouncementBell(){
                try {
                    const res = await fetch('{{ route('announcements.bell') }}', { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) return;
                    const data = await res.json();
                    if (bellCountEl) {
                        bellCountEl.textContent = data.count || 0;
                        bellCountEl.style.display = (data.count && data.count > 0) ? 'inline-block' : 'none';
                    }
                    if (bellMenuEl) {
                        let html = '<li class="dropdown-header">Announcements</li>';
                        if (Array.isArray(data.items) && data.items.length){
                            data.items.forEach(function(it){
                                html += '<li><a class="dropdown-item" href="'+it.url+'">'
                                    +'<div class="d-flex align-items-start">'
                                    +'<div class="me-2"><i class="bi bi-megaphone"></i></div>'
                                    +'<div><div class="fw-semibold">'+escapeHtml(it.title)+'</div>'
                                    +'<small class="text-muted">'+(it.sent_at_human || '')+'</small></div>'
                                    +'</div>'
                                    +'</a></li>';
                            });
                        } else {
                            html += '<li><span class="dropdown-item text-muted">No recent announcements</span></li>';
                        }
                        html += '<li><hr class="dropdown-divider"></li>';
                        html += '<li><a class="dropdown-item" href="{{ route('announcements.index') }}">View all</a></li>';
                        bellMenuEl.innerHTML = html;
                    }
                } catch (e) { /* ignore */ }
            }
            function escapeHtml(s){
                return String(s).replace(/[&<>"']/g, function (m) {
                    return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'})[m];
                });
            }
            // Poll periodically to reflect new announcements without reload
            setInterval(refreshAnnouncementBell, 5000);
            // Initial fetch
            refreshAnnouncementBell();
            // Optional: refresh when page becomes visible again
            document.addEventListener('visibilitychange', function(){ if (!document.hidden) refreshAnnouncementBell(); });
        })();
    </script>
    <script>
        (function(){
            function textOfLabel(el){
                var id = el.getAttribute('id');
                if (id) {
                    var lbl = document.querySelector('label[for="'+id+'"]');
                    if (lbl) return lbl.textContent.trim().replace(/\s*\*\s*$/,'');
                }
                var p = el.closest('.col, .col-12, .col-md-12, .col-md-6, .col-md-4, .mb-3');
                if (p) {
                    var l = p.querySelector('.form-label');
                    if (l) return l.textContent.trim().replace(/\s*\*\s*$/,'');
                }
                return '';
            }
            function makePlaceholder(el){
                var label = textOfLabel(el);
                if (!label) return '';
                if (el.type === 'email') return 'name@example.com';
                if (el.tagName.toLowerCase() === 'textarea') return 'Enter '+label.toLowerCase();
                return 'Enter '+label.toLowerCase();
            }
            function ensureSelectDefault(sel){
                var label = textOfLabel(sel);
                if (!label) label = 'option';
                var first = sel.querySelector('option');
                var hasDefault = first && (first.value === '' || first.disabled);
                if (!hasDefault) {
                    var opt = document.createElement('option');
                    opt.value = '';
                    opt.disabled = true;
                    opt.selected = !sel.value;
                    opt.textContent = 'Select '+label.toLowerCase();
                    sel.insertBefore(opt, first);
                }
            }
            function process(){
                document.querySelectorAll('input.form-control, textarea.form-control').forEach(function(el){
                    if (el.type === 'hidden') return;
                    if (el.hasAttribute('placeholder')) return;
                    var ph = makePlaceholder(el);
                    if (ph) el.setAttribute('placeholder', ph);
                });
                document.querySelectorAll('select.form-select').forEach(function(sel){
                    ensureSelectDefault(sel);
                });
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', process);
            } else {
                process();
            }
        })();
    </script>
    <div class="container-fluid">
        <div class="row">
            @auth
            <!-- Sidebar -->
            <nav class="col-md-2 d-md-block sidebar">
                <div class="sidebar-inner">
                    <div class="sidebar-scroll">
                        <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2-fill"></i>
                                <span>Dashboard</span>
                                @if(request()->routeIs('dashboard'))
                                    <i class="bi bi-chevron-right ms-auto"></i>
                                @endif
                            </a>
                        </li>
                        @if(auth()->user()->isSuperAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('residents.*') ? 'active' : '' }}" href="{{ route('residents.index') }}">
                                <i class="bi bi-people-fill"></i>
                                <span>Residents</span>
                                @if(request()->routeIs('residents.*'))
                                    <i class="bi bi-chevron-right ms-auto"></i>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('households.*') ? 'active' : '' }}" href="{{ route('households.index') }}">
                                <i class="bi bi-house-fill"></i>
                                <span>Households</span>
                                @if(request()->routeIs('households.*'))
                                    <i class="bi bi-chevron-right ms-auto"></i>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('census.*') ? 'active' : '' }}" href="{{ route('census.index') }}">
                                <i class="bi bi-bar-chart-fill"></i>
                                <span>Census</span>
                                @if(request()->routeIs('census.*'))
                                    <i class="bi bi-chevron-right ms-auto"></i>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('resident-transfers.*') ? 'active' : '' }}" href="{{ route('resident-transfers.index') }}">
                                <i class="bi bi-arrow-left-right"></i>
                                <span>Resident Transfers</span>
                                @if(auth()->user()->isSecretary())
                                    @php
                                        $pendingCount = \App\Models\ResidentTransfer::where('status', 'pending')->count();
                                    @endphp
                                    @if($pendingCount > 0)
                                        <span class="badge bg-danger rounded-pill ms-2">{{ $pendingCount }}</span>
                                    @endif
                                @endif
                                @if(request()->routeIs('resident-transfers.*'))
                                    <i class="bi bi-chevron-right ms-auto"></i>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('household-events.*') ? 'active' : '' }}" href="{{ route('household-events.index') }}">
                                <i class="bi bi-calendar-event"></i>
                                <span>Household Events</span>
                                @if(request()->routeIs('household-events.*'))
                                    <i class="bi bi-chevron-right ms-auto"></i>
                                @endif
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->isSuperAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('puroks.*') ? 'active' : '' }}" href="{{ route('puroks.index') }}">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <span>Puroks</span>
                                    @if(request()->routeIs('puroks.*'))
                                        <i class="bi bi-chevron-right ms-auto"></i>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('certificates.*') ? 'active' : '' }}" href="{{ route('certificates.index') }}">
                                    <i class="bi bi-file-earmark-text-fill"></i>
                                    <span>Certificates</span>
                                    @if(request()->routeIs('certificates.*'))
                                        <i class="bi bi-chevron-right ms-auto"></i>
                                    @endif
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="collapse" href="#calamityManagementSubmenu" role="button" aria-expanded="{{ request()->routeIs('calamities.*') ? 'true' : 'false' }}" aria-controls="calamityManagementSubmenu">
                                    <i class="bi bi-lightning-fill"></i>
                                    <span>Calamity Management</span>
                                    <i class="bi bi-chevron-down ms-auto"></i>
                                </a>
                                <div class="collapse {{ request()->routeIs('calamities.*') ? 'show' : '' }}" id="calamityManagementSubmenu">
                                    <ul class="nav flex-column ms-3">
                                        <li class="nav-item">
                                            <a class="nav-link {{ request()->routeIs('calamities.*') ? 'active' : '' }}" href="{{ route('calamities.index') }}">
                                                <i class="bi bi-exclamation-triangle"></i>
                                                <span>Calamity Incident</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('web.calamity-affected-households.index') }}">
                                                <i class="bi bi-people"></i>
                                                <span>Affected Residents / Household</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('web.evacuation-centers.index') }}">
                                                <i class="bi bi-building"></i>
                                                <span>Evacuation Center</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('web.relief-items.index') }}">
                                                <i class="bi bi-box-seam"></i>
                                                <span>Relief Goods Inventory</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('web.relief-distributions.index') }}">
                                                <i class="bi bi-truck"></i>
                                                <span>Relief Distribution</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('web.damage-assessments.index') }}">
                                                <i class="bi bi-clipboard-check"></i>
                                                <span>Damage Assessment & Reporting</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('web.notifications.index') }}">
                                                <i class="bi bi-megaphone"></i>
                                                <span>Emergency Notification / Announcement</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('web.response-team-members.index') }}">
                                                <i class="bi bi-people-fill"></i>
                                                <span>Calamity Response Team / Volunteers</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('web.calamity-reports.index') }}">
                                                <i class="bi bi-file-text"></i>
                                                <span>Calamity Report</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('approvals.*') ? 'active' : '' }}" href="{{ route('approvals.index') }}">
                                    <i class="bi bi-clock-history"></i>
                                    <span>Approvals</span>
                                    @php
                                        $pendingCount = \App\Models\Resident::pending()->count() + \App\Models\Household::pending()->count();
                                    @endphp
                                    @if($pendingCount > 0)
                                        <span class="badge bg-danger rounded-pill ms-auto">{{ $pendingCount }}</span>
                                    @elseif(request()->routeIs('approvals.*'))
                                        <i class="bi bi-chevron-right ms-auto"></i>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('archived.*') ? 'active' : '' }}" href="{{ route('archived.index') }}">
                                    <i class="bi bi-archive-fill"></i>
                                    <span>Archived</span>
                                    @if(request()->routeIs('archived.*'))
                                        <i class="bi bi-chevron-right ms-auto"></i>
                                    @endif
                                </a>
                            </li>
                        @endif
                        @if(auth()->user()->isCalamityHead())
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="collapse" href="#calamityManagementSubmenu" role="button" aria-expanded="{{ request()->routeIs('calamities.*') ? 'true' : 'false' }}" aria-controls="calamityManagementSubmenu">
                                    <i class="bi bi-lightning-fill"></i>
                                    <span>Calamity Management</span>
                                    <i class="bi bi-chevron-down ms-auto"></i>
                                </a>
                                <div class="collapse {{ request()->routeIs('calamities.*') ? 'show' : '' }}" id="calamityManagementSubmenu">
                                    <ul class="nav flex-column ms-3">
                                        <li class="nav-item">
                                            <a class="nav-link {{ request()->routeIs('calamities.*') ? 'active' : '' }}" href="{{ route('calamities.index') }}">
                                                <i class="bi bi-exclamation-triangle"></i>
                                                <span>Calamity Incident</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('web.calamity-affected-households.index') }}">
                                                <i class="bi bi-people"></i>
                                                <span>Affected Residents / Household</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('web.evacuation-centers.index') }}">
                                                <i class="bi bi-building"></i>
                                                <span>Evacuation Center</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('web.relief-items.index') }}">
                                                <i class="bi bi-box-seam"></i>
                                                <span>Relief Goods Inventory</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('web.relief-distributions.index') }}">
                                                <i class="bi bi-truck"></i>
                                                <span>Relief Distribution</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('web.damage-assessments.index') }}">
                                                <i class="bi bi-clipboard-check"></i>
                                                <span>Damage Assessment & Reporting</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('web.notifications.index') }}">
                                                <i class="bi bi-megaphone"></i>
                                                <span>Emergency Notification / Announcement</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('web.response-team-members.index') }}">
                                                <i class="bi bi-people-fill"></i>
                                                <span>Calamity Response Team / Volunteers</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('web.calamity-reports.index') }}">
                                                <i class="bi bi-file-text"></i>
                                                <span>Calamity Report</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif
                        </ul>
                    </div>
                </div>
            </nav>
            @endauth

            <!-- Main Content -->
               <main class="col-md-10 ms-sm-auto px-md-4 py-4" style="margin-left: var(--sidebar-width); padding-top: 2rem;">

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @hasSection('full_width')
                <div class="content-full">
                    @yield('content')
                </div>
                @else
                <div class="content-container">
                    @yield('content')
                </div>
                @endif
                
                <!-- Footer -->
                <footer class="footer mt-5">
                    <div class="container-fluid">
                        <p class="footer-text mb-0">
                            <i class="bi bi-heart-fill text-danger"></i> 
                            Building a connected community — one record at a time.
                        </p>
                        <small class="text-muted">© {{ date('Y') }} Barangay Matina Pangi. All rights reserved.</small>
                    </div>
                </footer>
            </main>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div id="successToast" class="toast toast-success" role="alert" data-bs-autohide="true" data-bs-delay="2500">
            <div class="toast-header">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong class="me-auto">Success</strong>
            </div>
            <div class="toast-body">
                {{ session('success') ?? 'Action completed successfully!' }}
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Toast Notification Script -->
    <script>
        @if(session('success'))
            (function(){
                var el = document.getElementById('successToast');
                var body = el ? el.querySelector('.toast-body') : null;
                if (body) { body.textContent = @json(session('success')); }
                var t = new bootstrap.Toast(el, { autohide: true, delay: 2500 });
                t.show();
            })();
        @endif
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var sidebar = document.querySelector('.sidebar');
            var toggleBtn = document.getElementById('sidebarToggle');
            var main = document.querySelector('main');
            var root = document.documentElement;
            root.style.setProperty('--sidebar-width', getComputedStyle(root).getPropertyValue('--sidebar-collapsed').trim() || '64px');
            
            if (sidebar) {
                sidebar.addEventListener('mouseenter', function() {
                    document.body.classList.add('sidebar-hover');
                    root.style.setProperty('--sidebar-width', getComputedStyle(root).getPropertyValue('--sidebar-expanded').trim() || '256px');
                });
                sidebar.addEventListener('mouseleave', function() {
                    document.body.classList.remove('sidebar-hover');
                    root.style.setProperty('--sidebar-width', getComputedStyle(root).getPropertyValue('--sidebar-collapsed').trim() || '64px');
                });
                Array.prototype.forEach.call(sidebar.querySelectorAll('.nav-link'), function(a){
                    var s = a.querySelector('span');
                    if (s && !a.getAttribute('title')) a.setAttribute('title', s.textContent.trim());
                });
            }
        });
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
    
    @stack('scripts')
</body>
</html>

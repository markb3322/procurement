<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
date_default_timezone_set('Asia/Manila');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 75px; 
            --admin-green: #198754;
            --dark-bg: #1a1d20;
        }

        body { font-family: 'Inter', sans-serif; background-color: #f4f7f6; transition: all 0.3s; }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--dark-bg);
            color: white;
            padding-top: 20px;
            z-index: 1050;
            border-right: 3px solid var(--admin-green);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            overflow: hidden; 
        }

        .sidebar.collapsed { 
            width: var(--sidebar-collapsed-width); 
        }

        .sidebar-header {
            padding: 10px 20px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #333;
            margin-bottom: 20px;
            white-space: nowrap;
            justify-content: flex-start;
        }

        .sidebar.collapsed .sidebar-header span,
        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .sidebar-footer p,
        .sidebar.collapsed .sidebar-footer small {
            display: none;
        }

        .sidebar .nav-link {
            color: #ced4da;
            padding: 12px 25px;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            white-space: nowrap;
        }

        .sidebar .nav-link i { font-size: 1.2rem; margin-right: 15px; min-width: 25px; }

        .sidebar.collapsed .nav-link i { margin-right: 0; }
        .sidebar.collapsed .nav-link { justify-content: center; padding: 12px 0; }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background-color: var(--admin-green);
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 20px;
            font-size: 0.75rem;
            color: #6c757d;
            border-top: 1px solid #333;
            text-align: center;
        }

        /* Top Navbar Styling */
        .top-navbar {
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width); 
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .top-navbar.full-width { 
            left: var(--sidebar-collapsed-width); 
        }

        .admin-title { 
            font-weight: 700; 
            color: var(--dark-bg); 
            margin: 0; 
            letter-spacing: -0.5px; 
            font-size: 1.1rem; 
            cursor: pointer; 
            user-select: none;
        }
        .admin-title span { color: var(--admin-green); }

        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.5rem;
            margin-right: 15px;
            cursor: pointer;
            color: var(--dark-bg);
        }

        .live-clock {
            background: #f8f9fa;
            padding: 8px 15px;
            border-radius: 8px;
            font-weight: 600;
            color: #555;
            border: 1px solid #ddd;
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            .sidebar { left: -var(--sidebar-width); }
            .sidebar.active { left: 0; width: var(--sidebar-width) !important; }
            .top-navbar { left: 0; }
            .top-navbar.full-width { left: 0; }
        }
    </style>
</head>
<body>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="d-flex align-items-center">
            <i class="bi bi-shield-lock-fill text-success me-2 fs-5"></i>
            <span class="fw-bold text-uppercase small tracking-wider">Admin Panel</span>
        </div>
    </div>
    
    <ul class="nav flex-column mb-auto">
        <li class="nav-item">
            <a class="nav-link" href="admin_dashboard.php">
                <i class="bi bi-speedometer2"></i> 
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="register.php">
                <i class="bi bi-person-plus"></i> 
                <span>Register User</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="admin_table_view.php">
                <i class="bi bi-database"></i> 
                <span>Database View</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="reports.php">
                <i class="bi bi-bar-chart-line"></i> 
                <span>Generate Reports</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <p class="mb-0">Procurement Data System</p>
        <small>All Right Reserved &copy; 2026</small>
    </div>
</div>

<div class="top-navbar" id="topNavbar">
    <div class="navbar-left d-flex align-items-center">
        <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
        <h4 class="admin-title" onclick="toggleSidebar()">
            <span>PROCUREMENT</span> DATA SYSTEM
        </h4>
    </div>
    
    <div class="navbar-right">
        <div class="live-clock" id="liveClock">
            <i class="bi bi-calendar3 me-2 text-success"></i> Loading...
        </div>
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const topNavbar = document.getElementById('topNavbar');
        const adminWrapper = document.getElementById('adminWrapper'); 
        
        sidebar.classList.toggle('collapsed');
        topNavbar.classList.toggle('full-width');
        
        if (adminWrapper) {
            adminWrapper.classList.toggle('collapsed-active');
        }
        
        if (window.innerWidth <= 768) {
            sidebar.classList.toggle('active');
        }
    }

    function updateClock() {
        const now = new Date();
        const options = { 
            weekday: 'short', year: 'numeric', month: 'short', day: 'numeric',
            hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true 
        };
        const clockElement = document.getElementById('liveClock');
        if(clockElement) {
            clockElement.innerHTML = '<i class="bi bi-calendar3 me-2 text-success"></i> ' + now.toLocaleString('en-US', options);
        }
    }

    setInterval(updateClock, 1000);
    updateClock();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
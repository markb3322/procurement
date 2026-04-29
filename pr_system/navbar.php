<?php 
// Start session to access logged-in user data
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// 1. Determine Display Name
$s_nickname = (!empty($_SESSION['nickname'])) ? trim($_SESSION['nickname']) : '';
$s_first    = (!empty($_SESSION['first_name'])) ? trim($_SESSION['first_name']) : '';

if ($s_nickname !== '') {
    $user_display_name = $s_nickname;
} elseif ($s_first !== '') {
    $user_display_name = $s_first;
} else {
    $user_display_name = 'Guest';
}

// 2. Determine Profile Picture
$user_pic = $_SESSION['profile_pic'] ?? '';

// FIX: Path synchronized to "uploads/profiles/" to match register.php and save.php
$upload_path = "uploads/profiles/" . $user_pic;

// Check if file exists in the profiles subfolder
if (!empty($user_pic) && $user_pic !== 'default.png' && file_exists($upload_path)) {
    $nav_profile_img = $upload_path;
} else {
    // Fallback to UI-Avatars
    $nav_profile_img = "https://ui-avatars.com/api/?name=" . urlencode($user_display_name) . "&background=198754&color=fff";
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
    .navbar-custom {
        background-color: #1a1d20; 
        padding: 0.8rem 1.5rem;
        border-bottom: 2px solid #198754; 
    }
    .navbar-brand { font-weight: 800; letter-spacing: -0.5px; }
    .text-procure { color: #198754; } 
    .nav-link { font-weight: 500; color: #e0e0e0 !important; transition: all 0.3s ease; }
    .nav-link:hover { color: #198754 !important; }
    .user-profile-img { width: 35px; height: 35px; object-fit: cover; border: 2px solid #198754; }
    
    .dropdown-menu { 
        border: none; 
        box-shadow: 0 5px 15px rgba(0,0,0,0.2); 
        border-radius: 8px;
        display: block; 
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all 0.3s ease-in-out;
        pointer-events: none; 
    }

    .dropdown:hover > .dropdown-menu,
    .dropdown-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
        pointer-events: auto;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <span class="text-procure">PROCUREMENT</span> DATA SYSTEM
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php"><i class="bi bi-house-door me-1"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="table.php"><i class="bi bi-table me-1"></i> Table View</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="notes.php"><i class="bi bi-journal-check me-1"></i> Note Task</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://192.168.35.11/accomplishment/login.php" target="_blank">
                        <i class="bi bi-award me-1"></i> Accomplishment
                    </a>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-plus-circle me-1"></i> Add New
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="catering.php?type=catering">Catering</a></li>
                        <li><a class="dropdown-item" href="office_supply.php?type=office-supplies">Office Supplies</a></li>
                        <li><a class="dropdown-item" href="ict_devices.php?type=ict">ICT Devices</a></li>
                        <li><a class="dropdown-item" href="furnitures.php?type=furnitures">Furnitures</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="fabrication_installation.php?type=fabrication">Fabrication & Installation</a></li>
                        <li><a class="dropdown-item" href="heavy_equipment.php?type=heavy-equipment">Heavy Equipment</a></li>
                        <li><a class="dropdown-item" href="appliances.php?type=appliances">Appliances</a></li>
                        <li><a class="dropdown-item" href="fixtures.php?type=fixtures">Fixtures</a></li>
                    </ul>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="me-2 d-none d-lg-inline text-white"><?php echo htmlspecialchars($user_display_name); ?></span>
                        <img src="<?php echo $nav_profile_img; ?>" 
                             class="rounded-circle user-profile-img" alt="User">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="profile.php?id=<?php echo $_SESSION['user_id']; ?>"><i class="bi bi-person me-2"></i> Profile Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Maintain original dropdown hover logic
    document.querySelectorAll('.dropdown').forEach(function(everydropdown) {
        everydropdown.addEventListener('mouseenter', function(e){
            let el_link = this.querySelector('a[data-bs-toggle]');
            if(el_link != null){
                let nextEl = el_link.nextElementSibling;
                el_link.classList.add('show');
                nextEl.classList.add('show');
            }
        });
        everydropdown.addEventListener('mouseleave', function(e){
            let el_link = this.querySelector('a[data-bs-toggle]');
            if(el_link != null){
                let nextEl = el_link.nextElementSibling;
                el_link.classList.remove('show');
                nextEl.classList.remove('show');
            }
        });
    });
</script>
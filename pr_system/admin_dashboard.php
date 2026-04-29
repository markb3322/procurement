<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db.php'; 

/**
 * LOGIC FOR GREETINGS (Tagalog-style Time Cycles)
 */
date_default_timezone_set('Asia/Manila'); 
$hour = (int)date('H');

if ($hour >= 5 && $hour < 12) {
    // 5:00 AM to 11:59 AM - Buntag (Morning)
    $greeting = "Good Morning";
    $icon = "bi-sun-fill text-warning";
    $subtext = "Ready to start the day? Here is your system overview.";
} elseif ($hour >= 12 && $hour < 18) {
    // 12:00 PM to 5:59 PM - Udto/Hapon (Afternoon)
    $greeting = "Good Afternoon";
    $icon = "bi-brightness-high-fill text-info";
    $subtext = "Keep up the momentum! You're doing great today.";
} elseif ($hour >= 18 && $hour < 21) {
    // 6:00 PM to 8:59 PM - Gabii (Evening)
    $greeting = "Good Evening";
    $icon = "bi-moon-stars-fill text-primary";
    $subtext = "Wrapping up the day? Check the latest user updates.";
} else {
    // 9:00 PM to 4:59 AM - Gabii/Lawom Gabii (Night)
    $greeting = "Good Night";
    $icon = "bi-moon-fill text-secondary";
    $subtext = "The system is running smoothly while you rest.";
}

// Fetch Users
try {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll(PDO::FETCH_OBJ); 
    $total_users = count($users);
} catch (PDOException $e) {
    $users = [];
    $total_users = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-accent: #4361ee;
        }

        body { 
            background: #f0f2f5; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: #2b2d42;
        }

        .admin-wrapper {
            margin-left: var(--sidebar-width);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 30px;
            padding-top: 100px; /* Added space for the fixed navbar */
        }

        .admin-wrapper.collapsed-active { margin-left: 75px; }

        /* Greeting Section */
        .welcome-section {
            background: linear-gradient(135deg, #1e1e2f 0%, #2d3436 100%);
            color: white;
            padding: 2.9rem;
            border-radius: 24px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        /* Stat Card */
        .stat-card {
            background: white;
            border: none;
            border-radius: 18px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            width: fit-content;
            min-width: 250px;
        }
        .stat-icon {
            width: 54px; height: 54px;
            border-radius: 14px;
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary-accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; margin-right: 15px;
        }

        /* Table Card */
        .card-premium {
            border: none;
            border-radius: 24px;
            background: white;
            box-shadow: 0 10px 40px rgba(0,0,0,0.04);
            overflow: hidden;
        }

        .card-header-premium {
            background: #fff;
            border-bottom: 1px solid #f0f0f0;
            padding: 1.8rem;
        }

        .table-custom thead { background: #f8f9fa; }
        .table-custom th {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1.2px;
            color: #8d99ae;
            border: none;
            padding: 15px 20px;
        }

        .user-avatar {
            width: 45px; height: 45px;
            border-radius: 12px;
            object-fit: cover;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }

        .status-online {
            width: 10px; height: 10px;
            background: #10b981;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }

        .btn-view {
            background: #f0f3ff;
            color: var(--primary-accent);
            border: none;
            border-radius: 10px;
            padding: 8px 16px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-view:hover { background: var(--primary-accent); color: white; transform: translateY(-2px); }

        @media (max-width: 768px) { .admin-wrapper { margin-left: 0 !important; } }
    </style>
</head>
<body>

<?php include 'navbar_admin.php'; ?>

<div class="admin-wrapper" id="adminWrapper">
    
    <div class="welcome-section d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <h1 class="fw-bold mb-1"><i class="bi <?php echo $icon; ?> me-2"></i> <?php echo $greeting; ?>, Admin!</h1>
            <p class="mb-0 opacity-75 fs-5"><?php echo $subtext; ?></p>
        </div>
        <div class="stat-card shadow-sm">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div>
                <h6 class="text-muted mb-0 small uppercase fw-bold">Active Directory</h6>
                <h3 class="fw-bold mb-0 text-dark"><?php echo $total_users; ?> <span class="fs-6 fw-normal text-muted">Users</span></h3>
            </div>
        </div>
    </div>

    <div class="card card-premium mt-4">
        <div class="card-header-premium d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-0 text-dark">User Management Registry</h4>
                <p class="text-muted small mb-0">Monitor and manage all registered system users</p>
            </div>
            <div class="badge bg-success-subtle text-success border border-success-subtle p-2 px-4 rounded-pill">
                <span class="status-online"></span> System Online
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-custom mb-0 align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4">Profile & Full Name</th>
                            <th>ID Number</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($users)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">No users found in the database.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($users as $row): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center py-2">
                                        <?php 
                                            $img_file = "uploads/profiles/" . $row->profile_pic;
                                            $img_path = (!empty($row->profile_pic) && file_exists($img_file)) ? $img_file : "https://ui-avatars.com/api/?name=".urlencode($row->first_name." ".$row->last_name)."&background=4361ee&color=fff&bold=true";
                                        ?>
                                        <img src="<?php echo $img_path; ?>" class="user-avatar me-3">
                                        <div>
                                            <div class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($row->first_name . " " . $row->last_name); ?></div>
                                            <small class="text-muted">@<?php echo htmlspecialchars($row->nickname ?: 'no-alias'); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border fw-bold px-3 py-2" style="font-family: monospace;">
                                        <?php echo htmlspecialchars($row->id_number); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-success-subtle text-success px-3">
                                        Active
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    <?php echo date('M d, Y', strtotime($row->created_at)); ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="view.php?id=<?php echo $row->id; ?>" class="btn btn-view me-2">
                                            <i class="bi bi-person-badge me-2"></i>Profile
                                        </a>
                                        <a href="./process/delete.php?id=<?php echo $row->id; ?>" class="btn btn-outline-danger border-0 btn-sm" onclick="return confirm('Are you sure you want to permanently delete this user?')">
                                            <i class="bi bi-trash3"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
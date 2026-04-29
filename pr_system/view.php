<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'db.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: admin_table_view.php");
    exit();
}

$id = $_GET['id'];

// Fetch User Data
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$user) {
        echo "User not found.";
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile - <?php echo htmlspecialchars($user->first_name); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --admin-green: #198754;
        }
        body { background-color: #f4f7f6; font-family: 'Inter', sans-serif; }
        
        .admin-wrapper {
            margin-left: var(--sidebar-width);
            padding: 100px 30px 30px 30px;
            transition: all 0.3s ease;
        }
        .admin-wrapper.collapsed-active { margin-left: 75px; }

        .profile-card {
            background: white;
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .profile-header-bg {
            background: linear-gradient(135deg, #1a1d20 0%, #198754 100%);
            height: 120px;
        }
        .profile-avatar-container {
            margin-top: -60px;
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-avatar-view {
            width: 130px;
            height: 130px;
            border-radius: 30px;
            border: 5px solid white;
            object-fit: cover;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .info-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            color: #8d99ae;
            margin-bottom: 4px;
        }
        .info-value {
            font-weight: 600;
            color: #2b2d42;
            font-size: 1.05rem;
        }
        .detail-group {
            padding: 15px;
            border-radius: 12px;
            background: #f8f9fa;
            border: 1px solid #eee;
            height: 100%;
        }
    </style>
</head>
<body>

<?php include 'navbar_admin.php'; ?>

<div class="admin-wrapper" id="adminWrapper">
    <div class="container-fluid">
        <div class="mb-4">
            <a href="admin_table_view.php" class="btn btn-light shadow-sm border px-3 py-2">
                <i class="bi bi-arrow-left me-2"></i> Back to Directory
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="profile-card">
                    <div class="profile-header-bg"></div>
                    
                    <div class="profile-avatar-container">
                        <?php 
                            $img_file = "uploads/profiles/" . $user->profile_pic;
                            $img_path = (!empty($user->profile_pic) && file_exists($img_file)) ? $img_file : "https://ui-avatars.com/api/?name=".urlencode($user->first_name." ".$user->last_name)."&background=198754&color=fff&size=128";
                        ?>
                        <img src="<?php echo $img_path; ?>" class="profile-avatar-view" alt="Profile">
                        <h3 class="fw-bold mt-3 mb-0"><?php echo htmlspecialchars($user->first_name . " " . $user->last_name); ?></h3>
                        <span class="badge bg-success-subtle text-success px-3 rounded-pill">Registered Personnel</span>
                    </div>

                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="detail-group">
                                    <div class="info-label">Identification Number</div>
                                    <div class="info-value text-success"><?php echo htmlspecialchars($user->id_number); ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-group">
                                    <div class="info-label">Nickname / Alias</div>
                                    <div class="info-value"><?php echo htmlspecialchars($user->nickname ?: 'Not set'); ?></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-label">First Name</div>
                                <div class="info-value"><?php echo htmlspecialchars($user->first_name); ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Last Name</div>
                                <div class="info-value"><?php echo htmlspecialchars($user->last_name); ?></div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-label">Middle Initial</div>
                                <div class="info-value"><?php echo htmlspecialchars($user->middle_initial ?: 'N/A'); ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Account Created</div>
                                <div class="info-value"><i class="bi bi-calendar-event me-2"></i><?php echo date('F d, Y', strtotime($user->created_at)); ?></div>
                            </div>

                            <div class="col-12"><hr class="my-2 opacity-50"></div>

                            <div class="col-md-12 text-center">
                                <div class="info-label">System Status</div>
                                <div class="info-value"><i class="bi bi-check-circle-fill me-2 text-success"></i>Verified Profile</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-light p-3 text-center border-top">
                        <small class="text-muted">Procurement Data System &bull; Confidential Record</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
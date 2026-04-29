<?php 
// Start session to access logged-in user data
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * DATABASE CONNECTION
 */
include 'db.php'; 

// Fetch all users for the table
try {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll(PDO::FETCH_OBJ); 
    $total_users = count($users);
} catch (PDOException $e) {
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_OBJ);
    $total_users = count($users);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - PDS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        
        /* Main Content Wrapper */
        .admin-wrapper {
            margin-left: 260px; /* Original Sidebar Width */
            transition: all 0.3s ease;
            padding: 20px;
        }

        /* FIX: Kapag naka-collapsed, 75px ang ititira nating space para sa icons */
        .admin-wrapper.collapsed-active {
            margin-left: 75px; 
        }

        @media (max-width: 768px) {
            .admin-wrapper { margin-left: 0 !important; }
        }

        .card-premium {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            background: #fff;
        }
        .card-header-dark {
            background: #1a1d20;
            color: #fff;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.2rem;
        }
        .profile-upload-preview {
            width: 100px; height: 100px;
            border-radius: 50%; object-fit: cover;
            border: 3px solid #198754; margin-bottom: 10px;
        }
        .table-custom thead { background-color: #1a1d20; color: white; }
        .btn-action { padding: 5px 10px; font-size: 0.85rem; border-radius: 6px; }
        .status-badge { background: #198754; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; }
    </style>
</head>
<body>

<?php include 'navbar_admin.php'; ?>

<div class="admin-wrapper" id="adminWrapper">
    <div class="container-fluid py-5 px-4">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card card-premium">
                    <div class="card-header-dark">
                        <h5 class="mb-0"><i class="bi bi-person-plus me-2"></i>Register New User</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="process/save.php" method="POST" enctype="multipart/form-data">
                            <div class="text-center mb-4">
                                <img src="https://ui-avatars.com/api/?name=User&background=198754&color=fff" id="preview" class="profile-upload-preview">
                                <input type="file" name="profile_picture" class="form-control form-control-sm" onchange="previewImage(event)" accept="image/*">
                                <small class="text-muted">Upload Profile Picture</small>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-5">
                                    <label class="small fw-bold">First Name</label>
                                    <input type="text" name="first_name" class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="small fw-bold">M.I.</label>
                                    <input type="text" name="middle_initial" class="form-control" maxlength="1">
                                </div>
                                <div class="col-md-5">
                                    <label class="small fw-bold">Last Name</label>
                                    <input type="text" name="last_name" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="small fw-bold">Nickname</label>
                                    <input type="text" name="nickname" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="small fw-bold">ID Number</label>
                                    <input type="text" name="id_number" class="form-control" placeholder="XX-XXXX" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold">Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold">Confirm Password</label>
                                    <input type="password" name="confirm_password" class="form-control" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-100 mt-4 py-2 fw-bold">
                                <i class="bi bi-save me-2"></i>SAVE USER
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card card-premium">
                    <div class="card-header-dark d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-people me-2"></i>User Registry Table List</h5>
                        <span class="status-badge">Total Users: <?php echo $total_users; ?></span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-custom mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Full Name</th>
                                        <th>Nickname</th>
                                        <th>ID Number</th>
                                        <th>Password</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($users as $row): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <?php 
                                                    $img_file = "uploads/profiles/" . $row->profile_pic;
                                                    $img_path = (!empty($row->profile_pic) && file_exists($img_file)) ? $img_file : "https://ui-avatars.com/api/?name=".urlencode($row->first_name." ".$row->last_name)."&background=198754&color=fff";
                                                ?>
                                                <img src="<?php echo $img_path; ?>" class="rounded-circle me-2" width="35" height="35" style="object-fit: cover; border: 1px solid #ddd;">
                                                <span><?php echo htmlspecialchars($row->first_name . " " . ($row->middle_initial ? $row->middle_initial . ". " : "") . $row->last_name); ?></span>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($row->nickname ?: '---'); ?></td>
                                        <td><span class="fw-bold"><?php echo htmlspecialchars($row->id_number); ?></span></td>
                                        <td><code class="text-muted">********</code></td>
                                        <td class="text-center">
                                            <a href="edit.php?id=<?php echo $row->id; ?>" class="btn btn-warning btn-action text-dark">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="./process/delete.php?id=<?php echo $row->id; ?>" class="btn btn-danger btn-action" onclick="return confirm('Are you sure you want to delete this user?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // In-page function to sync with navbar toggle
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const topNavbar = document.getElementById('topNavbar');
        const adminWrapper = document.getElementById('adminWrapper');
        
        sidebar.classList.toggle('collapsed');
        topNavbar.classList.toggle('full-width');
        
        // Ito ang mag-aadjust ng margin para hindi matakpan ang icons
        adminWrapper.classList.toggle('collapsed-active');

        if (window.innerWidth <= 768) {
            sidebar.classList.toggle('active');
        }
    }

    // Image preview logic
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('preview');
            output.src = reader.result;
        }
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
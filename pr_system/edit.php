<?php 
include 'db.php'; 

// 1. Get User ID from URL
if (!isset($_GET['id'])) {
    header("Location: register.php");
    exit();
}

$id = $_GET['id'];

// 2. Fetch Current User Data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_OBJ); 

if (!$user) {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - PDS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        
        /* Main Content Wrapper to sync with Navbar */
        .admin-wrapper {
            margin-left: 260px; /* Default sidebar width */
            transition: all 0.3s ease;
            min-height: 100vh;
            display: flex;
            align-items: center; /* Vertical Center */
            justify-content: center; /* Horizontal Center */
            padding: 60px 30px;
        }

        /* Kapag naka-collapsed ang sidebar sa navbar_admin.php */
        .admin-wrapper.collapsed-active {
            margin-left: 75px; 
        }

        @media (max-width: 768px) {
            .admin-wrapper { margin-left: 0 !important; padding-top: 80px; }
        }

        .card-premium {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            background: #fff;
            width: 100%;
            max-width: 600px; /* Pinapanatili ang size ng form */
        }
        .card-header-dark {
            background: #1a1d20;
            color: #fff;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.2rem;
        }
        .profile-upload-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #198754;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<?php include 'navbar_admin.php'; ?>

<div class="admin-wrapper" id="adminWrapper">
    <div class="card card-premium">
        <div class="card-header-dark d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Update User Information</h5>
            <a href="register.php" class="btn btn-sm btn-outline-light">Back to List</a>
        </div>
        <div class="card-body p-4">
            <form action="process/save.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
                <input type="hidden" name="from_table" value="1">
                
                <div class="text-center mb-4">
                    <?php 
                        $img_path = (!empty($user->profile_pic) && file_exists("uploads/".$user->profile_pic)) 
                                    ? "uploads/".$user->profile_pic 
                                    : "https://ui-avatars.com/api/?name=".urlencode($user->first_name." ".$user->last_name)."&background=198754&color=fff";
                    ?>
                    <img src="<?php echo $img_path; ?>" id="preview" class="profile-upload-preview">
                    <input type="file" name="profile_pic" class="form-control form-control-sm mx-auto" style="max-width: 250px;" onchange="previewImage(event)" accept="image/*">
                    <small class="text-muted d-block mt-1">Leave blank to keep current picture</small>
                </div>

                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="small fw-bold">First Name</label>
                        <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($user->first_name); ?>" required>
                    </div>
                    <div class="col-md-2">
                        <label class="small fw-bold">M.I.</label>
                        <input type="text" name="middle_initial" class="form-control" maxlength="1" value="<?php echo htmlspecialchars($user->middle_initial); ?>">
                    </div>
                    <div class="col-md-5">
                        <label class="small fw-bold">Last Name</label>
                        <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($user->last_name); ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="small fw-bold">Nickname</label>
                        <input type="text" name="nickname" class="form-control" value="<?php echo htmlspecialchars($user->nickname); ?>">
                    </div>
                    <div class="col-12">
                        <label class="small fw-bold">ID Number</label>
                        <input type="text" name="id_number" class="form-control" value="<?php echo htmlspecialchars($user->id_number); ?>" required>
                    </div>
                    
                    <div class="col-12 mt-4">
                        <div class="alert alert-info py-2" style="font-size: 0.85rem;">
                            <i class="bi bi-info-circle me-2"></i>Leave password fields blank if you don't want to change it.
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="small fw-bold">New Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Optional">
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Optional">
                    </div>
                </div>

                <div class="row g-2 mt-4">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                            <i class="bi bi-check-circle me-2"></i>UPDATE CHANGES
                        </button>
                    </div>
                    <div class="col-md-4">
                        <a href="register.php" class="btn btn-light w-100 py-2 border">CANCEL</a>
                    </div>
                </div>
            </form>
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
        
        // Adjust margin for the centered content
        if(adminWrapper) {
            adminWrapper.classList.toggle('collapsed-active');
        }

        if (window.innerWidth <= 768) {
            sidebar.classList.toggle('active');
        }
    }

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
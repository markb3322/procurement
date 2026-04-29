<?php 
include 'db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// --- DYNAMIC ID CONNECTION ---
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$userId = $_SESSION['user_id']; 
// -----------------------------

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);

// FIX: Added PDO::FETCH_ASSOC to ensure $user is an array, not an object
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fallback if record is somehow missing
if (!$user) {
    $user = [
        'profile_pic' => '',
        'first_name' => 'Not',
        'middle_initial' => 'F',
        'last_name' => 'Found',
        'nickname' => '',
        'id_number' => '0000'
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f0f2f5; font-family: 'Inter', sans-serif; }
        .profile-card { border: none; border-radius: 20px; background: #fff; overflow: hidden; }
        .profile-header { background: linear-gradient(45deg, #198754, #1a1d20); height: 100px; }
        .avatar-container { margin-top: -60px; position: relative; }
        .profile-img-preview { 
            width: 120px; height: 120px; object-fit: cover; 
            border-radius: 50%; border: 5px solid #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.1); 
        }
        .form-label { font-weight: 600; font-size: 0.85rem; color: #555; }
        .form-control:focus { box-shadow: none; border-color: #198754; }
        .btn-save { background: #198754; border: none; padding: 12px; font-weight: 600; border-radius: 10px; transition: 0.3s; }
        .btn-save:hover { background: #146c43; transform: translateY(-2px); }
        /* Style for the new Cancel button */
        .btn-cancel { padding: 12px; font-weight: 600; border-radius: 10px; transition: 0.3s; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card profile-card shadow-lg">
                <div class="profile-header"></div>
                <div class="card-body p-4 pt-0">
                    <form action="./process/save.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="user_id" value="<?php echo $userId; ?>">

                        <div class="text-center avatar-container mb-4">
                            <?php 
                                // Pointing to the central profiles folder
                                $img_file = "uploads/profiles/" . $user['profile_pic'];
                                if (!empty($user['profile_pic']) && file_exists($img_file)) {
                                    $display_pic = $img_file;
                                } else {
                                    $display_pic = "https://ui-avatars.com/api/?name=".urlencode($user['first_name'])."&background=198754&color=fff";
                                }
                            ?>
                            <img src="<?php echo $display_pic; ?>" id="preview" class="profile-img-preview mb-2">
                            <div>
                                <label for="pfp" class="btn btn-sm btn-light shadow-sm border">
                                    <i class="bi bi-camera"></i> Change Photo
                                </label>
                                <input type="file" name="profile_pic" id="pfp" hidden onchange="previewImage(event)" accept="image/*">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label class="form-label">FIRST NAME</label>
                                <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($user['first_name']); ?>" readonly>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">M.I.</label>
                                <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($user['middle_initial']); ?>" readonly>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="form-label">LAST NAME</label>
                                <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($user['last_name']); ?>" readonly>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">NICKNAME</label>
                            <input type="text" name="nickname" class="form-control form-control-lg" 
                                   value="<?php echo htmlspecialchars($user['nickname']); ?>" placeholder="How should we call you?">
                        </div>

                        <div class="row g-2">
                            <div class="col-8">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-save text-white">Update Profile</button>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-grid">
                                    <a href="index.php" class="btn btn-outline-secondary btn-cancel">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <p class="text-center mt-3 text-muted small">ID Number: <?php echo htmlspecialchars($user['id_number']); ?></p>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview');
            output.src = reader.result;
        }
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
</body>
</html>
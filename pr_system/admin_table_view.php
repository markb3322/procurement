<?php 
include 'db.php'; 

if (session_status() === PHP_SESSION_NONE) { session_start(); }
date_default_timezone_set('Asia/Manila');

// --- SECTION 1: USER DATA LOGIC ---
$search = $_GET['search'] ?? '';
$whereClause = "";
if (!empty($search)) {
    $whereClause = "WHERE first_name LIKE :search OR last_name LIKE :search OR id_number LIKE :search OR nickname LIKE :search";
}

try {
    $query = "SELECT * FROM users $whereClause ORDER BY created_at DESC";
    $stmt = $pdo->prepare($query);
    if (!empty($search)) {
        $stmt->bindValue(':search', '%' . $search . '%');
    }
    $stmt->execute();
    $all_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// --- SECTION 2: PDS DATA LOGIC (Restored) ---
$category = $_GET['cat'] ?? 'catering';
$table_map = [
    'catering'        => 'catering_records',
    'office_supplies' => 'office_supplies',
    'ict_devices'     => 'ict_devices',
    'furnitures'      => 'furnitures',
    'fabrication'     => 'fabrication_installation',
    'heavy_equipment' => 'heavy_equipment',
    'appliances'      => 'appliances', 
    'fixtures'        => 'fixtures'    
];
$current_pds_table = $table_map[$category] ?? 'catering_records';

try {
    $date_col = ($current_pds_table == 'catering_records') ? 'catering_date' : 'transaction_date';
    $pds_query = "SELECT *, $date_col AS display_date FROM $current_pds_table ORDER BY $date_col DESC";
    $pds_stmt = $pdo->query($pds_query);
    $pds_records = $pds_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $pds_records = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PDS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; font-family: 'Inter', sans-serif; }
        .admin-wrapper { margin-left: 260px; transition: all 0.3s; padding: 30px; min-height: 100vh; padding-top: 100px; }
        .admin-wrapper.collapsed-active { margin-left: 85px; }
        @media (max-width: 768px) { .admin-wrapper { margin-left: 0 !important; padding: 20px; padding-top: 80px; } }
        
        .table-card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); background: #fff; margin-bottom: 40px; }
        .user-avatar { width: 40px; height: 40px; object-fit: cover; border-radius: 50%; border: 2px solid #eee; }
        .sub-search-bar { border-radius: 10px; height: 45px; border: 1px solid #dee2e6; width: 100%; max-width: 350px; padding: 0 15px; }

        .pds-tabs { display: flex; gap: 8px; overflow-x: auto; padding-bottom: 10px; margin-bottom: 15px; }
        .pds-tab { white-space: nowrap; padding: 6px 15px; border-radius: 20px; text-decoration: none; color: #666; background: #eee; font-size: 0.75rem; font-weight: 700; transition: 0.2s; }
        .pds-tab.active { background: #198754; color: #fff; }
    </style>
</head>
<body>

<?php include 'navbar_admin.php'; ?>

<div class="admin-wrapper" id="adminWrapper">
    <div class="container-fluid">
        
        <div class="mb-3 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="bi bi-people text-success fs-3 me-2"></i>
                <h3 class="fw-bold mb-0">Registered Users</h3>
            </div>
            
            <form action="" method="GET" class="d-flex align-items-center gap-2">
                <input type="text" name="search" class="form-control sub-search-bar" placeholder="Search users..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-success" style="border-radius: 10px;"><i class="bi bi-search"></i></button>
            </form>
        </div>

        <div class="card table-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Profile</th>
                                <th>Full Name</th>
                                <th>ID Number</th>
                                <th>Nickname</th>
                                <th>Date Registered</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($all_users)): ?>
                                <?php foreach ($all_users as $user): ?>
                                <tr>
                                    <td class="ps-4">
                                        <?php 
                                            $fileName = trim($user['profile_pic'] ?? '');
                                            $uploadPath = "uploads/profiles/" . $fileName;
                                            $displayPic = (!empty($fileName) && $fileName !== 'default.png' && file_exists($uploadPath)) ? $uploadPath : "https://ui-avatars.com/api/?name=".urlencode($user['first_name'])."&background=198754&color=fff";
                                        ?>
                                        <img src="<?php echo $displayPic; ?>?t=<?php echo time(); ?>" class="user-avatar" alt="User">
                                    </td>
                                    <td><div class="fw-semibold"><?php echo htmlspecialchars($user['first_name'] . " " . $user['last_name']); ?></div></td>
                                    <td><code class="text-primary fw-bold"><?php echo htmlspecialchars($user['id_number']); ?></code></td>
                                    <td><?php echo htmlspecialchars($user['nickname'] ?? '-'); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                    <td><span class="badge bg-success-subtle text-success">Active</span></td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete(<?php echo $user['id']; ?>)"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="7" class="text-center py-4 text-muted">No users found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <hr class="my-5">

        <div class="mb-3">
            <div class="d-flex align-items-center mb-3">
                <i class="bi bi-archive text-primary fs-3 me-2"></i>
                <h3 class="fw-bold mb-0">Procurement Records</h3>
            </div>

            <div class="pds-tabs">
                <?php foreach($table_map as $key => $val): ?>
                    <a href="?cat=<?php echo $key; ?>&search=<?php echo $search; ?>" class="pds-tab <?php echo ($category == $key) ? 'active' : ''; ?>">
                        <?php echo strtoupper(str_replace('_', ' ', $key)); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="card table-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-4">Title / Project</th>
                                <th>PR No.</th>
                                <th>PO No.</th>
                                <th>Suppliers</th>
                                <th>Date</th>
                                <th class="pe-4">Total Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pds_records)): ?>
                                <?php foreach ($pds_records as $row): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['title']); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($row['quarter']); ?> Quarter</small>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($row['pr_no']); ?></span></td>
                                    <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($row['po_no']); ?></span></td>
                                    <td><?php echo htmlspecialchars($row['suppliers']); ?></td>
                                    <td><?php echo (!empty($row['display_date'])) ? date('M d, Y', strtotime($row['display_date'])) : '-'; ?></td>
                                    <td class="fw-bold text-success pe-4">
                                        ₱<?php echo number_format((float)($row['total_cost'] ?? 0), 2); ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="text-center py-5 text-muted">No procurement data found in <?php echo $category; ?>.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function confirmDelete(id) {
        if(confirm("Are you sure you want to delete this user?")) {
            window.location.href = "./process/delete.php?id=" + id;
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
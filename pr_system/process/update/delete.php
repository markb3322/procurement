<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Pathing to db.php - looking in root
if (file_exists('../../db.php')) {
    include '../../db.php';
} else {
    die("Error: db.php not found.");
}

$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$category = isset($_GET['cat']) ? $_GET['cat'] : null;

// The "Authorization List" - including both your URL keys and SQL table names
$allowed = [
    'catering', 'catering_records',
    'office_supplies',
    'ict_devices',
    'furnitures',
    'fabrication', 'fabrication_installation',
    'heavy_equipment',
    'appliances',
    'fixtures'
];

if ($id && in_array($category, $allowed)) {
    // Map short category names to real table names for the SQL query
    $table_map = [
        'catering'    => 'catering_records',
        'fabrication' => 'fabrication_installation'
    ];
    $actual_table = $table_map[$category] ?? $category;

    try {
        $stmt = $pdo->prepare("DELETE FROM `$actual_table` WHERE id = ?");
        $result = $stmt->execute([$id]);

        if ($result) {
            $_SESSION['msg'] = "Record successfully deleted.";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['msg'] = "Record not found.";
            $_SESSION['msg_type'] = "danger";
        }
    } catch (PDOException $e) {
        $_SESSION['msg'] = "Database Error: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
    }
} else {
    $_SESSION['msg'] = "Invalid Request: Table '$category' or ID missing.";
    $_SESSION['msg_type'] = "warning";
}

// Redirect back to table.php (two levels up from process/update/)
header("Location: ../../table.php?cat=" . $category);
exit();
?>
<?php
// 1. Start session and include database
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 2. Collect and sanitize input
    $first_name     = trim($_POST['first_name']);
    $middle_initial = strtoupper(trim($_POST['middle_initial']));
    $last_name      = trim($_POST['last_name']);
    $nickname       = trim($_POST['nickname']);
    $id_number      = trim($_POST['id_number']);
    $password       = $_POST['password'];

    // 3. Basic Validation
    if (empty($id_number) || empty($password) || empty($first_name)) {
        header("Location: ../register.php?error=empty_fields");
        exit();
    }

    try {
        // 4. Check if ID Number already exists (Prevent Duplicates)
        $checkStmt = $pdo->prepare("SELECT id FROM users WHERE id_number = ?");
        $checkStmt->execute([$id_number]);
        
        if ($checkStmt->rowCount() > 0) {
            header("Location: ../register.php?error=id_exists");
            exit();
        }

        // 5. Hash the password (Security Best Practice)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // 6. Insert New User
        // Note: profile_pic defaults to 'default.png' based on your SQL schema
        $sql = "INSERT INTO users (first_name, middle_initial, last_name, nickname, id_number, password, profile_pic) 
                VALUES (?, ?, ?, ?, ?, ?, 'default.png')";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $first_name, 
            $middle_initial, 
            $last_name, 
            $nickname, 
            $id_number, 
            $hashedPassword
        ]);

        // 7. Success! Redirect to login or profile
        header("Location: ../login.php?registration=success");
        exit();

    } catch (PDOException $e) {
        // Handle database errors
        die("Error creating account: " . $e->getMessage());
    }
} else {
    // If someone tries to access this file directly without POST
    header("Location: register.php");
    exit();
}
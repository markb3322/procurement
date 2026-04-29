<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_number = trim($_POST['id_number']);
    $password  = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id_number = :id LIMIT 1");
        $stmt->execute([':id' => $id_number]);
        $user = $stmt->fetch(PDO::FETCH_OBJ); // Fetch as object to match your code

        if ($user && password_verify($password, $user->password)) {
            // --- ADDED THIS LINE ---
            $_SESSION['user_id']     = $user->id; 
            // -----------------------
            $_SESSION['first_name']  = $user->first_name;
            $_SESSION['nickname']    = $user->nickname;
            $_SESSION['last_name']   = $user->last_name;
            $_SESSION['profile_pic'] = $user->profile_pic;
            
            header("Location: index.php");
            exit();
        } else {
            header("Location: login.php?error=1");
            exit();
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
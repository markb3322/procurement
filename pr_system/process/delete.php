<?php
session_start();
// File is in process/delete.php, so one level up to find db.php
include '../db.php'; 

// Get ID from URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    try {
        // 1. Get the filename first to delete the actual image file
        $stmt_img = $pdo->prepare("SELECT profile_pic FROM users WHERE id = ?");
        $stmt_img->execute([$id]);
        $user = $stmt_img->fetch(PDO::FETCH_OBJ);

        if ($user && $user->profile_pic != 'default.png') {
            // Path to profile images relative to the process/ folder
            $file_path = '../uploads/profiles/' . $user->profile_pic;
            if (file_exists($file_path)) { 
                unlink($file_path); // Deletes the physical file from the server
            }
        }

        // 2. Delete User from Database
        // This removes the entire row for that ID
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$id])) {
            // Redirect changed to register.php as requested
            echo "<script>alert('User Deleted Successfully!'); window.location.href='../register.php';</script>";
            exit();
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Default redirect to register if no ID is found in the URL
    header("Location: ../register.php");
}
?>
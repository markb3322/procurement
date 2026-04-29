<?php
/**
 * PDS Database Connection Brain
 * Built for XAMPP / Localhost environments
 */

$host     = 'localhost';
$dbname   = 'procurement_db'; 
$username = 'root';           
$password = '';               

try {
    // 1. Create a PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // 2. Set Error Mode to Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    /**
     * BRIDGE FOR SAVE SCRIPTS
     * Your save scripts use MySQLi syntax ($conn). 
     * This line creates that connection so your scripts don't crash.
     */
    $conn = new mysqli($host, $username, $password, $dbname);

} catch (PDOException $e) {
    die("
    <div style='font-family: Arial, sans-serif; padding: 25px; background: #fff5f5; border: 2px solid #ffcccc; color: #cc0000; border-radius: 12px; margin: 30px auto; max-width: 600px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);'>
        <h3 style='margin-top: 0; color: #b30000;'>⚠️ Database Connection Failed</h3>
        <p style='font-size: 0.95rem; line-height: 1.5;'>
            <strong>Error Details:</strong> " . htmlspecialchars($e->getMessage()) . "
        </p>
        <hr style='border: 0; border-top: 1px solid #ffcccc; margin: 15px 0;'>
        <p style='font-size: 0.85rem; color: #666;'>
            Please check if <strong>XAMPP (MySQL)</strong> is running and if the database <strong>'$dbname'</strong> exists.
        </p>
    </div>
    ");
}
?>
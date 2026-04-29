<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// 1. Include the database connection
// Assuming db.php is in the root and this file is in 'process/'
require_once '../db.php';

// 2. Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // General Info & Specs
    $transaction_date = $_POST['transaction_date'] ?? date('Y-m-d');
    $title            = $_POST['title'] ?? '';
    $items            = $_POST['items'] ?? '';
    $quarter          = $_POST['quarter'] ?? '';
    $brand            = $_POST['brand'] ?? '';
    $specs            = $_POST['specs'] ?? ''; 
    $qty              = (int)($_POST['qty'] ?? 0);
    $unit             = $_POST['unit'] ?? '';
    $unit_cost        = (float)($_POST['unit_cost'] ?? 0);
    $suppliers        = $_POST['suppliers'] ?? '';
    $payment_mode     = $_POST['payment_mode'] ?? '';
    $remarks          = $_POST['remarks'] ?? '';

    // Auto-calculate Total Cost
    $total_cost = $_POST['total_cost'];
    if (empty($total_cost) || $total_cost == 0) {
        $total_cost = number_format($qty * $unit_cost, 2, '.', '');
    }

    // Tracking Numbers
    $pr_no         = $_POST['pr_no'] ?? '';
    $nc_no         = $_POST['nc_no'] ?? '';
    $po_no         = $_POST['po_no'] ?? '';
    $padmo_no      = $_POST['padmo_no'] ?? '';
    $go_finance_no = $_POST['go_finance_no'] ?? '';

    // Checklist Mapping (Statuses and Remarks)
    $s = $_POST['status'] ?? []; 
    $r = $_POST['doc_remarks'] ?? [];

    try {
        $sql = "INSERT INTO ict_devices (
                    transaction_date, title, items, quarter, brand, specs, qty, unit, 
                    unit_cost, suppliers, total_cost, payment_mode, remarks, 
                    pr_no, nc_no, po_no, padmo_no, go_finance_no,
                    status_pr, remarks_pr, status_abc, remarks_abc, 
                    status_ppmp, remarks_ppmp, status_act_des, remarks_act_des, 
                    status_iar_are, remarks_iar_are, status_pdrs, remarks_pdrs, 
                    status_app, remarks_app, status_letter, remarks_letter, 
                    status_obr, remarks_obr
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Using $pdo from your db.php
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            $transaction_date, $title, $items, $quarter, $brand, $specs, $qty, $unit,
            $unit_cost, $suppliers, $total_cost, $payment_mode, $remarks,
            $pr_no, $nc_no, $po_no, $padmo_no, $go_finance_no,
            $s[0] ?? 'Not Complete', $r[0] ?? '', 
            $s[1] ?? 'Not Complete', $r[1] ?? '', 
            $s[2] ?? 'Not Complete', $r[2] ?? '', 
            $s[3] ?? 'Not Complete', $r[3] ?? '', 
            $s[4] ?? 'Not Complete', $r[4] ?? '', 
            $s[5] ?? 'Not Complete', $r[5] ?? '', 
            $s[6] ?? 'Not Complete', $r[6] ?? '', 
            $s[7] ?? 'Not Complete', $r[7] ?? '', 
            $s[8] ?? 'Not Complete', $r[8] ?? ''
        ]);

        // SUCCESS: Proceeding to table.php
        $_SESSION['msg'] = "ICT Device Added Successfully!";
        $_SESSION['msg_type'] = "success";
        header("Location: ../table.php?status=success&view=ict");
        exit();

    } catch (PDOException $e) {
        // ERROR: Return to previous page or table.php with error message
        $_SESSION['msg'] = "Database Error: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
        header("Location: ../table.php?error=1");
        exit();
    }
}
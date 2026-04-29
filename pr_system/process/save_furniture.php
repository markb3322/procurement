<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// 1. Include the database connection from the root
require_once '../db.php';

// 2. Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // General Info & Specifications
    $transaction_date = $_POST['transaction_date'] ?? date('Y-m-d');
    $title            = $_POST['title'] ?? '';
    $quarter          = $_POST['quarter'] ?? '';
    $items            = $_POST['items'] ?? '';
    $specs            = $_POST['specs'] ?? ''; 
    $qty              = (int)($_POST['qty'] ?? 0);
    $unit             = $_POST['unit'] ?? '';
    $unit_cost        = (float)($_POST['unit_cost'] ?? 0);
    $suppliers        = $_POST['suppliers'] ?? '';
    $payment_mode     = $_POST['payment_mode'] ?? 'Upon Completion';
    $remarks          = $_POST['remarks'] ?? '';

    // Auto-calculate Total Cost if the manual field is empty
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

    // Checklist Mapping (Statuses and Remarks from the form arrays)
    $s = $_POST['status'] ?? []; 
    $r = $_POST['doc_remarks'] ?? [];

    try {
        $sql = "INSERT INTO furnitures (
                    transaction_date, title, quarter, items, specs, qty, unit, 
                    unit_cost, suppliers, total_cost, payment_mode, remarks, 
                    pr_no, nc_no, po_no, padmo_no, go_finance_no,
                    status_pr, remarks_pr, status_abc, remarks_abc, 
                    status_ppmp, remarks_ppmp, status_act_des, remarks_act_des, 
                    status_iar_are, remarks_iar_are, status_pdrs, remarks_pdrs, 
                    status_app, remarks_app, status_letter, remarks_letter, 
                    status_obr, remarks_obr
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Using $pdo from your db.php
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            $transaction_date, $title, $quarter, $items, $specs, $qty, $unit,
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
        $_SESSION['msg'] = "Furniture Record Added Successfully!";
        $_SESSION['msg_type'] = "success";
        header("Location: ../table.php?status=success&view=furnitures");
        exit();

    } catch (PDOException $e) {
        // ERROR: Return to table.php with error details
        $_SESSION['msg'] = "Database Error: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
        header("Location: ../furniture.php?error=1");
        exit();
    }
}
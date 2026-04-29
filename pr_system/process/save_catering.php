<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once '../db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1. Collect General Form Data
        $title         = $_POST['title'] ?? '';
        $catering_date = $_POST['catering_date'] ?? date('Y-m-d'); 
        $quarter       = $_POST['quarter'] ?? '';
        
        // UPDATED: Changed to 'item' to match HTML name="item"
        $menu          = $_POST['item'] ?? '';         
        
        $unit_pax      = $_POST['unit_pax'] ?? '';
        
        // UPDATED: Changed to 'specs' to match HTML name="specs"
        $description   = $_POST['specs'] ?? '';  
        
        $qty           = (int)($_POST['qty'] ?? 0);
        $unit_cost     = (float)($_POST['unit_cost'] ?? 0);
        $suppliers     = $_POST['suppliers'] ?? '';
        $payment_mode  = $_POST['payment_mode'] ?? 'Upon Completion';
        $remarks       = $_POST['remarks'] ?? '';
        
        $pr_no         = $_POST['pr_no'] ?? '';
        $nc_no         = $_POST['nc_no'] ?? '';
        $po_no         = $_POST['po_no'] ?? '';
        $padmo_no      = $_POST['padmo_no'] ?? '';
        $go_finance_no = $_POST['go_finance_no'] ?? '';

        // 2. Handle Checklist Arrays
        $statuses = $_POST['status'] ?? [];
        $rem_list = $_POST['doc_remarks'] ?? [];

        $status_pr       = $statuses[0] ?? 'Not Complete';
        $remarks_pr      = $rem_list[0] ?? '';
        $status_abc      = $statuses[1] ?? 'Not Complete';
        $remarks_abc     = $rem_list[1] ?? '';
        $status_ppmp     = $statuses[2] ?? 'Not Complete';
        $remarks_ppmp    = $rem_list[2] ?? '';
        $status_act_des  = $statuses[3] ?? 'Not Complete';
        $remarks_act_des = $rem_list[3] ?? '';
        $status_iar_are  = $statuses[4] ?? 'Not Complete';
        $remarks_iar_are = $rem_list[4] ?? '';
        $status_pdrs     = $statuses[5] ?? 'Not Complete';
        $remarks_pdrs    = $rem_list[5] ?? '';
        $status_app      = $statuses[6] ?? 'Not Complete';
        $remarks_app     = $rem_list[6] ?? '';
        $status_letter   = $statuses[7] ?? 'Not Complete';
        $remarks_letter  = $rem_list[7] ?? '';
        $status_obr      = $statuses[8] ?? 'Not Complete';
        $remarks_obr     = $rem_list[8] ?? '';

        $total_cost = $qty * $unit_cost;

        // 3. Prepare SQL
        $sql = "INSERT INTO catering_records (
            title, catering_date, quarter, menu, unit_pax, description, qty, unit_cost, total_cost, 
            suppliers, payment_mode, remarks, pr_no, nc_no, po_no, padmo_no, go_finance_no,
            status_pr, remarks_pr, status_abc, remarks_abc, status_ppmp, remarks_ppmp,
            status_act_des, remarks_act_des, status_iar_are, remarks_iar_are, 
            status_pdrs, remarks_pdrs, status_app, remarks_app, status_letter, remarks_letter, 
            status_obr, remarks_obr
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, 
            ?, ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )";

        $stmt = $pdo->prepare($sql);
        
        $params = [
            $title, $catering_date, $quarter, $menu, $unit_pax, $description, $qty, $unit_cost, $total_cost,
            $suppliers, $payment_mode, $remarks, $pr_no, $nc_no, $po_no, $padmo_no, $go_finance_no,
            $status_pr, $remarks_pr, $status_abc, $remarks_abc, $status_ppmp, $remarks_ppmp,
            $status_act_des, $remarks_act_des, $status_iar_are, $remarks_iar_are,
            $status_pdrs, $remarks_pdrs, $status_app, $remarks_app, $status_letter, $remarks_letter,
            $status_obr, $remarks_obr
        ];

        if ($stmt->execute($params)) {
            header("Location: ../table.php?cat=catering&msg=success");
            exit();
        }
    } catch (Exception $e) {
        header("Location: ../table.php?cat=catering&msg=error&details=" . urlencode($e->getMessage()));
        exit();
    }
}
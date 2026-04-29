<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Use absolute path check to ensure db.php is actually found
if (file_exists('../db.php')) {
    include '../db.php';
} else {
    die("Error: db.php not found at ../db.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Check if $conn was actually initialized in db.php
    if (!isset($conn) || $conn === null) {
        die("Error: Database connection variable (\$conn) is null. Check your db.php file.");
    }

    // 1. General Information
    $transaction_date = $_POST['transaction_date'] ?? '';
    $title            = $_POST['title'] ?? '';
    $item             = $_POST['items'] ?? ''; 
    $quarter          = $_POST['quarter'] ?? '';
    $brand            = $_POST['brand'] ?? '';
    $specs            = $_POST['specs'] ?? '';
    $qty              = $_POST['qty'] ?? 0;
    $unit             = $_POST['unit'] ?? '';
    $unit_cost        = $_POST['unit_cost'] ?? 0;
    $suppliers        = $_POST['suppliers'] ?? '';
    $total_cost       = $_POST['total_cost'] ?? '';
    $payment_mode     = $_POST['payment_mode'] ?? 'Upon Completion';
    $remarks          = $_POST['remarks'] ?? '';

    // 2. Tracking Numbers
    $pr_no         = $_POST['pr_no'] ?? '';
    $nc_no         = $_POST['nc_no'] ?? '';
    $po_no         = $_POST['po_no'] ?? '';
    $padmo_no      = $_POST['padmo_no'] ?? '';
    $go_finance_no = $_POST['go_finance_no'] ?? '';

    // 3. Checklist Mapping
    $status      = $_POST['status'] ?? array_fill(0, 9, 'Not Complete');
    $doc_remarks = $_POST['doc_remarks'] ?? array_fill(0, 9, '');

    $s_pr      = $status[0]; $r_pr      = $doc_remarks[0];
    $s_abc     = $status[1]; $r_abc     = $doc_remarks[1];
    $s_ppmp    = $status[2]; $r_ppmp    = $doc_remarks[2];
    $s_act_des = $status[3]; $r_act_des = $doc_remarks[3];
    $s_iar_are = $status[4]; $r_iar_are = $doc_remarks[4];
    $s_pdrs    = $status[5]; $r_pdrs    = $doc_remarks[5];
    $s_app     = $status[6]; $r_app     = $doc_remarks[6];
    $s_letter  = $status[7]; $r_letter  = $doc_remarks[7];
    $s_obr     = $status[8]; $r_obr     = $doc_remarks[8];

    // 4. SQL Preparation (36 Question Marks)
    $sql = "INSERT INTO appliances (
                transaction_date, title, item, quarter, brand, specs, 
                qty, unit, unit_cost, suppliers, total_cost, payment_mode, remarks, 
                pr_no, nc_no, po_no, padmo_no, go_finance_no,
                status_pr, remarks_pr, status_abc, remarks_abc, status_ppmp, remarks_ppmp,
                status_act_des, remarks_act_des, status_iar_are, remarks_iar_are,
                status_pdrs, remarks_pdrs, status_app, remarks_app, 
                status_letter, remarks_letter, status_obr, remarks_obr
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // FIXED: Added one 's' to make it 36 characters to match the 36 variables below
        $stmt->bind_param("ssssssisdsssssssssssssssssssssssssss", 
            $transaction_date, $title, $item, $quarter, $brand, $specs,
            $qty, $unit, $unit_cost, $suppliers, $total_cost, $payment_mode, $remarks,
            $pr_no, $nc_no, $po_no, $padmo_no, $go_finance_no,
            $s_pr, $r_pr, $s_abc, $r_abc, $s_ppmp, $r_ppmp,
            $s_act_des, $r_act_des, $s_iar_are, $r_iar_are,
            $s_pdrs, $r_pdrs, $s_app, $r_app, 
            $s_letter, $r_letter, $s_obr, $r_obr
        );

        if ($stmt->execute()) {
            $_SESSION['msg'] = "Appliance Procurement Record Successfully Saved!";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['msg'] = "Execution Error: " . $stmt->error;
            $_SESSION['msg_type'] = "danger";
        }
        $stmt->close();
    } else {
        $_SESSION['msg'] = "Prepare Error: " . $conn->error;
        $_SESSION['msg_type'] = "danger";
    }

    $conn->close();
    header("Location: ../appliances.php");
    exit();
}
?>
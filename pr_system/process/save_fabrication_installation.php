<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Capture General Specifications & Scope
    $transaction_date = $_POST['transaction_date'];
    $title            = $_POST['title'];
    $item             = $_POST['items']; // Form 'items' -> Table 'item'
    $quarter          = $_POST['quarter'];
    $scope_of_work    = $_POST['scope_of_work'] ?? ''; 
    $specs            = $_POST['specs'];
    $qty              = $_POST['qty'];
    $unit             = $_POST['unit'];
    $unit_cost        = $_POST['unit_cost'];
    $suppliers        = $_POST['suppliers'];
    $total_cost       = $_POST['total_cost'];
    $payment_mode     = $_POST['payment_mode'];
    $remarks          = $_POST['remarks'];

    // 2. Capture Tracking Numbers
    $pr_no         = $_POST['pr_no'];
    $nc_no         = $_POST['nc_no'];
    $po_no         = $_POST['po_no'];
    $padmo_no      = $_POST['padmo_no'];
    $go_finance_no = $_POST['go_finance_no'];

    // 3. Map Checklist Arrays (Index 0-8 based on your form loop)
    $status      = $_POST['status'];      
    $doc_remarks = $_POST['doc_remarks']; 

    $s_pr      = $status[0]; $r_pr      = $doc_remarks[0];
    $s_abc     = $status[1]; $r_abc     = $doc_remarks[1];
    $s_ppmp    = $status[2]; $r_ppmp    = $doc_remarks[2];
    $s_act_des = $status[3]; $r_act_des = $doc_remarks[3];
    $s_iar_are = $status[4]; $r_iar_are = $doc_remarks[4];
    $s_pdrs    = $status[5]; $r_pdrs    = $doc_remarks[5];
    $s_app     = $status[6]; $r_app     = $doc_remarks[6];
    $s_letter  = $status[7]; $r_letter  = $doc_remarks[7];
    $s_obr     = $status[8]; $r_obr     = $doc_remarks[8];

    // 4. Prepared Statement (Verified 36 Columns and 36 Placeholders)
    $sql = "INSERT INTO fabrication_installation (
                transaction_date, title, item, quarter, scope_of_work, specs, 
                qty, unit, unit_cost, suppliers, total_cost, payment_mode, remarks, 
                pr_no, nc_no, po_no, padmo_no, go_finance_no,
                status_pr, remarks_pr, status_abc, remarks_abc, status_ppmp, remarks_ppmp,
                status_act_des, remarks_act_des, status_iar_are, remarks_iar_are,
                status_pdrs, remarks_pdrs, status_app, remarks_app, 
                status_letter, remarks_letter, status_obr, remarks_obr
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    // binding 36 parameters (Corrected types string and variable count)
    $stmt->bind_param("ssssssisdsssssssssssssssssssssssssss", 
        $transaction_date, $title, $item, $quarter, $scope_of_work, $specs,
        $qty, $unit, $unit_cost, $suppliers, $total_cost, $payment_mode, $remarks,
        $pr_no, $nc_no, $po_no, $padmo_no, $go_finance_no,
        $s_pr, $r_pr, $s_abc, $r_abc, $s_ppmp, $r_ppmp,
        $s_act_des, $r_act_des, $s_iar_are, $r_iar_are,
        $s_pdrs, $r_pdrs, $s_app, $r_app, 
        $s_letter, $r_letter, $s_obr, $r_obr
    );

    if ($stmt->execute()) {
        $_SESSION['msg'] = "Fabrication & Installation Record Saved!";
        $_SESSION['msg_type'] = "success";
    } else {
        $_SESSION['msg'] = "Error: " . $stmt->error;
        $_SESSION['msg_type'] = "danger";
    }

    $stmt->close();
    $conn->close();

    header("Location: ../fabrication_installation.php");
    exit();
}
?>
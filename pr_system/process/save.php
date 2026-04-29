<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Identify the type of submission
    $userId = $_POST['user_id'] ?? null;
    $formType = $_POST['form_type'] ?? '';

    // --- CASE 1: PROCUREMENT FORMS (Catering, ICT, etc.) ---
    $procurement_tables = [
        'catering' => 'catering_records',
        'office_supplies' => 'office_supplies',
        'ict_devices' => 'ict_devices',
        'furnitures' => 'furnitures',
        'fabrication' => 'fabrication_installation',
        'heavy_equipment' => 'heavy_equipment',
        'appliances' => 'appliances',
        'fixtures' => 'fixtures'
    ];

    if (array_key_exists($formType, $procurement_tables)) {
        $table = $procurement_tables[$formType];
        
        // Capture Form Fields
        $date = $_POST['transaction_date'] ?? date('Y-m-d');
        $title = $_POST['title'] ?? '';
        $item = $_POST['item'] ?? ($_POST['items'] ?? '');
        $quarter = $_POST['quarter'] ?? '';
        $brand = $_POST['brand'] ?? '';
        $specs = $_POST['specs'] ?? ($_POST['scope_of_work'] ?? '');
        $qty = $_POST['qty'] ?? 0;
        $unit = $_POST['unit'] ?? '';
        $unit_cost = $_POST['unit_cost'] ?? 0;
        $suppliers = $_POST['suppliers'] ?? '';
        $total_cost = $_POST['total_cost'] ?? '';
        $pay_mode = $_POST['payment_mode'] ?? 'Upon Completion';
        $remarks = $_POST['remarks'] ?? '';

        // Tracking Nos
        $pr = $_POST['pr_no'] ?? '';
        $nc = $_POST['nc_no'] ?? '';
        $po = $_POST['po_no'] ?? '';
        $padmo = $_POST['padmo_no'] ?? '';
        $go = $_POST['go_finance_no'] ?? '';

        // Checklist Statuses
        $s = $_POST['status'] ?? array_fill(0, 9, 'Not Complete');

        try {
            $sql = "INSERT INTO $table (
                transaction_date, title, item, quarter, brand, specs, qty, unit, 
                unit_cost, suppliers, total_cost, payment_mode, remarks,
                pr_no, nc_no, po_no, padmo_no, go_finance_no,
                status_pr, status_abc, status_ppmp, status_act_des, 
                status_iar_are, status_pdrs, status_app, status_letter, status_obr
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $date, $title, $item, $quarter, $brand, $specs, $qty, $unit, 
                $unit_cost, $suppliers, $total_cost, $pay_mode, $remarks,
                $pr, $nc, $po, $padmo, $go,
                $s[0], $s[1], $s[2], $s[3], $s[4], $s[5], $s[6], $s[7], $s[8]
            ]);

            header("Location: ../$formType.php?success=saved");
            exit();
        } catch (PDOException $e) {
            die("Procurement Error: " . $e->getMessage());
        }
    }

    // --- CASE 2: USER UPDATE LOGIC (Maintains your original code) ---
    elseif ($userId) {
        $nickname = trim($_POST['nickname'] ?? '');
        $profile_pic = null;
        $file_input = isset($_FILES['profile_pic']) ? 'profile_pic' : 'profile_picture';

        if (isset($_FILES[$file_input]) && $_FILES[$file_input]['error'] == 0) {
            $upload_dir = "../uploads/profiles/"; 
            if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }
            $file_ext = pathinfo($_FILES[$file_input]['name'], PATHINFO_EXTENSION);
            $file_name = time() . "_user" . $userId . "." . $file_ext;
            if (move_uploaded_file($_FILES[$file_input]['tmp_name'], $upload_dir . $file_name)) {
                $profile_pic = $file_name;
            }
        }

        try {
            if ($profile_pic) {
                $sql = "UPDATE users SET nickname = ?, profile_pic = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nickname, $profile_pic, $userId]);
                if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $userId) {
                    $_SESSION['profile_pic'] = $profile_pic; 
                }
            } else {
                $sql = "UPDATE users SET nickname = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nickname, $userId]);
            }
            
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $userId) {
                $_SESSION['nickname'] = $nickname; 
            }

            header("Location: ../admin_dashboard.php?update=success"); 
            exit();
        } catch (PDOException $e) {
            die("Update Error: " . $e->getMessage());
        }
    }

    // --- CASE 3: REGISTRATION LOGIC (Maintains your original code) ---
    else {
        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $mi = $_POST['middle_initial'] ?? '';
        $nickname = $_POST['nickname'] ?? '';
        $id_num = $_POST['id_number'] ?? '';
        $pass = $_POST['password'] ?? '';

        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

        $profile_pic = null;
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            $upload_dir = "../uploads/profiles/";
            if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }
            $file_name = time() . "_" . $_FILES['profile_picture']['name'];
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_dir . $file_name)) {
                $profile_pic = $file_name;
            }
        }

        try {
            $sql = "INSERT INTO users (first_name, last_name, middle_initial, nickname, id_number, password, profile_pic) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$first_name, $last_name, $mi, $nickname, $id_num, $hashed_password, $profile_pic]);

            header("Location: ../register.php?success=1");
            exit();
        } catch (PDOException $e) {
            die("Registration Error: " . $e->getMessage());
        }
    }
}
?>
<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include '../db.php'; 

$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    // Using PDO as per your original code
    $stmt = $pdo->prepare("SELECT * FROM ict_devices WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        die("<div class='alert alert-danger'>Record not found.</div>");
    }
} else {
    die("<div class='alert alert-danger'>No ID specified.</div>");
}

// Cast to float for number_format safety
$unit_cost = (float)($row['unit_cost'] ?? 0);
$total_cost = (float)($row['total_cost'] ?? 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICT_Slip_<?php echo htmlspecialchars($row['pr_no']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #6c757d; padding: 20px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        .slip-card { 
            background: white; 
            width: 210mm; 
            min-height: 297mm;
            margin: auto;
            padding: 40px; 
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }

        .system-title { color: #198754; font-weight: 800; font-size: 1.4rem; margin-bottom: 0; }
        .slip-subtitle { color: #198754; font-weight: 600; font-size: 1rem; border-bottom: 2px solid #198754; display: inline-block; padding-bottom: 2px; }
        .current-date { font-size: 0.8rem; font-weight: bold; text-align: right; }

        .label-text { font-size: 0.7rem; font-weight: 700; color: #444; text-transform: uppercase; margin-bottom: 0; }
        .value-text { font-size: 0.85rem; color: #000; padding: 2px 0; border-bottom: 1px solid #dee2e6; margin-bottom: 10px; min-height: 1.2rem; }
        
        .tracking-box { border: 1px dashed #adb5bd; padding: 10px; margin: 15px 0; }
        .tracking-item { border-right: 1px solid #dee2e6; padding: 0 10px; }
        .tracking-item:last-child { border-right: none; }

        .cost-highlight { font-weight: 800; color: #198754; font-size: 1.1rem; }

        .table-checklist { width: 100%; border: 1.5px solid #000; margin-top: 15px; }
        .table-checklist th { border: 1px solid #000; background: #fff; font-size: 0.75rem; text-align: center; padding: 4px; }
        .table-checklist td { border: 1px solid #000; font-size: 0.75rem; padding: 4px; vertical-align: middle; }
        .status-cell { text-align: center; font-weight: bold; font-size: 0.7rem; }

        .signature-section { margin-top: 50px; }
        .sig-line { border-top: 1.5px solid #000; width: 80%; margin: auto; padding-top: 5px; font-weight: bold; font-size: 0.85rem; }
        .sig-subtext { font-size: 0.7rem; color: #666; }

        .footer-note { font-size: 0.6rem; color: #adb5bd; margin-top: 30px; text-align: center; }

        @media print {
            body { background: none; padding: 0; }
            .no-print { display: none !important; }
            .slip-card { box-shadow: none; border: none; margin: 0; padding: 20px; width: 100%; }
        }
    </style>
</head>
<body>

<div class="container no-print mb-3 text-center">
    <button onclick="window.print()" class="btn btn-primary btn-sm px-4">Print A4 Document</button>
    <a href="../table.php?cat=ict_devices" class="btn btn-dark btn-sm px-4">Exit</a>
</div>

<div class="slip-card">
    <div class="row align-items-end mb-4">
        <div class="col-8">
            <h1 class="system-title">PROCUREMENT DATA SYSTEM</h1>
            <h2 class="slip-subtitle">ICT Devices Requisition & Tracking Slip</h2>
        </div>
        <div class="col-4 text-end">
            <div class="label-text">CURRENT DATE</div>
            <div class="current-date"><?php echo date('n/j/Y'); ?></div>
        </div>
    </div>

    <div class="row">
        <div class="col-8">
            <div class="label-text">PROJECT / ACTIVITY TITLE</div>
            <div class="value-text fw-bold text-uppercase"><?php echo htmlspecialchars($row['title']); ?></div>
        </div>
        <div class="col-4">
            <div class="label-text">MODE OF PAYMENT</div>
            <div class="value-text"><?php echo htmlspecialchars($row['payment_mode']); ?></div>
        </div>
    </div>

    <div class="row">
        <div class="col-3">
            <div class="label-text">TRANSACTION DATE</div>
            <div class="value-text"><?php echo date("m/d/Y", strtotime($row['transaction_date'])); ?></div>
        </div>
        <div class="col-2">
            <div class="label-text">QUARTER</div>
            <div class="value-text"><?php echo htmlspecialchars($row['quarter']); ?></div>
        </div>
        <div class="col-3">
            <div class="label-text">BRAND / MODEL</div>
            <div class="value-text text-uppercase fw-bold"><?php echo htmlspecialchars($row['brand']); ?></div>
        </div>
        <div class="col-4">
            <div class="label-text">SUPPLIER</div>
            <div class="value-text text-uppercase"><?php echo htmlspecialchars($row['suppliers']); ?></div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="label-text">DETAILED DESCRIPTION / SPECIFICATIONS</div>
            <div class="value-text" style="min-height: 2.5rem;"><?php echo nl2br(htmlspecialchars($row['specs'])); ?></div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-6">
            <div class="label-text">ICT ITEM</div>
            <div class="value-text"><?php echo htmlspecialchars($row['items']); ?></div>
        </div>
        <div class="col-3 text-center">
            <div class="label-text">UNIT</div>
            <div class="value-text"><?php echo htmlspecialchars($row['unit']); ?></div>
        </div>
        <div class="col-3 text-center">
            <div class="label-text">QUANTITY</div>
            <div class="value-text"><?php echo $row['qty']; ?></div>
        </div>
    </div>

    <div class="tracking-box">
        <div class="row text-center g-0">
            <div class="col tracking-item">
                <div class="label-text">PR NO.</div>
                <div class="small fw-bold"><?php echo $row['pr_no'] ?: '-'; ?></div>
            </div>
            <div class="col tracking-item">
                <div class="label-text">NC NO.</div>
                <div class="small fw-bold"><?php echo $row['nc_no'] ?: '-'; ?></div>
            </div>
            <div class="col tracking-item">
                <div class="label-text">PO NO.</div>
                <div class="small fw-bold"><?php echo $row['po_no'] ?: '-'; ?></div>
            </div>
            <div class="col tracking-item">
                <div class="label-text">PADMO NO.</div>
                <div class="small fw-bold"><?php echo $row['padmo_no'] ?: '-'; ?></div>
            </div>
            <div class="col">
                <div class="label-text">FINANCE NO.</div>
                <div class="small fw-bold text-primary"><?php echo $row['go_finance_no'] ?: '-'; ?></div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-6 border-end">
            <div class="label-text">UNIT COST</div>
            <div class="fw-bold">₱<?php echo number_format($unit_cost, 2); ?></div>
        </div>
        <div class="col-6 px-4">
            <div class="label-text text-success">TOTAL COST</div>
            <div class="cost-highlight">₱<?php echo number_format($total_cost, 2); ?></div>
        </div>
    </div>

    <div class="mt-3">
        <div class="label-text">GENERAL REMARKS</div>
        <div class="value-text border-0 text-uppercase" style="font-size: 0.75rem;">
            <?php echo !empty($row['remarks']) ? htmlspecialchars($row['remarks']) : "N/A"; ?>
        </div>
    </div>

    <table class="table-checklist">
        <thead>
            <tr>
                <th width="40%">Document Checklist</th>
                <th width="20%">Status</th>
                <th width="40%">Specific Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Array matching labels to database columns
            $checklist = [
                'PR' => 'pr', 
                'ABC' => 'abc', 
                'PPMP' => 'ppmp', 
                'ACT DES' => 'act_des', 
                'IAR/ARE' => 'iar_are', 
                'PDRS' => 'pdrs', 
                'APP' => 'app', 
                'LETTER REQUEST' => 'letter_request', 
                'MANUAL OBR' => 'manual_obr',
                'BI-DOCS' => 'pr' // Linked to PR as per original logic
            ];

            foreach($checklist as $label => $col): 
                $statusValue = $row[$col] ?? 'Not Complete';
                $isComplete = (strcasecmp($statusValue, 'Complete') == 0 || strcasecmp($statusValue, 'Done') == 0);
                
                $statusColor = $isComplete ? '#198754' : '#dc3545';
                $displayText = $isComplete ? 'COMPLETE' : 'PENDING';
                $remarkText = $isComplete ? 'Document Verified' : 'Pending Compliance';
            ?>
            <tr>
                <td class="ps-2 fw-bold text-uppercase"><?php echo $label; ?></td>
                <td class="status-cell" style="color: <?php echo $statusColor; ?>;"><?php echo $displayText; ?></td>
                <td class="ps-2 text-muted"><?php echo $remarkText; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="row signature-section text-center">
        <div class="col-6">
            <div class="sig-line">Requested By</div>
            <div class="sig-subtext">(Signature over Printed Name)</div>
        </div>
        <div class="col-6">
            <div class="sig-line">Authorized Official</div>
            <div class="sig-subtext">(Date Signed)</div>
        </div>
    </div>

    <div class="footer-note">
        This is a computer-generated document from the Procurement Data System @ 2026
    </div>
</div>

</body>
</html>
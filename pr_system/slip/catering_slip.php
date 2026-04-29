<?php
require_once '../db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Error: No Record ID provided.");
}

try {
    $stmt = $pdo->prepare("SELECT * FROM catering_records WHERE id = ?");
    $stmt->execute([$id]); 
    $row = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$row) {
        die("Error: Record not found.");
    }
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Catering_Slip_<?php echo $row->pr_no; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* A4 BOND PAPER SIMULATION */
        body { background: #525659; margin: 0; padding: 0; }
        
        .a4-page {
            width: 210mm;
            min-height: 297mm;
            padding: 12mm 20mm; 
            margin: 10mm auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
            position: relative;
            font-family: Arial, sans-serif;
            color: #000;
        }

        .doc-header { border-bottom: 3px solid #198754; margin-bottom: 12px; padding-bottom: 8px; }
        .doc-title { text-transform: uppercase; font-weight: 900; letter-spacing: 1px; color: #198754; }
        
        .info-group { margin-bottom: 10px; }
        .info-label { font-size: 9px; text-transform: uppercase; color: #555; font-weight: bold; display: block; }
        .info-value { font-size: 12px; border-bottom: 1px solid #ddd; padding: 2px 0; min-height: 20px; }

        .table-checklist { width: 100%; margin-top: 15px; font-size: 10.5px; }
        .table-checklist th { background: #f8f9fa; border: 1px solid #000; padding: 5px; text-align: center; }
        .table-checklist td { border: 1px solid #000; padding: 5px; }

        .status-text { font-weight: bold; text-transform: uppercase; }
        .text-complete { color: green; }
        .text-not { color: red; }

        .signature-box { margin-top: 35px; }

        @media print {
            body { background: none; }
            .a4-page { margin: 0; box-shadow: none; width: 100%; border: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="container no-print text-center mt-3">
    <button onclick="window.print()" class="btn btn-primary px-4">Print A4 Document</button>
    <a href="../table.php?cat=catering" class="btn btn-outline-light px-4">Exit</a>
</div>

<div class="a4-page">
    <div class="doc-header d-flex justify-content-between align-items-end">
        <div>
            <h2 class="doc-title mb-0" style="font-size: 22px;">Procurement Data System</h2>
            <p class="mb-0">Catering Service Requisition & Tracking Slip</p>
        </div>
        <div class="text-end">
            <small class="info-label">Current Date</small>
            <div class="fw-bold"><?php echo date('n/j/Y'); ?></div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-8 info-group">
            <span class="info-label">Event / Activity Title</span>
            <div class="info-value fw-bold"><?php echo htmlspecialchars($row->title); ?></div>
        </div>
        <div class="col-4 info-group">
            <span class="info-label">Mode of Payment</span>
            <div class="info-value"><?php echo htmlspecialchars($row->payment_mode ?: 'Upon Completion'); ?></div>
        </div>
    </div>

    <div class="row">
        <div class="col-4 info-group">
            <span class="info-label">Catering Date</span>
            <div class="info-value"><?php echo date("n/j/Y", strtotime($row->catering_date)); ?></div>
        </div>
        <div class="col-4 info-group">
            <span class="info-label">Quarter</span>
            <div class="info-value"><?php echo htmlspecialchars($row->quarter); ?></div>
        </div>
        <div class="col-4 info-group">
            <span class="info-label">Supplier</span>
            <div class="info-value"><?php echo htmlspecialchars($row->suppliers); ?></div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 info-group">
            <span class="info-label">Detailed Description / Specifications</span>
            <div class="info-value" style="min-height: 40px;"><?php echo nl2br(htmlspecialchars($row->description)); ?></div>
        </div>
    </div>

    <div class="row">
        <div class="col-6 info-group">
            <span class="info-label">Menu Detail</span>
            <div class="info-value"><?php echo htmlspecialchars($row->menu); ?></div>
        </div>
        <div class="col-3 info-group">
            <span class="info-label">Unit/Pax</span>
            <div class="info-value"><?php echo htmlspecialchars($row->unit_pax); ?></div>
        </div>
        <div class="col-3 info-group">
            <span class="info-label">Quantity</span>
            <div class="info-value"><?php echo number_format($row->qty); ?></div>
        </div>
    </div>

    <div class="row mt-2" style="background: #fdfdfd; padding: 8px; border: 1px dashed #ccc; margin: 0 1px;">
        <div class="col-2 info-group">
            <span class="info-label">PR NO.</span>
            <div class="info-value small"><?php echo $row->pr_no ?: 'N/A'; ?></div>
        </div>
        <div class="col-2 info-group">
            <span class="info-label">NC NO.</span>
            <div class="info-value small"><?php echo $row->nc_no ?: 'N/A'; ?></div>
        </div>
        <div class="col-2 info-group">
            <span class="info-label">P.O NO.</span>
            <div class="info-value small"><?php echo $row->po_no ?: 'N/A'; ?></div>
        </div>
        <div class="col-3 info-group">
            <span class="info-label">PADMO NO.</span>
            <div class="info-value small"><?php echo $row->padmo_no ?: 'N/A'; ?></div>
        </div>
        <div class="col-3 info-group">
            <span class="info-label">FINANCE NO.</span>
            <div class="info-value small fw-bold text-primary"><?php echo $row->go_finance_no ?: 'N/A'; ?></div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-4 info-group">
            <span class="info-label">Unit Cost</span>
            <div class="info-value">₱<?php echo number_format($row->unit_cost, 2); ?></div>
        </div>
        <div class="col-8 info-group">
            <span class="info-label">Total Cost</span>
            <div class="info-value fw-bold text-success">₱<?php echo number_format($row->total_cost, 2); ?></div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12 info-group">
            <span class="info-label">General Remarks</span>
            <div class="info-value"><?php echo nl2br(htmlspecialchars($row->remarks ?: 'No additional remarks.')); ?></div>
        </div>
    </div>

    <table class="table-checklist">
        <thead>
            <tr>
                <th width="35%">Document Checklist</th>
                <th width="20%">Status</th>
                <th width="45%">Specific Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $checks = [
                'PR'             => ['status' => $row->status_pr,       'remark' => $row->remarks_pr],
                'ABC'            => ['status' => $row->status_abc,      'remark' => $row->remarks_abc],
                'PPMP'           => ['status' => $row->status_ppmp,     'remark' => $row->remarks_ppmp],
                'ACT DES'        => ['status' => $row->status_act_des,  'remark' => $row->remarks_act_des],
                'IAR/ARE'        => ['status' => $row->status_iar_are,  'remark' => $row->remarks_iar_are],
                'PDRS'           => ['status' => $row->status_pdrs,     'remark' => $row->remarks_pdrs],
                'APP'            => ['status' => $row->status_app,      'remark' => $row->remarks_app],
                'LETTER REQUEST' => ['status' => $row->status_letter,   'remark' => $row->remarks_letter],
                'MANUAL OBR'     => ['status' => $row->status_obr,      'remark' => $row->remarks_obr],
                'BI-DOCS'        => ['status' => 'Pending',             'remark' => 'No data available']
            ];
            foreach($checks as $label => $data): ?>
            <tr>
                <td><strong><?php echo $label; ?></strong></td>
                <td class="text-center status-text <?php echo ($data['status'] == 'Complete') ? 'text-complete' : 'text-not'; ?>">
                    <?php echo $data['status'] ?: 'Not Complete'; ?>
                </td>
                <td><?php echo htmlspecialchars($data['remark']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="row signature-box text-center">
        <div class="col-6">
            <div style="border-bottom: 1px solid #000; width: 80%; margin: 0 auto;"></div>
            <div class="fw-bold mt-1">Requested By</div>
            <small>(Signature over Printed Name)</small>
        </div>
        <div class="col-6">
            <div style="border-bottom: 1px solid #000; width: 80%; margin: 0 auto;"></div>
            <div class="fw-bold mt-1">Authorized Official</div>
            <small>(Date Signed)</small>
        </div>
    </div>

    <div class="mt-4 text-center">
        <p style="font-size: 9px; color: #aaa; margin: 0;">This is a computer-generated document from the Procurement Data System @ 2026</p>
    </div>
</div>

</body>
</html>
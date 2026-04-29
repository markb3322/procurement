<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'db.php'; 

$category = $_GET['cat'] ?? 'catering';

$table_map = [
    'catering'        => 'catering_records',
    'office_supplies' => 'office_supplies',
    'ict_devices'     => 'ict_devices',
    'furnitures'      => 'furnitures',
    'fabrication'     => 'fabrication_installation',
    'heavy_equipment' => 'heavy_equipment',
    'appliances'      => 'appliances', 
    'fixtures'        => 'fixtures'    
];

$current_table = $table_map[$category] ?? 'catering_records';

try {
    $date_col = ($current_table == 'catering_records') ? 'catering_date' : 'transaction_date';
    $stmt = $pdo->prepare("SELECT *, $date_col AS display_date FROM $current_table ORDER BY $date_col DESC");
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_OBJ); 
} catch (Exception $e) {
    $records = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procurement Data Table | PDS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --pds-green: #198754; --pds-dark: #1a1d20; }
        body { background: #f0f2f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; min-height: 100vh; display: flex; flex-direction: column; }
        .record-card { border: none; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); background: #fff; }
        .table-responsive-custom { overflow-x: auto; border-radius: 10px; scrollbar-width: thin; }
        .table-custom { width: 100%; table-layout: auto; white-space: nowrap; margin-bottom: 0; border-collapse: collapse; }
        .table-custom thead { background: var(--pds-dark); color: white; }
        .table-custom th, .table-custom td { padding: 12px 15px; font-size: 0.85rem; vertical-align: middle; border: 1px solid #dee2e6 !important; }
        .table-custom th { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .btn-menu { border-radius: 8px; font-weight: 600; transition: 0.3s; padding: 8px 16px; border: 1px solid #ddd; background: #fff; }
        .btn-menu.active { background: var(--pds-green); color: white; border-color: var(--pds-green); }
        .text-missing { color: #dc3545; font-weight: bold; }
        .text-complete { color: #198754; font-weight: bold; }
        footer.pds-footer { background-color: #111; color: #fff; padding: 20px 0; margin-top: auto; }
        .footer-brand { color: #fff; font-weight: bold; text-transform: uppercase; }
        .footer-brand span { color: #198754; }
        .footer-copy { color: #198754; font-size: 0.85rem; }
        .sticky-end { position: sticky; right: 0; z-index: 2; box-shadow: -5px 0 10px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container-fluid px-4 mb-5">
    
    <?php if (isset($_SESSION['msg'])): ?>
        <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show mt-3 shadow-sm">
            <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="record-card p-4 mb-4 mt-3">
        <div class="row align-items-center">
            <div class="col-md-7">
                <h3 class="fw-bold mb-1">Procurement Data Table View</h3>
                <p class="text-muted small mb-0 text-uppercase tracking-wider">Comprehensive log for audit, tracking, and financial reconciliation.</p>
            </div>
            <div class="col-md-5 text-md-end">
                <a href="process/download_excel.php?cat=<?php echo $category; ?>" class="btn btn-outline-dark btn-sm rounded-pill px-3">
                    <i class="bi bi-file-earmark-excel me-1"></i> Export to Excel
                </a>
            </div>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2 mb-4 justify-content-center">
        <?php foreach($table_map as $key => $val): ?>
            <a href="?cat=<?php echo $key; ?>" class="btn-menu text-decoration-none text-center <?php echo ($category == $key) ? 'active' : 'text-dark'; ?>">
                <?php echo strtoupper(str_replace('_', ' ', $key)); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="record-card">
        <div class="table-responsive-custom">
            <table class="table table-hover table-custom">
                <thead>
                    <tr>
                        <th>Title</th><th>Date</th><th>Quarter</th>
                        <?php if($category == 'catering'): ?>
                            <th>Menu</th><th>Unit/Pax</th><th>Descriptions</th>
                        <?php elseif($category == 'office_supplies'): ?>
                            <th>Item</th><th>Articles</th><th>Brand</th><th>Unit</th>
                        <?php elseif($category == 'fabrication'): ?>
                            <th>Item</th><th>Scope of Work</th><th>Specs</th><th>Unit</th>
                        <?php elseif($category == 'furnitures'): ?>
                            <th>Item</th><th>Specs</th><th>Unit</th>
                        <?php else: ?>
                            <th>Item</th><th>Brand</th><th>Specs</th><th>Unit</th>
                        <?php endif; ?>

                        <th>Qty</th><th>Unit Cost</th><th>Suppliers</th><th>Total Cost</th>
                        <th>Bidocs</th><th>Mode of Payment</th><th>Remarks</th>
                        <th>PR No</th><th>NC No</th><th>P.O No</th><th>PADMO No</th><th>Finance No</th>
                        
                        <th class="bg-light">PR Status</th><th class="bg-light">ABC</th><th class="bg-light">PPMP</th>
                        <th class="bg-light">ACT DES</th><th class="bg-light">IAR/ARE</th><th class="bg-light">PDRS</th>
                        <th class="bg-light">APP</th><th class="bg-light">LETTER</th><th class="bg-light">OBR</th>
                        <th class="text-center sticky-end bg-white">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($records)): ?>
                        <tr><td colspan="45" class="text-center py-5">No records found for <?php echo $current_table; ?>.</td></tr>
                    <?php else: foreach($records as $row): ?>
                    <tr>
                        <td class="fw-bold"><?php echo htmlspecialchars($row->title); ?></td>
                        <td><?php echo date("n/j/Y", strtotime($row->display_date)); ?></td>
                        <td><?php echo $row->quarter; ?></td>
                        
                        <?php if($category == 'catering'): ?>
                            <td><?php echo $row->menu; ?></td><td><?php echo $row->unit_pax; ?></td><td><?php echo $row->description; ?></td>
                        <?php elseif($category == 'office_supplies'): ?>
                            <td><?php echo $row->items ?? $row->item; ?></td><td><?php echo $row->articles; ?></td><td><?php echo $row->brand; ?></td><td><?php echo $row->unit; ?></td>
                        <?php elseif($category == 'fabrication'): ?>
                            <td><?php echo $row->item; ?></td><td><?php echo $row->scope_of_work; ?></td><td><?php echo $row->specs; ?></td><td><?php echo $row->unit; ?></td>
                        <?php elseif($category == 'furnitures'): ?>
                            <td><?php echo $row->item ?? $row->items ?? 'N/A'; ?></td>
                            <td><?php echo $row->specs ?? ''; ?></td>
                            <td><?php echo $row->unit ?? ''; ?></td>
                        <?php else: ?>
                            <td><?php echo $row->item ?? $row->items ?? 'N/A'; ?></td>
                            <td><?php echo $row->brand ?? ''; ?></td>
                            <td><?php echo $row->specs ?? ''; ?></td>
                            <td><?php echo $row->unit ?? ''; ?></td>
                        <?php endif; ?>

                        <td><?php echo $row->qty; ?></td>
                        <td>₱<?php echo number_format($row->unit_cost, 2); ?></td>
                        <td><?php echo $row->suppliers; ?></td>
                        <td class="fw-bold text-success">₱<?php echo is_numeric($row->total_cost) ? number_format($row->total_cost, 2) : $row->total_cost; ?></td>
                        
                        <td class="text-muted small fw-bold text-center">NO DATA</td>

                        <td><?php echo $row->payment_mode; ?></td>
                        <td><?php echo $row->remarks; ?></td>
                        <td><?php echo $row->pr_no; ?></td>
                        <td><?php echo $row->nc_no; ?></td>
                        <td><?php echo $row->po_no; ?></td>
                        <td><?php echo $row->padmo_no; ?></td>
                        <td><?php echo $row->go_finance_no; ?></td>
                        
                        <?php foreach(['pr','abc','ppmp','act_des','iar_are','pdrs','app','letter','obr'] as $c): 
                            $prop = "status_" . $c;
                            $status = $row->$prop; ?>
                            <td class="<?php echo ($status == 'Complete') ? 'text-success fw-bold' : 'text-danger fw-bold'; ?>">
                                <?php echo ($status == 'Complete') ? 'Complete' : 'Not Complete'; ?>
                            </td>
                        <?php endforeach; ?>
                        
                        <td class="text-center sticky-end bg-white">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="slip/<?php echo $category; ?>_slip.php?id=<?php echo $row->id; ?>" class="btn btn-sm btn-light border" title="View A4 Slip">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                <a href="process/update/<?php echo $category; ?>_edit.php?id=<?php echo $row->id; ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                
                                <a href="process/update/delete.php?id=<?php echo $row->id; ?>&cat=<?php echo $category; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<footer class="pds-footer border-top mt-auto">
    <div class="container-fluid px-4 text-center text-md-start">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="footer-brand">PROCUREMENT <span>DATA SYSTEM</span></div>
            <div class="footer-copy">Bepo-Peso All Rights Reserved @ 2026</div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
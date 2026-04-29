<?php
require_once '../db.php'; 

$category = $_GET['cat'] ?? 'catering';
$paper_size = $_GET['paper'] ?? 'A4'; 

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
$filename = "Procurement_Report_" . $category . "_" . date('Y-m-d') . ".doc";

try {
    $date_col = ($current_table == 'catering_records') ? 'catering_date' : 'transaction_date';
    $stmt = $pdo->prepare("SELECT *, $date_col AS display_date FROM $current_table ORDER BY $date_col DESC");
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_OBJ);

    if (!$records) { die("No records found."); }

    $total_sum = 0;
    foreach($records as $r) { $total_sum += (float)$r->total_cost; }

    header("Content-Type: application/vnd.ms-word");
    header("Content-Disposition: attachment; filename=" . $filename);

    ?>
    <html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
    <head>
        <meta charset="utf-8">
        <style>
            body { font-family: 'Segoe UI', Arial; }
            .header { text-align: center; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th { background-color: #198754; color: white; border: 1px solid #000; padding: 8px; }
            td { border: 1px solid #000; padding: 6px; font-size: 10pt; }
            .summary { border: 2px solid #198754; padding: 15px; margin-top: 20px; background: #f9f9f9; }
            .footer-cell { text-align: center; padding-top: 40px; border: none !important; }
            .line { border-top: 1px solid #000; width: 80%; margin: 0 auto; }
        </style>
    </head>
    <body>
        <div class="header">
            <h2 style="margin-bottom:0;">PROCUREMENT DATA SYSTEM</h2>
            <p>Summary Report: <?php echo ucwords(str_replace('_', ' ', $category)); ?></p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Item Title</th>
                    <?php if($category !== 'furnitures'): ?><th>Brand</th><?php endif; ?>
                    <th>Qty</th>
                    <th>Total Cost</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($records as $row): ?>
                <tr>
                    <td><?php echo date("m/d/Y", strtotime($row->display_date)); ?></td>
                    <td><b><?php echo htmlspecialchars($row->title); ?></b></td>
                    <?php if($category !== 'furnitures'): ?><td><?php echo $row->brand ?? 'N/A'; ?></td><?php endif; ?>
                    <td><?php echo $row->qty; ?></td>
                    <td>₱<?php echo number_format((float)$row->total_cost, 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="summary">
            <h4>Mathematical Expenditure Summary:</h4>
            <p>Total Cost ($\sum C$) calculated as sum of unit price $\times$ quantity:</p>
            <p style="font-size: 14pt; color: #198754;"><b>Total: ₱<?php echo number_format($total_sum, 2); ?></b></p>
        </div>

        <table style="margin-top: 50px; border: none;">
            <tr>
                <td class="footer-cell"><div class="line"></div><b>PREPARED BY</b></td>
                <td class="footer-cell"><div class="line"></div><b>REVIEWED BY</b></td>
                <td class="footer-cell"><div class="line"></div><b>AUTHORIZED BY</b></td>
            </tr>
        </table>
    </body>
    </html>
    <?php
    exit;
} catch (Exception $e) { die($e->getMessage()); }
?>
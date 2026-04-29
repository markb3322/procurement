<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'db.php'; 

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

// Data Aggregation for Charts and Cards
$labels = [];
$costs = [];
$total_sum = 0;
$max_expense = 0;

try {
    $date_col = ($current_table == 'catering_records') ? 'catering_date' : 'transaction_date';
    $stmt = $pdo->prepare("SELECT *, $date_col AS display_date FROM $current_table ORDER BY $date_col DESC");
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_OBJ); 

    foreach($records as $r) {
        $cost_val = (float)$r->total_cost;
        $labels[] = substr($r->title, 0, 15) . '...';
        $costs[] = $cost_val;
        $total_sum += $cost_val;
        if($cost_val > $max_expense) $max_expense = $cost_val;
    }
    $avg_cost = count($records) > 0 ? $total_sum / count($records) : 0;
} catch (Exception $e) {
    $records = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Reports | PDS Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root { 
            --pds-primary: #0f172a; 
            --pds-accent: #198754; 
            --pds-bg: #f1f5f9;
        }

        body { background: var(--pds-bg); font-family: 'Segoe UI', Roboto, sans-serif; }

        .premium-header {
            background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
            color: white;
            border-radius: 1.5rem;
            padding: 3rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .stat-card {
            border: none;
            border-radius: 1rem;
            transition: transform 0.3s ease;
        }
        .stat-card:hover { transform: translateY(-5px); }

        .chart-plate {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }

        .math-box {
            background: #f8fafc;
            border-left: 5px solid var(--pds-accent);
            padding: 1rem;
            font-family: 'Courier New', monospace;
        }

        @media print {
            .no-print { display: none !important; }
            #printableArea { display: block !important; width: 100% !important; margin: 0 !important; padding: 0 !important; }
            .paper-a4 { width: 210mm; }
            .paper-letter { width: 8.5in; }
            .paper-legal { width: 8.5in; }
            .table-report { width: 100%; border-collapse: collapse; }
            .table-report th, .table-report td { border: 1px solid #000; padding: 6px; font-size: 9pt; }
        }

        @media screen { #printableArea { display: none; } }
    </style>
</head>
<body>

<?php include 'navbar_admin.php'; ?>

<div class="container py-4 no-print">
    <div class="premium-header d-flex justify-content-between align-items-center">
        <div>
            <h6 class="text-success text-uppercase fw-bold mb-2" style="letter-spacing: 2px;">Management Suite</h6>
            <h1 class="display-5 fw-bold mb-0">Procurement Report Analytics</h1>
            <p class="lead opacity-75 mt-2">Professional tracking for <?php echo ucwords(str_replace('_', ' ', $category)); ?></p>
        </div>
        <a href="process/download_report.php?cat=<?php echo $category; ?>&paper=<?php echo $paper_size; ?>" class="btn btn-success btn-lg px-5 rounded-pill shadow-lg">
            <i class="bi bi-file-earmark-word-fill me-2"></i> Export to Word
        </a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card shadow-sm p-3 bg-white border-start border-primary border-4">
                <div class="d-flex align-items-center">
                    <div class="badge bg-primary-soft p-3 rounded-circle me-3" style="background: #e0e7ff;"><i class="bi bi-layers text-primary fs-4"></i></div>
                    <div>
                        <h6 class="text-muted mb-1 small fw-bold">TOTAL RECORDS</h6>
                        <h4 class="mb-0 fw-bold"><?php echo count($records); ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card shadow-sm p-3 bg-white border-start border-success border-4">
                <div class="d-flex align-items-center">
                    <div class="badge p-3 rounded-circle me-3" style="background: #dcfce7;"><i class="bi bi-cash-stack text-success fs-4"></i></div>
                    <div>
                        <h6 class="text-muted mb-1 small fw-bold">TOTAL SPENT</h6>
                        <h4 class="mb-0 fw-bold">₱<?php echo number_format($total_sum, 0); ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card shadow-sm p-3 bg-white border-start border-warning border-4">
                <div class="d-flex align-items-center">
                    <div class="badge p-3 rounded-circle me-3" style="background: #fef9c3;"><i class="bi bi-graph-up text-warning fs-4"></i></div>
                    <div>
                        <h6 class="text-muted mb-1 small fw-bold">HIGHEST EXPENSE</h6>
                        <h4 class="mb-0 fw-bold">₱<?php echo number_format($max_expense, 0); ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card shadow-sm p-3 bg-white border-start border-info border-4">
                <div class="d-flex align-items-center">
                    <div class="badge p-3 rounded-circle me-3" style="background: #e0f2fe;"><i class="bi bi-calculator text-info fs-4"></i></div>
                    <div>
                        <h6 class="text-muted mb-1 small fw-bold">AVG. PER ITEM</h6>
                        <h4 class="mb-0 fw-bold">₱<?php echo number_format($avg_cost, 0); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="chart-plate h-100">
                <h5 class="fw-bold mb-4"><i class="bi bi-graph-up-arrow me-2"></i>Cost Distribution (X: Title, Y: Cost)</h5>
                <canvas id="costChart" height="150"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <h5 class="fw-bold mb-3">Report Settings</h5>
                <div class="mb-3">
                    <label class="small fw-bold text-muted">DATA CATEGORY</label>
                    <select class="form-select bg-light border-0" onchange="location.href='?paper=<?php echo $paper_size; ?>&cat=' + this.value">
                        <?php foreach($table_map as $key => $val): ?>
                            <option value="<?php echo $key; ?>" <?php echo ($category == $key) ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', ' ', $key)); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="small fw-bold text-muted">PAPER SIZE</label>
                    <div class="btn-group w-100">
                        <a href="?cat=<?php echo $category; ?>&paper=A4" class="btn btn-outline-dark <?php echo ($paper_size == 'A4') ? 'active' : ''; ?>">A4</a>
                        <a href="?cat=<?php echo $category; ?>&paper=Letter" class="btn btn-outline-dark <?php echo ($paper_size == 'Letter') ? 'active' : ''; ?>">Short</a>
                        <a href="?cat=<?php echo $category; ?>&paper=Legal" class="btn btn-outline-dark <?php echo ($paper_size == 'Legal') ? 'active' : ''; ?>">Long</a>
                    </div>
                </div>
                <div class="math-box">
                    <p class="mb-1 text-muted small">FINAL TOTAL:</p>
                    <h3 class="fw-bold text-success mb-0">₱<?php echo number_format($total_sum, 2); ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="printableArea" class="mx-auto p-5 bg-white <?php echo 'paper-'.strtolower($paper_size); ?>">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-uppercase mb-0">Procurement Data System</h2>
        <p class="text-muted">Summary Report | Category: <?php echo ucwords(str_replace('_', ' ', $category)); ?></p>
        <div style="border-bottom: 3px solid #198754; width: 100px; margin: 10px auto;"></div>
    </div>

    <table class="table-report">
        <thead>
            <tr>
                <th>Date</th>
                <th>Project/Item Title</th>
                <?php if($category !== 'furnitures'): ?><th>Brand</th><?php endif; ?>
                <th>Qty</th>
                <th>Total Cost</th>
                <th>Supplier</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($records as $row): ?>
            <tr>
                <td><?php echo date("m/d/Y", strtotime($row->display_date)); ?></td>
                <td><strong><?php echo htmlspecialchars($row->title); ?></strong></td>
                <?php if($category !== 'furnitures'): ?><td><?php echo $row->brand ?? 'N/A'; ?></td><?php endif; ?>
                <td><?php echo $row->qty; ?></td>
                <td class="fw-bold">₱<?php echo number_format((float)$row->total_cost, 2); ?></td>
                <td><?php echo $row->suppliers; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="mt-5 pt-4 border-top">
        <h6 class="fw-bold text-uppercase mb-3">Mathematical Expenditure Summary:</h6>
        <p>Total Cost ($\sum C$) for the period of 2026 is calculated as:</p>
        <div class="p-3 bg-light rounded">
            $$\text{Total Cost} = \sum_{i=1}^{n} (\text{Unit Cost}_i \times \text{Quantity}_i) = \text{₱}<?php echo number_format($total_sum, 2); ?>$$
        </div>
    </div>

    <div class="mt-5 row text-center">
        <div class="col-4">
            <div style="border-bottom: 1px solid #000; margin-top: 40px;"></div>
            <p class="small fw-bold">PREPARED BY</p>
        </div>
        <div class="col-4">
            <div style="border-bottom: 1px solid #000; margin-top: 40px;"></div>
            <p class="small fw-bold">REVIEWED BY</p>
        </div>
        <div class="col-4">
            <div style="border-bottom: 1px solid #000; margin-top: 40px;"></div>
            <p class="small fw-bold">AUTHORIZED BY</p>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('costChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Expenditure (₱)',
                data: <?php echo json_encode($costs); ?>,
                backgroundColor: 'rgba(25, 135, 84, 0.7)',
                borderColor: '#198754',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } },
            plugins: { legend: { display: false } }
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
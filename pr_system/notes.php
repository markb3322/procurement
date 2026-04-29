<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'db.php';
date_default_timezone_set('Asia/Manila');

// --- 1. CALENDAR LOGIC ---
$month = isset($_GET['m']) ? (int)$_GET['m'] : (int)date('m');
$year = isset($_GET['y']) ? (int)$_GET['y'] : (int)date('Y');

$prevMonth = ($month == 1) ? 12 : $month - 1;
$prevYear = ($month == 1) ? $year - 1 : $year;
$nextMonth = ($month == 12) ? 1 : $month + 1;
$nextYear = ($month == 12) ? $year + 1 : $year;

// --- 2. CATEGORIES & TABLE MAP ---
$table_map = [
    'catering'        => ['table' => 'catering_records', 'date_col' => 'catering_date', 'desc' => 'Food and Beverages'],
    'office_supplies' => ['table' => 'office_supplies', 'date_col' => 'transaction_date', 'desc' => 'Office Consumables'],
    'ict_devices'     => ['table' => 'ict_devices', 'date_col' => 'transaction_date', 'desc' => 'IT Hardware'],
    'furnitures'      => ['table' => 'furnitures', 'date_col' => 'transaction_date', 'desc' => 'Office Workstations'],
    'fixtures'        => ['table' => 'fixtures', 'date_col' => 'transaction_date', 'desc' => 'Built-in Equipment'],
    'fabrication'     => ['table' => 'fabrication_installation', 'date_col' => 'transaction_date', 'desc' => 'Customized Services'],
    'heavy_equipment' => ['table' => 'heavy_equipment', 'date_col' => 'transaction_date', 'desc' => 'Machinery'],
    'appliances'      => ['table' => 'appliances', 'date_col' => 'transaction_date', 'desc' => 'Electronic Devices']
];

$selected_cat = $_GET['cat'] ?? 'catering';
$current_info = $table_map[$selected_cat] ?? $table_map['catering'];
$current_table = $current_info['table'];
$date_col_name = $current_info['date_col'];

// --- 3. FETCH DATA FOR CALENDAR ---
$calendar_notes = [];
foreach ($table_map as $catKey => $info) {
    $sql = "SELECT DISTINCT " . $info['date_col'] . " as d FROM " . $info['table'] . " WHERE MONTH(" . $info['date_col'] . ") = ? AND YEAR(" . $info['date_col'] . ") = ? AND pr_no != ''";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$month, $year]);
    while($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $day = (int)date('j', strtotime($r['d']));
        $calendar_notes[$day][] = ['title' => strtoupper($catKey), 'is_note' => false];
    }
}

$stmt_notes = $pdo->prepare("SELECT id, note_date, title, note_content FROM journal_notes WHERE MONTH(note_date) = ? AND YEAR(note_date) = ?");
$stmt_notes->execute([$month, $year]);
while($n = $stmt_notes->fetch(PDO::FETCH_ASSOC)) {
    $day = (int)date('j', strtotime($n['note_date']));
    $calendar_notes[$day][] = [
        'id' => $n['id'], 
        'title' => $n['title'], 
        'content' => $n['note_content'], 
        'is_note' => true
    ];
}

// --- 4. FETCH DATA FOR AUDIT TABLE ---
try {
    $stmt_table = $pdo->prepare("SELECT *, $date_col_name AS display_date FROM $current_table ORDER BY $date_col_name DESC");
    $stmt_table->execute();
    $records = $stmt_table->fetchAll(PDO::FETCH_OBJ); 
} catch (Exception $e) {
    $records = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal & Audit | PDS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --pds-green: #198754; --pds-dark: #1a1d20; }
        body { background: #f4f7f6; font-family: 'Inter', sans-serif; }
        .calendar-card { border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.08); overflow: hidden; background: #fff; }
        .cal-header { background: var(--pds-green); color: white; padding: 20px; }
        .days-grid { display: grid; grid-template-columns: repeat(7, 1fr); }
        .day-header { padding: 10px; background: #f8f9fa; font-weight: bold; font-size: 0.8rem; text-align: center; border: 0.5px solid #eee; }
        .day-cell { min-height: 115px; padding: 8px; border: 0.5px solid #eee; cursor: pointer; transition: 0.2s; position: relative; }
        .day-num { font-weight: 800; color: #adb5bd; display: block; margin-bottom: 5px; }
        .today-cell { background: #e8f5e9 !important; }
        .mini-sticky { font-size: 0.62rem; padding: 3px 6px; margin-bottom: 3px; border-left: 3px solid #fbc02d; background: #fff9c4; border-radius: 3px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-weight: 600; }
        .procurement-sticky { border-left-color: #1976d2; background: #e3f2fd; color: #0d47a1; }
        .no-data-sticky { border-left: 2px solid #dee2e6; background: #f8f9fa; color: #adb5bd; font-style: italic; font-weight: normal; }
        .audit-card { border: none; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); background: #fff; }
        .btn-menu { border-radius: 30px; font-weight: 600; padding: 8px 20px; border: 1px solid #ddd; background: #fff; font-size: 0.75rem; text-transform: uppercase; text-decoration: none; color: #666; transition: 0.3s; }
        .btn-menu.active { background: var(--pds-dark); color: white; border-color: var(--pds-dark); }
        .table-custom thead th { background: #f8f9fa; color: #333; font-weight: 700; text-transform: uppercase; font-size: 0.75rem; border: none; padding: 15px; }
        .table-custom td { padding: 18px 15px; border-bottom: 1px solid #f0f0f0; }
        .calendar-badge { background: #fff; border: 1px solid #eee; border-radius: 10px; overflow: hidden; display: inline-block; min-width: 60px; text-align: center; }
        .cal-m { background: #f8f9fa; color: #666; font-size: 0.6rem; font-weight: bold; padding: 2px; border-bottom: 1px solid #eee; }
        .add-note-btn { position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px; border-radius: 50%; background: var(--pds-green); color: white; border: none; font-size: 24px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); z-index: 1000; }
        .btn-delete { color: #dc3545; cursor: pointer; }
        .progress { height: 10px; border-radius: 10px; background-color: #e9ecef; margin: 8px 0; }
        .progress-bar { border-radius: 10px; transition: width 0.8s ease; }
        footer { background: #fff; color: #333; padding: 30px 0; border-top: 1px solid #eee; margin-top: 50px; }
        .footer-logo { font-weight: 800; font-size: 0.9rem; letter-spacing: 1px; color: var(--pds-dark); }
        .footer-logo span { color: var(--pds-green); }
        .footer-sub { font-size: 0.75rem; color: #888; margin-top: 5px; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container py-5">
    <div class="card calendar-card mb-5">
        <div class="cal-header d-flex justify-content-between align-items-center">
            <a href="?cat=<?php echo $selected_cat; ?>&m=<?php echo $prevMonth; ?>&y=<?php echo $prevYear; ?>" class="btn btn-outline-light btn-sm rounded-pill"><i class="bi bi-chevron-left"></i></a>
            <h4 class="fw-bold mb-0 text-uppercase"><?php echo date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?></h4>
            <a href="?cat=<?php echo $selected_cat; ?>&m=<?php echo $nextMonth; ?>&y=<?php echo $nextYear; ?>" class="btn btn-outline-light btn-sm rounded-pill"><i class="bi bi-chevron-right"></i></a>
        </div>
        <div class="days-grid">
            <?php 
            $labels = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            foreach($labels as $l) echo "<div class='day-header'>$l</div>";
            $firstDay = date('w', mktime(0,0,0,$month,1,$year));
            $daysCount = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            for($i=0; $i<$firstDay; $i++) echo "<div class='day-cell bg-light opacity-50'></div>";
            for($day=1; $day<=$daysCount; $day++):
                $curDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
                $isToday = ($curDate == date('Y-m-d'));
            ?>
                <div class="day-cell <?php echo $isToday ? 'today-cell' : ''; ?>" onclick="openQuickNote('<?php echo $curDate; ?>')">
                    <span class="day-num"><?php echo $day; ?></span>
                    <?php if(isset($calendar_notes[$day])): ?>
                        <?php foreach($calendar_notes[$day] as $note): ?>
                            <div class="mini-sticky <?php echo !$note['is_note'] ? 'procurement-sticky' : ''; ?>" 
                                 <?php if($note['is_note']): ?>
                                 onclick="event.stopPropagation(); editNote(<?php echo $note['id']; ?>, '<?php echo $curDate; ?>', '<?php echo addslashes($note['title']); ?>', '<?php echo addslashes($note['content']); ?>')"
                                 <?php endif; ?>>
                                <i class="bi <?php echo $note['is_note'] ? 'bi-pin-fill' : 'bi-check-circle-fill'; ?>"></i> <?php echo htmlspecialchars($note['title']); ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="mini-sticky no-data-sticky">
                            <i class="bi bi-dash-circle"></i> No data
                        </div>
                    <?php endif; ?>
                </div>
            <?php endfor; ?>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2 mb-4 justify-content-center">
        <?php foreach($table_map as $key => $val): ?>
            <a href="?cat=<?php echo $key; ?>&m=<?php echo $month; ?>&y=<?php echo $year; ?>" class="btn-menu <?php echo ($selected_cat == $key) ? 'active' : ''; ?>">
                <?php echo str_replace('_', ' ', $key); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="audit-card">
        <div class="p-4 border-bottom bg-white" style="border-radius: 15px 15px 0 0;">
            <h5 class="fw-bold mb-0 text-success text-uppercase"><i class="bi bi-shield-lock-fill me-2"></i>System Compliance Review</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-custom">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 35%;">Project Title</th>
                        <th class="text-center" style="width: 15%;">Date</th>
                        <th style="width: 50%;">Compliance Status & Progress</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($records)): ?>
                        <tr><td colspan="3" class="text-center py-5 text-muted">No entries found for this category.</td></tr>
                    <?php else: foreach($records as $row): 
                        
                        // --- DYNAMIC TRACING LOGIC ---
                        $row_arr = (array)$row;
                        // Full list of possible fields across all tables
                        $possible_fields = ['pr_no', 'po_no', 'suppliers', 'total_cost', 'abc', 'ppmp'];
                        
                        $total_required = 0;
                        $filled_count = 0;

                        foreach ($possible_fields as $field) {
                            // MAG-MINUS SA PERCENTAGE: Kung ang column ay exist sa table na ito
                            if (array_key_exists($field, $row_arr)) {
                                $total_required++; // Bilangin lang ang fields na talagang nasa table
                                
                                // Check if the existing field has valid data
                                if (!empty($row_arr[$field]) && 
                                    $row_arr[$field] !== '0' && 
                                    $row_arr[$field] !== '0.00' && 
                                    $row_arr[$field] !== 'N/A') {
                                    $filled_count++;
                                }
                            }
                        }

                        // Ngayon, kung 4 columns lang ang meron ang table, /4 ang divisor (100% possible)
                        $percent = ($total_required > 0) ? round(($filled_count / $total_required) * 100) : 0;
                        
                        if ($percent >= 90) {
                            $bar_class = 'bg-success';
                            $text_class = 'text-success';
                            $status_label = '<i class="bi bi-patch-check-fill"></i> Verified';
                        } elseif ($percent >= 30) {
                            $bar_class = 'bg-warning';
                            $text_class = 'text-warning';
                            $status_label = '<i class="bi bi-exclamation-triangle-fill"></i> Pending Review';
                        } else {
                            $bar_class = 'bg-danger';
                            $text_class = 'text-danger';
                            $status_label = '<i class="bi bi-x-circle-fill"></i> Incomplete';
                        }

                        $dTime = strtotime($row->display_date);
                    ?>
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark"><?php echo htmlspecialchars($row->title ?? 'Untitled Project'); ?></div>
                            <div class="text-muted small">ID: #<?php echo $row->id; ?></div>
                        </td>
                        <td class="text-center">
                            <div class="calendar-badge shadow-sm">
                                <div class="cal-m text-uppercase"><?php echo date("M", $dTime); ?></div>
                                <div class="fw-bold py-1"><?php echo date("d", $dTime); ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-between align-items-center mb-0">
                                <span class="small fw-bold <?php echo $text_class; ?>">
                                    <?php echo $status_label; ?>
                                </span>
                                <span class="small fw-bold text-muted"><?php echo $percent; ?>%</span>
                            </div>
                            <div class="progress shadow-sm">
                                <div class="progress-bar <?php echo $bar_class; ?>" 
                                     style="width: <?php echo $percent; ?>%;"></div>
                            </div>
                            <div style="font-size: 0.68rem;" class="text-muted">
                                <?php if($percent >= 90): ?>
                                    <span class="text-success">System Trace: File compliance fully satisfied.</span>
                                <?php elseif($percent >= 30): ?>
                                    <span class="text-warning">System Trace: Partial data detected. Completion required.</span>
                                <?php else: ?>
                                    <span class="text-danger">System Trace: Critical missing data in mandatory fields.</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<footer>
    <div class="container text-center">
        <div class="footer-logo text-uppercase">PROCUREMENT <span class="text-success">DATA SYSTEM</span></div>
        <div class="footer-sub">Bepo-Peso | All Rights Reserved @ 2026</div>
    </div>
</footer>

<button class="add-note-btn shadow-lg" onclick="openQuickNote('<?php echo date('Y-m-d'); ?>')"><i class="bi bi-plus-lg"></i></button>

<div class="modal fade" id="noteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="save_note.php" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0" id="modalTitle">Journal Entry</h5>
                    <div id="deleteAction" style="display:none;">
                        <a href="#" id="deleteBtn" class="btn-delete" onclick="return confirm('Delete this note?')"><i class="bi bi-trash3-fill"></i></a>
                    </div>
                </div>
                <input type="hidden" name="note_id" id="noteId">
                <input type="hidden" name="action" id="formAction" value="save">
                <input type="date" name="note_date" id="targetDate" class="form-control mb-2 rounded-3" required>
                <input type="text" name="title" id="noteTitle" class="form-control mb-2 rounded-3" placeholder="Entry Title" required>
                <textarea name="note_content" id="noteContent" class="form-control mb-3 rounded-3" rows="3" placeholder="Write your notes here..."></textarea>
                <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold">Confirm Action</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const noteModal = new bootstrap.Modal(document.getElementById('noteModal'));
function openQuickNote(date) {
    document.getElementById('modalTitle').innerText = "New Journal Entry";
    document.getElementById('formAction').value = "save";
    document.getElementById('noteId').value = "";
    document.getElementById('targetDate').value = date;
    document.getElementById('noteTitle').value = "";
    document.getElementById('noteContent').value = "";
    document.getElementById('deleteAction').style.display = "none";
    noteModal.show();
}
function editNote(id, date, title, content) {
    document.getElementById('modalTitle').innerText = "Edit Journal Entry";
    document.getElementById('formAction').value = "update";
    document.getElementById('noteId').value = id;
    document.getElementById('targetDate').value = date;
    document.getElementById('noteTitle').value = title;
    document.getElementById('noteContent').value = content;
    document.getElementById('deleteAction').style.display = "block";
    document.getElementById('deleteBtn').href = "save_note.php?action=delete&id=" + id;
    noteModal.show();
}
</script>
</body>
</html>
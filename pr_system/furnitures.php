<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Furniture Procurement - PDS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --admin-green: #198754; --dark-bg: #1a1d20; }
        body { background-color: #f4f7f6; font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; flex-direction: column; }
        .main-content { flex: 1; padding: 40px 0; }
        .card-premium { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .card-header-dark { background: var(--dark-bg); color: #fff; border-radius: 15px 15px 0 0 !important; padding: 1rem; }
        .section-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--admin-green); letter-spacing: 1px; margin-bottom: 15px; display: block; border-left: 4px solid var(--admin-green); padding-left: 10px; }
        .status-complete { background-color: #d1e7dd !important; color: #0f5132 !important; font-weight: bold; }
        .status-pending { background-color: #f8d7da !important; color: #842029 !important; font-weight: bold; }
        footer { background-color: #1a1d20; color: white; padding: 20px 0; border-top: 4px solid var(--admin-green); margin-top: auto; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="main-content">
    <form action="process/save_furniture.php" method="POST">
        <input type="hidden" name="form_type" value="furnitures">
        <div class="container">
            
            <?php if(isset($_SESSION['msg'])): ?>
                <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show shadow-sm mb-4" role="alert">
                    <i class="bi <?php echo ($_SESSION['msg_type'] == 'success') ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'; ?> me-2"></i> 
                    <?php 
                        echo $_SESSION['msg']; 
                        unset($_SESSION['msg']); 
                        unset($_SESSION['msg_type']); 
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row mb-4 align-items-center">
                <div class="col-md-6">
                    <h3 class="fw-bold m-0">Furniture Form</h3>
                    <p class="text-muted m-0">Manage office furniture procurement records.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-inline-block text-start bg-white p-3 rounded shadow-sm border">
                        <label class="small fw-bold">Transaction Date</label>
                        <input type="date" name="transaction_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card card-premium mb-4">
                        <div class="card-body p-4">
                            <span class="section-label">General Specifications</span>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="small fw-bold">Title</label>
                                    <input type="text" name="title" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold">Items</label>
                                    <input type="text" name="items" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold">Quarter</label>
                                    <input type="text" name="quarter" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold">Specs</label>
                                    <input type="text" name="specs" class="form-control">
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="small fw-bold">Qty</label>
                                    <input type="number" name="qty" id="qty" class="form-control calc-trigger">
                                </div>
                                <div class="col-md-4">
                                    <label class="small fw-bold">Unit</label>
                                    <input type="text" name="unit" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="small fw-bold">Unit Cost</label>
                                    <input type="number" step="0.01" name="unit_cost" id="unit_cost" class="form-control calc-trigger">
                                </div>

                                <div class="col-md-6">
                                    <label class="small fw-bold">Suppliers</label>
                                    <input type="text" name="suppliers" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold text-success">Total Cost (Auto/Manual)</label>
                                    <input type="text" name="total_cost" id="total_cost" class="form-control fw-bold text-success">
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold">Mode of Payment</label>
                                    <select name="payment_mode" class="form-select">
                                        <option value="Upon Completion">Upon Completion</option>
                                        <option value="Progress Billing">Progress Billing</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold">Remarks</label>
                                    <input type="text" name="remarks" class="form-control">
                                </div>
                            </div>

                            <hr class="my-4">
                            <span class="section-label">Tracking Nos.</span>
                            <div class="row g-3">
                                <div class="col-md-6"><label class="small fw-bold">PR NO.</label><input type="text" name="pr_no" class="form-control"></div>
                                <div class="col-md-6"><label class="small fw-bold">NC NO.</label><input type="text" name="nc_no" class="form-control"></div>
                                <div class="col-md-4"><label class="small fw-bold">P.O NO.</label><input type="text" name="po_no" class="form-control"></div>
                                <div class="col-md-4"><label class="small fw-bold">PADMO NO.</label><input type="text" name="padmo_no" class="form-control"></div>
                                <div class="col-md-4"><label class="small fw-bold">G.O Finance NO.</label><input type="text" name="go_finance_no" class="form-control"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card card-premium">
                        <div class="card-header-dark d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="bi bi-clipboard-check me-2"></i>Check List</h6>
                            <a href="furniture_bidocs.php" class="btn btn-sm btn-outline-light" style="font-size: 0.7rem;"> FURNITURES BIDOCS </a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0" style="font-size: 0.85rem;">
                                    <thead>
                                        <tr>
                                            <th class="ps-3">List</th>
                                            <th>Status</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $docs = ['PR', 'ABC', 'PPMP', 'ACT DES', 'IAR/ARE', 'PDRS', 'APP', 'LETTER REQUEST', 'MANUAL OBR'];
                                        foreach($docs as $doc): ?>
                                        <tr>
                                            <td class="fw-bold align-middle ps-3"><?php echo $doc; ?></td>
                                            <td width="90px">
                                                <select name="status[]" class="form-select form-select-sm status-trigger" onchange="updateChecklist(this)">
                                                    <option value="Not Complete">NC</option>
                                                    <option value="Complete">C</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="doc_remarks[]" class="form-control form-control-sm remark-box" 
                                                       value="Please compile the document." readonly>
                                                <input type="hidden" name="doc_name[]" value="<?php echo $doc; ?>">
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white p-3">
                            <button type="submit" class="btn btn-success w-100 fw-bold py-3 shadow-sm">
                                <i class="bi bi-save2-fill me-2"></i>SAVE FURNITURE RECORD
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<footer>
    <div class="container text-center text-md-start">
        <div class="row align-items-center">
            <div class="col-md-6">
                <span class="fw-bold">PROCUREMENT <span style="color: var(--admin-green);">DATA SYSTEM</span></span>
            </div>
            <div class="col-md-6 text-md-end">
                <small style="color: var(--admin-green);">Bepo-Peso alright Reserved @ 2026</small>
            </div>
        </div>
    </div>
</footer>

<script>
// --- Calculation Logic ---
const qtyInp = document.getElementById('qty');
const costInp = document.getElementById('unit_cost');
const totalInp = document.getElementById('total_cost');

function solveTotal() {
    const q = parseFloat(qtyInp.value) || 0;
    const c = parseFloat(costInp.value) || 0;
    totalInp.value = (q * c).toFixed(2);
}

document.querySelectorAll('.calc-trigger').forEach(el => {
    el.addEventListener('input', solveTotal);
});

// Original Checklist Logic
function updateChecklist(select) {
    const row = select.closest('tr');
    const remarkField = row.querySelector('.remark-box');
    const docName = row.cells[0].innerText;
    
    if(select.value === 'Complete') {
        select.classList.add('status-complete');
        select.classList.remove('status-pending');
        remarkField.value = "System Complete";
        remarkField.style.color = "#198754";
        remarkField.style.fontWeight = "bold";
    } else {
        select.classList.add('status-pending');
        select.classList.remove('status-complete');
        remarkField.value = "Please compile the " + docName + " document.";
        remarkField.style.color = "#dc3545";
        remarkField.style.fontWeight = "normal";
    }
}
document.querySelectorAll('.status-trigger').forEach(s => s.classList.add('status-pending'));
</script>
</body>
</html>
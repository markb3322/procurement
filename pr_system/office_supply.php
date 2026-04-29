<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Office Supplies Management - PDS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --admin-green: #198754; --dark-bg: #1a1d20; }
        body { 
            background-color: #f4f7f6; 
            font-family: 'Inter', sans-serif; 
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .main-content { flex: 1; padding: 40px 0; }
        .card-premium { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .card-header-dark { background: var(--dark-bg); color: #fff; border-radius: 15px 15px 0 0 !important; padding: 1rem; }
        .section-label { 
            font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--admin-green); 
            letter-spacing: 1px; margin-bottom: 15px; display: block; border-left: 4px solid var(--admin-green); padding-left: 10px; 
        }
        .form-control:focus, .form-select:focus { border-color: var(--admin-green); box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25); }
        
        .table-checklist th { background: #f8f9fa; font-size: 0.85rem; }
        .status-complete { background-color: #d1e7dd !important; color: #0f5132 !important; font-weight: bold; }
        .status-pending { background-color: #f8d7da !important; color: #842029 !important; font-weight: bold; }

        footer { background-color: #1a1d20; color: white; padding: 20px 0; margin-top: auto; border-top: 4px solid var(--admin-green); }

        .btn-header-light {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 4px 10px;
            border: 1px solid rgba(255,255,255,0.2);
            background: transparent;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-header-light:hover { background: rgba(255,255,255,0.1); color: white; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="main-content">
    <div class="container">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <i class="bi <?php echo ($_SESSION['msg_type'] == 'success') ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'; ?> me-2"></i>
                <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']);
                    unset($_SESSION['msg_type']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="process/save_office_supplies.php" method="POST">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h3 class="fw-bold">Office Supplies Procurement</h3>
                    <p class="text-muted">Manage inventory, tracking, and document bidocs.</p>
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
                            <span class="section-label">General Information</span>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="small fw-bold">Title</label>
                                    <input type="text" name="title" class="form-control" placeholder="Project/Activity Title" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold">Items</label>
                                    <input type="text" name="items" class="form-control" placeholder="Main item name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold">Quarter</label>
                                    <input type="text" name="quarter" class="form-control" placeholder="e.g. 1st Quarter">
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold">Articles</label>
                                    <input type="text" name="articles" class="form-control" placeholder="Enter Articles">
                                </div>
                                <div class="col-md-4">
                                    <label class="small fw-bold">Brand</label>
                                    <input type="text" name="brand" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="small fw-bold">Quantity</label>
                                    <input type="number" name="qty" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="small fw-bold">Unit</label>
                                    <input type="text" name="unit" class="form-control" placeholder="pc/box/ream">
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold">Unit Cost</label>
                                    <input type="number" step="0.01" name="unit_cost" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold text-success">Total Cost</label>
                                    <input type="text" name="total_cost" class="form-control fw-bold text-success" placeholder="Manual Entry Only">
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold">Suppliers</label>
                                    <input type="text" name="suppliers" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold">Mode of Payment</label>
                                    <select name="payment_mode" class="form-select">
                                        <option value="Upon Completion">Upon Completion</option>
                                        <option value="Progress Billing">Progress Billing</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="small fw-bold">Remarks</label>
                                    <textarea name="remarks" class="form-control" rows="2" placeholder="General remarks..."></textarea>
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
                            <h6 class="mb-0"><i class="bi bi-list-check me-2"></i>Check List</h6>
                            <a href="#" class="btn-header-light">OFFICE SUPPLY BIDOCS</a>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-checklist mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Document</th>
                                        <th class="text-center">Status</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $checkItems = ['PR', 'ABC', 'PPMP', 'ACT DES', 'IAR/ARE', 'PDRS', 'APP', 'LETTER', 'OBR'];
                                    foreach($checkItems as $item): ?>
                                    <tr>
                                        <td class="small fw-bold align-middle ps-3"><?php echo $item; ?></td>
                                        <td width="90px">
                                            <select name="status[]" class="form-select form-select-sm status-trigger" onchange="toggleStatus(this)">
                                                <option value="Not Complete">NC</option>
                                                <option value="Complete">C</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="doc_remarks[]" class="form-control form-control-sm remark-input" value="Please compile the document." readonly>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-white p-3">
                            <button type="submit" class="btn btn-success w-100 fw-bold py-3 shadow-sm">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i>SAVE OFFICE SUPPLY RECORD
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<footer>
    <div class="container text-center">
        <span class="fw-bold">PROCUREMENT <span style="color: var(--admin-green);">DATA SYSTEM</span></span>
        <br>
        <small style="color: var(--admin-green);">Bepo-Peso All Rights Reserved @ 2026</small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleStatus(select) {
    const row = select.closest('tr');
    const remarkField = row.querySelector('.remark-input');
    
    if(select.value === 'Complete') {
        select.classList.add('status-complete');
        select.classList.remove('status-pending');
        remarkField.value = "Complete";
        remarkField.style.color = "#198754"; 
        remarkField.style.fontWeight = "bold";
    } else {
        select.classList.add('status-pending');
        select.classList.remove('status-complete');
        remarkField.value = "Please compile the document.";
        remarkField.style.color = "#dc3545"; 
        remarkField.style.fontWeight = "normal";
    }
}

document.querySelectorAll('.status-trigger').forEach(s => s.classList.add('status-pending'));
</script>

</body>
</html>
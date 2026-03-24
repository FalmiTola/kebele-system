<?php
// modules/vital/issue_death.php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

// Get residents who are alive
$residents = $pdo->query("SELECT id, fname, lname FROM individuals WHERE status = 'alive' ORDER BY fname")->fetchAll();

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resident_id = $_POST['resident_id'];
    $death_date = $_POST['death_date'];
    $death_reason = $_POST['death_reason'];
    $issue_date = date('Y-m-d');
    
    // Generate cert number: DC/YEAR/XXXX
    $year = date('Y');
    $lastIdStmt = $pdo->prepare("SELECT cert_number FROM vital_certificates WHERE cert_type = 'death' AND cert_number LIKE ? ORDER BY id DESC LIMIT 1");
    $lastIdStmt->execute(["DC/$year/%"]);
    $lastId = $lastIdStmt->fetchColumn();
    
    $nextNumber = 1;
    if ($lastId) {
        $parts = explode('/', $lastId);
        $nextNumber = intval(end($parts)) + 1;
    }
    
    $cert_number = "DC/$year/" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

    try {
        $pdo->beginTransaction();

        // 1. Mark as deceased
        $stmt_status = $pdo->prepare("UPDATE individuals SET status = 'deceased', death_date = ?, death_reason = ? WHERE id = ?");
        $stmt_status->execute([$death_date, $death_reason, $resident_id]);

        // 2. Insert certificate
        $stmt_cert = $pdo->prepare("INSERT INTO vital_certificates (resident_id, cert_type, cert_number, issue_date, remarks) VALUES (?, 'death', ?, ?, ?)");
        $stmt_cert->execute([$resident_id, $cert_number, $issue_date, "Reason: $death_reason"]);

        $cert_id = $pdo->lastInsertId();
        
        $pdo->commit();
        $success = "Death certificate generated: $cert_number. Resident status updated.";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Failed: " . $e->getMessage();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-skull me-2 text-danger"></i><?php echo __('death_cert'); ?></h2>
    <a href="index.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back</a>
</div>

<?php if ($success): ?>
    <div class="alert alert-danger d-flex align-items-center bg-danger text-white border-0 shadow">
        <i class="fas fa-exclamation-triangle me-3 fa-2x"></i>
        <div>
            <strong><?php echo $success; ?></strong><br>
            <a href="print.php?id=<?php echo $cert_id; ?>" class="btn btn-sm btn-light mt-2" target="_blank">Print Death Certificate</a>
        </div>
    </div>
<?php endif; ?>

<?php if ($error): ?> <div class="alert alert-danger"><?php echo $error; ?></div> <?php endif; ?>

<div class="row">
    <div class="col-md-7">
        <div class="card p-4 border-0 shadow-sm">
            <form method="POST">
                <div class="mb-4">
                    <label class="form-label fw-bold">Resident</label>
                    <select name="resident_id" class="form-select select2" required>
                        <option value="">-- Search Resident --</option>
                        <?php foreach ($residents as $r): ?>
                            <option value="<?php echo $r['id']; ?>"><?php echo "{$r['fname']} {$r['lname']} (#{$r['id']})"; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">Date of Death</label>
                        <input type="date" name="death_date" class="form-control" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Cause of Death / Remarks</label>
                    <textarea name="death_reason" class="form-control" rows="3" placeholder="Enter cause of death if available..." required></textarea>
                </div>
                <button type="submit" class="btn btn-danger w-100 py-3 fw-bold" onclick="return confirm('Attention: This will mark the resident as DECEASED in all system records. Continue?')">
                    <i class="fas fa-file-invoice me-2"></i>Register Death & Issue Certificate
                </button>
            </form>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card bg-light border-0 p-4 h-100">
            <h6 class="fw-bold mb-3">Critical Action Impact</h6>
            <p class="small text-muted mb-4">Issuing a death certificate is a non-reversible operational action that has the following effects:</p>
            <ul class="small text-muted">
                <li class="mb-2"><strong>ID Card Revocation:</strong> The resident's digital ID is automatically invalidated.</li>
                <li class="mb-2"><strong>Census Update:</strong> Total active resident count decreases automatically.</li>
                <li class="mb-2"><strong>Official Record:</strong> A permanent legal death record is created in the Kebele database.</li>
            </ul>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

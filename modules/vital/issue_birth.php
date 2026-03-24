<?php
// modules/vital/issue_birth.php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

// Get residents without birth cert
$residents = $pdo->query("SELECT id, fname, lname FROM individuals WHERE id NOT IN (SELECT resident_id FROM vital_certificates WHERE cert_type = 'birth') ORDER BY fname")->fetchAll();

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resident_id = $_POST['resident_id'];
    $issue_date = date('Y-m-d');
    
    // Generate cert number: BC/YEAR/XXXX
    $year = date('Y');
    $lastIdStmt = $pdo->prepare("SELECT cert_number FROM vital_certificates WHERE cert_type = 'birth' AND cert_number LIKE ? ORDER BY id DESC LIMIT 1");
    $lastIdStmt->execute(["BC/$year/%"]);
    $lastId = $lastIdStmt->fetchColumn();
    
    $nextNumber = 1;
    if ($lastId) {
        $parts = explode('/', $lastId);
        $nextNumber = intval(end($parts)) + 1;
    }
    
    $cert_number = "BC/$year/" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    $remarks = $_POST['remarks'] ?? '';

    $stmt = $pdo->prepare("INSERT INTO vital_certificates (resident_id, cert_type, cert_number, issue_date, remarks) VALUES (?, 'birth', ?, ?, ?)");
    try {
        $stmt->execute([$resident_id, $cert_number, $issue_date, $remarks]);
        $cert_id = $pdo->lastInsertId();
        $success = "Birth certificate generated: $cert_number";
    } catch (PDOException $e) {
        $error = "Failed: " . $e->getMessage();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-baby me-2 text-primary"></i><?php echo __('birth_cert'); ?></h2>
    <a href="index.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back</a>
</div>

<?php if ($success): ?>
    <div class="alert alert-success d-flex align-items-center">
        <i class="fas fa-check-circle me-3 fa-2x"></i>
        <div>
            <strong><?php echo $success; ?></strong><br>
            <a href="print.php?id=<?php echo $cert_id; ?>" class="btn btn-sm btn-success mt-2" target="_blank">Print Certificate</a>
        </div>
    </div>
<?php endif; ?>

<?php if ($error): ?> <div class="alert alert-danger"><?php echo $error; ?></div> <?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <div class="card p-4 border-0 shadow-sm">
            <form method="POST">
                <div class="mb-4">
                    <label class="form-label fw-bold">Select Resident</label>
                    <select name="resident_id" class="form-select select2" required>
                        <option value="">-- Search Resident --</option>
                        <?php foreach ($residents as $r): ?>
                            <option value="<?php echo $r['id']; ?>"><?php echo "{$r['fname']} {$r['lname']} (#{$r['id']})"; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Remarks (Optional)</label>
                    <textarea name="remarks" class="form-control" rows="3" placeholder="Additional notes..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
                    <i class="fas fa-certificate me-2"></i>Generate Birth Certificate
                </button>
            </form>
        </div>
    </div>
    <div class="col-md-6">
        <div class="bg-grad-primary p-5 rounded-4 text-white">
            <h5><i class="fas fa-info-circle me-2"></i>Birth Registration Logic</h5>
            <ul class="mt-4 space-y-3 opacity-90">
                <li><i class="fas fa-check me-2"></i> Validates resident eligibility.</li>
                <li><i class="fas fa-check me-2"></i> Assigns professional sequential BC numbering.</li>
                <li><i class="fas fa-check me-2"></i> Stores official record in the Kebele archives.</li>
            </ul>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

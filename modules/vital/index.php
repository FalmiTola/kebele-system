<?php
// modules/vital/index.php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

// Fetch certificates
$query = "SELECT vc.*, i.fname, i.lname, i.status 
          FROM vital_certificates vc 
          JOIN individuals i ON vc.resident_id = i.id 
          ORDER BY vc.issue_date DESC";
$certs = $pdo->query($query)->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-file-signature me-2 text-primary"></i><?php echo __('vital_records'); ?></h2>
    <div class="d-flex gap-2">
        <a href="issue_birth.php" class="btn btn-primary">
            <i class="fas fa-baby me-2"></i><?php echo __('birth_cert'); ?>
        </a>
        <a href="issue_death.php" class="btn btn-danger">
            <i class="fas fa-skull me-2"></i><?php echo __('death_cert'); ?>
        </a>
    </div>
</div>

<div class="card p-4 border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="text-muted small text-uppercase">
                <tr>
                    <th class="border-0">Cert #</th>
                    <th class="border-0">Resident</th>
                    <th class="border-0">Type</th>
                    <th class="border-0">Issue Date</th>
                    <th class="border-0 text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($certs)): ?>
                    <tr><td colspan="5" class="text-center py-5 text-muted">No vital certificates issued yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($certs as $c): ?>
                        <tr>
                            <td class="fw-bold text-primary"><?php echo $c['cert_number']; ?></td>
                            <td><?php echo "{$c['fname']} {$c['lname']}"; ?></td>
                            <td>
                                <span class="badge <?php echo $c['cert_type'] === 'birth' ? 'bg-info' : 'bg-danger'; ?>">
                                    <?php echo $c['cert_type'] === 'birth' ? __('birth_cert') : __('death_cert'); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($c['issue_date'])); ?></td>
                            <td class="text-end">
                                <a href="print.php?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-light border" target="_blank">
                                    <i class="fas fa-print me-1"></i> Print
                                </a>
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <a href="delete.php?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

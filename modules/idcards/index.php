<?php
// modules/idcards/index.php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

$query = "SELECT ic.*, i.fname, i.lname, i.phot, ag.age, i.occ, i.nat
          FROM id_cards ic
          JOIN individuals i ON ic.resident_id = i.id
          LEFT JOIN ages ag ON i.id = ag.id";
$id_cards = $pdo->query($query)->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>ID Card Management</h2>
    <?php if ($_SESSION['role'] !== 'security'): ?>
    <a href="generate.php" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Issue New ID Card
    </a>
    <?php endif; ?>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID Number</th>
                    <th>Full Name</th>
                    <th>Issue Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($id_cards as $card): ?>
                <tr>
                    <td><strong><?php echo $card['id_number']; ?></strong></td>
                    <td><?php echo "{$card['fname']} {$card['lname']}"; ?></td>
                    <td><?php echo date('M d, Y', strtotime($card['issue_date'])); ?></td>
                    <td>
                        <?php if ($_SESSION['role'] !== 'security'): ?>
                            <a href="print.php?id=<?php echo $card['id']; ?>" class="btn btn-sm btn-outline-success" target="_blank" title="Print ID">
                                <i class="fas fa-print me-1"></i> Print
                            </a>
                            
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <a href="delete.php?id=<?php echo $card['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Revoke this ID card?')" title="Delete Record">
                                    <i class="fas fa-trash"></i>
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-muted small italic">View Only</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($id_cards)): ?>
                <tr><td colspan="4" class="text-center py-4">No ID cards issued yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

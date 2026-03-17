<?php
// modules/families/index.php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

$query = "SELECT f.*, i.fname, i.lname, h.area
          FROM families f 
          LEFT JOIN individuals i ON f.lead_id = i.id
          LEFT JOIN houses h ON f.hnum = h.hnum";
$families = $pdo->query($query)->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Family Management</h2>
    <?php if ($_SESSION['role'] !== 'security'): ?>
    <a href="create.php" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Register Family
    </a>
    <?php endif; ?>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>House #</th>
                    <th>Family Leader</th>
                    <th>Family Members Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($families as $f): ?>
                <tr>
                    <td><strong>H-<?php echo $f['hnum']; ?></strong></td>
                    <td><?php echo "{$f['fname']} {$f['lname']}"; ?></td>
                    <td><span class="badge bg-info p-2 px-3"><?php echo $f['fam_no']; ?> Members</span></td>
                    <td>
                        <a href="view.php?hnum=<?php echo $f['hnum']; ?>" class="btn btn-sm btn-outline-primary" title="View"><i class="fas fa-eye"></i></a>
                        
                        <?php if ($_SESSION['role'] !== 'security'): ?>
                            <a href="edit.php?hnum=<?php echo $f['hnum']; ?>" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-edit"></i></a>
                            
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <a href="delete.php?hnum=<?php echo $f['hnum']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete this family record?')"><i class="fas fa-trash"></i></a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($families)): ?>
                <tr><td colspan="4" class="text-center py-4">No families registered yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

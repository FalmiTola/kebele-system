<?php
// modules/houses/index.php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

$query = "SELECT h.*, i.fname, i.lname 
          FROM houses h 
          LEFT JOIN individuals i ON h.own_id = i.id";
$houses = $pdo->query($query)->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>House Management</h2>
    <?php if ($_SESSION['role'] !== 'security'): ?>
    <a href="create.php" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add New House
    </a>
    <?php endif; ?>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>House Number</th>
                    <th>Area (sqm)</th>
                    <th>Number of Doors</th>
                    <th>Owner Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($houses as $h): ?>
                <tr>
                    <td class="fw-bold">H-<?php echo $h['hnum']; ?></td>
                    <td><?php echo $h['area']; ?> m²</td>
                    <td><?php echo $h['door']; ?></td>
                    <td><?php echo $h['fname'] ? "{$h['fname']} {$h['lname']}" : "<span class='text-danger'>Unknown ID: {$h['own_id']}</span>"; ?></td>
                    <td>
                        <a href="view.php?hnum=<?php echo $h['hnum']; ?>" class="btn btn-sm btn-outline-primary" title="View Details"><i class="fas fa-eye"></i></a>
                        
                        <?php if ($_SESSION['role'] !== 'security'): ?>
                            <a href="edit.php?hnum=<?php echo $h['hnum']; ?>" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-edit"></i></a>
                            
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <a href="delete.php?hnum=<?php echo $h['hnum']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this house record?')" title="Delete"><i class="fas fa-trash"></i></a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($houses)): ?>
                <tr><td colspan="5" class="py-4 text-muted">No houses registered in the system yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

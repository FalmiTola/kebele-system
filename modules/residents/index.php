<?php
// modules/residents/index.php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

// Search functionality
$search = $_GET['search'] ?? '';
$query = "SELECT i.*, a.pho_no, ag.age 
          FROM individuals i 
          LEFT JOIN addresses a ON i.id = a.id 
          LEFT JOIN ages ag ON i.id = ag.id";

if ($search) {
    $query .= " WHERE i.fname LIKE :s OR i.lname LIKE :s OR i.id LIKE :s";
}

$stmt = $pdo->prepare($query);
if ($search) {
    $stmt->execute(['s' => "%$search%"]);
} else {
    $stmt->execute();
}
$residents = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Resident Management</h2>
    <?php if ($_SESSION['role'] !== 'security'): ?>
    <a href="create.php" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Register New Resident
    </a>
    <?php endif; ?>
</div>

<div class="card p-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <form class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search by name or ID..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-secondary">Search</button>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Photo</th>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Sex/Age</th>
                    <th>Occupation</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($residents as $r): ?>
                <tr>
                    <td>
                        <img src="../../assets/images/<?php echo $r['phot']; ?>" class="rounded-circle" width="40" height="40" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($r['fname']); ?>&background=random'">
                    </td>
                    <td>#<?php echo $r['id']; ?></td>
                    <td><?php echo "{$r['fname']} {$r['mname']} {$r['lname']}"; ?></td>
                    <td><?php echo "{$r['s']} / {$r['age']}"; ?></td>
                    <td><?php echo $r['occ']; ?></td>
                    <td><?php echo $r['pho_no'] ?? 'N/A'; ?></td>
                    <td>
                        <?php if ($_SESSION['role'] === 'security'): ?>
                            <a href="view.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-outline-secondary" title="View Details"><i class="fas fa-eye"></i></a>
                        <?php else: ?>
                            <a href="edit.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-edit"></i></a>
                            
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <a href="delete.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this resident?')" title="Delete"><i class="fas fa-trash"></i></a>
                            <?php endif; ?>

                            <a href="../idcards/generate.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-outline-primary" title="Generate ID"><i class="fas fa-id-card"></i></a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($residents)): ?>
                <tr><td colspan="7" class="text-center py-4 text-muted">No residents found matching your criteria.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

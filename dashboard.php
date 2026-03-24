<?php
// dashboard.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

// Fetch stats
$totalResidents = $pdo->query("SELECT COUNT(*) FROM individuals")->fetchColumn();
$totalFamilies = $pdo->query("SELECT COUNT(*) FROM families")->fetchColumn();
$totalHouses = $pdo->query("SELECT COUNT(*) FROM houses")->fetchColumn();
$totalIDs = $pdo->query("SELECT COUNT(*) FROM id_cards")->fetchColumn();

// Fetch recent residents
$recentResidents = $pdo->query("SELECT * FROM individuals ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>

<!-- Welcome Banner -->
<div class="card bg-grad-primary mb-4 border-0 p-5 position-relative overflow-hidden">
    <div class="row align-items-center position-relative z-1">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-2 mb-3">
                <img src="assets/img/ethiopia_flag.png" alt="Ethiopia" height="25" class="rounded shadow-sm">
                <img src="assets/img/oromia_flag.png" alt="Oromia" height="25" class="rounded shadow-sm">
                <span class="text-white-50 ms-2 small fw-bold uppercase tracking-wider">Official Administration Workspace</span>
            </div>
            <h1 class="display-5 fw-bold text-white mb-2">
                <?php echo __('welcome'); ?>, <?php echo $_SESSION['username'] ?? 'Staff'; ?>!
            </h1>
            <p class="lead text-white-50 mb-0">
                You are currently managing the digital records of Hirmata Mentina Kebele. Your contributions help build a more efficient community.
            </p>
        </div>
        <div class="col-md-4 text-center d-none d-md-block">
            <i class="fas fa-landmark text-white shadow-sm" style="font-size: 8rem; opacity: 0.1;"></i>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card bg-grad-primary border-0">
            <p><?php echo __('total_residents'); ?></p>
            <h2><?php echo number_format($totalResidents); ?></h2>
            <i class="fas fa-users"></i>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card bg-grad-secondary border-0">
            <p><?php echo __('total_families'); ?></p>
            <h2><?php echo number_format($totalFamilies); ?></h2>
            <i class="fas fa-users-rectangle"></i>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card bg-grad-accent border-0">
            <p><?php echo __('total_houses'); ?></p>
            <h2><?php echo number_format($totalHouses); ?></h2>
            <i class="fas fa-home"></i>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card bg-grad-warning border-0">
            <p><?php echo __('ids_issued'); ?></p>
            <h2><?php echo number_format($totalIDs); ?></h2>
            <i class="fas fa-id-card"></i>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card p-4 border-0 shadow-sm mb-4">
            <h5 class="fw-bold mb-4 d-flex align-items-center">
                <i class="fas fa-history me-2 text-primary"></i> Recently Registered Residents
            </h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="text-muted small text-uppercase">
                        <tr>
                            <th class="border-0">Name</th>
                            <th class="border-0">Sex / Age</th>
                            <th class="border-0">Occupation</th>
                            <th class="border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentResidents)): ?>
                            <tr><td colspan="4" class="text-center py-5">No residents registered yet.</td></tr>
                        <?php else: ?>
                            <?php foreach ($recentResidents as $resident): ?>
                                <tr>
                                    <td class="fw-semibold"><?php echo "{$resident['fname']} {$resident['lname']}"; ?></td>
                                    <td><span class="badge bg-light text-dark"><?php echo $resident['s']; ?></span></td>
                                    <td><?php echo $resident['occ']; ?></td>
                                    <td class="text-end">
                                        <a href="modules/residents/view.php?id=<?php echo $resident['id']; ?>" class="btn btn-sm btn-light">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card p-4 border-0 shadow-sm h-100">
            <h5 class="fw-bold mb-4">Quick Operations</h5>
            <div class="d-grid gap-3">
                <a href="modules/residents/create.php" class="btn btn-light text-start p-3 border">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-bold">New Resident</p>
                            <small class="text-muted">Register a citizen</small>
                        </div>
                    </div>
                </a>
                <a href="modules/houses/create.php" class="btn btn-light text-start p-3 border">
                     <div class="d-flex align-items-center">
                        <div class="bg-success text-white rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-house-chimney"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-bold">Add House</p>
                            <small class="text-muted">Register property</small>
                        </div>
                    </div>
                </a>
                <a href="modules/idcards/index.php" class="btn btn-light text-start p-3 border">
                     <div class="d-flex align-items-center">
                        <div class="bg-info text-white rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-id-badge"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-bold">ID Services</p>
                            <small class="text-muted">Generate & Print</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

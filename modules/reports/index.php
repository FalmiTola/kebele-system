<?php
// modules/reports/index.php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    die("<div class='alert alert-danger m-5'>Access Denied: You do not have permission to view administrative reports.</div>");
}

// Stats Queries
$sexDist = $pdo->query("SELECT s, COUNT(*) as count FROM individuals GROUP BY s")->fetchAll();
$ageDist = $pdo->query("SELECT 
    CASE 
        WHEN age < 18 THEN 'Under 18'
        WHEN age BETWEEN 18 AND 35 THEN '18-35'
        WHEN age BETWEEN 36 AND 60 THEN '36-60'
        ELSE '60+'
    END as age_group,
    COUNT(*) as count
    FROM ages GROUP BY age_group")->fetchAll();

$houseStats = $pdo->query("SELECT COUNT(*) as total_houses, AVG(area) as avg_area FROM houses")->fetch();
$familyStats = $pdo->query("SELECT COUNT(*) as total_families, SUM(fam_no) as total_people FROM families")->fetch();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Administrative Reports</h2>
    <button onclick="window.print()" class="btn btn-outline-secondary">
        <i class="fas fa-print me-2"></i>Print Report
    </button>
</div>

<div class="row g-4">
    <!-- Sex Distribution -->
    <div class="col-md-6">
        <div class="card p-4 h-100 shadow-sm">
            <h5 class="mb-4 text-primary">Population by Sex</h5>
            <div class="table-responsive">
                <table class="table border">
                    <thead class="table-light">
                        <tr>
                            <th>Category</th>
                            <th>Count</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = array_sum(array_column($sexDist, 'count'));
                        foreach ($sexDist as $row): 
                            $percent = $total > 0 ? round(($row['count'] / $total) * 100, 1) : 0;
                        ?>
                        <tr>
                            <td><?php echo $row['s']; ?></td>
                            <td><?php echo $row['count']; ?></td>
                            <td>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar" role="progressbar" style="width: <?php echo $percent; ?>%"><?php echo $percent; ?>%</div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Age Distribution -->
    <div class="col-md-6">
        <div class="card p-4 h-100 shadow-sm">
            <h5 class="mb-4 text-secondary">Population by Age Group</h5>
            <div class="table-responsive">
                <table class="table border">
                    <thead class="table-light">
                        <tr>
                            <th>Age Group</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ageDist as $row): ?>
                        <tr>
                            <td><?php echo $row['age_group']; ?></td>
                            <td><?php echo $row['count']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- General Stats -->
    <div class="col-md-12">
        <div class="card p-4 shadow-sm">
            <h5 class="mb-4 text-success">Housing & Family Overview</h5>
            <div class="row text-center">
                <div class="col-md-3 border-end">
                    <h3 class="mb-0"><?php echo $houseStats['total_houses']; ?></h3>
                    <p class="text-muted mb-0">Total Houses</p>
                </div>
                <div class="col-md-3 border-end">
                    <h3 class="mb-0"><?php echo round($houseStats['avg_area'], 2); ?> m²</h3>
                    <p class="text-muted mb-0">Avg. House Area</p>
                </div>
                <div class="col-md-3 border-end">
                    <h3 class="mb-0"><?php echo $familyStats['total_families']; ?></h3>
                    <p class="text-muted mb-0">Total Families</p>
                </div>
                <div class="col-md-3">
                    <h3 class="mb-0"><?php echo $familyStats['total_people'] ?? 0; ?></h3>
                    <p class="text-muted mb-0">People in Families</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-4 p-3 bg-light rounded small text-muted">
    Report generated on: <?php echo date('Y-m-d H:i:s'); ?> - Hirmata Mentina Kebele Administration System
</div>

<?php 
// Add reports link to sidebar dynamically if needed, or I'll just update sidebar.php manually next.
require_once __DIR__ . '/../../includes/footer.php'; 
?>

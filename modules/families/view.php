<?php
// modules/families/view.php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

$hnum = $_GET['hnum'] ?? null;
$stmt = $pdo->prepare("SELECT f.*, i.*, h.area, ad.pho_no, ag.age
                       FROM families f 
                       JOIN individuals i ON f.lead_id = i.id
                       JOIN houses h ON f.hnum = h.hnum
                       LEFT JOIN addresses ad ON i.id = ad.id
                       LEFT JOIN ages ag ON i.id = ag.id
                       WHERE f.hnum = ?");
$stmt->execute([$hnum]);
$f = $stmt->fetch();

if (!$f) {
    header('Location: index.php');
    exit;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Family Details: House #<?php echo $hnum; ?></h2>
    <a href="index.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back to List</a>
</div>

<div class="row g-4">
    <!-- Family Summary -->
    <div class="col-md-4">
        <div class="card p-4 h-100 shadow-sm text-center">
            <h5 class="text-primary mb-3">Family Overview</h5>
            <div class="mb-3">
                <span class="display-4 fw-bold text-dark"><?php echo $f['fam_no']; ?></span>
                <p class="text-muted">Total Members</p>
            </div>
            <div class="p-3 bg-light rounded text-start">
                <p class="mb-1"><strong>House Area:</strong> <?php echo $f['area']; ?> m²</p>
                <p class="mb-0"><strong>Location:</strong> Hirmata Mentina</p>
            </div>
        </div>
    </div>

    <!-- Leader Details -->
    <div class="col-md-8">
        <div class="card p-4 h-100 shadow-sm">
            <h5 class="text-success mb-4"><i class="fas fa-user-tie me-2"></i>Family Leader Information</h5>
            <div class="row">
                <div class="col-md-3 text-center">
                    <img src="../../assets/images/<?php echo $f['phot']; ?>" class="img-fluid rounded shadow-sm" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($f['fname']); ?>&size=150'">
                </div>
                <div class="col-md-9">
                    <table class="table table-sm table-borderless mt-2">
                        <tr><td class="fw-bold" style="width: 150px;">Full Name:</td><td><?php echo "{$f['fname']} {$f['mname']} {$f['lname']}"; ?></td></tr>
                        <tr><td class="fw-bold">Birth Date:</td><td><?php echo $f['age']; ?> Years Old</td></tr>
                        <tr><td class="fw-bold">Occupation:</td><td><?php echo $f['occ']; ?></td></tr>
                        <tr><td class="fw-bold">Phone:</td><td><?php echo $f['pho_no'] ?? 'N/A'; ?></td></tr>
                        <tr><td class="fw-bold">Marital Status:</td><td><?php echo $f['mar']; ?></td></tr>
                    </table>
                    <div class="mt-3">
                        <a href="../residents/edit.php?id=<?php echo $f['lead_id']; ?>" class="btn btn-sm btn-outline-primary">Edit Leader Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

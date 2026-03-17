<?php
// modules/houses/view.php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

$hnum = $_GET['hnum'] ?? null;
$stmt = $pdo->prepare("SELECT h.*, i.fname, i.lname, i.phot, ad.pho_no 
                       FROM houses h 
                       JOIN individuals i ON h.own_id = i.id
                       LEFT JOIN addresses ad ON i.id = ad.id
                       WHERE h.hnum = ?");
$stmt->execute([$hnum]);
$h = $stmt->fetch();

if (!$h) {
    header('Location: index.php');
    exit;
}

// Fetch family in this house
$stmt_fam = $pdo->prepare("SELECT f.*, i.fname, i.lname FROM families f JOIN individuals i ON f.lead_id = i.id WHERE f.hnum = ?");
$stmt_fam->execute([$hnum]);
$family = $stmt_fam->fetch();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>House Details: #<?php echo $hnum; ?></h2>
    <a href="index.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back to Houses</a>
</div>

<div class="row g-4">
    <!-- House Info -->
    <div class="col-md-5">
        <div class="card p-4 h-100 shadow-sm">
            <h5 class="text-primary mb-4"><i class="fas fa-info-circle me-2"></i>Property Information</h5>
            <table class="table table-sm">
                <tr><td class="fw-bold">House Number:</td><td>H-<?php echo $h['hnum']; ?></td></tr>
                <tr><td class="fw-bold">Total Area:</td><td><?php echo $h['area']; ?> m²</td></tr>
                <tr><td class="fw-bold">Doors:</td><td><?php echo $h['door']; ?></td></tr>
                <tr><td class="fw-bold">Location:</td><td>Hirmata Mentina Kebele</td></tr>
            </table>
            
            <h5 class="text-info mt-4 mb-3"><i class="fas fa-user-shield me-2"></i>Owner Information</h5>
            <div class="d-flex align-items-center p-3 bg-light rounded">
                <img src="../../assets/images/<?php echo $h['phot']; ?>" class="rounded-circle me-3" width="60" height="60" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($h['fname']); ?>'">
                <div>
                    <h6 class="mb-0"><?php echo "{$h['fname']} {$h['lname']}"; ?></h6>
                    <small class="text-muted"><?php echo $h['pho_no'] ?? 'No phone contact'; ?></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Family Info -->
    <div class="col-md-7">
        <div class="card p-4 h-100 shadow-sm border-start border-info border-4">
            <h5 class="text-info mb-4"><i class="fas fa-users-rectangle me-2"></i>Resident Family</h5>
            <?php if ($family): ?>
                <div class="alert alert-info py-2">
                    This house is assigned to a registered family profile.
                </div>
                <table class="table">
                    <tr><td class="fw-bold">Family Leader:</td><td><?php echo "{$family['fname']} {$family['lname']}"; ?></td></tr>
                    <tr><td class="fw-bold">Family ID:</td><td>FAM-<?php echo $family['hnum']; ?></td></tr>
                    <tr><td class="fw-bold">Members:</td><td><span class="badge bg-dark"><?php echo $family['fam_no']; ?> People</span></td></tr>
                </table>
                <div class="mt-3">
                    <a href="../families/view.php?hnum=<?php echo $hnum; ?>" class="btn btn-primary">View Full Family Profile</a>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No family profile is currently linked to this house number.</p>
                    <a href="../families/create.php?hnum=<?php echo $hnum; ?>" class="btn btn-sm btn-outline-success">Create Family Profile Now</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
